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
        Schema::create('requirements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete();
            $table->enum('source', ['chat', 'email', 'call', 'document', 'other']);
            $table->text('content');
            $table->enum('status', ['requested', 'agreed', 'rejected', 'pending_clarification']);
            $table->boolean('is_in_scope')->default(true);
            $table->text('clarification_notes')->nullable();
            $table->json('attachments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requirements');
    }
};
