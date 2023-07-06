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
        Schema::create('ticket_data', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('ticket_id')->nullable();
            $table->foreign('ticket_id')->references('id')->on('tickets')->onDelete('cascade');

            $table->unsignedBigInteger('supply_id')->nullable();
            $table->foreign('supply_id')->references('id')->on('supplies')->onDelete('cascade');

            $table->unsignedBigInteger('officecontent_id')->nullable();
            $table->foreign('officecontent_id')->references('id')->on('office_contents')->onDelete('cascade');

            $table->integer('quantity')->nullable();
            $table->string('unit')->nullable();

            $table->text('notes')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticket_data');
    }
};
