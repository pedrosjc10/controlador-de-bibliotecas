<?php
    class Banco{
        private static $HOST = '127.0.0.1';

        private static $USER = 'root';

        private static $PWD = '';

        private static $DB = 'projeto_api_paw';

        private static $PORT = 3306;

        private static $CONNECTION = null;

        private static function connect(){
            if(Banco::$CONNECTION==null){
                Banco::$CONNECTION = new mysqli(Banco::$HOST, Banco::$USER, Banco::$PWD, Banco::$DB, Banco::$PORT);
                if(Banco::$CONNECTION -> connect_error){
                    error_reporting(E_ERROR | E_PARSE);

                    $objRespons = new stdClass();
                    $objRespons->cod = 1;
                    $objRespons->msg = "erro ao conectar";
                    $objRespons->error = Banco::$CONNECTION -> connect_error;
                    die(json_encode($objRespons));
                }
            }
        }
        public static function getConnect(){
            if(Banco::$CONNECTION==null){
                Banco::connect();
            }
            return Banco::$CONNECTION;
        }

    }
?>