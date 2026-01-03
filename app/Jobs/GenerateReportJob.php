<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\ReportGeneratorService;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable as BusQueueable;
use Throwable;

class GenerateReportJob implements ShouldQueue
{
    use BusQueueable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 3;

    public $timeout = 500;

    public function __construct(
        public string $startDate,
        public string $endDate
    ) {
    }

    public function handle(ReportGeneratorService $service): void
    {
        $service->generate(
            $this->startDate,
            $this->endDate
        );
    }

    public function failed(Throwable $e): void
    {

        Log::error('Report generation failed', [
            'date_range' => [$this->startDate, $this->endDate],
            'error' => $e->getMessage()
        ]);

    }
}
