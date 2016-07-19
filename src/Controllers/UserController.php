<?php

namespace Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;
use Controllers\Base\RestController;
use Models\User;

class UserController extends RestController
{
	public $displayableFields = ['id','username','email','session_token'];
	public $searchable = ['username','email'];

	public function create($request, $response)
	{
		$parsedBody = $request->getParsedBody();
		$data = $parsedBody['data'];

		User::create([
			'username' => $data['username'],
			'email'    => $data['email'], 
			'password' => $data['password'], 
			'salt'     => ''
		]);
		return $this->createResponse($response, true);
	}

	public function retrieve($request, $response, $args)
	{
		if(isset($args['id'])){
			$users = User::where('id', $args['id'])
			->get($this->displayableFields)
			->toArray();
		}else{
			$users = User::all($this->displayableFields)
			->toArray();	
		}
		
		return $this->createResponse($response, true, ['action' => 'Get', 'users' => $users, 'params' => $args]);
	}

	public function update($request, $response, $args)
	{
		$id = isset($args['id']) ? $args['id'] : false;

		if(!$id)
			return $this->createResponse($response, false, ['msg' => 'No Id Presented']);

		$data = $request->getParsedBody()['data'];

		$user = User::find($id);

		$user->update($data);

		return $this->createResponse($response, true);
	}

	public function delete($request, $response, $args)
	{
		$id = isset($args['id']) ? $args['id'] : false;

		if(!$id)
			return $this->createResponse($response, false, ['msg' => 'No Id Presented. ']);

		$user = User::find($id);

		if(!$user)
			return $this->createResponse($response, false, ['msg' => 'User doesnt exists. ']);

		$user->delete($user);
		
		return $this->createResponse($response, true);
	}

	public function find($request, $response, $args)
	{
		$params = explode('/', $args['params']);
		$searchArguments = $this->getSearchArray($params);

		foreach ($searchArguments as $key => $value) {
			$user = $this->db->table('users')->where($key, 'LIKE', '%'.$value.'%');
		}

		$users = $user->get();

		return $this->createResponse($response, true, ['Method' => 'Find', 'params' => $searchArguments, 'results' => $users]);
	}

}