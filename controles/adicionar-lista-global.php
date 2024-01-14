<?php

if (!isset($_SERVER['HTTP_REFERER']) &&  !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) 
    ||  !strpos($_SERVER['HTTP_REFERER'], 'lista.php' )) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}

require_once('listas.php');
require_once('msg.php');

if (isset($_POST['nome']) && isset($_POST['categoria'])) {
    $nome = $_POST['nome'];
    $categoria = $_POST['categoria'];
    if ($nome !== "" && $categoria !== "") {
        if (!adicionarListaGlobal($nome, $categoria)) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}

