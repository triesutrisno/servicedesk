<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMLayananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_layanan', function (Blueprint $table) {
            $table->increments('id');
            $table->string('kode_layanan',5)->nullable();
            $table->string('nama_layanan',150)->nullable();
            $table->string('keterangan',255)->nullable();
            $table->string('kode_biro',10)->nullable();
            $table->string('comp',5)->nullable();
            $table->string('status_layanan',2)->nullable();
            $table->string('gambar',150)->nullable();
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
        Schema::dropIfExists('m_layanan');
    }
}
