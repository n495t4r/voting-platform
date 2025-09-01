<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('actor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->string('actor_type')->nullable(); // User, System, etc.
            $table->string('event'); // Action performed
            $table->json('payload')->nullable(); // Event details
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('previous_hash')->nullable(); // For hash chain integrity
            $table->string('current_hash')->nullable();
            $table->timestamps();
            
            $table->index(['election_id', 'created_at']);
            $table->index(['actor_id', 'created_at']);
            $table->index(['event']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
