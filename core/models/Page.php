<?php
namespace Jade\Model\Page;

class Model
{
  public $template;
  public $data = array();

  public function __construct($route){    
    if (empty($route['page'])) {
      $this->data = array(
        'site_url' => get_site_url()
      );
      $this->template = (!empty(get_option('homepage_template')) ? get_option('homepage_template') : '/templates/pages/homepage.html.twig');
    }
    else {
      $this->template = '/templates/pages/' . get_page_template($route['page']);
    }

    if ($this->template == '/templates/pages/') {
      // Set browser header
      header("HTTP/1.0 404 Not Found");

      // Determine template and referer
      $this->template = get_template_path() . '/404.html.twig';
      
      $this->data = array(
        'referer' => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : get_site_url()),
        'site_url' => get_site_url()
      );
    }
  }

}

?>
