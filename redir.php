<?php
function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
cors();
require_once("controles/links.php");

if (isset($_GET['acesso']) && isset($_GET['usuario'])) {
    $acesso = $_GET['acesso'];
    $usuario = $_GET['usuario'];
    if ($usuario !== "") {
        if ($data = buscarLink($usuario, $acesso)) {
            $usuario = buscarUsuario($usuario);
            $canal = $data['nome_link'];
            $date = date('d/m/Y H:i:s');
            
            criarLog($usuario['id_usuario'], $canal, $date);
            header("Location: " . $data['link_link']);
        } else {
            header('HTTP/1.0 404 Not Found');
        }
    } else {
        header('HTTP/1.0 404 Not Found');
    }
} else {
    header('HTTP/1.0 404 Not Found');
}
