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
        Schema::create('company_formations', function (Blueprint $table) {
            $table->id();
            $table->string('reference_number')->unique();
            $table->enum('status', ['draft', 'submitted', 'under_review', 'approved', 'rejected', 'completed'])
                  ->default('draft');
            
            // Company basic info
            $table->string('company_name');
            $table->string('alternative_company_name')->nullable();
            $table->enum('designation', ['ltd', 'inc', 'corp', 'llc']);
            
            // JSON columns for complex data
            $table->json('point_of_contact');
            $table->json('company_info');
            $table->json('countries_of_interest');
            $table->json('shares_structure');
            $table->json('shareholders');
            $table->json('beneficial_owners');
            $table->json('directors');
            
            // Timestamps
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->text('notes')->nullable();
            
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('reference_number');
            $table->index('status');
            $table->index('company_name');
            $table->index('submitted_at');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_formations');
    }
};
