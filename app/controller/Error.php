<?php

namespace techweb\app\controller;

use techweb\core\Controller;

class Error extends Controller
{

    public function __construct()
    {
        $this->setLayout('default');
    }

    public function internalServerError()
    {
        $this->loadView('internal_server_error');
    }
    
    public function forbidden()
    {
        $this->loadView('forbidden');
    }

    public function notFound()
    {
        $this->loadView('not_found');
    }

}