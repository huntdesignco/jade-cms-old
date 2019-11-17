<?php
namespace Jade;

// Load composer packages
require_once('assets/vendor/autoload.php');

// Set up twig
use Twig_Environment;
use Twig_Loader_Filesystem;

// Load router
require_once('router.php');

// Load database
require_once('database.php');

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

    // Create twig function to return navigation menu

    $this->NavBuilder = new \Twig\TwigFunction('build_nav_menu', function ($menu_name, $options) {
      $this->build_nav_menu($menu_name, $options);
    }, ['is_safe' => ['html']]);
    $this->twig->addFunction($this->NavBuilder);
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

  public function build_nav_menu($menu_name, $options) {
    if ($menu_name == 'primary') {

      // Get all pages marked for primary navbar
      $table = $this->db->table('pages');
      $stmt = $this->db->pdo->prepare("SELECT * FROM " . $table . " WHERE is_navbar = 1");
      $stmt->execute();
      $pages = $stmt->fetchAll();
      
      if ($options['style'] == 'list') {
        echo '<ul' . (!empty($options['ul_class']) ? ' class="' . $options['ul_class'] . '"' : '') . '>';

        if ($options['show_home']) {
          echo '<li' . (!empty($options['li_class']) ? ' class="' . $options['li_class'] . ($this->current_page == 'home' && $options['active'] == 'li' ? ' active' : '') . '"' : '') . '>';
          echo '<a href="' . $this->site_url . '"' . (!empty($options['a_class']) ? 'class="' . $options['a_class'] . '"' : '') . '>' . 'Home' . '</a>';
          echo '</li>';
        }
        
        foreach ($pages as &$page) {
          echo '<li' . (!empty($options['li_class']) ? ' class="' . $options['li_class'] . ($this->current_page == $page['slug'] && $options['active'] == 'li' ? ' active' : '') . '"' : '') . '>';
          echo '<a href="' . $this->site_url . '/' . $page['slug'] . '"' . (!empty($options['a_class']) ? 'class="' . $options['a_class'] . '"' : '') . '>' . $page['name'] . '</a>';
          echo '</li>';
        }

        echo '</ul>';
      }
    }
  }


  //////////////////////////////
  // Conditional functions
  //////////////////////////////

  public function is_page() {
    if (!empty($this->page_name)) { return true; }
    else { return false; }
  }
  
  public function is_module() {
    if (!empty($this->module_name)) { return true; }
    else { return false; }
  }

  //////////////////////////////
  // Get functions
  //////////////////////////////

  public function get_page_template($slug) {

    // Get page template information via sql
    $table = $this->db->table('pages');
    $stmt = $this->db->pdo->prepare("SELECT twig_template FROM " . $table . " WHERE slug = :slug");
    $stmt->execute(['slug' => $slug]);
    $result = $stmt->fetch();

    if (!empty($result)) { return $result['twig_template']; }
    else { return false; }

  }

  public function get_template_path() {

    // Get template path
    $theme = $this->get_theme_name();
    if (!$theme) { 
      return '/themes/jade/templates'; 
    }
    else { return '/themes/' . $theme . '/templates'; }

  }

  public function get_page_title() {

    // Determine if is page
    if ($this->is_page()) { 
      $table = $this->db->table('pages');
      $stmt = $this->db->pdo->prepare("SELECT * FROM " . $table . " WHERE slug = :slug");
      $stmt->execute(['slug' => $this->page_name]);
      $result = $stmt->fetch();
    }

    // Determine if is module
    elseif ($this->is_module()) { 
      $table = $this->db->table('modules');
      $stmt = $this->db->pdo->prepare("SELECT * FROM " . $table . " WHERE slug = :slug");
      $stmt->execute(['slug' => $this->module_name]);
      $result = $stmt->fetch();
    }

    if (!empty($result)) { return $result['name'] . ' - ' . $result['title']; }
    else { return constant('SITE_NAME') . ' - ' . constant('SITE_DESC'); }

  }

  public function get_option($name) {

    // Get option via sql
    $table = $this->db->table('options');

    $stmt = $this->db->pdo->prepare("SELECT value FROM " . $table . " WHERE name = :name");
    $stmt->execute(['name' => $name]);
    $result = $stmt->fetch();

    if (!empty($result)) { return $result['value']; }
    else { return false; }
  }

  public function get_theme_name() {
    return $this->get_option('theme');
  }

}

?>
