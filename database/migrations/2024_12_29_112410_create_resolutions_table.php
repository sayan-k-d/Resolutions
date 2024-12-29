<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResolutionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resolutions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name'); // Resolution name
            $table->text('description'); // Resolution description
            $table->string('status')->default('public'); // Status (e.g., public/private), default to 'public'
            $table->integer('likes')->default(0); // Count of likes, default to 0
            $table->integer('comments')->default(0); // Count of comments, default to 0
            $table->unsignedBigInteger('user_id'); // Foreign key for users table
            $table->timestamps(); // Created at and updated at timestamps

            // Define foreign key constraint
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resolutions');
    }
}
