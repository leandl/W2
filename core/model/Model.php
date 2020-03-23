<?php

    /**
     * Classe resposavel por criar as querys sql.
     */
    class Model{
        
        private $conn;
        private $table;
        private $pk;
        private $attributes;

        /**
         * Função resposavel por pegar os variaveis das classes
         * que herdam desta classe. 
         */
        private function getMetaDados(){
            $this->attributes = array_slice(get_object_vars($this), 0, -4);
            $this->table = $this->attributes[array_keys($this->attributes)[0]];
            $this->pk = array_keys($this->attributes)[1];
        }

        function __construct(){
            $this->getMetaDados();
            $this->conn = new Connection();
        }

        /**
         * Função responsavel por criar as query de inserção no banco.
         * @param array $data - dados a serem inseridos no banco.
         */
        public function insert(Array $data){
            $conn = $this->conn;
            $table = $this->table;
            
            $atts = [];
            $values = [];
            
            foreach ($data as $key => $value) {
                $atts[] = "`$key`";
                $values[] = ":$key";
            }

            $strAtts = implode(',', $atts);
            $strValues = implode(',', $values);
                
            $sql = "INSERT INTO `$table` ($strAtts) VALUES ($strValues)";
            Log::add(Log::DEBUG, 'Query: [' . $sql . ']');

            $stmt = $conn->prepare($sql);
            foreach($data as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }

            return $stmt->execute();
        }

        /**
         * Função responsavel por criar a query de delete do banco.
         * @param array $data - dados que seram deletados
         */
        public function delete(Array $data){
            $conn = $this->conn;

            $arrayWhere = [];

            foreach ($data as $key => $value) {
                $arrayWhere[] = "`$key`=:$key";
            }

            $strWhere = implode($arrayWhere, ' AND ');

            $sql = "DELETE FROM `$this->table` WHERE $strWhere";
            Log::debug('Query: '.$sql);

            $stmt = $conn->prepare($sql);
            foreach($data as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }

            return $stmt->execute();
        }

        public function update(Array $dataSelect, Array $dataUpdate){
            $conn = $this->conn;

            $SQLSelect = [];
            $SQLUpdate = [];

            foreach ($dataSelect as $key => $value) {
                $SQLSelect[] = "`$key`=:$key";
            }

            foreach ($dataUpdate as $key => $value) {
                $SQLUpdate[] = "`$key`=:$key";
            }

            $sql = "UPDATE `$this->table` SET ".implode($SQLUpdate, ',')." WHERE ".implode($SQLSelect, ' AND ');
            Log::add(Log::DEBUG, 'Query => '.$sql);

            $stmt = $conn->prepare($sql);

            foreach($dataUpdate as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }

            foreach($dataSelect as $key => $value){
                $stmt->bindValue(':'.$key, $value);
            }

            return $stmt->execute();
        }
        

        private function createWhere(Array $data){
            $strWhere = 'WHERE ';
            $dataWhere = []; 

            $comps = [
                '=' => '=', '!' => '<>', 
                '~' => 'LIKE',
                '<' => '<', '>' => '>',
                '<=' => '<=', '>=' => '>='
            ];

            $explode = $size = NULL;

            foreach ($data as $key => $value) {
                if(is_numeric($key)){
                    $strWhere .= "$value ";
                }else{
                    $comp = '=';
                    foreach($comps as $char => $compChar){
                        $explode = explode($char, $key);
                        $size = count($explode);
                        if($size === 2){
                            $comp = $compChar;
                            $key = $explode[0];
                            break;
                        }
                    }
                    $keyHash = $key . rand() . date('U_s');
                    $keyHash = md5($keyHash);
                    $strWhere .=  "`$key` $comp :$keyHash ";
                    $dataWhere[$keyHash] = $value;
                }
            }

            return [
                $strWhere,
                $dataWhere
            ];
        }

        function replaceCharSpecial(String $string, Array $arrayReplace){
            foreach($arrayReplace as $key => $value){
                $string = str_replace($key, $value, $string);
            }
            return $string;
        }


        public function list(Array $data=[]){
            $conn = $this->conn;

            $fields = (isset($data['fields'])) ? $data['fields'] : '*';
            $table = (isset($data['table'])) ? $data['table'] : "`$this->table`";

            list( $sqlWhere, $dataWhere ) = (isset($data['where'])) ? $this->createWhere($data['where']) : [ "", "" ];

            $limit = (isset($data['limit'])) ? $data['limit'] : '50';
            $order = (!isset($data['order'])) ? "`$this->pk`" : $this->replaceCharSpecial($data['order'], [
                '+' => ' nao sei',
                '-' => ' DESC'
            ]);
 
            $sql = "SELECT $fields FROM $table $sqlWhere ORDER BY $order LIMIT $limit";
            Log::add(Log::DEBUG, 'Query => '.$sql);
            
            if(is_array($dataWhere)){
                $stmt = $conn->prepare($sql);
                foreach($dataWhere as $key => $value){
                    $stmt->bindValue(":$key", $value);
                }
                $stmt->execute();
            }else{
                $stmt = $conn->query($sql);
            }
            return $stmt->fetchAll(PDO::FETCH_OBJ);
        }

    }
