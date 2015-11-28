This is a documentation for the Wordpress plugin boilerplate.

##Container

We use the Illuminate Container component to manage our bindings. The container and the other bindings are registered inside the `bootstrap/bootstrap.php`, you can add your own bindings there or create a new file and require it.

##Configuration

Plugin configuration is stored inside the `config/` folder, they are automatically loaded and every file must return a config array. The config format is `filename.config_value`.

```php
// Example
$config = App\Application::get('config');
$plugin_prefix = $config->get('app.plugin_prefix', 'default_value');

// You can also use the `getConfig` helper function.
$plugin_prefix = $getConfig('app.plugin_prefix', 'default_value');
```

##Routing

Routes are action triggered by the plugin and handled by a controller. A route file look like the following.

```php
// config/routes.php

return [
    [
        'action'        => 'plugin_dashboard',
        'controller'    => 'VideosController@dashboard',
        'capabilities'  => [
            'manage_options',
            'delete_themes'
        ]
    ]
];
```

- action: (required) action name, should be unique and will be used on the GET `page` parameter.
- controller: (required) Class controller and method name. You can specify your controllers namespace inside the `config/app.php` `controllersNamespace` attribute.
- capabilities: (optional) an array of Wordpress capabilities to guard the route.

##Menus

```php
// config/menus.php

return [
    [
        'page_title'    => 'Wordpress reciepes manager',
        'menu_title'    => 'Wordpress reciepes manager',
        'capability'    => getConfig('app.menus_default_capability'),
        'menu_slug'     => getConfig('app.plugin_prefix'), // plugin prefix
        'function'      => 'wrm_execute',
        'icon_url'      => 'dashicons-format-video',
        'position'      => 2,
        'parent'        => 'menu_slug',
    ]
];
```

The array key are defined exactly like Wordpress and can be used the same way.

- page_title: (required) Menu page title.
- menu_title: (optional) Menu item text. The `page_title` value is used if not set.
- capability: (optional) You can set a Wordpress capability, or used the one defined inside the app config value.
- menu_slug: (optional) The request page attribute. Only required for the main menu. You can attach a menu directly to a route by using the `actionToMenuSlug` helper function and passing the route action name as a parameter.
- function: (optional) If you want to manually handle a request, you can pass your function here.
- icon_url: (optional) Icon URL.
- position: (optional) Menu position.
- parent: (optional) Add as a sub menu. You can use the `actionToMenuSlug` function insert the sub menu to a menu that uses a route.

##Views

Twig templating engine is registered by default and can be used as the following.

```php
// src/Http/Controllers/VideosController.php

namespace App\Http\Controllers;

use App\Application;
use Illuminate\Http\Response;

class VideosController extends BaseController
{
    public function index()
    {
        $twig = Application::get('twig');
        $videos = [];
        $responseContent = $twig->render('videos.twig.htm', ['videos' => $videos]);

        return $responseContent;
    }
}
```

The views are stored inside the `public/views` folder, but you can change that inside the `config/app.php` - `views_path` attribute.
