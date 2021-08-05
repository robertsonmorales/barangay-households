<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individuals', function (Blueprint $table) {
            $table->id();
            // FK
            $table->unsignedBigInteger('family_id');
            $table->foreign('family_id')->references('id')->on('families');
            // ENDS HERE
            $table->string('individual_no')->nullable();
            $table->text('last_name')->nullable();
            $table->text('first_name')->nullable();
            $table->text('middle_name')->nullable();
            $table->string('suffix')->nullable();
            $table->string('gender')->nullable();
            $table->text('birthdate')->nullable();
            $table->string('ethnicity')->nullable();
            $table->string('relationship')->nullable();
            $table->string('marital_status')->nullable();
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
        Schema::dropIfExists('individuals');
    }
}
