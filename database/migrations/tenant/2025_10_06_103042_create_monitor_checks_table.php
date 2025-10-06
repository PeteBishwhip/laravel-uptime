<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('monitor_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('monitor_id')->constrained()->onDelete('cascade');
            $table->string('status'); // up, down, error
            $table->integer('response_time')->nullable(); // in milliseconds
            $table->integer('status_code')->nullable();
            $table->text('error_message')->nullable();
            $table->text('response_headers')->nullable(); // JSON
            $table->text('ssl_certificate_info')->nullable(); // JSON for SSL cert monitoring
            $table->timestamp('checked_at');
            $table->timestamps();

            $table->index(['monitor_id', 'checked_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitor_checks');
    }
};
