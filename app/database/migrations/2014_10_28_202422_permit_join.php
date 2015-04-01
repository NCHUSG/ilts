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
		// DB::statement('ALTER TABLE `ilt_identity_tags` MODIFY `u_id` INTEGER UNSIGNED NULL;');
		Schema::table('ilt_identity_tags', function($table)
		{
		    $table->renameColumn('u_id', 'u_id_tmp');
		});
		Schema::table('ilt_identity_tags', function($table)
		{
			$table->integer('u_id')->unsigned()->nullable();
		});
		$ilt_identity_tags = DB::table('ilt_identity_tags')->select(['i_id','u_id_tmp'])->get();
		foreach ($ilt_identity_tags as $i){
			var_dump($i);
			DB::table('ilt_identity_tags')->where('i_id', $i->i_id)->update(array('u_id' => $i->u_id_tmp));
		}

		Schema::table('ilt_identity_tags', function($table)
		{
			$table->dropColumn(array('u_id_tmp'));
		});
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

		DB::table('ilt_identity_tags')->whereNull('u_id')->delete();
	}

}
