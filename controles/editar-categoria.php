<?php

if (!isset($_SERVER['HTTP_REFERER']) &&  !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) 
    || !strpos($_SERVER['HTTP_REFERER'], 'categoria.php' )) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}

require_once('categorias.php');
require_once('msg.php');

if (isset($_POST['id']) && isset($_POST['nome'])) {
    $id = $_POST['id'];
    $nome = $_POST['nome'];
    if ($nome !== "" && $id !== "") {
        if (!editarCategoria($id, $nome)) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}
