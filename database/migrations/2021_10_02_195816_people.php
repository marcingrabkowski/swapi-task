<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class People extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() :void
    {
        Schema::create('people', function (Blueprint $table) {
            $table->id();
            $table->integer('external_id')->index();
            $table->string('name');
            $table->string('height');
            $table->string('mass');
            $table->string('hair_color');
            $table->string('gender');
            $table->integer('homeworld')->index()->nullable();
            $table->foreign('homeworld')
                ->references('external_id')
                ->on('planets');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() :void
    {
        Schema::drop('people');
    }
}
