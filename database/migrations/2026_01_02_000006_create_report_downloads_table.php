<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('report_downloads', function (Blueprint $table) {
            $table->id();
            $table->string('filename');
            $table->enum('status', ['processing', 'ready', 'failed'])->default('processing');
            $table->boolean('downloaded')->default(false);
            $table->timestamp('expires_at')->nullable();
            $table->timestamps();
            $table->index(['status', 'downloaded']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('report_downloads');
    }
};
