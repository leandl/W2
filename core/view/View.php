<?php
    /**
     * Classe responsavel por retorna parte 
     * visual para o navegador.
     **/
    class View {
        private $path;

        /**
         * Metodo reposavel por setar o caminho para Views.
         * @param string $path - caminho onde ficam as views.
         * @return void
         */
        public function setPath(string $path){
            $this->path = $path;
        }

        /**
         * Metodo resposavel por renderizar uma view.
         * @param string $file - arquivo a ser excutado.
         * @param array $_ - array das variaveis que seram ultiliza na view.
         * @return void
         */
        public function render(String $file='index', Array $_=[]){
            foreach ($_ as $key => $value) {
                $$key = $value;
            }
            require_once $this->path. '/' . $file . '.php';
        }

        /**
         * Metodo resposavel por rederizar jsons.
         * @param array $res - array que sera convertido para json.
         * @param int|string $status - code do status retornado para o navegador.
         * @param void
         */
        public function json(Array $res=[], $status='OK'){
            echo json_encode($res);
        }
        public function status($status='OK'){
            new HttpStatusCode($status);
            return $this;
        }

        /**
         * Metodo resposavel por redirecionar o usuario.
         * @param string $url - url de redirecionamento.
         * @return void
         */
        public function redirect(String $url='/'){
            Log::add(Log::DEBUG, 'Redirect url: '. $url);
            header('Location:' . BASE_URL . $url);
        }
    }