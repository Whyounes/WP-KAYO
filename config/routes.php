<?php
/*
    Register plugin routes. Example:

    return [
        [
            'action'        => '/videos', // URL action
            'controller'    => 'VideosController@index', // Controller method handler
            'capabilities'  => [
                'manage_options',
                'delete_themes'
            ] // Only allow use with capabilities
        ]
    ];
*/

return [
    [
        'action'        => 'plugin_dashboard', // plugin_dashboard action is the default when nothing is specified
        'controller'    => 'VideosController@dashboard',
        'capabilities'  => [
            'manage_options',
            'delete_themes'
        ]
    ],
    [
        'action'        => 'videos',
        'controller'    => 'VideosController@index'
    ],
    [
        'action'        => 'videos_json',
        'controller'    => 'VideosController@videosToJson'
    ]
];