<?php

if (!isset($_SERVER['HTTP_REFERER']) &&  !strpos($_SERVER['HTTP_REFERER'], $_SERVER['HTTP_HOST'] ) 
    ||  !strpos($_SERVER['HTTP_REFERER'], 'link.php' )) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}

require_once('links.php');
require_once('msg.php');

if (isset($_POST['nome']) && isset($_POST['link']) && isset($_POST['categoria']) && isset($_POST['logo'])) {
    $nome = $_POST['nome'];
    $link = $_POST['link'];
    $categoria = $_POST['categoria'];
    $logo = $_POST['logo'];
    if ($nome !== "" && $link !== "" && $categoria !== "" && $logo !== "") {
        if (!adicionarlink($nome, $link, $categoria, $logo)) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}
