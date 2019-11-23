<?php
namespace Jade;

// Load composer packages
require_once('assets/vendor/autoload.php');

// Load router
require_once('core/router.php');

// Load database
require_once('core/database.php');

// Set up twig
use Twig_Environment;
use Twig_Loader_Filesystem;

class Core
{
  public $router;
  public $loader;
  public $twig;
  public $options;

  // Globals
  public $site_domain;
  public $ssl_enabled;
  public $current_page;
  public $site_url;
  public $site_folder;
  public $page_name;
  public $module_name;
  public $pages;

  public $assets = array();

  public function __construct() {

    // Globals
    $this->site_domain = constant('SITE_DOMAIN');
    $this->ssl_enabled = constant('USE_SSL');
    $this->site_folder = constant('SITE_FOLDER');

    $this->site_url = ($this->ssl_enabled == true ? 'https://' : 'http://') . $this->site_domain . (isset($this->site_folder) === true && $this->site_folder !== '' ? '/' . $this->site_folder : '');

    // Initiate routing object
    $this->router = new Core\Router();
    $this->options = array(
      'strict_variables' => false,
      'debug' => false,
      'cache'=> false
    );

    // Database
    $this->db = new \Database();

    // Set up twig templating
    $this->loader = new \Twig\Loader\FilesystemLoader(__DIR__.'/');
    $this->twig = new \Twig\Environment($this->loader, $this->options);

    // Create twig function to build navigation menus
    $this->NavBuilder = new \Twig\TwigFunction('build_nav_menu', function ($menu_name, $options) {
      build_nav_menu($menu_name, $options);
    }, ['is_safe' => ['html']]);
    $this->twig->addFunction($this->NavBuilder);

    // Create twig function to load javascript assets
    $this->jsAssets = new \Twig\TwigFunction('load_js_assets', function () {
      load_js_assets();
    }, ['is_safe' => ['html']]);
    $this->twig->addFunction($this->jsAssets);

    // Create twig function to load css assets
    $this->cssAssets = new \Twig\TwigFunction('load_css_assets', function () {
      load_css_assets();
    }, ['is_safe' => ['html']]);
    $this->twig->addFunction($this->cssAssets);

  }

  public function header($route) {

    // Load required MVC frameworks
    require_once(__DIR__.'/models/Header.php');
    require_once(__DIR__.'/views/Header.php');
    require_once(__DIR__.'/controllers/Header.php');

    // Initiate MVC objects
    $model = new Model\Header\Model($route);
    $controller = new Controller\Header\Controller($model);
    $view = new View\Header\View($controller, $model);

    // Display page
    $view->render();

  }
  
  public function navigation($route) {

    // Load required MVC frameworks
    require_once(__DIR__.'/models/Navigation.php');
    require_once(__DIR__.'/views/Navigation.php');
    require_once(__DIR__.'/controllers/Navigation.php');

    // Initiate MVC objects
    $model = new Model\Navigation\Model($route);
    $controller = new Controller\Navigation\Controller($model);
    $view = new View\Navigation\View($controller, $model);

    // Display page
    $view->render();

  }

  public function page($route) {

    // Load required MVC frameworks
    require_once(__DIR__.'/models/Page.php');
    require_once(__DIR__.'/views/Page.php');
    require_once(__DIR__.'/controllers/Page.php');

    // Initiate MVC objects
    $model = new Model\Page\Model($route);
    $controller = new Controller\Page\Controller($model);
    $view = new View\Page\View($controller, $model);

    // Display page
    $view->render();

  }

  public function controller($route) {
    //if ($route['controller'] == 'categories') { $class = "Categories"; }

    // Load required MVC frameworks
    require_once(__DIR__.'/models/'. $class . '.php');
    require_once(__DIR__.'/views/'. $class . '.php');
    require_once(__DIR__.'/controllers/'. $class .'.php');

    // Initiate MVC objects
    $model_name = 'Jade\Model\\' . $class .'\Model';
    $model = new $model_name($route);

    $controller_name = 'Jade\Controller\\' . $class .'\Controller';
    $controller = new $controller_name($model);

    $view_name = 'Jade\View\\' . $class .'\View';
    $view = new $view_name($controller, $model);

    // Display page
    $view->render();
  }
  
  public function footer($route) {

    // Load required MVC frameworks
    require_once(__DIR__.'/models/Footer.php');
    require_once(__DIR__.'/views/Footer.php');
    require_once(__DIR__.'/controllers/Footer.php');

    // Initiate MVC objects
    $model = new Model\Footer\Model($route);
    $controller = new Controller\Footer\Controller($model);
    $view = new View\Footer\View($controller, $model);

    // Display page
    $view->render();
    
  }

}

?>
