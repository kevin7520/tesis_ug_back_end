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

    public function getJuego($json){
      
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['id'])){
            return $_respustas->error_400();
        }else{
            $id = $datos['id'];
            $datos = $this->obtenerJuego($id);
            if($datos){
        
                $result = $_respustas->response;
                $result["result"] = array(
                    "fecha_creacion" => $datos["fecha_creacion"],
                    "fecha_finalizacion" => $datos["fecha_finalizacion"],
                    "json" => $datos["json"],
                );
                return $result;
            }
            else{
                return $_respustas->error_200("not_game");
            }
        }
    }

    public function postCreateJuego($json){
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['id_profesor']) || !isset($datos["fechaCreacion"]) || !isset($datos["fechaFinilizacion"]) || !isset($datos["json"])){
            return $_respustas->error_400();
        }
        else {
            $usuario = $datos['id_profesor'];
            $fecha_creacion = $datos['fechaCreacion'];
            $fecha_finalizacion = $datos['fechaFinilizacion'];
            $json_data = $datos['json'];
            
            $datos = $this->crearJuego($usuario,$fecha_creacion,$fecha_finalizacion,$json_data);
            if($datos){
                $result = $_respustas->response;
                $result["result"] = array(
                    "id_juego" => $datos
                );
                return $result;
            }
            else {
                return $_respustas->error_200("La creación del juego no fue exitosa. Por favor, inténtelo nuevamente más tarde.");
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

    private function crearJuego($id_usuario,$fecha_creacion,$fecha_finalizacion,$json){
        $query = "INSERT INTO juegos (id_profesor,fecha_creacion,fecha_finalizacion,json) VALUES ('$id_usuario', '$fecha_creacion', '$fecha_finalizacion','$json')";
        return parent::nonQueryId($query);
    }

    private function obtenerJuego($id_juego) {
        $query = "SELECT *FROM juegos WHERE id_juego = '$id_juego'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0])){
            return $datos[0];
        } else {
            return 0;
        }
    }

}




?>