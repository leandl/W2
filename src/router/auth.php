<?php
    global $router;

    //ROTAS DE Autenticação
    $router->group('auth');


    $router->get('/status', function($req, $res){
        $res
            ->status('OK')
            ->json([
                'auth' => Auth::isValid()
            ]);
    });

    $router->post('/login', function($req, $res){
        Auth::set('Admin', [
            'nome' => 'admin',
            'email' => 'admin@admin.com'
        ]);

        $res
            ->status('OK')->json([
                'auth' => TRUE
            ]);
    });

    if(Auth::isValid()){
        $router->get('/logoff', function($req, $res){
            Auth::delete();
            $res
                ->status(200)
                ->json([
                    'auth' => FALSE
                ]);
        });
    }