<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePelanggaranPostingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pelanggaran_posting', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('posting_id')->unsigned();
            $table->foreign('posting_id')->references('id')->on('posting');
            $table->integer('pelanggaran_id')->unsigned();
            $table->foreign('pelanggaran_id')->references('id')->on('pelanggaran');
            //$table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pelanggaran_posting');
    }
}
