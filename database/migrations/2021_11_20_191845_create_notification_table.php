<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['T'])->comment("Store notification type");
            $table->enum('send', ['Y', 'N'])->comment("Stores whether the notification was sent with (Y) as yes and (N) as no");
            $table->string('title')->comment("Stores the title of the notification that will be sent");
            $table->text('message')->comment("Stores the message of the notification that will be sent");
            $table->unsignedBigInteger('id_user')->comment("Stores the id of the user who was notified");
            $table->foreign('id_user', 'fk_id_user')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notification');
    }
}
