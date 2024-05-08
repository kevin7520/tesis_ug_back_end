<?php
   require_once "clases/conexion/conexion.php";

   $conexion = new Conexion();

   $query = "INSERT INTO `tesis`.`usuario` (`idUsuario`, `usuario`, `nombres`, `apellidos`, `password`, `correo`, `registroFecha`) VALUES ('3', 'asd', 'dasdsa', 'dasdsa', 'dsadsa', 'dsadsada', 'NOW()');";

   print_r($conexion->nonQueryId($query));
?>