<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBookingRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_requests', function (Blueprint $table) {
            $table->increments('id');
            $table->string('request_group');
            $table->integer('user_id'); // user requesting substitute
            $table->integer('substitute_id'); // user having an availability
            $table->integer('availability_id'); // requested availability
            $table->integer('nursery_id'); // nursery where the booking is needed
            $table->integer('workgroup_id')->nullable();
            $table->integer('purpose_id')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->text('message')->nullable();
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
        Schema::dropIfExists('booking_requests');
    }
}
