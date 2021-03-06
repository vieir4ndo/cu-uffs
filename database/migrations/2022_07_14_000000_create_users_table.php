<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('uid')->unique();
            $table->text('enrollment_id')->unique();
            $table->string('email')->unique();
            $table->string('password');
            $table->rememberToken();
            $table->text('profile_photo');
            $table->text('bar_code');
            $table->foreignId('type')->constrained('user_types');
            $table->boolean('active')->default(true);
            $table->boolean('status_enrollment_id');
            $table->text('course')->nullable();
            $table->date('birth_date');
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
        Schema::dropIfExists('users');
    }
}
