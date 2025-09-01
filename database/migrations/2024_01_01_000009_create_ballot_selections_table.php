<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ballot_selections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ballot_id')->constrained()->onDelete('cascade');
            $table->foreignId('position_id')->constrained()->onDelete('cascade');
            $table->foreignId('candidate_id')->constrained()->onDelete('cascade');
            $table->integer('rank')->nullable(); // For ranked choice voting
            $table->timestamps();
            
            $table->index(['ballot_id', 'position_id']);
            $table->index(['position_id', 'candidate_id']);
            $table->unique(['ballot_id', 'position_id', 'candidate_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballot_selections');
    }
};
