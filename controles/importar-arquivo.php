<?php
include('categorias.php');
include('links.php');
foreach($_FILES['files']['tmp_name'] as $key => $tmp_name)
{
    $file_tmp = $_FILES['files']['tmp_name'][$key];
    $data = file_get_contents($file_tmp);
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
