<?php

namespace techweb\core\router;

use techweb\core\exception\RouterException;

class Router
{
    private $url;

    private $routes = [];
    private $namedRoutes = [];

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    private function add(string $method, string $path, $callable, $name): Route
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if (is_string($callable) && $name === null) {
            $name = $callable;
        }

        if ($name) {
            $this->namedRoutes[$name] = $route;
        }

        return $route;
    }

    public function put(string $path, $callable, string $name = null): Route
    {
        return $this->add('PUT', $path, $callable, $name);
    }

    public function get(string $path, $callable, string $name = null): Route
    {
        return $this->add('GET', $path, $callable, $name);
    }

    public function post(string $path, $callable, string $name = null): Route
    {
        return $this->add('POST', $path, $callable, $name);
    }

    public function delete(string $path, $callable, string $name = null): Route
    {
        return $this->add('DELETE', $path, $callable, $name);
    }

    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {
            throw new RouterException('REQUEST_METHOD does not exists');
        }

        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route)
        {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new RouterException('No matching route');
    }

    public function url(string $name, $parameters = []): string
    {
        if (!isset($this->namedRoutes[$name])) {
            throw new RouterException('No route matching this name');
        }

        return $this->namedRoutes[$name]->getUrl($parameters);
    }

}