<?php

namespace App\Jobs;

use App\Models\ReportDownload;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class NotifyReportReady implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        private string $filename
    ) {
    }

    /**
     * Maneja la notificación cuando un reporte está listo para descargar.
     * 
     * NOTA: La implementación actual de ReportDownload solo es demostrativa y está simplificada 
     * por fines de la prueba técnica.
     * En un entorno real este método debería implementar la lógica completa de notificación
     * de Laravel, incluyendo:
     * - Notificaciones por WebSockets para actualización en tiempo real
     * - Envío de correos electrónicos con el archivo adjunto o enlace de descarga
     * - Notificaciones push u otros canales configurados
     * - entre otros
     * 
     * Este método centraliza toda la lógica para notificar al usuario cuando su archivo Excel
     * está listo, ya sea enviando el archivo directamente o proporcionando un enlace seguro
     * para la descarga.
     * 
     * @return void
     */
    public function handle(): void
    {

        ReportDownload::create([
            'filename' => $this->filename,
            'status' => 'ready',
            'expires_at' => now()->addDays(7),
        ]);

        Log::info('Report ready for download', [
            'filename' => $this->filename,
        ]);

    }
}
