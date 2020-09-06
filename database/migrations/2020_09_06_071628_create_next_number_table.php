<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNextNumberTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('next_number', function (Blueprint $table) {
            $table->id();
            $table->string('tahun',4)->nullable();         
            $table->string('keterangan',250)->nullable();  
            $table->integer('nextnumber')->nullable();           
            $table->string('status',2)->nullable();        
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
        Schema::dropIfExists('next_number');
    }
}
