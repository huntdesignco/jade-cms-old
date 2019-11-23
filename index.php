<?php
// Load website configuration
require_once('config/config.php');

// Load main controller
require_once('core/jade.php');

// Initiate core MVC object
$jade = new Jade\Core();

// Load global functions
require_once('core/functions.php');

// Load init functions
require_once('core/init.php');

// Load twig functions
require_once('core/twig.php');

// Initiate database object
$jade->db->connect();

// Get all pages
$table = $jade->db->table('pages');
$stmt = $jade->db->pdo->prepare("SELECT * FROM " . $table);
$stmt->execute();
$pages = $stmt->fetchAll();

foreach ($pages as &$page) {
  $jade->router->add("/(" . $page['slug'] . ")/", array("page"), array("GET", "POST"));
}

// Page route
$jade->router->add("/(.*)", array("page"), array("GET", "POST"));

// Parse URL and determine what should be loaded
$route = $jade->router->parse(($jade->site_folder !== '' ? substr($_SERVER['REQUEST_URI'],(strlen($jade->site_folder) + 1)) : $_SERVER['REQUEST_URI']), $_SERVER['REQUEST_METHOD']);

// Set the current page
$jade->current_page = (isset($route['controller']) ? $route['controller'] : ($route['page'] == '' ? 'home' : $route['page']));

reset($route);

if (key($route) == 'page') { 
  // Set page/module name
  $jade->page_name = $route['page'];
  $jade->module_name = '';

  $jade->header($route);
  $jade->navigation($route);
  $jade->page($route); 
  $jade->footer($route); 
}

elseif (key($route) == 'module') { 
  // Set page/module name
  $jade->module_name = $route['module'];
  $jade->page_name = '';

  $jade->header($route);
  $jade->navigation($route);
  $jade->controller($route);
  $jade->footer($route); 
}
?>
