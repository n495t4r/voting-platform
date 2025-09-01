<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ballots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->string('ballot_uid')->unique(); // Public identifier for receipts
            $table->foreignId('voter_id')->nullable()->constrained()->onDelete('set null'); // Can be anonymized
            $table->timestamp('submitted_at');
            $table->integer('revision')->default(1); // For revote tracking
            $table->string('hash_chain')->nullable(); // For audit integrity
            $table->timestamps();
            
            $table->index(['election_id', 'submitted_at']);
            $table->index(['ballot_uid']);
            $table->index(['voter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ballots');
    }
};
