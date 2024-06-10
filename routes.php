<?php

use Bramus\Router\Router;

//Admin
use Hoang\Mvcoop\Controllers\Admin\AccountController;
use Hoang\Mvcoop\Controllers\Admin\DashboardController;
use Hoang\Mvcoop\Controllers\Admin\CommentController;
use Hoang\Mvcoop\Controllers\Admin\CategoryController;
use Hoang\Mvcoop\Controllers\Admin\PostController;
use Hoang\Mvcoop\Controllers\Admin\UserController;


//User
use Hoang\Mvcoop\Controllers\Client\HomeController;
use Hoang\Mvcoop\Controllers\Client\AccountController as ClientAccountController;
use Hoang\Mvcoop\Controllers\Client\PostController as ClientPostController;
use Hoang\Mvcoop\Controllers\Client\CategoryController as ClientCategoryController;
use Hoang\Mvcoop\Controllers\Client\ContactController;
use Hoang\Mvcoop\Controllers\Client\AboutUsController;


// Create Router instance
$router = new Router();


// Phương thức
$router->get('/',                                           HomeController::class . "@home");
$router->match('GET|POST','/profile',               ClientAccountController::class . "@profile");
$router->match('GET|POST','/postDetail/{id_post}',  ClientPostController::class . "@postDetail");
$router->get('/category',                                   ClientCategoryController::class . "@listCategory");
$router->get('/category/{slug}/{page}',                     ClientCategoryController::class . "@postByCategory");
$router->get('/category/{slug}',                            ClientCategoryController::class . "@postByCategory");
$router->get('/result',                                     HomeController::class . "@searchPost");
$router->get('/contact',                                    ContactController::class . "@contact");
$router->get('/aboutUs',                                    AboutUsController::class . "@aboutUs");

$router->mount('/auth', function () use ($router) {
    $router->match('GET|POST','/login',             AccountController::class . "@login");
    $router->match('GET|POST','/register',          AccountController::class . "@register");
    $router->match('GET|POST','/forgot-password',   AccountController::class . "@forgotPassword");
    $router->get('/logout',                                 AccountController::class . "@logout");
});

$router->mount('/admin', function () use ($router) {

    $router->get('/',                                                           DashboardController::class . "@dashboard");

    $router->mount('/categories', function () use ($router) {
        $router->get('/',                                                       CategoryController::class . "@listCategory");
        $router->match('GET|POST', '/addCategories',                    CategoryController::class . "@addCategories");
        $router->match('GET|POST', '/editCategories/{id_category}',     CategoryController::class . "@editCategories");
        $router->get('/categoryDetail/{id_category}',                           CategoryController::class . "@categoryDetail");
        $router->match('GET|POST', '/deleteCategories/{id_category}',   CategoryController::class . "@deleteCategories");
        $router->get('/searchCategory',                                         CategoryController::class . "@searchCategory");
    });

    $router->mount('/posts', function () use ($router) {
        $router->get('/',                                           PostController::class . "@listPost");
        $router->match('GET|POST', '/addPost',              PostController::class . "@addPost");
        $router->match('GET|POST', '/editPost/{id_post}',   PostController::class . "@editPost");
        $router->match('GET|POST', '/detailPost/{id_post}', PostController::class . "@detailPost");
        $router->match('GET|POST', '/deletePost/{id_post}', PostController::class . "@deletePost");
        $router->get('/searchPost',                                 PostController::class . "@searchPost");
    });

    $router->mount('/users', function () use ($router) {
        $router->get('/',                                           UserController::class . "@listUser");
        $router->match('GET|POST', '/addUser',              UserController::class . "@addUser");
        $router->match('GET|POST', '/editUser/{id_post}',   UserController::class . "@editUser");
        $router->match('GET|POST', '/deleteUser/{id_post}', UserController::class . "@deleteUser");
        $router->get('/searchUser',                                 UserController::class . "@searchUser");
    });

    $router->mount('/comments', function () use ($router) {
        $router->get('/',                                           CommentController::class . "@listComment");
        $router->get('/commentByIdPost/{idPost}',                              CommentController::class . "@commentDetail");
    });

});


//Check trước khi truy cập vào đường dẫn này
$router->before('GET|POST', '/admin/*', function () {
    $user = (new \Hoang\Mvcoop\Models\Admin\UserModel())->getUserById($_SESSION['account_id'] ?? 0);
    if(!isset($_SESSION['account_id'])) {
        header('Location: /auth/login');
        exit();
    } else {
        if($user['role'] == 0) {
            header('Location: /');
            exit();
        }

    }
});

$router->before('GET|POST', '/admin/.*', function () {
    $user = (new \Hoang\Mvcoop\Models\Admin\UserModel())->getUserById($_SESSION['account_id'] ?? 0);
    if(!isset($_SESSION['account_id'])) {
        header('Location: /auth/login');
        exit();
    } else {
        if($user['role'] == 0) {
            header('Location: /');
            exit();
        }

    }
});


$router->set404(function () {
    if (str_starts_with($_SERVER['REQUEST_URI'], '/admin')) {
        $dasboard = new DashboardController();
        $dasboard->pageNotFound();
    } else {
        $home = new HomeController();
        $home->pageNotFound();
    }
});

// Run it!
$router->run();
