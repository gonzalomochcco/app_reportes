<?php

namespace App\Exports;

use App\Models\SubscriptionReport;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;
use Throwable;


class CreditReportExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    WithChunkReading,
    ShouldQueue
{

    public $timeout = 900;

    public $tries = 2;

    public function __construct(
        private string $startDate,
        private string $endDate
    ) {
    }

    /**
     * Obtiene la consulta para el reporte de crédito.
     *
     * Construye y retorna una consulta de Eloquent que selecciona reportes de suscripción
     * con sus relaciones asociadas (préstamos, tarjetas de crédito y otras deudas)
     * dentro de un rango de fechas específico.
     * 
     * Adicionalmente se puede usar en lugar de query() el método generator() para hacer uso de streaming
     * y reducir aún más el uso de memoria en casos de datasets extremadamente grandes.
     *
     * @return \Illuminate\Database\Eloquent\Builder Query builder configurado con:
     *         - Selección de campos: id, subscription_id, created_at
     *         - Relaciones cargadas: subscription, loans, creditCards, otherDebts
     *         - Filtro por rango de fechas entre startDate y endDate
     *         - Ordenamiento descendente por fecha de creación
     */
    public function query(): Builder
    {
        return SubscriptionReport::query()
            ->select(['id', 'subscription_id', 'created_at'])
            ->with([
                'subscription:id,full_name,document,email,phone',
                'loans:id,subscription_report_id,bank,status,expiration_days,amount',
                'creditCards:id,subscription_report_id,bank,line,used',
                'otherDebts:id,subscription_report_id,entity,expiration_days,amount'
            ])
            ->whereBetween('created_at', [
                $this->startDate . ' 00:00:00',
                $this->endDate . ' 23:59:59'
            ])
            ->orderBy('created_at', 'desc');
    }

    public function map($report): array
    {

        if (!$report->subscription) {
            return [];
        }

        $rows = [];

        foreach ($report->loans as $loan) {
            $rows[] = [
                $report->id,
                $report->subscription->full_name,
                $report->subscription->document,
                $report->subscription->email,
                $report->subscription->phone,
                $loan->bank,
                'Préstamo',
                $loan->status,
                $loan->expiration_days,
                $loan->bank,
                $loan->amount,
                null,
                null,
                $report->created_at,
                'Activo'
            ];
        }

        foreach ($report->creditCards as $card) {
            $rows[] = [
                $report->id,
                $report->subscription->full_name,
                $report->subscription->document,
                $report->subscription->email,
                $report->subscription->phone,
                $card->bank,
                'Tarjeta de crédito',
                null,
                null,
                $card->bank,
                null,
                $card->line,
                $card->used,
                $report->created_at,
                'Activo'
            ];
        }

        foreach ($report->otherDebts as $debt) {
            $rows[] = [
                $report->id,
                $report->subscription->full_name,
                $report->subscription->document,
                $report->subscription->email,
                $report->subscription->phone,
                $debt->entity,
                'Otra deuda',
                null,
                $debt->expiration_days,
                $debt->entity,
                $debt->amount,
                null,
                null,
                $report->created_at,
                'Activo'
            ];
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nombre Completo',
            'DNI',
            'Email',
            'Teléfono',
            'Compañía',
            'Tipo de deuda',
            'Situación',
            'Atraso',
            'Entidad',
            'Monto total',
            'Línea total',
            'Línea usada',
            'Reporte subido el',
            'Estado'
        ];
    }

    /**
     * Define el tamaño del chunk para la exportación.
     *
     * Este método especifica cuántos registros se procesarán en cada lote
     * durante la exportación para optimizar el uso de memoria.
     *
     * @return int El número de registros por chunk (1000)
     */
    public function chunkSize(): int
    {
        return 1000;
    }

    /**
     * Maneja los errores cuando falla la exportación del reporte de crédito.
     * 
     * Este método se ejecuta automáticamente cuando ocurre una excepción durante
     * el proceso de exportación. Registra información detallada del error en los logs
     * del sistema, incluyendo el rango de fechas, el mensaje de error y el stack trace.
     * 
     * NOTA: Este método se puede ampliar para ejecutar lógica adicional en caso de fallo,
     * como enviar notificaciones por email, actualizar el estado en BD, crear alertas,
     * o cualquier otra acción necesaria cuando un reporte falla.
     *
     * @param Throwable $e La excepción que causó el fallo en la exportación
     * @return void
     */
    public function failed(Throwable $e): void
    {
        Log::error('Credit report export failed', [
            'date_range' => [$this->startDate, $this->endDate],
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);
    }
}