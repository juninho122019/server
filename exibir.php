<?php
function cors() {

    // Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        // Decide if the origin in $_SERVER['HTTP_ORIGIN'] is one
        // you want to allow, and if so:
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 86400');    // cache for 1 day
    }

    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            // may also be using PUT, PATCH, HEAD etc
            header("Access-Control-Allow-Methods: GET, POST, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");

        exit(0);
    }
}
cors();
require_once('controles/usuarios.php');
require_once('controles/categorias.php');
require_once('controles/listas.php');

header('Content-type: text/plain');

//var_dump($_SERVER['HTTP_USER_AGENT']);
if (isset($_GET["usuario"]) && isset($_GET["lista"])) {
    $usuario = $_GET["usuario"];
    $idlista = $_GET["lista"];
    if ($usuario !== "" && $idlista !== "") {
        $lista = acessoLista($usuario, $idlista);
        if ($lista) {
	    include('controles/links.php');
            if(intval($idlista) == -1){
                $txt = "#EXTM3U\n\n";
		$links = '[{"name":"Erotic","url":"https://cdnfe1.azureedge.net/hls1/erotic.m3u8?wmsAuthSign="},{"name":"Evilangel","url":"https://cdnfe1.azureedge.net/hls1/evilangel.m3u8?wmsAuthSign="},{"name":"Pinko","url":"https://cdnfe1.azureedge.net/hls1/pinko.m3u8?wmsAuthSign="},{"name":"Sex Hot","url":"https://cdnfe1.azureedge.net/hls1/sexhot.m3u8?wmsAuthSign="}]';
		$links = json_decode($links, true);
	
                foreach($links as $link){
		    $link = $link['name'];
                    $channelName = preg_replace("/[^A-Za-z0-9]/", '', $link);
                    $channelName = strtolower($channelName);
                    $txt .= "#EXTINF:-1 tvg-logo=\"\" group-title=\"\", $link\n";
                    $txt = 'http://51.79.78.213/channel/'.$channelName.'/auto.m3u8' . "\n\n";
		    $url = 'http://51.79.78.213:81/channel/'.$channelName.'/auto.m3u8';
		    adicionarLink($link . " TP+", $url, '270', "");
                }
                return false;
            }
            if ($lista['global'] == 0) {
                echo $lista['lista'];
            } else {
                $links = listaGlobal($idlista);
                if ($links) {
                    echo "#EXTM3U\n";
                    foreach($links as $link) {
                        echo "\n#EXTINF:-1 tvg-logo=\"{$link['logo']}\" group-title=\"{$link['nome']}\", {$link['nome_link']}\n" . preg_replace('/exibir.php.*/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") ."redir.php?acesso={$link['acessoLink']}&usuario=$usuario\n";
                    }
                }
            }
        } else {
            echo "#EXTM3U\n#EXTINF:-1 tvg-logo=\"" . preg_replace('/exibir.php.*/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") . "img/bloqueado.png\" group-title=\"Acesso Bloqueado!\", Acesso Bloqueado!\nhttp://acessobloqueado.com";
        }
    } else {
        echo "#EXTM3U\n#EXTINF:-1 tvg-logo=\"" . preg_replace('/exibir.php.*/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") . "img/bloqueado.png\" group-title=\"Acesso Bloqueado!\", Acesso Bloqueado!\nhttp://acessobloqueado.com";
    }
} else {
    echo "#EXTM3U\n#EXTINF:-1 tvg-logo=\"" . preg_replace('/exibir.php.*/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") . "img/bloqueado.png\" group-title=\"Acesso Bloqueado!\", Acesso Bloqueado!\nhttp://acessobloqueado.com";
}
