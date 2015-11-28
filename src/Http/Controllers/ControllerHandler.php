<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Container\Container;
use App\Application;
use App\Http\Routes;

class ControllerHandler
{

    protected $app;

    public function __construct()
    {
        $this->app = Application::getInstance();
    }

    public function run()
    {
        $request = Request::capture();
        $config = $this->app->get("config");
        $action = $request->input( $config->get('app.request_action'), '/' );
        $responseContent = $this->runActionController($action);

        if ( !$responseContent ) return;

        $response = new Response( $responseContent );
        $response->send();
        die();
    }

    /**
     * Check if the action, controller, and method handler exists.
     * @throws \Exception
     * @param  string $action URL action
     * @return Response
     */
    protected function runActionController($action)
    {
        $config = $this->app->get('config');
        $controllersNamespace = $config->get('app.controllersNamespace');
        $route = Routes::getRouteFromRequestAction($action);

        if( !$route ) return;

        $controller = $controllersNamespace.$route['controller'];
        $tmpController = explode("@", $controller);
        if ( count($tmpController) < 2 )
        {
            throw new \Exception("Action `{$action}` is not properly defined.");
        }

        $classController = $tmpController[0];
        $methodController = $tmpController[1];

        if ( !class_exists($classController) )
        {
            throw new \Exception("Class controller `{$classController}` not found.");
        }

        $controllerInstance = new $classController;
        if ( !method_exists($classController, $methodController) )
        {
            throw new \Exception("Method `{$methodController}` not found in `{$classController}`.");
        }

        $this->guard($route);

        return $controllerInstance->$methodController();
    }

    /**
     * Halt if user doesn't have the required capabilities
     * @return void
     */
    public function guard($route)
    {
        if( 
            !isset($route['capabilities']) || 
                ( 
                    !is_string($route['capabilities']) && 
                    !is_array($route['capabilities'])
                )
        ) {
            return;
        }

        $capabilities = is_string($route['capabilities']) ? [$route['capabilities']] : $route['capabilities'];
        foreach ($capabilities as $capability)
        {
            if( !current_user_can($capability) )
            {
                wp_die( __( 'You do not have sufficient permissions to access this page.' ), 403 );
            }
        }
    }

    /**
     * Validate if the controller method executable.
     * @return [type] [description]
     */
    protected function validateAction()
    {
    }
}