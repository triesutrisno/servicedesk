<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiketDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiket_detail', function (Blueprint $table) {
            $table->increments('tiketDetailId');
            $table->smallInteger('tiketId')->nullable();
            $table->string('nikTeknisi',10)->nullable();
            $table->string('keterangan',1500)->nullable();
            $table->string('tiketDetailStatus',2)->nullable();
            $table->string('namaAkun',150)->nullable();
            $table->string('passwordAkun',250)->nullable();
            $table->date('tglWawancara')->nullable();
            $table->date('tglMulaiMengerjakan')->nullable();
            $table->date('tglSelesaiMengerjakan')->nullable();
            $table->date('tglImplementasi')->nullable();
            $table->date('tglPelatihan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tiket_detail');
    }
}
