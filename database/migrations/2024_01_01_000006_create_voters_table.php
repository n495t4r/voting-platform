<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('external_id')->nullable(); // For importing from external systems
            $table->enum('status', ['invited', 'verified', 'voted', 'revoked'])->default('invited');
            $table->timestamp('verified_at')->nullable();
            $table->timestamp('voted_at')->nullable();
            $table->timestamps();
            
            $table->index(['election_id', 'status']);
            $table->index(['email']);
            $table->index(['phone']);
            $table->unique(['election_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voters');
    }
};
