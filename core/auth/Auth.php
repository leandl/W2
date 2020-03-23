<?php
    /**
     * Classe responsavel por controlar autenticação do usuario.
     **/
    class Auth{
        
        /**
         * metodo reposavel por autenticar um usuario.
         * @param string $type - tipo de usuario.
         * @param array $user - informações do usuario.
         * @return void
         */
        static public function set(String $type, Array $user){
            Log::add(Log::DEBUG, 'Setado usuario '.$type );
            Session::set('auth', [
                'type' => $type,
                'user' => $user
            ]);
        }

        /**
         * metodo reposavel por retornar autenticacão do usuario.
         * @param void
         * @return array
         */
        static public function get(){
            return Session::get('auth');
        }

        /**
         * metodo reposavel verificar se o usuario esta autenticacão.
         * @param string|array $typesUserValid - tipos de usuarios validos
         * @return bool
         */
        static public function isValid($typesUserValid = []){
            $auth = self::get();

            if(empty($auth['type'])){
                return false;
            }

            $type = $auth['type'];
            if(!is_array($typesUserValid)){
                return ($type===$typesUserValid) ? TRUE : FALSE;
            }

            if(empty($typesUserValid)){
                return TRUE;
            }

            return (in_array($type, $typesUserValid)) ? TRUE : FALSE;
        }

        /**
         * metodo reposavel por deletar a autenticacão do usuario.
         * @param void
         * @return void
         */
        static public function delete(){
            Session::delete('auth');
        }
    }
    

