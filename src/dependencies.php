<?php
// DIC configuration

$container = $app->getContainer();

// view renderer
$container['renderer'] = function ($c) {
    $settings = $c->get('settings')['renderer'];
    return new Slim\Views\PhpRenderer($settings['template_path']);
};

// monolog
$container['logger'] = function ($c) {
    $settings = $c->get('settings')['logger'];
    $logger = new Monolog\Logger($settings['name']);
    $logger->pushProcessor(new Monolog\Processor\UidProcessor());
    $logger->pushHandler(new Monolog\Handler\StreamHandler($settings['path'], Monolog\Logger::DEBUG));
    return $logger;
};

// Service factory for the ORM
$container['db'] = function ($container) {
    $capsule = new \Illuminate\Database\Capsule\Manager;
    $capsule->addConnection($container['settings']['dbslite']);

    $capsule->setAsGlobal();
    $capsule->bootEloquent();

    return $capsule;
};

//Error Handler
$container['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        $returnData = array('success' => false, 'data' => ['msg' => 'Error Encountered']);
        
        $c['logger']->error($exception->getMessage() . '(URI: '. $request->getUri() .')');

        return $c['response']->withStatus(500)
                             ->withJson($returnData);
    };
};

//Not Found Handler
$container['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {        
        $returnData = array('success' => false, 'data' => ['msg' => 'Page Not Found']);

        $c['logger']->info("Not Found Handler triggered  (URI: ". $request->getUri() . ")");

        //$c['renderer']->render($response, 'error404.php', []);

        return $c['response']->withStatus(404)
                             ->withJson($returnData);
    };
}; 

