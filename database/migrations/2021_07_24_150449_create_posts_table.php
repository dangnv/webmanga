<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->text('thumbnail')->comment('url for thumbnail');
            $table->string('title', 255);
            $table->string('slug', 512)->unique()->comment('slug for url');
            $table->integer('status')->default(0)->comment('status, 0: on going, 1: completed');
            $table->integer('is_new')->default(0)->comment('is_new, 1: new');
            $table->text('alt_names')->nullable();
            $table->string('author', 256)->nullable();
            $table->string('artist', 256)->nullable();
            $table->text('demographic')->nullable();
            $table->string('format', 256)->nullable();
            $table->text('description')->nullable();
            $table->bigInteger('views')->default(0);
            $table->timestamps();
            $table->index('status');
            $table->index('title');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
}
