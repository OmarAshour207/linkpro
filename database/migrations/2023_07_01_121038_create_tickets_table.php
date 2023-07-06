<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->date('date_from')->nullable();
            $table->date('date_to')->nullable();
            $table->time('time_from')->nullable();
            $table->text('content')->nullable();
            // 1 => on hold, 2 => processing, 3 => approved, 4 => rejected, 5 => delayed
            $table->tinyInteger('status')->default(1);

            $table->string('type')->nullable();

            $table->text('reason')->nullable();
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
        Schema::dropIfExists('tickets');
    }
};
