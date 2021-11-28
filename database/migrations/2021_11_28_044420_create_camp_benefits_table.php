<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCampBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('camp_benefits', function (Blueprint $table) {
            $table->id();
            // cara ke 1 untuk relasi antar tabel
            // $table->bigInteger('camp_id')->unsigned();
            // $table->unsignedBigInteger('camp_id');

            // cara ke 2 untuk relasi antar tabel
            $table->foreignId('camp_id')->constrained();

            $table->string('name');
            $table->timestamps();

            // cara ke 1 untuk relasi antar tabel
            // $table->foreign('camp_id')->references('id')->on('camps');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('camp_benefits');
    }
}
