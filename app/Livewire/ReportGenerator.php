<?php

namespace App\Livewire;

use App\Models\ReportDownload;
use Livewire\Component;
use Livewire\Attributes\Validate;
use App\Services\ReportGeneratorService;
use App\Livewire\Concerns\HasToast;
use Illuminate\Support\Facades\Storage;

class ReportGenerator extends Component
{

    use HasToast;

    #[Validate('required|date|before_or_equal:end_date')]
    public string $start_date;

    #[Validate('required|date|after_or_equal:start_date')]
    public string $end_date;

    public $pendingReports = [];

    public array $notifiedReportIds = [];

    protected function messages()
    {
        return [
            'start_date.required' => 'La fecha de inicio es obligatoria.',
            'start_date.date' => 'La fecha de inicio debe ser una fecha válida.',
            'start_date.before_or_equal' => 'La fecha de inicio debe ser igual o anterior a la fecha de fin.',
            'end_date.required' => 'La fecha de fin es obligatoria.',
            'end_date.date' => 'La fecha de fin debe ser una fecha válida.',
            'end_date.after_or_equal' => 'La fecha de fin debe ser igual o posterior a la fecha de inicio.',
        ];
    }

    public function mount()
    {
        $this->start_date = now()->subDays(30)->format('Y-m-d');
        $this->end_date = now()->format('Y-m-d');
        $this->checkPendingReport();
    }

    public function generateReport(ReportGeneratorService $reportGeneratorService)
    {

        $this->validate();

        $reportGeneratorService->generate(
            $this->start_date,
            $this->end_date
        );

        $this->toastSuccess(
            'El reporte se está generando. Recibirás una notificación cuando esté listo.'
        );

    }

    public function checkPendingReport()
    {
        $this->pendingReports = ReportDownload::where('status', 'ready')
            ->where('downloaded', false)
            ->latest()
            ->get();

        foreach ($this->pendingReports as $report) {
            if (!in_array($report->id, $this->notifiedReportIds)) {
                $this->toastSuccess('¡Tu reporte está listo para descargar!');
                $this->notifiedReportIds[] = $report->id;
            }
        }
    }

    public function downloadReport($reportId)
    {
        $report = ReportDownload::find($reportId);

        if (!$report) {
            $this->toastError('No hay reportes disponibles para descargar.');
            return;
        }

        $report->update(['downloaded' => true]);

        $this->notifiedReportIds = array_diff($this->notifiedReportIds, [$reportId]);
        $this->checkPendingReport();

        return Storage::download($report->filename);
    }

    public function render()
    {
        return view('livewire.report-generator', [
            'pendingReports' => $this->pendingReports,
        ]);
    }
}
