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
            $table->string('speed');
            $table->boolean('poor_cable');
            $table->boolean('update_pending');
            $table->boolean('obstruction');
            $table->boolean('login_issue');
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
