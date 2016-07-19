<?php

namespace Controllers\Base;

use Controllers\Base\BaseController;

class RestController extends BaseController
{
	protected $db;
	protected $logger;

	public function __construct($app)
	{
		$this->db = $app->getContainer()->db;
		$this->logger = $app->getContainer()->logger;
		//map the routes for the HTTP Methods
		$this->map($app);
	}

	protected function map($app)
	{
		$app->map(['POST'], '[/]', [$this, 'create']);
		$app->map(['GET'], '/find[/{params:.*}]', [$this, 'find']);
		$app->map(['GET'], '[/[{id}]]', [$this, 'retrieve']);
		$app->map(['PUT'], '/{id}', [$this, 'update']);
		$app->map(['DELETE'], '/{id}', [$this, 'delete']);
	}

	protected function getSearchArray($params)
	{
		$count = 0;
		$keys = [];
		$vals = [];
		$arguments = [];
		foreach ($params as $item) {
			if($count%2 == 0)
				$keys[] = $item;
			else
				$vals[] = $item; 
			$count++;
		}

		$count = 0;
		foreach($keys as $key){
			$arguments[$key] = isset($vals[$count]) ? $vals[$count] : '';
			$count++;
		}

		return $arguments;
	}

}