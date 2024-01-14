<?php

require_once('eventos.php');
require_once('msg.php');

if (isset($_POST['nome'])) {
    $nome = $_POST['nome'];
    if ($nome !== "") {
        if (!adicionarEvento($nome)) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}