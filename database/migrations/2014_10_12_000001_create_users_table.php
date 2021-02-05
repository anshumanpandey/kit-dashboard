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
			$table->engine = 'InnoDB';
            $table->id();
            $table->string('name');
			$table->string('role')->nullable();
            $table->string('email')->unique();
            $table->dateTime('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->foreignId('current_team_id')->nullable();
            $table->text('profile_photo_path')->nullable();
            $table->timestamps();
        });
		
		///insert admin
		DB::table('users')->insert([
			'role' => 'admin',
			'name' => 'Admin',
			'email' => 'admin@yopmail.com',
			'email_verified_at' => date('Y-m-d H:i:s'),
			'password' => Hash::make('kit@123%')
		]);
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
