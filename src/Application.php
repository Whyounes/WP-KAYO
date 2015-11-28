<?php

namespace App;

use Illuminate\Container\Container;
use Illuminate\Contracts\Config\Repository as RepositoryContract;
use Illuminate\Config\Repository as Config;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use App\Http\Routes;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;

class Application extends Container
{
    /**
     * Application (plugin) base path
     * @var string
     */
    protected $basePath;

    /**
     * Application routes
     * @var Routes
     */
    protected $routes;

    /**
     * Application menus
     * @var array
     */
    protected $menus;

    public function __construct()
    {
        $this->basePath = __DIR__.DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR;
        static::$instance = $this;
        $this->instance('app', $this);
    }

    /**
     * Make an object from the container
     * @param  string $object Object id
     * @return mixed          Resolved object
     */
    public static function get($object)
    {
        return static::$instance->make($object);
    }

    /**
     * Boot application (plugin)
     * @return void
     */
    public function boot()
    {
        $this->registerConfigRepository();
        $this->loadConfigurationFiles($this->app->make('config'));
        $this->registerLangRepository();

        $this->registerTwig();
    }

    /**
     * Register Twig bindings
     * @return void
     */
    protected function registerTwig()
    {
        $this->bind('twig.loader', function () {
            $config = static::make('config');
            $view_paths = $config->get('app.views_path');
            $loader = new \Twig_Loader_Filesystem($view_paths);

            return $loader;
        });

        $this->bind('twig', function () {
            $config = static::make('config');
            $options = [
                'debug' => WP_DEBUG,
                'cache' => $config->get('app.views_cache_path')
            ];

            $twig = new \Twig_Environment(static::make('twig.loader'), $options);

            // register Twig Extensions
            $twig->addExtension(new \Twig_Extension_Debug());

            // register Twig globals
            $twig->addGlobal('app', $this);
            $twig->addGlobal('config', $this->get('config'));
            $twig->addGlobal('lang', $this->get('lang'));

            return $twig;
        });
    }

    protected function registerLangRepository()
    {
        $config = $this->get('config');
        $loader = new FileLoader(new Filesystem(), $config->get('lang.lang_path'));
        $translator = new Translator($loader, get_locale());
        $translator->setFallback($config->get('lang.fallback_locale'));

        $this->instance('lang', $translator);
    }

    /**
     * Register config repository and load config folder
     * @return void
     */
    protected function registerConfigRepository()
    {
        $config = new Config;
        $this->instance('config', $config);
        $this->loadConfigurationFiles($config);
    }

    protected function loadConfigurationFiles(RepositoryContract $config)
    {
        foreach ($this->getConfigurationFiles() as $key => $path) {
            $config->set($key, require $path);
        }
    }

    protected function getConfigurationFiles()
    {
        $files = [];

        foreach (Finder::create()->files()->name('*.php')->in($this->configPath()) as $file) {
            $files[basename($file->getRealPath(), '.php')] = $file->getRealPath();
        }

        return $files;
    }

    /**
     * Attach menus to Wordpress
     * @return void
     */
    public function registerMenus()
    {
        foreach ($this->menus as $menu)
        {
            $menu['page_title'] = array_get($menu, 'page_title');
            $menu['menu_title'] = array_get($menu, 'menu_title', $menu['page_title']);
            $menu['capability'] = array_get($menu, 'capability', 'manage_options');
            $menu['icon_url'] = array_get($menu, 'icon_url', '');
            $menu['function'] = array_get($menu, 'function', 'wrm_execute');
            $menu_slug = array_get($menu, 'menu_slug', '');

            if( array_key_exists('parent', $menu) )
            {
                add_action('admin_menu', function() use($menu, $menu_slug) {
                    add_submenu_page(
                        $menu['parent'], 
                        $menu['page_title'], 
                        $menu['menu_title'], 
                        $menu['capability'], 
                        $menu_slug,
                        $menu['function'],
                        $menu['icon_url']
                    );
                });
            } else {
                add_action('admin_menu', function() use($menu, $menu_slug) {
                    add_menu_page(
                        $menu['page_title'], 
                        $menu['menu_title'], 
                        $menu['capability'], 
                        $menu_slug,
                        $menu['function'],
                        $menu['icon_url'],
                        array_get($menu, 'position')
                    );
                });
            } // else
        }// foreach
    }

    /**
     * Config path
     * @return string Path to config folder
     */
    public function configPath()
    {
        return $this->basePath.'config'.DIRECTORY_SEPARATOR;
    }

    public function basePath()
    {
        return $this->basePath;
    }
    /**
     * List of routes
     * @param array $routes
     */
    public function setRoutes($routes)
    {
        $routes->all()->each(function($item){
            if( !array_key_exists('action', $item) || !array_key_exists('controller', $item) ) {
                throw new \Exception("Every route definition should contain an `action` and a `controller.`");
            }
        });

        $this->routes = $routes;
    }
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * List of menus
     * @param array $menus
     */
    public function setMenus($menus)
    {
        $this->menus = $menus;
    }

    public function getMenus()
    {
        return $this->menus;
    }

    /**
     * Do something when plugin activated
     * @return void
     */
    public function activatePlugin()
    {
    }

    /**
     * Do something when plugin deactivated
     * @return void
     */
    public function deactivatePlugin()
    {
    }

    /**
     * Do something when plugin uninstalled
     * @return void
     */
    public static function uninstallPlugin()
    {
    }
}