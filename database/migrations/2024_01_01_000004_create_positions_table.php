<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('election_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('min_select')->default(1);
            $table->integer('max_select')->default(1);
            $table->integer('order')->default(0);
            $table->timestamps();
            
            $table->index(['election_id', 'order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('positions');
    }
};
