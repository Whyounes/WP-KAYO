<?php

use Illuminate\Http\Response;
use App\Application;

function formatResponse(Response $response)
{
    return $response;
}

/**
 * Prefix route with plugin prefix
 * @param  string $route Route action name
 * @return string        Menu slug
 */
function actionToMenuSlug($route)
{
    return sprintf("%s__%s", getConfig('app.plugin_prefix'), $route);
}

/**
 * Resolve a config variable from the config folder
 * @param  string $name Config variable name
 * @return mixed       Config value
 */
function getConfig($name)
{
    $config = Application::get('config');

    return $config->get($name);
}