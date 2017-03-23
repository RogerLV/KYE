<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DatabaseInitial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // staff
        Schema::create('Staff', function (Blueprint $table) {
            
        });

        // department

        // role mapping

        // occupational risk

        // key operation

        // operation log
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::drop('Staff');
    }
}
