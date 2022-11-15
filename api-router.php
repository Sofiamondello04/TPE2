<?php
require_once './libs/Router.php';
require_once './app/controllers/api-product.controller.php';


// creo el router
$router = new Router();

// defino la tabla de ruteo
$router->addRoute('products', 'GET', 'ApiProductController', 'getProducts'); //lista todos los productos- punto 2
$router->addRoute('products/:ID', 'GET', 'ApiProductController', 'getProduct');// muestra un solo producto
$router->addRoute('products', 'POST', 'ApiProductController', 'addProduct');// agrega un producto 

// ejecuto la ruta (sea cual sea)
$router->route($_GET["resource"], $_SERVER['REQUEST_METHOD']);


