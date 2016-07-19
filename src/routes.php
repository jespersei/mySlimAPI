<?php
// Routes

/*$app->get('/[{name}]', function ($request, $response, $args) {
    // Sample log message
    $this->logger->info("Slim-Skeleton '/' route");

    // Render index view
    return $this->renderer->render($response, 'index.phtml', $args);
});
*/


$app->get('[/]', function ($request, $response, $args) {
	return $this->renderer->render($response, 'main/index.php', $args);
});

$app->get('/test', function ($request, $response, $args) {
	return $this->renderer->render($response, 'main/index.php', $args);
});

$app->get('/migrate', 'Controllers\MigrationController:migrate')
	->setName('migrateTables');

$app->get('/populate', 'Controllers\MigrationController:populate')
	->setName('populateTables');

$app->get('/migrate/rollback[/[{tablename}]]', 'Controllers\MigrationController:rollback')
	->setName('rollbackTable');

$app->group('/admin', function () use ($app) {
	$app->get('[/]', function ($request, $response, $args) {
		return $this->renderer->render($response, 'admin/index.php', $args);
	});
});

$app->group('/api', function () use ($app) {
	$app->group('/users', function () use ($app) {
		return new Controllers\UserController($app);
	});

	$app->group('/clock', function () use ($app) {
		return new Controllers\ClockController($app);
	});
	
	/*$app->group('/logs', function () use ($app) {
		return new Controllers\LogsController($app);
	});*/

	$app->group('/test', function () use ($app) {
		return new Controllers\TestController($app);
	});

	$app->group('/auth', function () use ($app) {
		$app->post('/login', 'Controllers\AuthController:login');
		$app->get('/logout/{token}', 'Controllers\AuthController:logout');
		$app->post('/verify', 'Controllers\AuthController:authenticate');
	});
});





