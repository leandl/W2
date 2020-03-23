<?php

    /**
     * Classe responsavel por controlar os uploads e deletes dos arquivos.
     **/
    class File{   

        static private $arrayInfo;

        /**
         * metodo reposavel por setar as informacao dos
         * uploaads e deletes.
         * @param array $info - informações dos uploads e deletes.
         * @return void
         */
        static public function setInfo(Array $info = []){
            self::$arrayInfo = $info;
        }

        /**
         * metodo reposavel por retornar se um variavel e uma matriz.
         * @param array $array - variavel que sera verificado.
         * @return bool
         */
        static private function is_matrix(Array $array){
            $isMatrix = true;
            foreach($array as $key => $value){
                if(!is_array($value)){
                    $isMatrix = false;
                    break;
                }
            }
            return $isMatrix;
        }


        /**
         * metodo reposavel por deletar um arquivo.
         * @param  string $typeUpload - o tipo de upload que foi feito, para pegar o path para deletar.
         * @param  string $file - file que sera delatado.
         * @return array
         */
        static public function delete(String $typeUpload, String $files){

            if(!is_array($files)){
                return self::deleteFile($typeUpload, $files);
            }

            $array = [];
            foreach($files as $key => $file){  
                $array[] = self::deleteFile($typeUpload, $file); 
            }
            return $array;
        }   

        /**
         * metodo reposavel por deletar um arquivo.
         * @param  string $typeUpload - o tipo de upload que foi feito, para pegar o path para deletar.
         * @param  array $file - file que sera delatado.
         * @return array
         */
        static public function upload(String $typeUpload, Array $files){

            if(!self::is_matrix($files)){
                return self::saveFile($typeUpload, $files);
            }

            $array = [];
            foreach($files as $key => $file){  
                $array[] = self::saveFile($typeUpload, $file); 
            };
            return $array;
        }

        /**
         * metodo reposavel por deletar um arquivo.
         * @param  string $typeUpload - o tipo de upload que foi feito, para pegar o path para deletar.
         * @param  string $file - file que sera delatado.
         * @return array
         */
        static public function deleteFile(String $typeUpload, String $file){
            $path = self::$arrayInfo[$typeUpload]['path'] . '/' . $file;
            if(unlink($path)){
                return [
                    'err' => false,
                    'msg' => 'file successfully deleted'
                ];
            }else{
                return [
                    'err' => true,
                    'msg' => "failed to delete the file: $file"
                ];
            }
        }

        /**
         * metodo reposavel retornar um hash unica.
         * @param void
         * @return string
         */
        function hashFilename(){
            $hash = rand() . date('U_s');
            return md5($hash);
        }

        /**
         * metodo reposavel por fazer upload de arquivo.
         * @param  string $typeUpload - o tipo de upload que sera feito.
         * @param  array $file - file que sera feito upload.
         * @return array
         */
        static public function saveFile(String $typeUpload, Array $file){
    
            if(!empty(self::$arrayInfo[$typeUpload])){
                return [ 
                    'err' => true,
                    'msg' => 'Invalid upload type!'
                ];
            }
    
            $formatValid = self::$arrayInfo[$typeUpload]['formatValid'];

            if(!in_array($file['type'], $formatValid)){
                return [ 
                    'err' => true,
                    'msg' => 'Invalid file extesion!'
                ];
            }

            $path = self::$arrayInfo[$typeUpload]['path'];
            $prefix = self::$arrayInfo[$typeUpload]['prefix'];
            $filenameToArray = explode('.', $file['name']);
            $extension = end($filenameToArray);
            $hash = self::hashFilename();

            $filename = "$prefix.$hash.$extension";
            $path .= "/$filename"; 
            
            if(move_uploaded_file($file['tmp_name'], $path)){
                return [ 
                    'err' => false,
                    'filename' => $filename
                ];
            }else{
                return [ 
                    'err' => true,
                    'msg' => 'failed to upload'
                ];
            }
        }
    }
