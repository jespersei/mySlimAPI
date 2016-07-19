<?php

namespace Controllers;

use Controllers\Base\RestController;
use Models\User;

class TestController extends RestController
{
	public function create($request, $response, $args)
	{
		return $this->createResponse($response, true, ['action' => 'Post']);
	}
	public function retrieve($request, $response, $args)
	{
		$users = User::all()->toArray();
		return $this->createResponse($response, true, ['action' => 'Get', 'users' => $users]);
	}
	public function update($request, $response, $args)
	{
		return $this->createResponse($response, true, ['action' => 'Put']);
	}
	public function delete($request, $response, $args)
	{
		return $this->createResponse($response, true, ['action' => 'Delete']);
	}
}