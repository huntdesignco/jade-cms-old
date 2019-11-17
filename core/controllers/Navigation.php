<?php
namespace Jade\Controller\Navigation;

class Controller
{  
  private $model;

  public function __construct($model) {
    global $jade;

    $this->model = $model;

  }
}

?>
