<?php
namespace Jade\Model\Navigation;

class Model
{
  public $template;
  public $data = array();

  public function __construct($route){
    $this->data = array(
      'site_url' => get_site_url(),
      'current_page' => get_current_page(),
    );
    $this->template = get_template_path() . '/navigation.html.twig';
  }
}

?>
