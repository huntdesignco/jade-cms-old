<?php
namespace Jade\Model\Header;

class Model
{
  public $template;
  public $data = array();

  public function __construct(){
    global $jade;
    $this->data = array(
      'site_url' => $jade->site_url,
      'page_title' => $jade->get_page_title()
    );
    $this->template = $jade->get_template_path() . '/header.html.twig';

  }
}

?>
