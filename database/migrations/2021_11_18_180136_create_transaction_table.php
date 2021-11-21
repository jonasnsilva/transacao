<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_payer')->comment("Stores paying user id.");
            $table->unsignedBigInteger('id_payee')->comment('Stores beneficiary user id');
            $table->float('value')->comment("Stores the transaction amount.");
            $table->foreign('id_payer', 'fk_id_payer')->references('id')->on('user');
            $table->foreign('id_payee', 'fk_id_payee')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
}
