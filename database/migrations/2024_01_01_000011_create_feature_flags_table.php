<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_flags', function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->json('value'); // Boolean, string, or complex configuration
            $table->enum('scope', ['global', 'election'])->default('global');
            $table->foreignId('election_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->unique(['key', 'scope', 'election_id']);
            $table->index(['key', 'scope']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('feature_flags');
    }
};
