<?php
namespace Jade\Model\Navigation;

class Model
{
  public $template;
  public $data = array();

  public function __construct($route){
    global $jade;

    $this->data = array(
      'site_url' => $jade->site_url,
      'current_page' => $jade->current_page,
    );

    $this->template = $jade->get_template_path() . '/navigation.html.twig';
  }
}

?>
