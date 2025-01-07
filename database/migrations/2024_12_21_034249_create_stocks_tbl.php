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
            $table->unsignedBigInteger('description_id')->nullable();
            $table->string('team_tech')->nullable();
            $table->string('subsname')->nullable();
            $table->string('subsaccount_no')->nullable();
            $table->string('account_no')->nullable();
            $table->string('j_o_no')->nullable();
            $table->string('sar_no')->nullable();
            $table->string('serial_no')->nullable();
            $table->string('serial_new_no')->nullable();
            $table->string('ticket_no')->nullable();
            $table->date('date_active')->nullable();
            $table->date('date_released')->nullable();
            $table->date('date_used')->nullable();
            $table->date('date_repaired')->nullable();
            $table->tinyInteger('status')->nullable()->default(null);
            $table->timestamps();

            // Foreign Key Constraints
            $table->foreign('description_id')->references('id')->on('stocks_level')->onDelete('set null');
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
