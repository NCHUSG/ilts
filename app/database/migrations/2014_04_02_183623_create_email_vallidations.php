<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmailVallidations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('ilt_email_vallidations', function($table)
        {
            $table->increments('id');
            $table->integer('i_id')->unsigned();
            $table->string('type');
            $table->string('code');
            $table->string('email');
            $table->timestamp('expires');
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

        Schema::rename('ilt_email_vallidations', $prefix . 'ilt_email_vallidations' . $suffix);
	}

}
