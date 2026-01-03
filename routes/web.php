<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\ReportGenerator;

Route::redirect('/', '/reports');

Route::get('/reports', ReportGenerator::class)
    ->name('reports.index');
