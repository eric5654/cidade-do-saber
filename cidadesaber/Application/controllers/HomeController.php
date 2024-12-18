<?php
namespace Application\controllers;

use Application\core\Controller;

class HomeController extends Controller
{
    public function index()
    {
        $this->view('Application/index');
    }
}
