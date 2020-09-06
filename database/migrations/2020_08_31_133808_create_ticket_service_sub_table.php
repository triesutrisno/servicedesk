<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketServiceSubTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_service_sub', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('ServiceIDf')->nullable();
            $table->string('ServiceSubName',150)->nullable();
            $table->string('ServiceSubStatus',2)->nullable();
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
        Schema::dropIfExists('ticket_service_sub');
    }
}
