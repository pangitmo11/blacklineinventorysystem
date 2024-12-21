<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStocksLevelTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks_level', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->text('description')->nullable(); // Description of the product
            $table->tinyInteger('stocks_level_status')->nullable()->default(null);
            $table->timestamps(); // Created and updated timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stocks_level');
    }
}
