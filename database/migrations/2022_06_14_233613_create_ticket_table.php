<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTicketTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticket', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('student_id')->unsigned()->nullable();
            $table->foreign('student_id')
                ->references('id')
                ->on('student')
                ->onDelete('cascade');
            $table->bigInteger('professor_id')->unsigned()->nullable();
            $table->foreign('professor_id')
                ->references('id')
                ->on('professor')
                ->onDelete('cascade');
            $table->bigInteger('menu_id')->unsigned()->nullable();
            $table->foreign('menu_id')
                ->references('id')
                ->on('menu')
                ->onDelete('cascade');
            $table->bigInteger('quantity')->nullable();
            $table->boolean('consumed')->default(false);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('ticket');
    }
}
