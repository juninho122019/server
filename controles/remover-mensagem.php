<?php

require_once('mensagens.php');
require_once('msg.php');

if (isset($_POST['id'])) {
    if ($_POST['id'] !== "") {
        if (!removerMensagem($_POST['id'])) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}