<?php
namespace Jade\Model\Page;

class Model
{
  public $template;
  public $data = array();

  public function __construct($route){
    global $jade;
    
    if (empty($route['page'])) {
      $this->data = array(
        'site_url' => $jade->site_url
      );
      $this->template = (!empty($jade->get_option('homepage_template')) ? $jade->get_option('homepage_template') : '/templates/pages/homepage.html.twig');
    }
    else {
      $this->template = '/templates/pages/' . $jade->get_page_template($route['page']);
    }

    if ($this->template == '/templates/') {
      // Set browser header
      header("HTTP/1.0 404 Not Found");

      // Determine template and referer
      $this->template = $jade->get_template_path() . '/404.html.twig';
      
      $this->data = array(
        'referer' => (isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : $jade->site_url),
        'site_url' => $jade->site_url
      );
    }
  }

}

?>
