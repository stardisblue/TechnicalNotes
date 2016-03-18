<?php
/**
 * TechnicalNotes <https://www.github.com/stardisblue/TechnicalNotes>
 * Copyright (C) 2016  TechnicalNotes Team
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace techweb\core\router;

use techweb\core\Controller;
use techweb\core\Error;

class Route
{
    const MATCH_INDEX = 1;
    const METHOD_INDEX = 1;
    const CONTROLLER_INDEX = 0;
    private $path;
    private $callable;
    private $matches = [];
    private $parameters = [];

    public function __construct(string $path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
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

            /** @var Controller $controller */
            $controller = new $class();

            if (!is_callable([$controller, $method])) {
                Error::create('Router: method "' . $method . '" does not exists', 500);
            }

            $controller->beforeCall($method);
            $result = $controller->$method(...$this->matches);
            $controller->afterCall($method);

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

        foreach ($parameters as $key => $value) {
            $path = str_replace(':' . $key, $value, $path);
        }

        return $path;
    }

    private function parameterMatch(array $match): string
    {
        if (isset($this->parameters[$match[self::MATCH_INDEX]])) {
            return '(' . $this->parameters[$match[self::MATCH_INDEX]] . ')';
        }

        return '([^/]+)';
    }

}