<?php

namespace Controllers;

use Illuminate\Database\Capsule\Manager as Capsule;
use Controllers\Base\RestController;
use Models\Clock;
use Models\User;

class ClockController extends RestController
{
	public $displayableFields = ['id','user_id','activity','created_at'];
	public $searchable = ['activity'];

	public function create($request, $response)
	{
		$parsedBody = $request->getParsedBody();
		$data = $parsedBody['data'];

		Clock::create([
			'activity' => $data['activity']
		]);
		return $this->createResponse($response, true);
	}

	public function retrieve($request, $response, $args)
	{
		if(isset($args['id'])){
			$clockEntries = User::find($args['id'])
			->clock()
			->get($this->displayableFields)
			->toArray();
		}else{
			$clockEntries = Clock::all($this->displayableFields)
			->toArray();	
		}
		
		return $this->createResponse($response, true, ['action' => 'Get', 'Clock Entries' => $clockEntries, 'params' => $args]);
	
	}

	public function update($request, $response, $args)
	{
		return false;
	}

	public function delete($request, $response, $args)
	{
		$id = isset($args['id']) ? $args['id'] : false;

		if(!$id)
			return $this->createResponse($response, false, ['msg' => 'No Id Presented. ']);

		$clockEntry = Clock::find($id);

		if(!$clockEntry)
			return $this->createResponse($response, false, ['msg' => 'Entry doesnt exists. ']);

		$clockEntry->delete($clockEntry);
		
		return $this->createResponse($response, true);
	}

	public function find($request, $response, $args)
	{
		$params = explode('/', $args['params']);
		$searchArguments = $this->getSearchArray($params);

		foreach ($searchArguments as $key => $value) {
			$clock = $this->db->table('clock')->where($key, 'LIKE', '%'.$value.'%');
		}

		$clockEntries = $clock->get();

		return $this->createResponse($response, true, ['Method' => 'Find', 'params' => $searchArguments, 'results' => $clockEntries]);
	}

}