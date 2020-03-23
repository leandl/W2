<?php
    global $router;

    //ROTAS DO CONTROLLER TEST
    $router->group('api-test');

    if(Auth::isValid()){
        $router->post('/add', 'Test:add');
        //$router->get('/update', 'Test:update');
        $router->delete('/delete/{id}', 'Test:delete');
        $router->get('/find/{find}', 'Test:find');
        $router->get('/list', 'Test:list');
    }

   