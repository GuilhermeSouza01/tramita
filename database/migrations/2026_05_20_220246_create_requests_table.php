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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('request_type_id')->constrained()->restrictOnDelete();
            $table->foreignId('requester_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('department_id')->constrained()->restrictOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('status')->default('draft');
            $table->json('form_data')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('due_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['status', 'created_at']);
            $table->index(['requester_id', 'status']);
            $table->index('due_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
