<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();;
            $table->dateTime("date_time");
            $table->integer('amount');
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->foreignId('third_party_cashier_employee_id')->constrained('users');
            $table->foreignId('type')->constrained('ticket_or_entry_types');
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
}
