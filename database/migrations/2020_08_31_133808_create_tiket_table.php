<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTiketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tiket', function (Blueprint $table) {
            $table->increments('tiketId');
            $table->string('kode_tiket',11)->nullable();
            $table->string('comp',10)->nullable();
            $table->string('unit',10)->nullable();
            $table->string('biro',10)->nullable();
            $table->string('nikUser',10)->nullable();
            $table->string('tiketEmail',256)->nullable();
            $table->smallInteger('layananId')->nullable();
            $table->smallInteger('serviceId')->nullable();
            $table->smallInteger('subServiceId')->nullable();
            $table->string('tiketKeterangan',1500)->nullable();
            $table->string('file',256)->nullable(); 
            $table->string('tiketApprove',2)->nullable();
            $table->dateTime('tiketTglApprove')->nullable();
            $table->string('tiketNikAtasan',10)->nullable();
            $table->string('tiketEmailAtasan',256)->nullable();
            $table->string('tiketApproveService',2)->nullable();
            $table->dateTime('tiketTglApproveService')->nullable();
            $table->string('tiketNikAtasanService',10)->nullable();
            $table->string('tiketEmailAtasanService',256)->nullable();
            $table->string('tiketPrioritas',3)->nullable();
            $table->string('tiketStatus',3)->nullable();
            $table->string('namaLengkap',100)->nullable();
            $table->string('nikLengkap',10)->nullable();
            $table->string('noHp',20)->nullable();
            $table->string('flagForward',1)->nullable();
            $table->string('flagFeedback',1)->nullable();
            $table->string('remarkFeedback',256)->nullable();
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
        Schema::dropIfExists('tiket');
    }
}
