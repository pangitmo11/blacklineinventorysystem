<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('stocks_materials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('description_id')->nullable();
            $table->unsignedBigInteger('stocks_id')->nullable();
            $table->softDeletes();
            $table->timestamps();

            // Foreign keys
            $table->foreign('description_id')->references('id')->on('stocks_level')->onDelete('cascade');
            $table->foreign('stocks_id')->references('id')->on('stocks_tbl')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks_materials');
    }
};
