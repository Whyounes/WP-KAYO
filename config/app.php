<?php
return array(
    'plugin_prefix'             => 'wrm', // Your plugin prefix
    'controllersNamespace'      => '\App\Http\Controllers\\', // Global controllers namesapce
    'request_action'            => 'page', // The URL parameter used for routing
    'plugin_dashboard_action'   => 'plugin_dashboard', // default action for the root admin menu
    'views_path'                => __DIR__.'/../public/views/', // Templates path
    'views_cache_path'          => __DIR__.'/../cache/views/compiled', // Templates path
    'menus_default_capability'  => 'manage_options', // used for menus capability, may be overriden by guarding routes
);