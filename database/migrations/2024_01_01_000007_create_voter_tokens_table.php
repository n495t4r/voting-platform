<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voter_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->foreignId('voter_id')->constrained()->onDelete('cascade');
            $table->string('token_hash'); // Store hashed version for security
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->integer('usage_count')->default(0);
            $table->integer('max_usage')->default(1);
            $table->enum('channel', ['email', 'sms', 'whatsapp'])->default('email');
            $table->timestamps();
            
            $table->index(['token_hash']);
            $table->index(['election_id', 'voter_id']);
            $table->index(['expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voter_tokens');
    }
};
