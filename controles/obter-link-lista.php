<?php

if (!isset($_SERVER['HTTP_REFERER']) &&  !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) 
    || !strpos($_SERVER['HTTP_REFERER'], 'lista.php' )) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}

require_once('listas.php');

$idLista = $_POST['idLista'];
$idUsuario = $_POST['idUsuario'];

if ($idUsuario) {
    $link= obterLinkLista($idLista, $idUsuario);
    if ($link) { 
        echo $link;
    }
} else {
    echo "Usuário não informado!";
}