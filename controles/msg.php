<?php
if (basename($_SERVER["PHP_SELF"]) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: ../index.php");
    die();
}


function embranco() {
    header('HTTP/1.0 403 Forbidden');
    echo "Campo em branco detectado! Corrija";
}

function erro() {
    header('HTTP/1.0 403 Forbidden');
    echo "Erro ao realizar a operação. Verifique os dados!";
}

function invalido() {
    header('HTTP/1.0 403 Forbidden');
    echo "Dados inválidos! Tente novamente";
}