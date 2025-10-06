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
        Schema::create('monitors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type'); // http_ping, heartbeat, ssl_certificate, domain_expiration
            $table->string('url')->nullable();
            $table->integer('check_interval')->default(60); // in seconds
            $table->integer('timeout')->default(10); // in seconds
            $table->string('method')->default('GET'); // for HTTP monitors
            $table->text('expected_status_codes')->nullable(); // JSON array
            $table->text('headers')->nullable(); // JSON array
            $table->text('body')->nullable();
            $table->string('keyword')->nullable(); // for keyword monitoring
            $table->boolean('keyword_present')->default(true);
            $table->integer('grace_period')->default(0); // for heartbeat monitors, in seconds
            $table->integer('expected_heartbeat')->default(3600); // expected heartbeat interval in seconds
            $table->boolean('is_active')->default(true);
            $table->string('status')->default('unknown'); // up, down, unknown
            $table->timestamp('last_checked_at')->nullable();
            $table->timestamp('next_check_at')->nullable();
            $table->timestamp('last_heartbeat_at')->nullable();
            $table->integer('uptime_percentage')->default(100);
            $table->integer('average_response_time')->default(0); // in milliseconds
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitors');
    }
};
