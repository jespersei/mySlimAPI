<?php

namespace Controllers;

use Interop\Container\ContainerInterface;
use Controllers\Base\BaseController;
use Models\User;


class MigrationController extends BaseController
{	
	public function migrate($request, $response)
	{
		$this->migrateUsersTable();
		$this->migrateClockTable();
		$this->migrateLogsTable();

		
		return $this->createResponse($response, true, ['msg' => 'Migration Successful']);
		
	}

	public function rollback($request, $response, $args)
	{
		//get token
		$tablename = isset($args['tablename']) ? $args['tablename'] : false;

		if(!$tablename){
			$this->db->schema()->drop('users');
			$this->db->schema()->drop('clock');
			$this->db->schema()->drop('logs');
			return $this->createResponse($response, true, ['msg' => 'Rollbacked 3 Tables']);
		}else{
			$this->db->schema()->drop($tablename);
			return $this->createResponse($response, true, ['msg' => 'Rollbacked Table : '.$tablename]);
		}

	}

	public function populate()
	{
		User::create([
			'username' => 'admin',
			'email'=>'perseus.magbutay@gmail.com', 
			'password'=>'123456', 
			'salt'=>sha1('admin'.'-'.strtotime(date('Y-m-d H:i:s')))
			]);
		echo 'done';
		exit;
		//return $this->createResponse($response, true, ['msg' => 'Successfully populated Users table.']);
	}

	public function migrateUsersTable()
	{
		$this->db->schema()->create('users', function($table)
		{
            $table->increments('id');
            $table->string('username');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('salt');
            $table->string('session_token')->nullable();
            $table->timestamps();
		});
	}

	public function migrateClockTable()
	{
		$this->db->schema()->create('clock', function($table)
		{
            $table->increments('id');
            $table->integer('user_id');
            $table->string('activity');
            $table->timestamps();
		});
	}

	public function migrateLogsTable()
	{
		$this->db->schema()->create('logs', function($table)
		{
            $table->increments('id');
            $table->integer('user_id');
            $table->string('activity');
            $table->text('details');
            $table->timestamps();
		});
	}
}