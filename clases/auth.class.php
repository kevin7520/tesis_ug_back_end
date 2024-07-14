<?php
require_once 'conexion/conexion.php';
require_once 'conexion/respuestaGenerica.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

//Load Composer's autoloader
//require 'vendor/autoload.php';
require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);

class auth extends Conexion
{

    public function login($json)
    {

        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json, true);
        if (!isset($datos['usuario']) || !isset($datos["password"])) {
            return $_respustas->error_400();
        } else {
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = parent::encriptar($password);
            $datos = $this->obtenerDatosUsuario($usuario, $password);
            if ($datos) {
                //     //verificar si la contraseña es igual
                //         if($password == $datos[0]['Password']){
                //                 if($datos[0]['Estado'] == "Activo"){
                //                     //crear el token
                //$verificar  = $this->insertarToken($datos[0]['UsuarioId']);
                //                     if($verificar){
                //                             // si se guardo
                $result = $_respustas->response;
                $result["result"] = array(
                    "usuario" => $usuario,
                    "idUsuario" => $datos[0]['idUsuario'],
                    "migrado" => $datos[0]['mensaje']
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
            } else {
                return $_respustas->error_200("usuario_incorrecto");
            }
        }
    }

    public function CreacionUsuario($json)
    {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json, true);
        if (!isset($datos['usuario']) || !isset($datos["password"]) || !isset($datos["correo"])) {
            return $_respustas->error_400();
        } else {
            $usuario = $datos['usuario'];
            $password = $datos['password'];
            $password = parent::encriptar($password);
            $correo = $datos['correo'];
            if ($this->buscarUser($usuario) == 0) {
                $datos = $this->crearCuenta($usuario, $correo, $password);
                if ($datos) {
                    $result = $_respustas->response;
                    $result["result"] = array(
                        "usuario" => $usuario
                    );
                    return $result;
                } else {
                    return $_respustas->error_200("La creación de la cuenta no fue exitosa. Por favor, inténtelo nuevamente más tarde.");
                }
            } else {
                return $_respustas->error_200("user_ocupado");
            }


        }
    }

    public function migracionUsuario($json)
    {
        $_respustas = new RespuestaGenerica;
        $datos = json_decode($json, true);
        if (!isset($datos['usuario']) || !isset($datos['nombre']) || !isset($datos["apellido"]) || !isset($datos["rol"]) || !isset($datos["fechaNacimiento"]) || !isset($datos["id"])) {
            return $_respustas->error_400();
        } else {
            $nombre = $datos['nombre'];
            $apellido = $datos['apellido'];
            $rol = $datos['rol'];
            $fechaNacimiento = $datos['fechaNacimiento'];
            $id = $datos['id'];
            $usuario = $datos['usuario'];
            $datos = $this->migracionCuenta($nombre, $apellido, $rol, $fechaNacimiento, $id, $usuario);
            if ($datos) {
                $result = $_respustas->response;
                $result["result"] = array(
                    "proceso" => 'OK'
                );
                return $result;
            } else {
                return $_respustas->error_200("La migración no fue exitosa. Por favor, inténtelo nuevamente más tarde.");
            }
        }
    }



    private function obtenerDatosUsuario($usuario, $password)
    {
        $consulta = "CALL LOGIN(?, ?, @p_idUsuario, @p_mensaje)";
        $parametros = [
            ':p_usuario' => $usuario,
            ':p_password' => $password
        ];
        $datos = parent::obtenerDatosLogin($consulta, $parametros);
        if ($datos[0]["idUsuario"] != 0) {
            return $datos;
        } else {
            return 0;
        }
    }

    private function crearCuenta($usuario, $correo, $password)
    {
        $query = "INSERT INTO usuarios (usuario,correo,password) VALUES ('$usuario', '$correo', '$password')";
        return parent::nonQuery($query);
    }
    private function buscarUser($usuario)
    {
        $query = "SELECT *from usuarios where usuario = '$usuario'";
        $datos = parent::obtenerDatos($query);
        if (isset($datos[0])) {
            return 1;
        } else {
            return 0;
        }
    }

    private function migracionCuenta($nombre, $apellido, $rol, $fechaNacimiento, $id, $usuario)
    {
        $query = "UPDATE usuarios set nombres = '$nombre', apellidos = '$apellido', rol = '$rol', fechaNacimiento = '$fechaNacimiento', alias = '$usuario' where idUsuario = $id";
        return parent::nonQuery($query);
    }

    // private function enviarDatosEmail($correo) {
    //     try {
    //         //Server settings
    //         $mail->SMTPDebug = 0;                      //Enable verbose debug output
    //         $mail->isSMTP();                                            //Send using SMTP
    //         $mail->Host = 'smtp.gmail.com';                     //Set the SMTP server to send through
    //         $mail->SMTPAuth = true;                                   //Enable SMTP authentication
    //         $mail->Username = 'serious.game.ug@gmail.com';                     //SMTP username
    //         $mail->Password = 'dkvakqmywddzgjnb';                               //SMTP password
    //         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;            //Enable implicit TLS encryption
    //         $mail->Port = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`

    //         //Recipients
    //         $mail->setFrom('serious.game.ug@gmail.com', 'ADMINISTRADOR');
    //         $mail->addAddress('kaas7520@gmail.com');     //Add a recipient
    //         //Content
    //         $mail->isHTML(true);                                  //Set email format to HTML
    //         $mail->Subject = 'Restablecer contraseña';
    //         $mail->Body = 'This is the HTML message body <b>in bold!</b>';
    //         $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

    //         $mail->send();
    //         echo 'Message has been sent';
    //     } catch (Exception $e) {
    //         echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    //     }
    // }


    private function insertarToken($usuarioid)
    {
        $val = true;
        $token = bin2hex(openssl_random_pseudo_bytes(16, $val));
        $date = date("Y-m-d H:i");
        $estado = "Activo";
        $query = "INSERT INTO usuarios_token (UsuarioId,Token,Estado,Fecha)VALUES('$usuarioid','$token','$estado','$date')";
        $verifica = parent::nonQuery($query);
        if ($verifica) {
            return $token;
        } else {
            return 0;
        }
    }


}




?>