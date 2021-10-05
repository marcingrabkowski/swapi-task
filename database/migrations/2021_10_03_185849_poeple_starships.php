<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PoepleStarships extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() :void
    {
        Schema::create('people_starships', function (Blueprint $table) {
            $table->id();

            $table->integer('person_external_id')->index();
            $table->foreign('person_external_id')->references('external_id')->on('people');
            $table->integer('starship_external_id')->index();
            $table->foreign('starship_external_id')->references('external_id')->on('starships');

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
        Schema::drop('people_starships');
    }
}
