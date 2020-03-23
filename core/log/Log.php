<?php

	/**
     * Classe responsavel por gerar logs manuais.
     **/
    class Log{
			
		const ERROR = 1;
		const WARNING = 2;
		const INFO = 3;
		const DEBUG = 4;
		
		private static $nivel = self::DEBUG;
		private static $path;

		/**
		 * Metodo repodavel por criar um log de 'ERROR'.
		 * @param string|array|object $msg - messagem do log.
		 * @return void
		 */
		static public function error($msg){
			self::add(self::ERROR, $msg, 1);
		}

		/**
		 * Metodo repodavel por criar um log de 'WARNING'.
		 * @param string|array|object $msg - messagem do log.
		 * @return void
		 */
		static public function warning($msg){
			self::add(self::WARNING, $msg, 1);
		}

		/**
		 * Metodo repodavel por criar um log de 'INFO'.
		 * @param string|array|object $msg - messagem do log.
		 * @return void
		 */
		static public function info($msg){
			self::add(self::INFO, $msg, 1);
		}

		/**
		 * Metodo repodavel por criar um log de 'DEBUG'.
		 * @param string|array|object $msg - messagem do log.
		 * @return void
		 */
		static public function debug($msg){
			self::add(self::DEBUG, $msg, 1);
		}

		/**
		 * Exibe a memória que o sistema está usando no momento.
		 * @return void 
		 */
		public static function memoryInUse(){
			$size = memory_get_usage();
			$unit=array('b','kb','mb','gb','tb','pb');
			$memoryInUse = @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
			
			self::add(self::DEBUG, "Memoria em uso: $memoryInUse", 1);
		}

		/**
         * Metodo responsavel por adicionar um novo log.
		 * Necessita ser Static para ser possivel a chamada 
		 * em qualquer lugar.
         * @param int $nivel - nivel do log
         * @param string|array|object $msg - mensagem do log
		 * @param int $fileCall - file que chamou a função 'add'
         * @return void
         **/
		static public function add(Int $nivel, $msg, $fileCall = 0){
			if(self::$nivel>=$nivel){

				$arrayTreeFile = debug_backtrace();
				$fileExecut = str_replace(DIR, '', $arrayTreeFile[0]['file']);
				$fileExecut = str_replace('\\', '/', $fileExecut);

				$color = self::colorNivel($nivel);
				$colorPattern = self::colorNivel(0);
				$nameNivel = self::nameNivel($nivel);
				$dataNow =  date('d/m/Y H:i:s');
				$formatMsg = self::formatMsg($msg);

				$msgLog = "\n$color";
				$msgLog .= "$dataNow | $nameNivel | ($fileExecut) => $formatMsg$colorPattern";
				$path = self::$path.'/Log.log';
				
				file_put_contents($path, $msgLog, FILE_APPEND);
			}
		}

		/**
         * Metodo responsavel por retornar
         * o nome do nivel
         * @param int $nivel - nivel do erro
         * @return string
         **/
		private static function nameNivel($nivel) {
            switch ($nivel) {
                case self::ERROR : return                " ERROR "; 
                case self::WARNING : return              "WARNING"; 
                case self::INFO : return                 " INFOR "; 
                case self::DEBUG : return                " DEBUG ";
                default : return                         " [ - ] ";
            }
		}

        /**
         * Metodo responsavel por retornar
         * a cor do nivel
         * @param int $nivel - level do erro
         * @return string
         **/
		private static function colorNivel($nivel) {
            switch ($nivel) {
                case self::ERROR : return                "\033[1;31m"; //red
                case self::WARNING : return              "\033[1;33m"; //yellow
                case self::INFO : return                 "\033[1;36m"; //blue
                case self::DEBUG : return                "\033[1;32m"; //green
                default : return                         "\033[0m"; //white
			}
		}
		
		/**
         * Metodo que seta a caminho para o 
         * arquivo 'PHPLog.log'
         * @param string $path - caminho caminho para o arquivo 'PHPLog.log'
         * @return void
         **/
		public static function setPath(String $path){
			self::$path = $path;
		}

		/**
         * Metodo responsavel por foramta a messagem 
         * dependendo do conteudo da variavel $message
         * @param string|array|object $message - $messagem que vai aparece no log
         * @return string
         **/
		private static function formatMsg($message) {
			if (is_array($message)) { $msg = print_r($message, TRUE); }
			else if (is_object($message)) { $msg = self::var_dump_ret($message); }
			else { $msg = $message; }
			return $msg;
		}
		
	}


