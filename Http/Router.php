<?php

namespace Http;

class Router
{
  protected array $routes = [];
  public Request $request;
  
  public function __construct()
  {
  	$this->request = new Request();
  }

  
  public function get($path, $callback)
  {
  	$this->routes['get'][$path] = $callback;
  }

  public function post($path, $callback)
  {
  	$this->routes['post'][$path] = $callback;
  }

  // public function delete($path, $callback)
  // {
  // 	$this->routes['delete'][$path] = $callback;
  // }
  
  public function resolve()
  {
      $path = $this->request->getPath();
      $method = $this->request->getMethod();
      if (preg_match('/[0-9]+/',$path, $params)) {
        $callback = $this->routes[$method][str_replace($params[0], '{id}', $path)];
        return call_user_func($callback, $this->request, $params);
      }
      $callback = $this->routes[$method][$path] ?? false;
      
      if ($callback === false) {
          return "404";
      }

      return call_user_func($callback, $this->request);
  }
}