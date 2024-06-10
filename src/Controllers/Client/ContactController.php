<?php

namespace Hoang\Mvcoop\Controllers\Client;
use Hoang\Mvcoop\Commons\Controller;

class ContactController extends Controller
{
    public function __construct()
    {

    }

    public function contact()
    {
        if (!empty($_SESSION['url'])) {
            unset($_SESSION['url']);
        }

        return $this->renderViewClient('contact');
    }
}