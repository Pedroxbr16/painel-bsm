<?php
namespace Core;

class Router {
  private array $routes = ['GET'=>[], 'POST'=>[]];
  private $fallback;

  public function get(string $path, $handler){ $this->add('GET',$path,$handler); }
  public function post(string $path, $handler){ $this->add('POST',$path,$handler); }
  public function fallback(callable $h){ $this->fallback = $h; }

  private function add(string $method, string $path, $handler){
    $regex = '#^' . rtrim(preg_replace('#\{([a-zA-Z_]\w*)\}#', '(?P<$1>[^/]+)', $path), '/') . '/?$#';
    $this->routes[$method][] = ['regex'=>$regex, 'handler'=>$handler];
  }

  public function dispatch(){
    $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
    $uri    = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
    foreach ($this->routes[$method] ?? [] as $r) {
      if (preg_match($r['regex'], $uri, $m)) {
        $params = array_filter($m, 'is_string', ARRAY_FILTER_USE_KEY);
        $h = $r['handler'];
        if (is_array($h) && is_string($h[0])) { $c=new $h[0](); echo $c->{$h[1]}(...$params); return; }
        echo $h(...$params); return;
      }
    }
    echo $this->fallback ? ($this->fallback)() : '404';
  }
}
