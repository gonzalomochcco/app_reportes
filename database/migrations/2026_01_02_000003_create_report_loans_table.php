<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_report_id')->constrained()->cascadeOnDelete();
            $table->string('bank', 100);
            $table->string('status', 3);
            $table->string('currency', 3)->default('PEN');
            $table->decimal('amount', 12, 2);
            $table->integer('expiration_days')->default(0);
            $table->timestamps();
            $table->index(['subscription_report_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_loans');
    }
};
