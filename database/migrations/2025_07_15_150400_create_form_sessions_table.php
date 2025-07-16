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
        Schema::create('form_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 255)->unique();
            $table->integer('current_step')->default(1);
            $table->json('form_data');
            $table->timestamp('last_saved_at');
            $table->timestamps();
            
            // Indexes
            $table->index('session_id');
            $table->index('last_saved_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_sessions');
    }
};
