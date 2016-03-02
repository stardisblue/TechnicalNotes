<?php

namespace techweb\app\controller;

use techweb\core\Controller;

class Main extends Controller
{

    public function __construct()
    {
        $this->setLayout('default');
    }

    public function index()
    {
        $this->loadView('main');
    }

}
