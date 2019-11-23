<?php

//////////////////////////////
// Conditional functions
//////////////////////////////

function is_page() {
  global $jade;
  if (!empty($jade->page_name)) { return true; }
  else { return false; }
}

function is_module() {
  global $jade;
  if (!empty($jade->module_name)) { return true; }
  else { return false; }
}

//////////////////////////////
// Get functions
//////////////////////////////

function get_page_template($slug) {
  global $jade;

  // Get page template information via sql
  $table = $jade->db->table('pages');
  $stmt = $jade->db->pdo->prepare("SELECT twig_template FROM " . $table . " WHERE slug = :slug");
  $stmt->execute(['slug' => $slug]);
  $result = $stmt->fetch();

  if (!empty($result)) { return $result['twig_template']; }
  else { return false; }

}

function get_template_path() {
  global $jade;

  // Get template path
  $theme = get_theme_name();
  if (!$theme) { 
    return '/themes/jade/templates'; 
  }
  else { return '/themes/' . $theme . '/templates'; }

}

function get_page_title() {
  global $jade;

  // Determine if is page
  if (is_page()) { 
    $table = $jade->db->table('pages');
    $stmt = $jade->db->pdo->prepare("SELECT * FROM " . $table . " WHERE slug = :slug");
    $stmt->execute(['slug' => $jade->page_name]);
    $result = $stmt->fetch();
  }

  // Determine if is module
  elseif (is_module()) { 
    $table = $jade->db->table('modules');
    $stmt = $jade->db->pdo->prepare("SELECT * FROM " . $table . " WHERE slug = :slug");
    $stmt->execute(['slug' => $jade->module_name]);
    $result = $stmt->fetch();
  }

  if (!empty($result)) { return $result['name'] . ' - ' . $result['title']; }
  else { return constant('SITE_NAME') . ' - ' . constant('SITE_DESC'); }

}

function get_option($name) {
  global $jade;

  // Get option via sql
  $table = $jade->db->table('options');

  $stmt = $jade->db->pdo->prepare("SELECT value FROM " . $table . " WHERE name = :name");
  $stmt->execute(['name' => $name]);
  $result = $stmt->fetch();

  if (!empty($result)) { return $result['value']; }
  else { return false; }
}

function get_theme_name() {
  global $jade;
  return get_option('theme');
}

function get_site_url() {
  global $jade;

  if (!empty($jade->site_url)) { return $jade->site_url; }
  else { return false; }
}

function get_current_page() {
  global $jade;

  if (!empty($jade->current_page)) { return $jade->current_page; }
  else { return false; }
}

function get_jade_dir() {
  global $jade;
  
  return $jade->site_folder;
}

//////////////////////////////
// Register functions
//////////////////////////////

function register_asset($type, $tag, $source) {
  global $jade;

  // Store the asset in the global jade object
  $asset = array(
    'type' => $type, 
    'tag' => $tag, 
    'source' => $source
  );

  array_push($jade->assets, $asset);
}

?>