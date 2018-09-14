<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('request_id')->nullable(); // from where this booking is
            $table->integer('user_id'); // person asking for substitution
            $table->integer('substitute_id'); // person filling the substitution
            $table->integer('nursery_id'); // nursery in which the substitution is occuring
            $table->integer('purpose_id')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->text('comment')->nullable();
            $table->integer('status')->default(0);
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
        Schema::dropIfExists('bookings');
    }
}
