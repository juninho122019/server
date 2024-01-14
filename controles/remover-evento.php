<?php

require_once('eventos.php');
require_once('msg.php');

if (isset($_POST['id'])) {
    if ($_POST['id'] !== "") {
        if (!removerEvento($_POST['id'])) {
            erro();
        }
    } else {
        embranco();
    }
} else {
    invalido();
}