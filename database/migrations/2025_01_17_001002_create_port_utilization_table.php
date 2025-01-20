<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePortUtilizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('port_utilization', function (Blueprint $table) {
            $table->id(); // Auto-incrementing primary key
            $table->string('municipality')->nullable(); // Municipality
            $table->string('brgy_code')->nullable(); // Barangay code
            $table->string('barangay')->nullable(); // Barangay name
            $table->string('napcode')->nullable(); // Napcode
            $table->decimal('longitude', 10, 6)->nullable(); // Longitude with precision
            $table->decimal('latitude', 10, 6)->nullable(); // Latitude with precision
            $table->integer('no_of_deployed')->nullable(); // Number of deployed (integer)
            $table->integer('no_of_active')->nullable(); // Number of active (integer)
            $table->integer('no_of_available')->nullable(); // Number of available (integer)
            $table->timestamps(); // Created at and Updated at timestamps
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('port_utilization');
    }
}
