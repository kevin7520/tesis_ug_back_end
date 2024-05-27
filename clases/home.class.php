<?php
require_once 'conexion/conexion.php';
require_once 'conexion/respuestaGenerica.php';


class home extends Conexion{

    public function getUsuario($json){
      
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['idUsuario']) || !isset($datos["usuario"])){
            return $_respustas->error_400();
        }else{
            $usuario = $datos['usuario'];
            $idUsuario = $datos['idUsuario'];
            $datos = $this->obtenerUsuarioRegistro($idUsuario,$usuario);
            if($datos){
        
                $result = $_respustas->response;
                $result["result"] = array(
                    "registro" => $datos["registroLogin"],
                    "rol" => $datos["rol"]
                );
                return $result;
            }
            else{
                return $_respustas->error_200("user_false");
            }
        }
    }

    private function obtenerUsuarioRegistro($id_usuario, $usuario) {
        $query = "SELECT registroLogin, rol FROM usuarios WHERE idUsuario = '$id_usuario' AND usuario = '$usuario'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["registroLogin"])){
            return $datos[0];
        } else {
            return 0;
        }
    }


    // private function insertarToken($usuarioid){
    //     $val = true;
    //     $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
    //     $date = date("Y-m-d H:i");
    //     $estado = "Activo";
    //     $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
    //     $verifica = parent::nonQuery($query);
    //     if($verifica){
    //         return $token;
    //     }else{
    //         return 0;
    //     }
    // }


}




?>