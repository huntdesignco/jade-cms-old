<?php
namespace Jade\View\Navigation;

class View
{
  private $model;
  private $controller;

  public function __construct($controller,$model) {
    $this->controller = $controller;
    $this->model = $model;
  }

  public function render(){
    global $jade;
    echo $jade->twig->render($this->model->template, $this->model->data);
  }
}
