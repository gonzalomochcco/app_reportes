<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_report_id')->constrained()->cascadeOnDelete();
            $table->string('bank', 100);
            $table->string('currency', 3)->default('PEN');
            $table->decimal('line', 12, 2);
            $table->decimal('used', 12, 2);
            $table->timestamps();
            $table->index('subscription_report_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_credit_cards');
    }
};
