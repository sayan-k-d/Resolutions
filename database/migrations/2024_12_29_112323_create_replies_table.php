<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRepliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('replies', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('user_id'); // Foreign key for users table
            $table->unsignedBigInteger('comment_id'); // Foreign key for comments table
            $table->unsignedBigInteger('resolution_id'); // Foreign key for resolutions table
            $table->unsignedBigInteger('reply_id')->nullable(); // Self-referencing foreign key for nested replies
            $table->string('user_name'); // Name of the user
            $table->text('reply'); // The reply content
            $table->timestamps(); // Created at and updated at timestamps

            // Define foreign key constraints
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('comment_id')->references('id')->on('comments')->onDelete('cascade');
            $table->foreign('resolution_id')->references('id')->on('resolutions')->onDelete('cascade');
            $table->foreign('reply_id')->references('id')->on('replies')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('replies');
    }
}
