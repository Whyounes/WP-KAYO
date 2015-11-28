<?php

namespace App\Http;

use Illuminate\Support\Collection;
use App\Application;

class Routes
{
    /**
     * list of routes
     * @var Collection
     */
    protected $routes;

    public function __construct(array $routes = [])
    {
        $this->routes = new Collection($routes);
    }

    /**
     * If a route exist by name
     * @param  string $routeName Route name
     * @return boolean True if exists, false otherwise
     */
    public function exists($routeName)
    {
        return $this->routes->has($routeName);
    }

    /**
     * Get a route by name
     * @param  string $routeName Route name
     * @return array             Route definition
     */
    public function get($routeName)
    {
        return $this->routes->get($routeName);
    }

    /**
     * Return all routes
     * @return array|Collection
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    public function all()
    {
        return $this->routes;
    }

    public static function getRouteFromRequestAction($requestAction)
    {
        $config = Application::get('config');
        $format = $config->get("app.plugin_prefix")."__%s";
        $routes = Application::get('routes');
        $routeAction = null;
        $route = null; // route to return

        sscanf($requestAction, $format, $routeAction);
        $routeAction = !isset($routeAction) || empty($routeAction) ? $config->get('app.plugin_dashboard_action') : $routeAction;
        // maybe throw an exception if `plugin_dashboard_action`is not set

        if ( !$routeAction ) return false; // format not exact

        $routes->all()->each(function($item) use($routeAction, &$route) {
            if ( $item['action'] === $routeAction )
            {
                $route = $item;
                return;
            }
        });

        return $route;
    }
}