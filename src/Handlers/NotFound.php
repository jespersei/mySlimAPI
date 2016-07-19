<?php

namespace Handlers;

use Interop\Container\ContainerInterface;
use \Psr\Http\Message\ServerRequestInterface;
use \Psr\Http\Message\ResponseInterface;


class NotFound
{
	protected $renderer;
	protected $db;
	protected $logger;
   
    //Constructor
    public function __invoke($request, $response)
	{
		//$this->db = $app->getContainer()->db;
		//$this->logger = $app->getContainer()->logger;
		//map the routes for the HTTP Methods
		//$this->map($app);
		//$route = $request->getAttribute('route');
		//var_dump($request->getUri()->getPath());

		$path = $request->getUri()->getPath();

		//return $this->map($path);
		return $this->showHandler($path,$request,$response);
	}

	protected function map($path)
	{
		$container = new \Slim\Container;
		$app = new \Slim\App($container);
		//$app = Slim::getInstance();

		$app->map(['POST','GET','PUT','DELETE'], '/api[/{params:.*}]', [$this, 'apiHandler']);
		$app->map(['POST','GET','PUT','DELETE'], '/[{params:.*}]', [$this, 'pageHandler']);
	}

	protected function showHandler($path,$request,$response)
	{
		//parse base path

		//use chcking against the path
		
		//call the function
		return $this->pageHandler($request,$response);
	}

	public function apiHandler($request,$response)
	{
		$returnData = array('success' => false, 'data' => ['msg' => 'Page Not Found']);
		var_dump(1);
		return $response
			->withStatus(404)
            ->withHeader('Content-Type', 'text/html')
            ->withJson($returnData);
	}

	public function pageHandler($request,$response)
	{
		return $response->write('Page Not Found');
	}

}