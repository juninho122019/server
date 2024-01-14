<?php
session_start();
require('usuarios.php');
$id_usuario = $_GET['id_usuario'];
if(true || $_SESSION['admin'] || $_SESSION['original'] != $_SESSION['id_usuario']){
   $usuario = porid($id_usuario);
   logarUsuario($usuario['nome_usuario']);
   //$_SESSION['original'] = $original;
   $_SESSION['id_usuario'] = $id_usuario;
   if($id_usuario != $_SESSION['original']) { 
	$_SESSION['admin'] = 0; 
	$_SESSION['vendedor'] = 1;
   } 
   else {
	$original = porid($_SESSION['original']);
	$_SESSION['vendedor'] = intval($original['vendedor']) == 1;
	$_SESSION['admin'] = intval($original['admin']) == 1;
   }
}
?>
