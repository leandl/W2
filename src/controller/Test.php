<?php

    Import::model('/ModelUser');

    class test{

        public function add($req, $res){
            $modelUser = new ModelUser();

            $body = $req->body;
            $name = $body['name'];
            $email = $body['email'];
           
            $user = [
                'name' => $name,
                'email' => $email
            ];

            $modelUser->insert($userData);
             
            $res
                ->status(200)
                ->json([
                    'ok' => 'ok',
                    'user' => $user
                ]); 
    
        }

        public function list($req, $res){
            $modelUser = new ModelUser();

            $listUsers = $modelUser->list();

            $res
                ->status(200)
                ->json([
                    'ok' => 'ok',
                    'users' => $listUsers
                ]); 
        }


        public function find($req, $res){
            $modelUser = new ModelUser();

            $params = $req->params;
            $find = $params['find'];
            $where = (is_numeric($find)) ? [ 'id=' => $find ] : [ 'name=' => $find ];

            $listUsers = $modelUser->list([ 'where' => $where ]);

            $res
                ->status(200)
                ->json([
                    'ok' => 'ok',
                    'users' => $listUsers
                ]); 
        }

        public function delete($req, $res){
            try {
                $modelUser = new ModelUser();

                $params = $req->params;
                $id = $params['id'];
    
                $modelUser
                    ->valid([ 'id' => $id ])
                    ->delete([ 'id' => $id ]);
                 
                $res
                    ->status(200)
                    ->json([ 'ok' => 'ok' ]); 
            
            } catch ($err) {
                $this->responseErr($res, $err);
            }
            
        }

        public function responseErr($res, $err){
            $res
                ->status(401)
                ->json([ 'ok' => $err ]);
        } 
    }  

