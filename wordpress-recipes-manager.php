<?php
/*
    Plugin Name: Wordpress Recipes Manager
    Plugin URI: http://wrm.if36.com
    Description: Wordpress Recipes Manager
    Version: 1.0.0
    Author: Karim Lamghari & Younes Rafie
    Author URI: http://wrm.if36.com
    Text Domain: WRM-lang
    Domain Path: /languages/
*/

require_once __DIR__.'/vendor/autoload.php';

use App\Application;

// Boot application
$app = require_once __DIR__.'/bootstrap/bootstrap.php';


register_activation_hook(__FILE__, array($app, 'activatePlugin'));
register_deactivation_hook(__FILE__, array($app, 'deactivatePlugin'));
register_uninstall_hook(__FILE__, array(get_class($app), 'uninstallPlugin'));


add_action( 'wp_ajax_wrm_execute', 'wrm_execute' );
add_action( 'wp_ajax_nopriv_wrm_execute', 'wrm_execute' );

function wrm_execute()
{
    /* 
        BaseController should exist on the App\Http\Controllers\ namespace, 
        while other controller can be changed from the config
    */
    $controller = new App\Http\Controllers\ControllerHandler;
    $controller->run();
}
