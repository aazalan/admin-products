<?php

class Router
{
    // массив для хранения соответствия url => функция
    private static $routes = array();
   
    // запрещаем создание и копирование статического объекта
    private function __construct() {}
    private function __clone() {}
   
   
    // данный метод принимает шаблон url-адреса
    // как шаблон регулярного выражения и связывает его
    // с пользовательской функцией
    public static function route($name, $callback)
    {
        // функция str_replace здесь нужна, для экранирования всех прямых слешей
        // так как они используются в качестве маркеров регулярного выражения
        $name = '/^' . str_replace('/', '\/', $name) . '$/';
        self::$routes[$name] = $callback;
    }
   
   
    // данный метод проверяет запрошенный $url(адрес) на
    // соответствие адресам, хранящимся в массиве $routes
    public static function execute($url)
    {
        foreach (self::$routes as $name => $callback)
        {
            if (preg_match($name, $url, $params)) // сравнение идет через регулярное выражение
            {
                // соответствие найдено, поэтому удаляем первый элемент из массива $params
                // который содержит всю найденную строку
                array_shift($params);
                return call_user_func_array($callback, array_values($params));
            }
        }
    }
}

(\d+)

class Router
{
  public static $request;
  private $supportedHttpMethods = array(
    "GET",
    "POST"
  );
  private static $routes = array();

  function __construct(Request $request)
  {
   $this->request = $request;
  }

  public static function route($name, $callback)
    {
        // функция str_replace здесь нужна, для экранирования всех прямых слешей
        // так как они используются в качестве маркеров регулярного выражения
        $name = '/^' . str_replace('/', '\/', $name) . '$/';
        self::$routes[$name] = $callback;
    }
   
   
    // данный метод проверяет запрошенный $url(адрес) на
    // соответствие адресам, хранящимся в массиве $routes
    public static function execute($url)
    {
        foreach (self::$routes as $name => $callback)
        {
            if (preg_match($name, $url, $params)) // сравнение идет через регулярное выражение
            {
                // соответствие найдено, поэтому удаляем первый элемент из массива $params
                // который содержит всю найденную строку
                array_shift($params);
                return call_user_func($callback, self::$request, array_values($params));
            }
        }
    }
  
}




AND price < :price_to AND 
        price > :price_from

        'price_to' => $properties['price_to'],
            'price_from' => $properties['price_to']
