<?php

if (!isset($_SERVER['HTTP_REFERER']) &&  !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) 
    ||  !strpos($_SERVER['HTTP_REFERER'], 'usuario.php' )) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}

require_once('usuarios.php');
require_once('msg.php');

if(isset($_POST['id']) && isset($_POST['estado']) && isset($_POST['nome']) && isset($_POST['contato']) && isset($_POST['login']) && isset($_POST['admin'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    $contato = $_POST['contato'];
    $login = $_POST['login'];
    $admin = $_POST['admin'];
    $vendedor = $_POST['vendedor'];
    $estado = $_POST['estado'];

    if (isset($_POST['senha'])) {
        $senha = $_POST['senha'];
    } else {
        $senha = "";
    }
    
    if (isset($_POST['lista'])) {
        $lista = $_POST['lista'];
    } else {
        $lista = [];
    }

    if ($estado !== "" && $id !== "" && $nome !== "" && $login !== "" && $admin !== "") {
        if (!editarUsuario($id, $nome,$login, $contato, $estado, $admin, $vendedor, $senha, $_POST['dia'], $lista)) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}

