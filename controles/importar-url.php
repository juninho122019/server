<?php
require_once("categorias.php");
require_once("links.php");
function get_url($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_setopt($ch, CURLOPT_REFERER, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    $page = curl_exec($ch);
    return $page;
}
$url = $_POST['url'];
if($url){
    $data = filter_var($url, FILTER_VALIDATE_URL) ? get_url($url) : $url;
    if (strpos($data, '#EXTINF') !== false) {
        $data = str_replace("'", '"', $data);
        $data = explode('#EXTINF:', $data);
        $groups = [];
        $channels = [];
        foreach($data as $item){
            $groupName = explode('title="', $item);
            if(count($groupName) > 1){
                $groupName = $groupName[1];
                $groupName = explode('"', $groupName)[0];
                $groupName = str_replace('"', "'", $groupName);
                $groupName = trim($groupName);
            } else {
                $groupName = "";
            }
            if(strlen(trim($groupName)) > 0){
                if(!array_key_exists($groupName, $groups)){
                    $category = obterCategoria(0, $groupName);
                    if(sizeof($categoria) == 0){
                        adicionarCategoria($groupName);
                        $category = obterCategoria(0, $groupName);
                    }
                    $groups[$groupName] = $category;
                } else {
                    $category = $groups[$groupName];
                }
                $channelName = explode(',', $item)[1];
                $channelName = explode("\n", $channelName)[0];
                $channelName = explode("http", $channelName)[0];
                $channelName = trim($channelName);
                if(!array_key_exists($channelName, $channels)){
                    $channel = obterLink(0, $channelName);
                    if(sizeof($channel) == 0 || !$channel){
                        $link = explode($channelName, $item)[1];
                        $link = trim($link);
                        $category_id = $category[0]['id'];
                        $image_url = explode('logo="', $item)[1];
                        $image_url = explode('" group', $image_url)[0];
                        $channels[$channelName] = true;
                        adicionarlink($channelName, $link, $category_id, $image_url);
                    } 
                }
            }
        }
    }
}
