<?php

    //Api
    define('MODE_API', FALSE);

    //Banco de dados
    define('DATABASE', 'test_W2');
    define('USER', 'root');
    define('PASSWORD', '');

    //Rotas
    define('BASE_URL', 'http://localhost/w2');
    define('URL', (empty($_REQUEST['url_router'])) ? '/' : $_REQUEST['url_router'] );
    define('STATIC_FILE', BASE_URL . '/public');

    //Path Framwork
    define('DIR', __DIR__);
    define('ROOT', __DIR__ . '/src');
    define('PUBLIC_FILES', __DIR__ . '/public');
    define('CONTROLLER', ROOT . '/controller');
    define('ROUTER', ROOT . '/router');
    define('MODEL', ROOT . '/model');
    define('VIEW', ROOT . '/view');

    //Log
    define('LOG', __DIR__ . '/log');
    date_default_timezone_set('Brazil/East');

    //Files
    define('FILE_DEFAULT_HEADER', [
        '/css/bootstrap.min.css',
        '/css/style.css'
    ]);
    define('FILE_DEFAULT_BODY', [
        '/js/jquery.js',
        '/js/bootstrap.bundle.min.js',
        '/js/alerts.js',
        '/js/Ajax.js'
    ]);

    //Files Upload
    define('UPLOADING', [
        'images' => [
            'path' => PUBLIC_FILES . '/static',
            'formatValid' => [
                "image/png",
                "image/jpeg"
            ],
            'prefix' => 'image'
        ]
    ]);