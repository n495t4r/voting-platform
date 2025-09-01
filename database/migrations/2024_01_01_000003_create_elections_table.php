<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->timestamp('starts_at');
            $table->timestamp('ends_at');
            $table->enum('status', ['draft', 'scheduled', 'open', 'closed', 'archived'])->default('draft');
            $table->json('settings')->nullable(); // Feature flags and election-specific settings
            $table->timestamps();
            
            $table->index(['status']);
            $table->index(['starts_at', 'ends_at']);
            $table->index(['organization_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
