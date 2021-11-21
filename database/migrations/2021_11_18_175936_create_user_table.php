<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment("Store the username.");
            $table->string('document')->comment("Stores the user's document, which can be cpf or cnpj.");
            $table->string('email')->comment("Stores the user's email.");
            $table->string('password')->comment("Store user password.");
            $table->float('balance')->comment("Stores the user's opening balance");
            $table->enum('user_type', ['C', 'S'])->comment('Stores the user type being (C) Common type and (S) Shopkeeper type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user');
    }
}
