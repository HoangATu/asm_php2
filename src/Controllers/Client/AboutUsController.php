<?php

namespace Hoang\Mvcoop\Controllers\Client;

use Hoang\Mvcoop\Commons\Controller;

class AboutUsController extends Controller
{
    public function __construct()
    {

    }

    public function aboutUs()
    {
        if (!empty($_SESSION['url'])) {
            unset($_SESSION['url']);
        }

        return $this->renderViewClient('aboutUs');
    }
}