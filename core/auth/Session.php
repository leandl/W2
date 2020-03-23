<?php

    session_start();

    /**
     * Classe responsavel por controlar a variavel $_SESSION.
     **/
    class Session{

        /**
         * Metodo responsavel por setar o
         * conteudo na variavel $_SESSION.
         * @param string $index - nome do index para setar na sessao. 
         * @param array $content - conteudo para setar na sessao.
         * @return void
         **/
        static public function set(String $index, Array $content){
            $_SESSION[$index] = $content;
        } 

        /**
         * Metodo responsavel por pegar o 
         * conteudo na variavel $_SESSION.
         * @param string $index - nome do index para pegar na sessao. 
         * @return array
         **/
        static public function get(String $index){
            if(!empty($_SESSION[$index])){
                return $_SESSION[$index];
            }else{
                return [];
            }
        } 

        /**
         * Metodo responsavel por deletar o 
         * conteudo na variavel $_SESSION.
         * @param string $index - nome do index para deletado na sessao. 
         * @return void
         **/
        static public function delete(String $index){
            Log::add(Log::DEBUG, 'Deletado $_SERVER["'.$index.'"]');
            unset($_SESSION[$index]);
        }

        /**
         * Metodo responsavel por deletar 
         * a variavel $_SESSION.
         * @param void
         * @return void
         **/
        static public function deleteAll(){
            session_destroy();
        }

    }

