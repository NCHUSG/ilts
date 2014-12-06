<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PermitJoin extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::statement('ALTER TABLE `ilt_identity_tags` MODIFY `u_id` INTEGER UNSIGNED NULL;');
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		echo "This will going to destroy all invitaions from group!\nThat is, rows in ilt_identity_tags with null u_id will be destroyed!!\n";
		$answer = readline("Are you sure? [Y/n]");
		if($answer !== 'Y')
			throw new Exception("Canceled by User");

		DB::statement('DELETE FROM `ilt_identity_tags` WHERE `u_id` IS NULL;');
		DB::statement('ALTER TABLE `ilt_identity_tags` MODIFY `u_id` INTEGER UNSIGNED NOT NULL;');
	}

}
