<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserPayloadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_payloads', function (Blueprint $table) {
            $table->id();
            $table->text("uid");
            $table->foreignId('operation')->constrained('operations');
            $table->foreignId("status")->constrained('user_operation_statuses');
            $table->text("message")->nullable();
            $table->text("payload")->nullable();
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
        Schema::dropIfExists('user_payloads');
    }
}
