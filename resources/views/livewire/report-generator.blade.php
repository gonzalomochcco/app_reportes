<div class="flex items-center justify-center p-6 lg:p-8" wire:poll.10s="checkPendingReport">

    <x-ui.toast position="top-right" maxToasts="5" />

    <div class="w-full max-w-2xl">

        <div class="text-center mb-12">
            <div class="inline-flex items-center justify-center mb-6">
                <div class="w-12 h-12 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
                <div class="text-left">
                    <h1 class="text-2xl font-bold text-gray-900">
                        Generador de Reportes
                    </h1>
                    <p class="text-base text-gray-600">
                        para <span class="text-red-500 font-semibold">Créditos</span>
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden">

            <div class="px-8 py-6 border-b border-gray-100">
                <h2 class="text-xl font-semibold text-gray-900 text-center">
                    Generar Reporte | Exportar a Excel
                </h2>
            </div>

            <div class="px-8 py-10">

                <form wire:submit.prevent="generateReport" class="space-y-8 mb-8">

                    <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="space-y-2">
                                <label for="start_date" class="block text-sm font-medium text-gray-700">
                                    Fecha Inicio
                                </label>
                                <input type="date" id="start_date" wire:model.live="start_date"
                                    class="block w-full px-4 py-3 bg-white rounded-lg border @error('start_date') border-red-500 @else border-gray-300 @enderror text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                                @error('start_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-2">
                                <label for="end_date" class="block text-sm font-medium text-gray-700">
                                    Fecha Fin
                                </label>
                                <input type="date" id="end_date" wire:model.live="end_date"
                                    class="block w-full px-4 py-3 bg-white rounded-lg border @error('end_date') border-red-500 @else border-gray-300 @enderror text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all duration-200">
                                @error('end_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div>
                        <x-ui.button type="submit" size="lg" icon="arrow-path"
                            class="w-full flex items-center justify-center rounded-lg" wire:loading.attr="disabled"
                            wire:target="generateReport">
                            Generar reporte
                        </x-ui.button>
                    </div>

                </form>

                @if (count($pendingReports) > 0)
                    <div class="mb-8 max-h-96 overflow-y-auto space-y-3 pr-2">
                        @foreach ($pendingReports as $report)
                            <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex items-center space-x-3">
                                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <div>
                                            <p class="font-medium text-green-900">Reporte listo</p>
                                            <p class="text-sm text-green-700">{{ basename($report->filename) }}</p>
                                        </div>
                                    </div>
                                    <x-ui.button wire:click="downloadReport({{ $report->id }})" icon="arrow-down-tray"
                                        size="sm" wire:loading.attr="disabled" wire:target="downloadReport"
                                        class="bg-green-600 hover:bg-green-700 text-white cursor-pointer rounded-lg">
                                        Descargar
                                    </x-ui.button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="text-center">
                    <button type="button" disabled
                        class="text-sm text-red-500 hover:text-red-600 font-medium transition-colors duration-200">
                        ¿Necesitas ayuda?
                    </button>
                </div>

            </div>
        </div>

        <div class="mt-8 text-center">
            <p class="text-sm text-gray-600">
                Verificación de Credenciales y Generación de Reportes para Análisis Crediticio Mejorado
            </p>
            <button
                class="mt-4 text-sm text-red-500 hover:text-red-600 font-medium underline transition-colors duration-200">
                Ver ejemplo de reporte
            </button>
        </div>

        <div class="mt-12 text-center">
            <p class="text-sm text-gray-500">
                Desarrollado por Gonzalo Mochcco con <span class="text-red-500">♥</span> usando Laravel
            </p>
        </div>
    </div>
</div>
