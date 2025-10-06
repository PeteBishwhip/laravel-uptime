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
        Schema::create('status_pages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('domain')->nullable();
            $table->boolean('is_public')->default(true);
            $table->text('description')->nullable();
            $table->string('logo_url')->nullable();
            $table->string('custom_css')->nullable();
            $table->boolean('show_uptime_percentage')->default(true);
            $table->boolean('show_incident_history')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pages');
    }
};
