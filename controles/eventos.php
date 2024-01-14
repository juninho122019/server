<?php

require_once("conexao.php");

function nomeEvento($id) {
    if ($id !== NULL) {
        global $conexao;
        $query = "select nome from eventos where id_evento =$id";
        $resultado = mysqli_query($conexao, $query);
        return mysqli_fetch_assoc($resultado)['nome'];
    } else {
        return "";
    }
}

function listarEventos() {
    $categorias = array();
    global $conexao;
    session_start();
    if($_SESSION['admin']){
        $query = "select * from eventos";
    }
    $resultado = mysqli_query($conexao, $query);
    while($categoria = mysqli_fetch_assoc($resultado)) {
        array_push($categorias, $categoria);
    }
    return $categorias;
}

function adicionarEvento($nome) {
    global $conexao;
    session_start();
    $query = "insert into eventos (nome) values ('{$nome}')";
    return mysqli_query($conexao, $query);
}

function removerEvento($id) {
    global $conexao;
    $query = "delete from eventos where id_evento=$id";
    return mysqli_query($conexao, $query);
}

function editarEvento($id, $nome) {
    global $conexao;
    $query = "update eventos set nome= '$nome' where id_evento=$id";
    return mysqli_query($conexao, $query);
}

function obterEvento($id) {
    $links = array();
    global $conexao;
    $query = "select * from eventos where id_evento = $id";
    $resultado = mysqli_query($conexao, $query);
    while($link = mysqli_fetch_assoc($resultado)) {
        array_push($links, $link);
    }
    return $links;
}