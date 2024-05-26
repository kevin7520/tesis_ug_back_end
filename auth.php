<?php
  require_once 'clases/auth.class.php';
  require_once 'clases/conexion/respuestaGenerica.php';

  $_auth = new auth;
  $_respuestas = new RespuestaGenerica;

    if($_SERVER['REQUEST_METHOD'] == "POST"){

        $postBody = file_get_contents("php://input");

        $datosArray = $_auth->login($postBody);

        header('Content-Type: application/json');
        if($datosArray["code"] != "200"){
            $responseCode = $datosArray["code"];
            http_response_code($responseCode);
        }
        else{
            http_response_code(200);
        }
        echo json_encode($datosArray);
    }
    else {
        if($_SERVER['REQUEST_METHOD'] == "PUT") {
            $postBody = file_get_contents("php://input");
    
            $datosArray = $_auth->CreacionUsuario($postBody);
    
            header('Content-Type: application/json');
            if($datosArray["code"] != "200"){
                $responseCode = $datosArray["code"];
                http_response_code($responseCode);
            }
            else{
                http_response_code(200);
            }
            echo json_encode($datosArray);
        }
        else{
            header('Content-Type: application/json');
            $datosArray = $_respuestas->error_405();
            echo json_encode($datosArray);
    
        }
    }
    

?>