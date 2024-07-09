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
        Schema::create('organisation_users', function (Blueprint $table) {
              $table->uuid('user_id');
        $table->uuid('organisation_id');
        $table->foreign('user_id')->references('userId')->on('users')->onDelete('cascade');
        $table->foreign('organisation_id')->references('id')->on('organisations')->onDelete('cascade');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('organisation_users');
    }
};
