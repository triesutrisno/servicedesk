<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket_service', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ServiceName',150)->nullable();
            $table->smallInteger('id_layanan')->nullable();
            $table->string('min_eselon',5)->nullable();
            $table->string('keterangan',255)->nullable();
            $table->string('gambar',150)->nullable();
            $table->string('ServiceStatus',2)->nullable();
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
        Schema::dropIfExists('ticket_service');
    }
}
