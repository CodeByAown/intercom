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
        Schema::create('kits', function (Blueprint $table) {
            $table->id();
            $table->string('kit_number');
            $table->foreignId('site_id')->constrained()->onDelete('cascade')->onUpdate('cascade');
            $table->string('speed')->nullable();
            $table->boolean('poor_cable')->default(false);
            $table->boolean('update_pending')->default(false);
            $table->boolean('obstruction')->default(false);
            $table->boolean('login_issue')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kits');
    }
};
