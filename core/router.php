<?php
namespace Jade\Core;

class Router {

  private $routes = array();

  public function add($pattern, $tokens = array(), $methods) {
    $this->routes[] = array(
      "pattern" => $pattern,
      "tokens" => $tokens,
      "methods" => $methods
    );
  }

  public function parse($url, $method) {
    $tokens = array();

    foreach ($this->routes as $route) {
      preg_match("@^" . $route['pattern'] . "$@", $url, $matches);

      if ($matches) { 
        foreach ($route['methods'] as &$route_method) {
          if ($method == $route_method) {
            foreach ($matches as $key=>$match) {
              // Not interested in the complete match, just the tokens
              if ($key == 0) {
                  continue;
              }
              // Save the token
              $tokens[$route['tokens'][$key-1]] = $match;
            }
            return $tokens;
          }
        }
      }
    }
  }
}
?>
