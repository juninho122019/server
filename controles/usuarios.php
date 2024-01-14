<?php
if (basename($_SERVER["PHP_SELF"]) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}
require_once("conexao.php");

function buscaUsuario($usuario, $senha, $cliente = false) {
    global $conexao;
    $usuario = mysqli_real_escape_string($conexao, $usuario);
    if($cliente)
	$cliente = ' or vendedor = 0';

    $query = "select * from usuario where login_usuario = '{$usuario}' and senha_usuario = '{$senha}' and (admin = 1 or vendedor = 1".$cliente.") and estado_usuario = 1";
    $resultado = mysqli_query($conexao, $query);
    $usuario = mysqli_fetch_assoc($resultado);
    return $usuario;
}
function listarLogs($id_usuario){
    global $conexao;
    $query = "select * from logs where id_usuario = " . $id_usuario . " order by id_log DESC limit 50";
    $resultado = mysqli_query($conexao, $query);
    $logs = [];
    while ($log = mysqli_fetch_assoc($resultado)) {
	$logs[] = $log;
    }
    return $logs;
}
function porid($id){
    global $conexao;
    $query = "SELECT * from usuario where id_usuario = ".$id."";
    $resultado = mysqli_query($conexao, $query);
    echo  $id;
    return mysqli_fetch_assoc($resultado);
}
function logarUsuario($usuario) {
    $_SESSION['usuario'] = $usuario;
    $_SESSION['logado'] = true;
}

function usuarioLogado() {
    return $_SESSION['usuario'];
}

function checarUsuario() {
    if (isset($_SESSION['usuario']) && $_SESSION['logado']) {
        return true;
    } else {
        return false;
    }
}

function listarUsuarios() {
    $usuarios = array();
    global $conexao;
    session_start();
    $query = "select * from usuario where id_criador = ".$_SESSION['id_usuario']."";
    if($_SESSION['admin']){
        $query = "select * from usuario";
    }
    if($_SESSION['original'] !== $_SESSION['id_usuario']){
	$query = "select * from usuario where id_criador = ".$_SESSION['id_usuario']." or id_usuario = ".$_SESSION['original']."";
    }
    $resultado = mysqli_query($conexao, $query);
    while($usuario = mysqli_fetch_assoc($resultado)) {
  	$q = "select * from usuario where id_usuario = ".$usuario['id_criador']."";
	$q = mysqli_query($conexao, $q);
	$usuario['criador'] = mysqli_fetch_assoc($q);
        array_push($usuarios, $usuario);
    }
    return $usuarios;
}

function listasUsuario($id) {
    $listas = array();
    global $conexao;
    $query = "select lista.* FROM lista_usuario INNER JOIN usuario ON (usuario.id_usuario = lista_usuario.id_usuario) INNER JOIN lista ON (lista.id_lista = lista_usuario.id_lista) where usuario.id_usuario = $id";
    $resultado = mysqli_query($conexao, $query);
    while($lista = mysqli_fetch_assoc($resultado)) {
        array_push($listas, $lista);
    }
    return $listas;
}

function removerUsuario($id) {
    global $conexao;
    $query = "delete from usuario where id_usuario=$id";
    return mysqli_query($conexao, $query);
}

function adicionarUsuario($nome, $contato, $login, $senha, $admin, $vendedor, $dia = 0, $lista) {
    global $conexao;
    $acesso = md5(sha1($login . "iptv"));
    if ($senha !== "") {
        $senha = md5(sha1($senha . "iptv"));
    }
    if(trim($dia) == ''){
	$dia = 0;
    }
    session_start();
    $query = "insert into usuario (id_criador, nome_usuario, contato_usuario, login_usuario, senha_usuario, admin, vendedor, acesso, dia) values (".$_SESSION['id_usuario'].", '{$nome}', '{$contato}', '{$login}', '{$senha}', {$admin}, {$vendedor}, '{$acesso}', '{$dia}')";
    $resultado = mysqli_query($conexao, $query);
    $id = mysqli_insert_id($conexao);
    echo mysqli_error($conexao);
    echo $query;
    if (count($lista) > 0) {
        for ($i =0; $i < count($lista); $i++) {
            mysqli_query($conexao, "insert into lista_usuario (id_lista, id_usuario) values ($lista[$i], $id)");
        }
    }
    return $resultado;

}

function editarUsuario($id, $nome,$login, $contato, $estado, $admin, $vendedor, $senha, $dia = 0, $lista) {
    global $conexao;
    if(trim($dia) == '')
	$dia = 0;

    if ($senha !== "") {
        $senha = md5(sha1($senha . "iptv"));
        $query = "update usuario set nome_usuario= '$nome', vendedor= $vendedor, login_usuario= '$login', contato_usuario= '$contato', estado_usuario= $estado, admin= $admin, senha_usuario= '$senha', dia= $dia where id_usuario=$id";
    } else {
        $query = "update usuario set nome_usuario= '$nome', vendedor= $vendedor, login_usuario= '$login', contato_usuario= '$contato', estado_usuario= $estado, admin= $admin, dia= $dia where id_usuario=$id";
    }
    echo $query;
    if (count($lista) > 0) {
        mysqli_query($conexao, "delete from lista_usuario where id_usuario= $id");
        for ($i =0; $i < count($lista); $i++) {
            if (mysqli_num_rows(mysqli_query($conexao, "select * from lista_usuario where id_usuario= $id and id_lista = $lista[$i]")) == 0) {
                mysqli_query($conexao, "insert into lista_usuario (id_lista, id_usuario) values ($lista[$i], $id)");
            }
        }
    } else {
        mysqli_query($conexao, "delete from lista_usuario where id_usuario= $id");
    }

    return mysqli_query($conexao, $query);
}

function acessoLista($acesso, $idlista) {
    global $conexao;
    $resultadoUsuario = mysqli_query($conexao,"select * from usuario where acesso= '$acesso' and estado_usuario = 1");
    $usuario = mysqli_fetch_assoc($resultadoUsuario);
    if ($usuario) {
        $resultadoLista = mysqli_query($conexao,"select lista.* FROM lista_usuario INNER JOIN usuario ON (usuario.id_usuario = 
        lista_usuario.id_usuario) INNER JOIN lista ON (lista.id_lista = lista_usuario.id_lista) where usuario.id_usuario = 
        {$usuario['id_usuario']} and lista.id_lista = $idlista");
        $lista = mysqli_fetch_assoc($resultadoLista);
        if ($lista) {
            return $lista;
        }
    }
}
