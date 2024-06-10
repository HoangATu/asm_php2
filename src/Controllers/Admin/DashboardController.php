<?php

namespace Hoang\Mvcoop\Controllers\Admin;

use Hoang\Mvcoop\Commons\Controller;

class DashboardController extends Controller
{
    public function dashboard() {
        return $this->renderViewAdmin('dashboard');
    }

    public function pageNotFound()
    {
        return $this->renderViewAdmin('404');
    }
}