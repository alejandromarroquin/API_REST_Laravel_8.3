<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Order', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('idUser')->unsigned();
            $table->foreign('idUser')->references('id')->on('User')->onDelete('cascade');
            $table->bigInteger('idProduct')->unsigned();
            $table->foreign('idProduct')->references('id')->on('Product')->onDelete('cascade');
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
        Schema::dropIfExists('Order');
    }
}
