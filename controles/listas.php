<?php


if (basename($_SERVER["PHP_SELF"]) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}
require_once("conexao.php");

function listarListas() {
    $listas = array();
    global $conexao;
    if($_SESSION['admin']){
        $query = "select * from lista";
    } else {
        $query = "select * from lista l where l.id_lista in (select lu.id_lista from lista_usuario lu where lu.id_usuario = ".$_SESSION['id_usuario'].")";
    }
    
    $resultado = mysqli_query($conexao, $query);
    while($lista = mysqli_fetch_assoc($resultado)) {
        array_push($listas, $lista);
    }
    return $listas;
}

function adicionarLista($nome, $lista) {
    global $conexao;
    $query = "insert into lista (nome_lista, lista, id_usuario) values ('$nome', '$lista', ".$_SESSION['id_usuario'].")";
    return mysqli_query($conexao, $query);
}

function adicionarListaGlobal($nome, $categoria) {
    global $conexao;
    $query = "insert into lista (nome_lista, global) values ('$nome', 1)";
    $resultado = mysqli_query($conexao, $query);
    $id = mysqli_insert_id($conexao);
    if (count($categoria) > 0) {
        mysqli_query($conexao, "delete from lista_global_categoria where id_lista= $id");
        for ($i =0; $i < count($categoria); $i++) {
            mysqli_query($conexao, "insert into lista_global_categoria (id_categoria, id_lista) values ($categoria[$i], $id)");
        }
    }
    return $resultado;
}

function editarListaGlobal($id, $nome, $categoria) {
    global $conexao;
    $query = "update lista set nome_lista= '$nome' where id_lista=$id";
    
    if (count($categoria) > 0) {
        mysqli_query($conexao, "delete from lista_global_categoria where id_lista= $id");
        for ($i =0; $i < count($categoria); $i++) {
            if (mysqli_num_rows(mysqli_query($conexao, "select * from lista_global_categoria where id_lista= $id and id_categoria = $categoria[$i]")) == 0) {
                mysqli_query($conexao, "insert into lista_global_categoria (id_categoria, id_lista) values ($categoria[$i], $id)");
            }
        }
    } else {
        mysqli_query($conexao, "delete from lista_global_categoria where id_lista= $id");
    }

    return mysqli_query($conexao, $query);
}

function categoriasLista($id) {
    $categorias = array();
    global $conexao;
    $query = "select categoria.* FROM lista_global_categoria INNER JOIN lista ON (lista.id_lista = lista_global_categoria.id_lista) INNER JOIN categoria ON (categoria.id = lista_global_categoria.id_categoria) where lista.id_lista = $id";
    $resultado = mysqli_query($conexao, $query);
    while($categoria = mysqli_fetch_assoc($resultado)) {
        array_push($categorias, $categoria);
    }
    return $categorias;
}

function removerLista($id) {
    global $conexao;
    $query = "delete from lista where id_lista=$id";
    return mysqli_query($conexao, $query);
}

function obterLista($id) {
    global $conexao;
    $query = "select lista.lista from lista where id_lista = $id";
    $resultado = mysqli_query($conexao, $query);
    return mysqli_fetch_assoc($resultado)['lista'];
}

function editarLista($id, $nome, $lista) {
    global $conexao;
    $query = "update lista set nome_lista= '$nome', lista = '$lista' where id_lista=$id";
    return mysqli_query($conexao, $query);
}

function obterListaUsuarios($id) {
    $usuarios = array();
    session_start();
    global $conexao;
    $query = "select u.id_usuario, u.nome_usuario from usuario u inner join lista_usuario lu on lu.id_usuario = u.id_usuario and lu.id_lista = ".$id." and lu.id_usuario != ".$_SESSION['id_usuario']." where u.id_criador = ".$_SESSION['id_usuario']." group by u.id_usuario";
    if($_SESSION['admin']){
        $query = "select u.id_usuario, u.nome_usuario from usuario u inner join lista_usuario lu on lu.id_usuario = u.id_usuario and lu.id_lista = ".$id." and lu.id_usuario != ".$_SESSION['id_usuario']." group by u.id_usuario";
    }
    $resultado = mysqli_query($conexao, $query);
    while ($usuario = mysqli_fetch_assoc($resultado)) {
        array_push($usuarios,$usuario);
    }
    return $usuarios;
}

function obterLinkLista($idLista,$idUsuario) {
    global $conexao;
    $query = "select acesso from usuario where id_usuario= $idUsuario";
    $resultadoUsuario = mysqli_query($conexao, $query);
    $usuario = mysqli_fetch_assoc($resultadoUsuario);
    return preg_replace('/controles\/obter-link-lista.php/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]exibir.php?usuario={$usuario['acesso']}&lista={$idLista}");
    
}

function listaGlobal($idlista) {
    $links = array();
    global $conexao;
    $resultado = mysqli_query($conexao, "select distinct link.nome_link, link.link_link, link.logo, link.acessoLink, 
    categoria.nome from link inner join lista_global_categoria on 
    (link.id_categoria = lista_global_categoria.id_categoria) inner join categoria on 
    (link.id_categoria = categoria.id) where lista_global_categoria.id_lista = $idlista order by categoria.nome asc");
    while($link = mysqli_fetch_assoc($resultado)) {
        array_push($links,$link);
    }
    return $links;
}
