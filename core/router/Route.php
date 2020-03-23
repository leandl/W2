<?php
    /**
     * Classe reposavel por entender as url e
     * executar um funcao ou metodo.
     */
    class Router{
        private $path;
        private $view = null;
        private $method;

        private $routerGroup;
        private $routerErr;
        private $err;
        private $routers = [
            'GET' => [],
            'POST' => [],
            'DELETE' => [],
            'PUT' => []
        ];

        function __construct($url, $view=null){
            $this->url = $url;
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->view = $view;
        }

        /** 
         * Metodo resposavel por set o caminho onde fica os 
         * arquivos de controles.
         * @param string $path - caminho onde fica os arquivos de controle.
         * @return void
         */
        public function setPath(String $path){
            $this->path = $path;
        }

        /** 
         * Metodo resposavel por agrupar as rotas.
         * @param string|null $group - nome do agrupamente de rotas.
         * @return void
         */
        public function group($group){
            $this->routerGroup = ($group) ? "/$group" : ''; 
        }

        /** 
         * Metodo resposavel por setar um controle caso 
         * não exista uma rota especifica.
         * @param string|function $controllerErr - controlador de error.
         * @return void
         */
        public function error($controllerErr){
            $this->routerErr = $controllerErr;
        }

        /** 
         * Metodo resposavel por adiciona um rota.
         * @param string $method - metodo http.
         * @param string $route - rota url.
         * @param string|function $controller - controlador a ser excutado.
         * @return void
         */
        private function addRouter(String $method, String $route, $controller){
            $this->routers[$method][] = [
                'routerFake' => preg_replace('~{([^}]*)}~', "([^/]+)", $this->routerGroup . $route),
                'router' => $this->routerGroup . $route,
                'controller' => $controller
            ];
        }

        /** 
         * Metodo resposavel por adiciona um rota GET.
         * @param string $route - rota url.
         * @param string|function $controller - controlador a ser excutado.
         * @return void
         */
        public function get(String $route, $controller){
            $this->addRouter('GET', $route, $controller);
        }


        /** 
         * Metodo resposavel por adiciona um rota POST.
         * @param string $route - rota url.
         * @param string|function $controller - controlador a ser excutado.
         * @return void
         */
        public function post(String $route, $controller){
            $this->addRouter('POST', $route, $controller);
        }

        /** 
         * Metodo resposavel por adiciona um rota DELETE.
         * @param string $route - rota url.
         * @param string|function $controller - controlador a ser excutado.
         * @return void
         */
        public function delete(String $route, $controller){
            $this->addRouter('DELETE', $route, $controller);
        }

        /** 
         * Metodo resposavel por adiciona um rota PUT.
         * @param string $route - rota url.
         * @param string|function $controller - controlador a ser excutado.
         * @return void
         */
        public function put(String $route, $controller){
            $this->addRouter('PUT', $route, $controller);
        }

        /** 
         * Metodo resposavel por pegar os parametro passa na url.
         * @param string $router - rota url.
         * @return array
         */
        private function getParams($router){
            preg_match_all("~\{\s* ([a-zA-Z_][a-zA-Z0-9_-]*) \}~x", $router, $keys, PREG_SET_ORDER);
            $routeDiff = array_values(array_diff(explode("/", $this->url), explode("/", $router)));

            $data = [];
            $offset = 0;

            foreach ($keys as $key) {
                $data[$key[1]] = ($routeDiff[$offset++] ?? null);
            }

            return $data;
        }

        /** 
         * Metodo resposavel por pegar os parametro passa no corpo da requisicao.
         * @return array
         */
        protected function formSpoofing(){
            $method = $this->method;
            $data = [];

            if ($method === "POST") {
                $data = filter_input_array(INPUT_POST, FILTER_DEFAULT);
            }

            if ($method === "GET") {
                $data = filter_input_array(INPUT_GET, FILTER_DEFAULT);
                unset($data['url_router']);
            }

            if (in_array($method, ["PUT", "DELETE"]) && !empty($_SERVER['CONTENT_LENGTH'])) {
                parse_str(file_get_contents('php://input', false, null, 0, $_SERVER['CONTENT_LENGTH']), $putPatch);
                $data = $putPatch;
            }

            return $data;
        }

        /** 
         * Metodo resposavel por pegar os parametro passa na url
         * e no corpo da requisicao.
         * @param string $router - rota url.
         * @return object
         */
        private function getData($router){
            $req = new stdClass();
            $req->params = $this->getParams($router);
            $req->body = $this->formSpoofing();
            $req->files = $_FILES;
            return $req;
        }

        /** 
         * Metodo resposavel por executar controle da rota.
         * @param string|function $controller - controlador a ser excutado.
         * @param array $data - parametro passa na url e no corpo da requisicao.
         * @return void
         */
        private function executeRouter($controller, Object $data){
            $view = $this->view;

            if(is_string($controller)){
                $array = explode(':', $controller);
                $class = $array[0];
                $action = $array[1];

                require "$this->path/$class.php";

                $class = new $class();
                $class->$action($data, $view);

            }else{
                $controller($data, $view);
            }
        }

        /** 
         * Metodo resposavel por retornar as rotas registradas.
         * @return array
         */
        public function getRouters(){
            return $this->routers;
        }

        /** 
         * Metodo resposavel por definir qual rota sera escutada.
         * @return void
         */
        public function execute(){
            $url = $this->url;
            $method = $this->method;
            $controller = null;
            $fakeRoute = null;
            $router = null;
            $data = [];

            $routersMethod = $this->routers[$method];

            foreach($routersMethod as $key => $value){
                $fakeRoute = $value['routerFake'];

                if(preg_match("~^$fakeRoute$~", $url, $found)){
                    $controller = $value['controller'];
                    $router = $value['router'];
                    $data = $this->getData($router);
                }
            }
            
            if($controller){
                Log::add(Log::DEBUG, "Rota a se executada: $method $url");
                $this->executeRouter($controller, $data);
            }else{
                Log::add(Log::DEBUG, "Rota que não foi executada: $method $url");
                
                $req = new stdClass();
                $req->status = 404;

                $this->executeRouter($this->routerErr, $req);
            }
        }
    }