<?php
require_once 'conexion/conexion.php';
require_once 'conexion/respuestaGenerica.php';


class auth extends Conexion{

    public function login($json){
      
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['usuario']) || !isset($datos["password"])){
            //error con los campos
            return $_respustas->error_400();
        }else{
            //todo esta bien 
            $usuario = $datos['usuario'];
            $password = $datos['password'];parent::desencriptar($datos['password']);
            $password = parent::desencriptar($password);
            $datos = $this->obtenerDatosUsuario($usuario);
            if($datos){
            //     //verificar si la contraseña es igual
            //         if($password == $datos[0]['Password']){
            //                 if($datos[0]['Estado'] == "Activo"){
            //                     //crear el token
            //                     $verificar  = $this->insertarToken($datos[0]['UsuarioId']);
            //                     if($verificar){
            //                             // si se guardo
                                         $result = $_respustas->response;
                                         $result["result"] = array(
                                             "usuario" => $usuario,
                                             "idUsuario" => $datos[0]['idUsuario']
                                         );
                                         return $result;
            //                     }else{
            //                             //error al guardar
            //                             return $_respustas->error_500("Error interno, No hemos podido guardar");
            //                     }
            //                 }else{
            //                     //el usuario esta inactivo
            //                     return $_respustas->error_200("El usuario esta inactivo");
            //                 }
            //         }else{
            //             //la contraseña no es igual
            //             return $_respustas->error_200("El password es invalido");
            //         }
            }else{
                return $_respustas->error_200("usuario_incorrecto");
            }
        }
    }



    private function obtenerDatosUsuario($usuario){
        $query = "SELECT idUsuario,password,estado FROM usuarios WHERE usuario = '$usuario'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["idUsuario"])){
            return $datos;
        }else{
            return 0;
        }
    }


    private function insertarToken($usuarioid){
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }


}




?>