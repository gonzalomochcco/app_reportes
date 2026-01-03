<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('subscription_reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_id')->constrained()->cascadeOnDelete();
            $table->string('period', 7);
            $table->timestamps();
            $table->index(['subscription_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subscription_reports');
    }
};
