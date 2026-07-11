<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('category'); // Health, Career, Learning, Personal, Finance
            $table->date('start_date');
            $table->date('target_date');
            $table->unsignedTinyInteger('progress')->default(0); // 0-100
            $table->unsignedInteger('streak')->default(0);
            $table->date('last_checkin')->nullable();
            $table->string('status')->default('active'); // active, completed
            $table->string('reward')->nullable();
            $table->boolean('reward_unlocked')->default(false);
            $table->decimal('amount_target', 12, 2)->nullable(); // Finance goals only
            $table->decimal('amount_saved', 12, 2)->nullable();  // Finance goals only
            $table->date('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};
