<?php

namespace techweb\core\router;

use techweb\core\Error;

class Route
{
    private $path;
    private $callable;

    private $matches = [];
    private $parameters = [];

    const MATCH_INDEX = 1;
    const METHOD_INDEX = 1;
    const CONTROLLER_INDEX = 0;

    public function __construct(string $path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    private function parameterMatch(array $match): string
    {
        if (isset($this->parameters[$match[self::MATCH_INDEX]])) {
            return '(' . $this->parameters[$match[self::MATCH_INDEX]] . ')';
        }

        return '([^/]+)';
    }

    public function match(string $url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'parameterMatch'], $this->path);

        if (!preg_match('#^' . $path . '$#i', $url, $matches)) {
            return false;
        }

        array_shift($matches);

        $this->matches = $matches;

        return true;
    }

    public function call()
    {
        if (is_array($this->callable)) {
            $namespace = 'techweb\\app\\controller\\';

            $method = reset($this->callable);
            $class = $namespace . key($this->callable);

            if (!class_exists($class)) {
                Error::create('Router: class "' . $class . '" does not exists', 500);
            }

            $controller = new $class();

            if (!is_callable([$controller, $method])) {
                Error::create('Router: method "' . $method . '" does not exists', 500);
            }

            $result = $controller->$method(...$this->matches);
            $controller->afterCall(...$method);
            return $result;

        } else {
            if (!is_callable($this->callable)) {
                Error::create('Router: method does not exists', 500);
            }


            return call_user_func_array($this->callable, $this->matches);
        }
    }

    public function with(string $parameter, string $regex): Route
    {
        $this->parameters[$parameter] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function getUrl(array $parameters): string
    {
        $path = $this->path;

        foreach ($parameters as $key => $value)
        {
            $path = str_replace(':' . $key, $value, $path);
        }

        return $path;
    }

}