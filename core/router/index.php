<?php

    require_once 'Route.php';

    $router = new Router(URL, $view);
    $router->setPath(CONTROLLER);

    require_once ROOT . '/routers.php';
    
    $router->execute();