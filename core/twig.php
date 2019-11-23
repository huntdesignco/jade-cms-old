<?php

//////////////////////////////
// Twig functions
//////////////////////////////

function build_nav_menu($menu_name, $options) {
  global $jade;

  if ($menu_name == 'primary') {

    // Get all pages marked for primary navbar
    $table = $jade->db->table('pages');
    $stmt = $jade->db->pdo->prepare("SELECT * FROM " . $table . " WHERE is_navbar = 1");
    $stmt->execute();
    $pages = $stmt->fetchAll();
    
    if ($options['style'] == 'list') {
      echo '<ul' . (!empty($options['ul_class']) ? ' class="' . $options['ul_class'] . '"' : '') . '>';

      if ($options['show_home']) {
        echo '<li' . (!empty($options['li_class']) ? ' class="' . $options['li_class'] . (get_current_page() == 'home' && $options['active'] == 'li' ? ' active' : '') . '"' : '') . '>';
        echo '<a href="' . get_site_url() . '"' . (!empty($options['a_class']) ? 'class="' . $options['a_class'] . '"' : '') . '>' . 'Home' . '</a>';
        echo '</li>';
      }
      
      foreach ($pages as &$page) {
        echo '<li' . (!empty($options['li_class']) ? ' class="' . $options['li_class'] . (get_current_page() == $page['slug'] && $options['active'] == 'li' ? ' active' : '') . '"' : '') . '>';
        echo '<a href="' . get_site_url() . '/' . $page['slug'] . '"' . (!empty($options['a_class']) ? 'class="' . $options['a_class'] . '"' : '') . '>' . $page['name'] . '</a>';
        echo '</li>';
      }

      echo '</ul>';
    }
  }
}

function load_js_assets() {
  global $jade;

  foreach ($jade->assets as &$asset) {
    if ($asset['type'] == 'js') {
      echo '<script type="text/javascript" src="' . $asset['source'] . '"></script>';
    }
  }
}
function load_css_assets() {
  global $jade;
  
  foreach ($jade->assets as &$asset) {
    if ($asset['type'] == 'css') {
      echo '<link rel="stylesheet" id="'. $asset['tag'] . '"' . ' ' . 'href="' . $asset['source'] . '"' . 'type="text/css" media="all"'  .' />';
    }
  }
}
?>