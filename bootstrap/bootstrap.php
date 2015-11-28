<?php

use App\Application;
use App\Http\Routes;

$app = new Application;
$app->boot();

// Register routes
$routes = new Routes($app->get('config')->get('routes'));
$app->instance('routes', $routes);
$app->setRoutes( $routes );


// Register menus
$app->setMenus( $app->get('config')->get('menus') );
$app->registerMenus();

return $app;
