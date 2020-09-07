<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMProgresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('m_progres', function (Blueprint $table) {
            $table->increments('progresId');
            $table->string('progresNama',150)->nullable();         
            $table->string('progresProsen',5)->nullable();      
            $table->string('progresStatus',2)->nullable();        
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
