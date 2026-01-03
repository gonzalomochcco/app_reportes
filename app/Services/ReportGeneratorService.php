<?php

namespace App\Services;
use App\Exports\CreditReportExport;
use App\Jobs\NotifyReportReady;
use Maatwebsite\Excel\Facades\Excel;

class ReportGeneratorService
{
    /**
     * Genera un reporte de crédito y lo encola para procesamiento asíncrono.
     * 
     * Este método crea un archivo Excel con datos de crédito dentro del rango de fechas
     * especificado y lo guarda en el disco local. Utiliza colas para procesamiento en segundo plano.
     * 
     * **OPTIMIZACIÓN - Excel::queue() con Chunking:**
     * Excel::queue() activa automáticamente el chunking (definido en CreditReportExport).
     * Cada chunk de 1000 registros se procesa en un job separado, permitiendo manejar
     * cientos de miles o incluso millones de registros sin problemas de memoria.
     * Todos los chunks se ejecutan en paralelo según los workers disponibles.
     * 
     * **Uso de chain():**
     * El método chain() espera a que TODOS los chunks se ejecuten correctamente antes
     * de ejecutar NotifyReportReady. Esto garantiza que el archivo Excel esté completamente
     * generado y guardado antes de notificar al usuario que está listo para descargar.
     * 
     * Si cualquier chunk falla, los trabajos encadenados NO se ejecutarán, evitando
     * notificaciones de reportes incompletos o corruptos.
     *
     * @param string $startDate Fecha de inicio del reporte en formato válido (ej: 'Y-m-d')
     * @param string $endDate Fecha de fin del reporte en formato válido (ej: 'Y-m-d')
     * 
     * @return void
     * 
     * @throws \Exception Si hay un error en la generación del nombre del archivo
     */
    public function generate(string $startDate, string $endDate): void
    {

        $fileName = $this->generateFileName($startDate, $endDate);

        Excel::queue(
            new CreditReportExport($startDate, $endDate),
            $fileName,
            'local'
        )->chain([
                    new NotifyReportReady($fileName)
                ]);

    }

    private function generateFileName(string $startDate, string $endDate): string
    {
        return sprintf(
            'reports/Reporte_%s_%s_%s.xlsx',
            $startDate,
            $endDate,
            uniqid()
        );
    }
}