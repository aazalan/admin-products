<?php

namespace Http;

class Request
{
    private $method;
    public function getPath()
    {
        $path = $_SERVER['REQUEST_URI'] ?? '/';
        $position = strpos($path, '?');

        if ($position === false) return $path;
        return substr($path, 0, $position);
    }

    public function getMethod()
    {
        $this->method = strtolower($_SERVER['REQUEST_METHOD']);
        return $this->method;
    }

    public function getBody()
  {
    if($this->method === "get")
    {
      return;
    }

    if ($this->method == "post")
    {

      $body = array();
      foreach($_POST as $key => $value)
      {
        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_SPECIAL_CHARS);
      }
      return $body;
    }
  }
}