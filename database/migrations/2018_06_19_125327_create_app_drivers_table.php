<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppDriversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_drivers', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('caddress')->nullable();
            $table->string('paddress')->nullable();
            $table->string('idcard')->nullable();
            $table->string('lcard')->nullable();
            $table->string('phone')->nullable();
            $table->string('lcat')->nullable();
            $table->string('lexp')->nullable();
            $table->string('taxipermit')->nullable();
            $table->string('police')->nullable();
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
        Schema::dropIfExists('app_drivers');
    }
}
