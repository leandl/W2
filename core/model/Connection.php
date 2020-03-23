<?php

    /**
     * Class resposavel por fazer a conexao com o banco de dados.
     */
    class Connection extends PDO{
        private $database = DATABASE;
        private $user = USER;
        private $password = PASSWORD;
        
        /**
         * Metodo construtor da class, resposavel por
         * fazer a conexao com o banco de dados.
         * @return void
         */
        function __construct(){
            $dsn = 'mysql:host=localhost;dbname='.$this->database;
            $options = [
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ];

            try{
                parent::__construct($dsn, $this->user, $this->password, $options);
                Log::add(Log::INFO, 'Banco Conectado!');
            }
            catch(PDOException $e){	
                Log::add(Log::ERROR, 'Database error: ' . $e->getMessage());
            }
        }
    }