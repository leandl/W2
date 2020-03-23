<?php
    
     /**
     * Classe responsavel pelo gerenciamento manual
     * de erro do sistema.
     * Caso um erro desconhecido seja encontrado,
     * a classe retorna o controle de erros para
     * o ambiente PHP
     **/
    class PHPLog{

        private $nivelErr = 0;
        protected $msg = '';
        protected $fileErr = '';
        protected $lineErr = 0;
        protected $arr_context = array();
        static private $path;
        
        function __construct($nivelErr, $msg, $fileErr, $lineErr, $context){
            $this->$nivelErr = $nivelErr;
            $this->msg = $msg;
            $this->fileErr = $fileErr;
            $this->lineErr = $lineErr;
            $this->arr_context = $context;
        }
 
        /**
         * Metodo responsavel por controlar os erros que
         * acontecem no sistema. Necessita ser Static para 
         * ser possivel a chamada externa pelo PHP
         *
         * @param int $nivelErr - level do erro
         * @param string $msg - mensagem de erro
         * @param string $fileErr - nome do arquivo com erro
         * @param int $lineErr - numero da linha do erro
         * @param array $arr_context - contexto em que erro ocorreu
         * @return bool
         **/
        public static function add($nivelErr, $msg, $fileErr, $lineErr, $arr_context){
            // criando uma instancia propria deste objeto...
            $self = new self($nivelErr, $msg, $fileErr, $lineErr, $arr_context);

            $fileExecut = str_replace(DIR, '', $self->fileErr);
			$fileExecut = str_replace('\\', '/', $fileExecut);

            $color = self::colorNivel($nivelErr);
            $colorPattern = self::colorNivel(0);
            $nameNivel = self::nameNivel($nivelErr);
            $dataNow =  date('d/m/Y H:i:s');
            $formatMsg = $self->msg;
            $line = $self->lineErr;

            $msgLog = "\r\n$color";
            $msgLog .= "[PHP] [$dataNow] ($fileExecut line $line) [$nameNivel] | $formatMsg.$colorPattern";
            $path = self::$path.'/PHPLog.log';
            
            return error_log($msgLog, 3 , $path);
        }

        /**
         * Metodo responsavel por retornar
         * o nome do nivel
         * @param int $nivel - nivel do erro
         * @return string
         **/
        private static function nameNivel($nivel) {
            switch ($nivel) {
                case E_USER_NOTICE: 
                case E_NOTICE: return                    "NOTICES"; 
                case E_USER_ERROR:
                case E_ERROR: return                     " ERROR "; 
                case E_USER_WARNING:
                case E_WARNING: return                   "WARNING";
                default : return                         "   -   ";
            }
		}

        /**
         * Metodo responsavel por retornar
         * a cor do nivel
         * @param int $nivel - level do erro
         * @return string
         **/
		private function colorNivel($nivel) {
            switch ($nivel) {
                case E_USER_NOTICE: 
                case E_NOTICE: return                    "\033[1;36m"; 
                case E_USER_ERROR:
                case E_ERROR: return                     "\033[1;31m";  
                case E_USER_WARNING:
                case E_WARNING: return                   "\033[1;33m"; 
                default : return                         "\033[0m";
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
    } 
 
    
    // por fim, solicitamos educadamente ao PHP
    // que nos de o controle sobre a gerencia de erros
    // do sistema. Passando como parametro, o nome da
    // classe e o nome do metodo que trata os erros.
    set_error_handler(array('PHPLog','add'));