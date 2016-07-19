<?php

namespace Controllers\Base;

use Interop\Container\ContainerInterface;

class BaseController
{
	protected $db;
   
    //Constructor
    public function __construct(ContainerInterface $ci) {
        $this->db = $ci['db'];
    }
	
	protected function createResponse($response, $success = false, $data = [], $customHead = [])
	{
		$returnData = array('success' => $success, 'data' => $data);
		$newResponse = $response->withJson($returnData);

		return $newResponse;
	}

	protected function getAttr($request, $attribute)
	{
		$attributeValue = isset($request->getParsedBody()[$attribute]) ? $request->getParsedBody()[$attribute] : false;
		return $attributeValue;
	}
	protected function getArg($args, $arg)
	{
		$argValue = isset($args[$arg]) ? $args[$arg] : false;
		return $argValue;
	}
}