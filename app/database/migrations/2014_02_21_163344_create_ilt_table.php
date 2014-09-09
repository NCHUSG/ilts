<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIltTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ilt_users', function($table)
		{
		    $table->increments('u_id');
		    $table->string('u_username')->unique();
		    $table->string('u_nick');
		    $table->string('u_email')->unique();
		    $table->string('u_status')->default('guest');
		    $table->string('u_authority')->nullable();
		    $table->timestamps();
		    $table->softDeletes();

		    // # 欄位特別說明
		    //
		    // ## u_status: 使用者狀態：
		    // 	- 未認證: Guest
		    // 	- 已認證: Certified（Email確認，只通過Provider不算，因為有可能是FB Provider）
		    // 	- 自主停用: Deactivate
		    // 	- 黑名單: Ban
		    //
		    // ## u_authority: 使用者權柄：
		   	//  - 管理
		    // 		- 總管理權限: ADMIN_ALL
		    // 		- 使用者管理權限： ADMIN_USER
		    // 		- 群組管理權限： ADMIN_GROUP
		    // 		- 身份標籤管理權限： ADMIN_TAG
		    // 		- 應用程式管理權限： ADMIN_APP

		});

		Schema::create('ilt_user_options', function($table)
		{
		    $table->integer('u_id')->unique();
		    $table->string('u_first_name')->nullable();
		    $table->string('u_last_name')->nullable();
		    $table->string('u_gender')->nullable();
		    $table->string('u_birthday')->nullable();
		    $table->string('u_phone')->nullable();
		    $table->string('u_address')->nullable();
		    $table->string('u_website')->nullable();
		    $table->string('u_gravatar')->nullable();
		    $table->text('u_description')->nullable();
		    $table->timestamps();
		    $table->softDeletes();
		});

		Schema::create('ilt_user_providers', function($table)
		{
		    $table->increments('u_p_id');
		    $table->string('u_p_type');
		    $table->string('u_p_identifier');
		    $table->string('u_p_email')->nullable();
			$table->string('u_id')->nullable();
		    $table->timestamps();
		    $table->softDeletes();
		});

		Schema::create('ilt_groups', function($table)
		{
		    $table->increments('g_id');
		    $table->string('g_code')->unique();
		    $table->string('g_name');
		    $table->integer('g_parent_id')->unsigned()->default(0);
		    $table->integer('g_level_sort')->unsigned()->default(0);
		    $table->string('g_status')->default('');
		    $table->timestamps();
		    $table->softDeletes();
		});

		Schema::create('ilt_identity_tags', function($table)
		{
		    $table->increments('i_id');
		    $table->integer('u_id')->unsigned();
		    $table->integer('g_id')->unsigned();
			$table->string('i_authority')->nullable();
		    $table->string('i_status')->default('');
		    $table->timestamps();
		    $table->softDeletes();
		});

		Schema::create('ilt_authority', function($table)
		{
		    $table->increments('a_id')->unique();
		    $table->string('a_type');
		    $table->string('a_code')->unique();
		    $table->string('a_name');
		    $table->string('a_comment');
		    $table->timestamps();

		    // # 注意
		    // 本資料表僅是用來說明，不會和其他程式產生關係。
		    // 通常是用來管理權柄，避免出現同名權柄，
		    // 以及用來統計有何種權柄的人數之類的資料。
		    //
		    // # 欄位特別說明
		    //
		    // ## a_type: 權柄類別：
		    // 	- 管理: Admin
		    // 	- 群組: Group
		    // 	- 應用程式: Scope
		    //
		    // ## a_code: 權柄代碼：
		   	//  - 用來便是權柄的代碼，以大小英文字母組成。
		    //
		    // ## a_name: 權柄名稱：
		   	//  - 權柄的中文名稱，方便理解與辨識。

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

		Schema::rename('ilt_users', 		$prefix . 'ilt_users' . $suffix);
		Schema::rename('ilt_user_options', 	$prefix . 'ilt_user_options' . $suffix);
		Schema::rename('ilt_user_providers',$prefix . 'ilt_user_providers' . $suffix);
		Schema::rename('ilt_groups', 		$prefix . 'ilt_groups' . $suffix);
		Schema::rename('ilt_identity_tags', $prefix . 'ilt_identity_tags' . $suffix);
		Schema::rename('ilt_authority', 	$prefix . 'ilt_authority' . $suffix);
	}

}
