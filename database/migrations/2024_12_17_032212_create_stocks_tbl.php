<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksTbl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks_tbl', function (Blueprint $table) {
            $table->id();
            $table->string('product_name')->nullable();
            $table->string('description')->nullable();
            $table->string('team_tech')->nullable();
            $table->string('account_no')->nullable();
            $table->string('j_o_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('serial_new_no')->nullable();
            $table->string('ticket_no')->nullable();
            $table->date('date_active')->nullable();
            $table->date('date_released')->nullable();
            $table->date('date_used')->nullable();
            $table->date('date_repaired')->nullable();
            $table->tinyInteger('status')->nullable()->default(null); // To store 0, 1, 2, 3, 4
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
        Schema::dropIfExists('stocks_tbl');
    }
}
