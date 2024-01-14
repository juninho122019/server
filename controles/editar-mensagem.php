<?php

require_once('mensagens.php');
require_once('msg.php');

if (isset($_POST['titulo'])) {
    if ($_POST['titulo'] !== "") {
        if (!editarMensagem($_POST['id'], $_POST['titulo'], $_POST['mensagem'], $_POST['id_evento'])) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}