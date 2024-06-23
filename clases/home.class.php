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

    public function getDatosUsuarios($json) {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['idUsuario']) || !isset($datos["usuario"])){
            return $_respustas->error_400();
        }else{
            $usuario = $datos['usuario'];
            $idUsuario = $datos['idUsuario'];
            $datos = $this->obtenerDatosUsuarios($idUsuario,$usuario);
            if($datos){
                $result = $_respustas->response;
                $result["result"] = array(
                    "usuario" => $datos["usuario"],
                    "nombres" => $datos["nombres"],
                    "apellidos" => $datos["apellidos"],
                    "correo" => $datos["correo"],
                    "rol" => $datos["rol"],
                    "fechaNacimiento" => $datos["fechaNacimiento"],
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

    public function getJuegosPublicos($json) {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        $datos = $this->obtenerJuegoPublicos();
        if($datos){
            $result = $_respustas->response;
            $result["result"] = $datos;
            return $result;
        }
        else{
            return $_respustas->error_200("not_game");
        }
    }
    public function getJuegosProfesor($json) {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['id'])){
            return $_respustas->error_400();
        }
        else {
            $id = $datos['id'];
            $datos = $this->obtenerJuegoProfesor($id);
            if($datos){
                $result = $_respustas->response;
                $result["result"] = $datos;
                return $result;
            }
            else{
                return $_respustas->error_200("not_game");
            }
        }  
    }

    public function getRequerimientos(){ 
        $_respustas = new RespuestaGenerica;
        $datos = $this->obtenerRequerimientos();
        if($datos){
            $result = $_respustas->response;
            $result["result"] = $datos;
            return $result;
        }
        else{
            return $_respustas->error_200("not_requerimientos");
        }
    }

    public function postCreateJuego($json){
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['id_profesor']) || !isset($datos["fechaCreacion"]) || !isset($datos["fechaFinilizacion"]) || !isset($datos["json"]) || !isset($datos["privacidad"])){
            return $_respustas->error_400();
        }
        else {
            $usuario = $datos['id_profesor'];
            $fecha_creacion = $datos['fechaCreacion'];
            $fecha_finalizacion = $datos['fechaFinilizacion'];
            $json_data = $datos['json'];
            $privacidad = $datos['privacidad'];
            
            $datos = $this->crearJuego($usuario,$fecha_creacion,$fecha_finalizacion,$json_data,$privacidad);
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

    public function postRequerimientos($json) {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        $datos = $this->guardarRequerimientos($datos['requisitos']);
        if(isset($datos[0])){
            $result = $_respustas->response;
            $result["result"] = $datos;
            return $result;
        } 
        else {
            return $_respustas->error_200("error_guardarTodos");
        }
    }

    public function postPuntaje($json) {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['id_persona']) || !isset($datos["id_juego"]) || !isset($datos["puntaje"]) || !isset($datos["hora_inicio"]) || !isset($datos["hora_fin"])){
            return $_respustas->error_400();
        }
        else {
            $id_persona = $datos['id_persona'];
            $id_juego = $datos['id_juego'];
            $puntaje = $datos['puntaje'];
            $hora_inicio = $datos['hora_inicio'];
            $hora_fin = $datos['hora_fin'];

            $datos = $this->crearPuntaje($id_persona,$id_juego,$puntaje,$hora_inicio,$hora_fin);
            if($datos){
                $result = $_respustas->response;
                $result["result"] = $datos[0]["mensaje"];
                return $result;
            }
            else {
                return $_respustas->error_200("La creación la puntación fue incorrecta");
            }
        }
    }

    public function postEditarUsuario($json) {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json,true);
        if(!isset($datos['id_persona']) || !isset($datos["password"]) || !isset($datos["nombres"]) || !isset($datos["apellidos"]) || !isset($datos["fechaN"]) || !isset($datos["new_password"])){
            return $_respustas->error_400();
        }
        else {
            $id_persona = $datos['id_persona']; 
            $password =  parent::encriptar($datos['password']); 
            $nombres = $datos['nombres']; 
            $apellidos = $datos['apellidos']; 
            $fechaN = $datos['fechaN']; 
            if(empty($datos['new_password'])) {
                $new_password = $datos['new_password'];
            }
            else {
                $new_password = parent::encriptar($datos['new_password']);
            }

            $datos = $this->editarPerfil($id_persona, $password, $nombres, $apellidos, $fechaN, $new_password);
            if($datos){
                $result = $_respustas->response;
                $result["result"] = $datos[0]["mensaje"];
                return $result;
            }
            else {
                return $_respustas->error_200("La edición del usuaruo fue incorrecta. Intenlo más tarde");
            }
        }
    }

    private function crearPuntaje($id_persona,$id_juego,$puntaje,$hora_inicio,$hora_fin) {
        $consulta = "CALL ASIGNAR_PUNTAJE(?, ?, ?, ?, ?, @p_mensaje)";
        $parametros = [
            ':p_id_persona' => $id_persona,
            ':p_id_juego' => $id_juego,
            ':p_puntaje' => $puntaje,
            ':p_hora_inicio' => $hora_inicio,
            ':p_hora_fin' => $hora_fin
        ];
        $datos = parent::obtenerDatosMensaje($consulta,$parametros);
        if($datos[0]["mensaje"] != 0){
            return $datos;
        }else{
            return 0;
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

    private function obtenerDatosUsuarios($id_usuario, $usuario) {
        $query = "SELECT usuario, nombres, apellidos, correo, rol, fechaNacimiento FROM usuarios WHERE idUsuario = '$id_usuario' AND usuario = '$usuario'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["usuario"])){
            return $datos[0];
        } else {
            return 0;
        }
    }

    private function crearJuego($id_usuario,$fecha_creacion,$fecha_finalizacion,$json,$privacidad){
        $query = "INSERT INTO juegos (id_profesor,fecha_creacion,fecha_finalizacion,json,juego_publico) VALUES ('$id_usuario', '$fecha_creacion', '$fecha_finalizacion','$json','$privacidad')";
        return parent::nonQueryId($query);
    }

    private function editarPerfil($id_persona, $password, $nombres, $apellidos, $fechaN, $new_password) {
        $consulta = "CALL EDITAR_USUARIO(?, ?, ?, ?, ?, ?, @p_mensaje)";
        $parametros = [
            ':p_nombres' => $nombres,
            ':p_id_usuario' => $id_persona,
            ':p_fechaN' => $fechaN,
            ':p_apellidos' => $apellidos,
            ':p_password' => $password,
            ':p_new_password' => $new_password
        ];
        $datos = parent::obtenerDatosMensaje($consulta,$parametros);
        if($datos[0]["mensaje"] != 0){
            return $datos;
        }else{
            return 0;
        }
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

    private function obtenerJuegoPublicos() {
        $query = "SELECT *FROM juegos WHERE juego_publico = 'S'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0])){
            return $datos;
        } else {
            return 0;
        }
    }
    private function obtenerJuegoProfesor($id_profesor) {
        $query = "SELECT *FROM juegos WHERE id_profesor = $id_profesor";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0])){
            return $datos;
        } else {
            return 0;
        }
    }

    private function obtenerRequerimientos() {
        $query = "SELECT *FROM requerimientos WHERE estado = 'A'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0])){
            return $datos;
        } else {
            return 0;
        }
    }

}




?>