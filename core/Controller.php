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

namespace techweb\core;

use techweb\core\exception\IOException;

abstract class Controller
{
    const LOG_NOTICE = 0;
    const LOG_WARNING = 1;
    const LOG_FATAL_ERROR = 2;

    protected $data = [];

    protected $layout = false;

    private static $currentLogFile;

    public function afterCall($method)
    {
        $method();
    }

    protected function loadView(string $view, array $data = [])
    {
        if (!empty($data) || !empty($this->data)) {
            extract(array_merge($this->data, $data));
        }

        $controller = explode('\\', static::class);

        $file = ROOT . '/app/view/' . strtolower(end($controller)) . '/' . $view . '.php';

        ob_start();

        if (file_exists($file)) {
            include_once $file;
        } else {
            Error::create('Error while loading view', 404);
        }

        $content = ob_get_clean();

        if (!$this->layout) {
            echo $content;
        } else {
            include_once ROOT . '/app/view/layout/' . $this->layout . '.php';
        }
    }

    protected function redirect(string $page = '')
    {
        header('Location: ' . WEB_ROOT . '/' . $page);
        exit;
    }

    protected function log(string $message, int $priority = self::LOG_NOTICE)
    {
        $log = date('H:i:s');

        switch ($priority) {
            case self::LOG_NOTICE:
                $log .= ' : ' . $message;
                break;
            case self::LOG_WARNING:
                $log .= ' WARNING : ' . $message;
                break;
            case self::LOG_FATAL_ERROR:
                $log .= ' FATAL ERROR : ' . $message;
        }

        try {
            $this->writeLog($log);
        } catch (IOException $ioException) {
            Error::create($ioException->getMessage(), 500);
        }
    }

    private function writeLog(string $message)
    {
        if (isset(self::$currentLogFile)) {
            file_put_contents(self::$currentLogFile, $message . PHP_EOL, FILE_APPEND);
        } else {
            if (file_exists(ROOT . '/log') === false) {
                mkdir(ROOT . '/log');
            }

            self::$currentLogFile = ROOT . '/log/' . date('d-m-Y') . '.log';

            if (!file_exists(self::$currentLogFile) && !fopen(self::$currentLogFile, 'a')) {
                throw new IOException('Unable to create log file');
            }

            $this->writeLog($message);
        }
    }

    protected function setLayout($layout, array $data = [])
    {
        $this->data = $data;
        $this->layout = file_exists(ROOT . '/app/view/layout/' . $layout . '.php') ? $layout : false;
    }

}