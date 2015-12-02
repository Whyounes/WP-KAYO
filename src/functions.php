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

/**
 * Trnaslate a text or a key
 * @param  string  $text Key or text to be translated
 * @param  boolean $echo Echo the result or return it
 * @param  array  $args  Aditionnal args for wordpress l10n functions
 * @return string        Translated text
 */
function trans($text, $echo = false, array $args = null)
{
    $config = Application::get('config');
    if ( $config->get('lang.use_wordpress') )
    {
        $lang = Application::get('lang');

        return $lang->get($text);
    }

    if ( $echo )
    {
        _e($text, $config->get('app.plugin_prefix'));
    } else {
        __($text, $config->get('app.plugin_prefix'));
    }
}// trans
