<?php
namespace Jade\Model\Footer;

class Model
{
  public $template;
  public $data = array();

  public function __construct(){
    global $jade;

    $this->template = $jade->get_template_path() . '/footer.html.twig';
    
  }
}

?>
