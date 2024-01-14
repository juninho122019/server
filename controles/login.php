<?php

if (!isset($_SERVER['HTTP_REFERER']) &&  !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) 
    &&  !strpos($_SERVER['HTTP_REFERER'], 'index.php' )) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}

session_start();
require_once("usuarios.php");

$usuario = $_POST["usuario"];
$senha = $_POST["senha"];
$senha = md5(sha1($senha . "iptv"));

$usuarioB = buscaUsuario($usuario, $senha);
if ($usuarioB && ($usuarioB['admin'] === "1" || $usuarioB['vendedor'] === "1")) {
    $_SESSION['id_usuario'] = $usuarioB['id_usuario'];
    $_SESSION['admin'] = $usuarioB['admin'] == "1";
    $_SESSION['vendedor'] = $usuarioB['vendedor'] == "1";
    logarUsuario($usuarioB['nome_usuario']);
    $_SESSION['original'] = $usuarioB['id_usuario'];
    setcookie('original', $usuarioB['id_usuario'], time() + (86400 * 30), "/");
    header("HTTP/1.1 200 OK");
} else {
    header('HTTP/1.0 403 Forbidden');
    echo "Usuário ou senha inválida!";
}
if (!is_writable(session_save_path())) {
    echo 'Session path "'.session_save_path().'" is not writable for PHP!'; 
}
