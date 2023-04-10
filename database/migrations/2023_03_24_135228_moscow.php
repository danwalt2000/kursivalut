<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('moscow', function (Blueprint $table) {
            $table->id();
            $table->string('vk_id');
            $table->string('vk_user');
            $table->string('owner_id');
            $table->string('date');
            $table->text('content');
            $table->text('content_changed');
            $table->text('phone');
            $table->float('rate');
            $table->integer('phone_showed');
            $table->integer('link_followed');
            $table->integer('popularity');
            $table->string('link');
            $table->string('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('moscow');
    }
};
