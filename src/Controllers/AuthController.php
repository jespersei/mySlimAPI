<?php

namespace Controllers;

use Controllers\Base\BaseController;
use Models\User;
use Models\Clock;

class AuthController extends BaseController
{
	public function authenticate($request, $response, $args)
	{
		$token = isset($request->getParsedBody()['token']) ? $request->getParsedBody()['token'] : false;
		$username = isset($request->getParsedBody()['username']) ? $request->getParsedBody()['username'] : false;
		
		//$user = User::whereUsername($username)->whereSessionToken($token)->get();
		$user = $this->db->table('users')->where('username', '=', $username )->where('session_token', '=', $token )->first();


		if(!empty($user)){
			$this->createResponse($response, true, ['msg' => 'Token verified.', 'token' => $token]);
		}else{
			$this->createResponse($response, false, ['msg' => 'Error in verfying token.']);
		}
	}

	public function login($request, $response, $args)
	{
		$username = isset($request->getParsedBody()['username']) ? $request->getParsedBody()['username'] : false;
		
		$user = $this->db->table('users')->where('username', '=', $username )->first();

		$password = isset($request->getParsedBody()['password']) ? $request->getParsedBody()['password'] : false;
		
		$hashedPassword =  sha1($user->salt.$password);

		if($user->password == $hashedPassword){
			$sessionToken = sha1(time().$user->salt);
			$this->db->table('users')->where('username', '=', $username )->update(['session_token' => $sessionToken]);
			Clock::create(['user_id' => $user->id, 'activity' => 'login']);
			$this->createResponse($response, true, ['session_token' => $sessionToken]);
		}else{
			$this->createResponse($response, false, ['msg' => 'Invalid Username/Password.']);
		}
	}

	public function logout($request, $response, $args)
	{
		$token = $this->getArg($args, 'token');

		if($token){
			$user = $this->db->table('users')->where('session_token', '=', $token )->first();
			if($user){
				$this->db->table('users')->where('session_token', '=', $token )->update(['session_token' => '']);
				Clock::create(['user_id' => $user->id, 'activity' => 'logout']);
				$this->createResponse($response, true, ['msg' => 'User "'.$user->username.'" has been logged out']);	
			}else{
				$this->createResponse($response,false, ['msg' => 'No token found']);
			}
		}		
	}

}