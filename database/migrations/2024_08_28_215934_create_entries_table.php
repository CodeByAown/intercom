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
        Schema::create('entries', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->foreignId('client_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('site_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->foreignId('kit_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('speed'); // Current speed
            $table->boolean('poor_cable'); // Indicates if there's a poor cable issue
            $table->boolean('update_pending'); // Indicates if updates are pending
            $table->boolean('obstruction'); // Indicates if there's an obstruction
            $table->boolean('login_issue'); // Indicates if there are login issues

            // New fields based on the updated form
            $table->integer('speed_10mbps')->nullable(); // 10MBPS Speed
            $table->integer('speed_100mbps')->nullable(); // 100MBPS Speed
            $table->integer('speed_1gbps')->nullable(); // 1GBPS Speed
            $table->integer('yes_cablefield')->nullable(); // Yes for Poor Cable
            $table->integer('no_cablefield')->nullable(); // No for Poor Cable
            $table->integer('auto_reboot')->nullable(); // Auto Reboot count
            $table->integer('manual_reboot')->nullable(); // Manual Reboot count
            $table->integer('no_updatepending')->nullable(); // No for Update Pending
            $table->integer('full_obstruction')->nullable(); // Full Obstruction count
            $table->integer('partial_obstruction')->nullable(); // Partial Obstruction count
            $table->integer('no_obstruction')->nullable(); // No Obstruction count
            $table->integer('yes_login_issue')->nullable(); // Yes for Login Issues
            $table->integer('no_login_issue')->nullable(); // No for Login Issues

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entries');
    }
};
