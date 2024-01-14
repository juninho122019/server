<?php


if (basename($_SERVER["PHP_SELF"]) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}
require_once("conexao.php");

function nomeCategoria($id) {
    if ($id !== NULL) {
        global $conexao;
        $query = "select categoria.nome from categoria where categoria.id =$id";
        $resultado = mysqli_query($conexao, $query);
        return mysqli_fetch_assoc($resultado)['nome'];
    } else {
        return "";
    }
}

function listarCategorias() {
    $categorias = array();
    global $conexao;
    session_start();
    $query = "select * from categoria where id_usuario = ".$_SESSION['id_usuario']."";
    if($_SESSION['admin']){
        $query = "select * from categoria";
    }
    $resultado = mysqli_query($conexao, $query);
    while($categoria = mysqli_fetch_assoc($resultado)) {
        array_push($categorias, $categoria);
    }
    return $categorias;
}

function listarCategoriasNaoVazias() {
    $categorias = array();
    global $conexao;
    $query = "select distinct categoria.* from categoria inner join link on (link.id_categoria = categoria.id)";
    $resultado = mysqli_query($conexao,$query);
    while ($categoria = mysqli_fetch_assoc($resultado)) {
        array_push($categorias,$categoria);
    }
    return $categorias;
}

function adicionarCategoria($nome) {
    global $conexao;
    session_start();
    $query = "insert into categoria (nome, id_usuario) values ('{$nome}', ".$_SESSION['id_usuario'].")";
    return mysqli_query($conexao, $query);
}

function removerCategoria($id) {
    global $conexao;
    $query = "delete from categoria where id=$id";
    return mysqli_query($conexao, $query);
}

function editarCategoria($id, $nome) {
    global $conexao;
    $query = "update categoria set nome= '$nome' where id=$id";
    $r = mysqli_query($conexao, $query);
    return $r;
}

function obterCategoria($id, $nome = false) {
    $links = array();
    global $conexao;
    $query = "select link.nome_link, link.link_link, link.logo, categoria.nome from link inner join categoria on (link.id_categoria = categoria.id ) where categoria.id = $id";
    if($nome){
        $query = "select * from categoria where nome = '".$nome."'";
    }
    $resultado = mysqli_query($conexao, $query);
    while($link = mysqli_fetch_assoc($resultado)) {
        array_push($links, $link);
    }
    return $links;
}
