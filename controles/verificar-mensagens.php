<?php
require_once("mensagens.php");
$mensagens = listarMensagensParaVendedor();
echo json_encode($mensagens);