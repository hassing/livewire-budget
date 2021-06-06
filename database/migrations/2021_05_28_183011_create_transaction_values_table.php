<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_values', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->tinyInteger("month");
            $table->decimal("value", 12, 2);
            $table->string("note", 1024);

            $table->unsignedBigInteger('post_id');
            $table->foreign('post_id')->references('id')->on('posts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_values');
    }
}
