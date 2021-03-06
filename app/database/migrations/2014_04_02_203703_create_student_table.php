<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStudentTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ilt_user_students', function($table)
		{
		    $table->increments('id');
		    $table->integer('u_id')->unsigned()->unique();
		    $table->string('email');
		    $table->integer('number')->nullable()->unsigned();
		    $table->string('department')->nullable();
		    $table->integer('grade')->nullable()->unsigned();
		    $table->boolean('is_valid')->default(false);
		    $table->timestamps();
		    $table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		$prefix = 'bak_';
		$suffix = '_' . date('YmdHis');

        Schema::rename('ilt_user_students', $prefix . 'ilt_user_students' . $suffix);
	}

}
