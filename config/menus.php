<?php

// $config = $app->get('config');

// [
//     'page_title'    => 'Wordpress reciepes manager',
//     'menu_title'    => 'Wordpress reciepes manager', // if not present, will use the `page_title`
//     'capability'    => 'manage_options', // default to manage_options
//     'menu_slug'     => 'wrm', // plugin prefix
//     'function'      => 'wrm_execute', // you can use this if you don't want the plugin to catch the route
//     'icon_url'      => 'dashicons-format-video',
//     'position'      => 2, // defaults to dashboard
//     'parent'        => 'menu_slug', // add as a sub menu to menu parent
// ]

return [
        [
            'page_title'    => 'Wordpress reciepes manager',
            'menu_title'    => 'Wordpress reciepes manager', // if not present, will use the `page_title`
            'capability'    => getConfig('app.menus_default_capability'),
            'menu_slug'     => getConfig('app.plugin_prefix'), // plugin prefix
            //'function'      => 'wrm_execute', // you can use this if you don't want the plugin to catch the route
            'icon_url'      => 'dashicons-format-video',
            //'position'      => 2, // defaults to dashboard
            //'parent'        => 'menu_slug', // add as a sub menu to menu parent
        ],
        [
            'page_title'    => 'Videos',
            'capability'    => getConfig('app.menus_default_capability'),
            'icon_url'      => 'dashicons-format-video',
            'menu_slug'     => actionToMenuSlug('videos'), 
            'parent'        => 'wrm', // use getConfig('app.plugin_prefix')
        ],
        [
            'page_title'    => 'Videos Json',
            'menu_title'    => 'Videos Json', // if not present, will use the `page_title`
            'capability'    => getConfig('app.menus_default_capability'),
            'function'      => 'wrm_execute', // you can use this if you don't want the plugin to catch the route
            'icon_url'      => 'dashicons-format-video',
            'menu_slug'     => actionToMenuSlug('videos_json'), 
            //'position'      => 2, // defaults to dashboard
            'parent'        => getConfig('app.plugin_prefix'), // add as a sub menu to menu parent
        ]
];