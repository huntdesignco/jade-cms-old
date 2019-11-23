<?php
namespace Jade\Model\Header;

class Model
{
  public $template;
  public $data = array();

  public function __construct(){
    $this->data = array(
      'site_url' => get_site_url(),
      'page_title' => get_page_title()
    );
    $this->template = get_template_path() . '/header.html.twig';
  }
}

?>
