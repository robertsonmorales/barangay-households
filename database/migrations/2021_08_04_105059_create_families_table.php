<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFamiliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('families', function (Blueprint $table) {
            $table->id();

            // FK
            $table->unsignedBigInteger('household_id');
            $table->foreign('household_id')->references('id')->on('households');
            // ENDS HERE

            $table->string('family_no')->nullable();
            $table->string('family_name')->nullable();
            $table->string('have_cell_radio_tv')->nullable();
            $table->string('have_vehicle')->nullable();
            $table->string('have_bicycle')->nullable();
            $table->string('have_pedicab')->nullable();
            $table->string('have_motorcycle')->nullable();
            $table->string('have_tricycle')->nullable();
            $table->string('have_four_wheeled')->nullable();
            $table->string('have_truck')->nullable();
            $table->string('have_motor_boat')->nullable();
            $table->string('have_boat')->nullable();
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
        Schema::dropIfExists('families');
    }
}
