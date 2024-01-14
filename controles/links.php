<?php


if (basename($_SERVER["PHP_SELF"]) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}
require_once("conexao.php");

function listarLinks() {
    $links = array();
    global $conexao;
    session_start();
    $query = "select * from link where id_usuario = ".$_SESSION['id_usuario']."";
    if($_SESSION['admin']){
        $query = "select * from link";
    }
    $resultado = mysqli_query($conexao, $query);
    while($link = mysqli_fetch_assoc($resultado)) {
        array_push($links, $link);
    }
    return $links;
}

function adicionarlink($nome, $link, $categoria, $logo) {
    global $conexao;
    session_start();
    $acesso = md5($nome);
    $query = "insert into link (id_usuario, nome_link, link_link, id_categoria, logo, acessoLink) values (".$_SESSION['id_usuario'].", '$nome', '$link', $categoria, '$logo', '$acesso')";
    return  mysqli_query($conexao, $query);
}
function criarLog($id, $canal, $data){
    global $conexao;
    session_start();
    $acesso = md5($nome);
    $query = "insert into logs (id_usuario, canal, data) values ($id, '$canal', '$data')";
    return mysqli_query($conexao, $query);    
}
function buscarLink($usuario, $acesso) {
    global $conexao;
    $query = "select link.* from link inner join categoria on (link.id_categoria = categoria.id) 
    inner join lista_global_categoria on (lista_global_categoria.id_categoria = categoria.id) 
    inner join lista_usuario on (lista_usuario.id_lista = lista_global_categoria.id_lista ) 
    inner join usuario on (lista_usuario.id_usuario = usuario.id_usuario) 
    where usuario.acesso = '$usuario' and link.acessoLink = '$acesso' and usuario.estado_usuario = 1";
    $resultado = mysqli_query($conexao, $query);
    return mysqli_fetch_assoc($resultado);
}
function buscarUsuario($acesso){
    global $conexao;
    $query = "select * from usuario where acesso = '".$acesso."'";
    $usuario = [];
    $resultado = mysqli_query($conexao, $query);
    while($link = mysqli_fetch_assoc($resultado)) {
        $usuario[] = $link;
    }
    return $usuario[0];
}
function removerlink($id) {
    global $conexao;
    $query = "delete from link where id_link=$id";
    return mysqli_query($conexao, $query);
}

function editarlink($id, $nome, $link, $categoria, $logo) {
    global $conexao;
    $query = "update link set nome_link= '$nome', link_link = '$link', id_categoria = $categoria, logo= '$logo' where id_link=$id";
    return mysqli_query($conexao, $query);
}

function obterLink($id, $nome = false) {
    global $conexao;
    $query = "select link.nome_link, link.link_link, link.logo, categoria.nome from link inner join categoria on (link.id_categoria = categoria.id) where link.id_link = $id";
    if($nome){
        $query = "select * from link where nome_link = '".$nome."'";
    }
    $resultado = mysqli_query($conexao, $query);
    return mysqli_fetch_assoc($resultado);
}
