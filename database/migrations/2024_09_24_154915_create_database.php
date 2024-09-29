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
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('interests', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('interests_users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('interest_id');
            $table->foreign('interest_id')->references('id')->on('interests')->onDelete('cascade');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unique(['user_id', 'interest_id']);
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('faculty_id')->index();
            $table->foreign('faculty_id')->references('id')->on('faculties')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['faculty_id']);
            $table->dropForeign(['faculty_id']);
            $table->dropColumn('faculty_id');
        });
        Schema::dropIfExists('interests_users');
        Schema::dropIfExists('interests');
        Schema::dropIfExists('faculties');




    }
};
