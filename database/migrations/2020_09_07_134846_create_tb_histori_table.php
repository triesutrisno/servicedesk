<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTbHistoriTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tb_histori', function (Blueprint $table) {            
            $table->increments('historiId');
            $table->smallInteger('tiketDetailId')->nullable();
            $table->string('keterangan',1500)->nullable();
            $table->smallInteger('progresId')->nullable();
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
        Schema::dropIfExists('tb_histori');
    }
}
