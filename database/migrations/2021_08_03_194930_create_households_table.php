<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHouseholdsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('households', function (Blueprint $table) {
            $table->id();

            // FK
            $table->unsignedBigInteger('house_id');
            $table->foreign('house_id')->references('id')->on('houses');
            // ENDS HERE

            $table->string('household_no')->nullable();
            $table->string('land_ownership')->nullable();
            $table->string('cr')->nullable();
            $table->string('shared_to')->nullable();
            $table->string('electricity_connection')->nullable();
            $table->string('disaster_kit')->nullable();
            $table->string('praticing_waste_segregation')->nullable();
            $table->integer('status')->default(1);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('households');
    }
}
