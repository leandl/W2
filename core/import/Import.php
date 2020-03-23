<?php

    class Import{

        private static $paths = [];

        public static function setPaths(Array $paths){
            self::$paths = $paths;
        }

        /**
         * Metodo resposavel por importar as routers da apLicação.
         * @param string|array $path - caminho(s) do(s) arquivo(s).
         * @return void
         */
        public static function router($paths = []){
            self::processImport('ROUTER', $paths);
        }

        /**
         * Metodo resposavel por importar as views da apLicação.
         * @param string|array $path - caminho(s) do(s) arquivo(s).
         * @return void
         */
        public static function view($paths = []){
            self::processImport('VIEW', $paths);
        }

        /**
         * Metodo resposavel por importar os controlles da apLicação.
         * @param string|array $path - caminho(s) do(s) arquivo(s).
         * @return void
         */
        public static function controller($paths = []){
            self::processImport('CONTROLLER', $paths);
        }

        /**
         * Metodo resposavel por importar os model da apLicação.
         * @param string|array $path - caminho(s) do(s) arquivo(s).
         * @return void
         */
        public static function model($paths = []){
            self::processImport('MODEL', $paths);
        } 

        /**
         * Metodo resposavel por verficar se será importado multiplo arquivos
         * ou apenas um.
         * @param string $type - qual tipo de importação será feita.
         * @param string|array $paths - caminho(s) do(s) arquivo(s).
         */
        private static function processImport(String $type, $paths){
            if(!is_array($paths)){
                $path = $paths;
                self::requireFile($type, $path);
                return;
            }

            foreach ($paths as $path) {
                self::requireFile($type, $path);
            }
        }

        /**
         * Metodo resposavel por importar aquivo.
         * @param string $type - qual tipo de importação será feita.
         * @param string|array $paths - caminho(s) do(s) arquivo(s).
         */
        private static function requireFile(String $type, String $path){
            require_once self::$paths[$type] . $path . '.php';
        }

    }