<?php

$router->group(null);
$router->get('/test', function($req, $res){
    Log::memoryInUse();
    $res
        ->status(401)
        ->json([
            1,2,3
        ]);
});

Import::router([
    '/test',
    '/auth'
]);

$router->error(function($req, $res){
    $res
        ->status($req->status)
        ->json([
            'err' => 'router not found',
            'url' => URL
        ]);
});