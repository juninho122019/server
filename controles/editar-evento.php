<?php

require_once('eventos.php');
require_once('msg.php');

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    if ($nome !== "") {
        if (!editarEvento($_POST['id'], $nome)) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}