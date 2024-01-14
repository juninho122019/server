<?php

require_once("conexao.php");

function listarMensagens() {
    $categorias = array();
    global $conexao;
    session_start();
    if($_SESSION['admin']){
        $query = "select m.*, u.nome_usuario as criador, e.nome as evento_nome from mensagens m inner join usuario u on u.id_usuario = m.id_criador inner join eventos e on e.id_evento = m.id_evento group by m.id_mensagem";
    }
    $resultado = mysqli_query($conexao, $query);
    while($categoria = mysqli_fetch_assoc($resultado)) {
        array_push($categorias, $categoria);
    }
    return $categorias;
}

function listarMensagensParaVendedor() {
    $categorias = array();
    global $conexao;
    session_start();
    $query = "select (SELECT COUNT(*) FROM lidas WHERE lida = 'sim' and id_usuario = ".$_SESSION['id_usuario']." and id_mensagem = m.id_mensagem) as lida, m.*, u.nome_usuario as criador, e.nome as evento_nome from mensagens m inner join usuario u on u.id_usuario = m.id_criador inner join eventos e on e.id_evento = m.id_evento where m.id_mensagem not in ((select l.id_mensagem from lidas l where l.remover = 1 and l.id_usuario = ".$_SESSION['id_usuario'].")) group by m.id_mensagem order by m.id_mensagem desc";
    $resultado = mysqli_query($conexao, $query);
    while($categoria = mysqli_fetch_assoc($resultado)) {
        if(intval($categoria['lida']) > 0){
            $categoria['lida'] = 'sim';
        } else {
            $categoria['lida'] = 'nao';
        }
        array_push($categorias, $categoria);
    }
    return $categorias;
}

function foiLida($id_mensagem) {
    $categorias = array();
    global $conexao;
    session_start();
    $query = "select * from lidas where id_mensagem = $id_mensagem and lida = 'sim' and id_usuario = ".$_SESSION['id_usuario']."";
    $resultado = mysqli_query($conexao, $query);
    $lida = false;
    while($categoria = mysqli_fetch_assoc($resultado)) {
       $lida = true;
    }
    return $lida;
}

function marcarComoLida($id_mensagem, $remover = 0) {
    global $conexao;
    session_start();
    $query = "select * from lidas where id_mensagem = $id_mensagem and lida = 'sim' and remover = $remover and id_usuario = ".$_SESSION['id_usuario']."";
    $resultado = mysqli_query($conexao, $query);
    $encontrou = false;
    while($categoria = mysqli_fetch_assoc($resultado)) {
       $encontrou = true;
    }
    if($encontrou == false){
        $query = "insert into lidas (id_mensagem, id_usuario, lida, remover) values ({$id_mensagem}, ".$_SESSION['id_usuario'].", 'sim', {$remover})";
        mysqli_query($conexao, $query);

    }
    return true;
}

function adicionarMensagem($titulo, $mensagem, $id_evento) {
    global $conexao;
    $horario = date("d/m/Y H:i:s");
    session_start();
    $query = "insert into mensagens (titulo, mensagem, id_evento, id_criador, data) values ('{$titulo}', '{$mensagem}', $id_evento, ".$_SESSION['id_usuario'].", '{$horario}')";
    echo $query;
    return mysqli_query($conexao, $query);
}

function removerMensagem($id) {
    global $conexao;
    $query = "delete from mensagens where id_mensagem=$id";
    return mysqli_query($conexao, $query);
}

function editarMensagem($id, $titulo, $mensagem, $id_evento) {
    global $conexao;
    $query = "update mensagens set titulo='$titulo', mensagem='$mensagem', id_evento=$id_evento where id_mensagem=$id";
    return mysqli_query($conexao, $query);
}

function obterMensagem($id) {
    $links = array();
    global $conexao;
    $query = "select m.*, u.nome_usuario, e.nome as evento_nome from mensagens m inner join usuario u on u.id_usuario = m.id_criador inner join eventos e on e.id_evento = m.id_evento where m.id_mensagem = $id group by m.id_mensagem";
    $resultado = mysqli_query($conexao, $query);
    while($link = mysqli_fetch_assoc($resultado)) {
        array_push($links, $link);
    }
    return $links;
}