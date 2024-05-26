<?php
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Allow-Methods: GET, POST, PUT, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
    class Conexion {
        private $server;
        private $user;
        private $password;
        private $database;
        private $port;
        private $conexion;

        function __construct() {
            $listaDatos = $this->datosConexion();
            foreach ($listaDatos as $key => $value) {
                $this->server = $value['server'];
                $this->user = $value['user'];
                $this->password = $value['password'];
                $this->database = $value['database'];
                $this->port = $value['port'];
            }

            $this->conexion = new mysqli($this->server,$this->user,$this->password,$this->database,$this->port);
            if($this->conexion->connect_errno){
                echo "algo va mal con la conexion";
                die();
            }
            // else {
            //     echo "Belleza mayonesa";
            // }
        }
        private function datosConexion() {
            $dirreccion = dirname(__FILE__);
            $jsonData = file_get_contents($dirreccion . "/" . "config");
            return json_decode($jsonData,true);
        }

        private function convertirUTF8($array){
            // Comentar la impresión del array original
            // print_r($array);
            
            // Recorrer el array y convertir los elementos a UTF-8 si es necesario
            array_walk_recursive($array, function (&$item, $key) {
                // Verificar si el elemento es una cadena y no está en UTF-8
                if (is_string($item) && !mb_check_encoding($item, 'UTF-8')) {
                    // Convertir el elemento a UTF-8
                    $item = utf8_encode($item);
                }
            });
            
            // Imprimir el array después de la conversión (opcional)
            return $array;
        }

        public function obtenerDatos($sqlstr){
            $results = $this->conexion->query($sqlstr);
            $resultArray = array();
            foreach ($results as $row) {
                $resultArray[] = $row;
            }
            return $this->convertirUTF8($resultArray);
        }

        public function nonQuery($sqlstr){
            $results = $this->conexion->query($sqlstr);
            return $this->conexion->affected_rows;
        }

        public function nonQueryId($sqlstr){
            $results = $this->conexion->query($sqlstr);
             $filas = $this->conexion->affected_rows;
             if($filas >= 1){
                return $this->conexion->insert_id;
             }else{
                 return 0;
             }
        }

        protected function encriptar($string){
            return md5($string);
        }

        protected function desencriptar($encryptedPassword) {
            $secretKey = "seriousGame";
            //$decryptedPassword = openssl_decrypt($encryptedPassword, 'AES-128-ECB', $secretKey);
            $decryptedPassword = openssl_decrypt(base64_decode($encryptedPassword), 'AES-128-ECB', $secretKey, OPENSSL_RAW_DATA);
            return $decryptedPassword;
        }

    }
?>