<?php
session_start();
include ('controles/usuarios.php');
if(isset($_POST['username']) && isset($_POST['password'])){
     $_GET['username'] = $_POST['username'];
     $_GET['password'] = $_POST['password'];
}
if(isset($_POST['action'])){
     $_GET['action'] = $_POST['action'];
}
if(!$_GET['password'] || !$_GET['username']){
	exit();
}
$pass = md5(sha1($_GET['password'] . "iptv"));
$usuarioB = buscaUsuario($_GET['username'], $pass, true);

if(!$usuarioB)
	exit('[]');

function name($str)
{
    return strtolower(preg_replace("/[^A-Za-z0-9]/", '', $str));
}
$raw = file_get_contents('/');
$raw = file_get_contents('' . preg_replace('/api.php.*/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") .'/exibir.php?usuario='.($usuarioB['acesso']).'&lista=1');
$_SESSION['raw'] = $raw;
$lines = explode("\n", $_SESSION['raw']);

function includes($search, $str)
{
    return strpos($str, $search) !== false;
}
$data = [];
$linkName = '';
$groupName = '';
$logo = '';

$types = [];
$types['channels'] = [];
$types['movies'] = [];
$types['series'] = [];
$types['others'] = [];
$links_types = [];
$now = time();
foreach ($lines as $line)
{
    if (includes('group-title', $line))
    {
        $line = trim(str_replace("'", '"', $line));
        $groupName = explode('group-title="', $line) [1];
        $groupName = explode('"', $groupName) [0];
        if (!array_key_exists($groupName, $data))
        {
            $data[$groupName] = ["links" => []];
        }
        $logo = explode('-logo="', $line) [1];
        $logo = explode('"', $logo) [0];
        $linkName = explode(('="' . $groupName), $line) [1];
        $linkName = trim(explode(',', $linkName) [1]);
		
        if (includes('|canal', $linkName))
        {
            $linkName = explode('|', $linkName) [0];
            if (!array_key_exists($groupName, $types['channels']))
            {
                $types['channels'][$groupName] = [];
            }
            $links_types[$linkName] = 'channels|' . (sizeof($types['channels'][$groupName])) . '';
            $types['channels'][$groupName][] = ["name" => $linkName, "groupName" => $groupName, "img" => $logo];
        }
        else if (includes('|filme', $linkName))
        {
            $linkName = explode('|', $linkName) [0];
            if (!array_key_exists($groupName, $types['movies']))
            {
                $types['movies'][$groupName] = [];
            }
            $links_types[$linkName] = 'movies|' . (sizeof($types['movies'][$groupName])) . '';
            $types['movies'][$groupName][] = ["name" => $linkName, "groupName" => $groupName, "img" => $logo];
        }
        else if (includes('|serie', $linkName))
        {
            $linkName = explode('|', $linkName) [0];
            if (!array_key_exists($groupName, $types['series']))
            {
                $types['series'][$groupName] = [];
            }
            $links_types[$linkName] = 'series|' . (sizeof($types['series'][$groupName])) . '';
            $types['series'][$groupName][] = ["name" => $linkName, "groupName" => $groupName, "img" => $logo];
        } 
		else if(includes('|outro', $linkName))
		{
            $linkName = explode('|', $linkName) [0];
            if (!array_key_exists($groupName, $types['others']))
            {
                $types['others'][$groupName] = [];
            }
            $links_types[$linkName] = 'others|' . (sizeof($types['others'][$groupName])) . '';
            $types['others'][$groupName][] = ["name" => $linkName, "groupName" => $groupName, "img" => $logo];
		}
        else
        {
            $linkName = explode('|', $linkName) [0];
            if (!array_key_exists($groupName, $types['channels']))
            {
                $types['channels'][$groupName] = [];
            }
            $links_types[$linkName] = 'channels|' . (sizeof($types['channels'][$groupName])) . '';
            $types['channels'][$groupName][] = ["name" => $linkName, "groupName" => $groupName, "img" => $logo];
        }
    }
    else if (includes('http', $line))
    {
        if (array_key_exists($linkName, $links_types))
        {
            $type = explode('|', $links_types[$linkName]);
            $count = $type[1];
            $type = $type[0];
            $types[$type][$groupName][$count]['url'] = $line;
        }
        $data[$groupName]['links'][] = ["name" => $linkName, "groupName" => $groupName, "img" => $logo, "url" => $line];
    }
}
$_SESSION['data'] = $data;
$_SESSION['types'] = $types;

$channels = [];
foreach (array_keys($_SESSION['types']['channels']) as $groupName)
{
    foreach ($_SESSION['types']['channels'][$groupName] as $channel)
    {
        $channels[] = $channel;
    }
}

$movies = [];
$i = 1;
foreach (array_keys($_SESSION['types']['movies']) as $groupName)
{
    $i++;
    foreach ($_SESSION['types']['movies'][$groupName] as $movie)
    {
        $movie['groupId'] = $i;
        $movies[] = $movie;
    }
}

$others = [];
foreach (array_keys($_SESSION['types']['others']) as $groupName)
{
    $i++;
    foreach ($_SESSION['types']['others'][$groupName] as $other)
    {
        $other['groupId'] = $i;
        $others[] = $other;
    }
}

if (isset($_GET['username']) && isset($_GET['password']))
{
    switch ($_GET['action'])
    {
        case "get_live_categories":
            $output = [];
            $output[] = ["category_id" => 1, "category_name" => "Live TV", "parent_id" => 0];
            echo json_encode($output);
        break;

        case "get_live_streams":
            $i = 0;
            $output = [];
            foreach ($channels as $channel)
            {
                $i++;
                $output[] = ["num" => $i, "name" => $channel["name"], "stream_type" => "live", "stream_id" => $i, "stream_icon" => $channel['img'], "epg_channel_id" => $channel['name'], "added" => '1518805153', "category_id" => '1', "tv_archive" => 0, "direct_source" => $channel['url'], "tv_archive_duration" => 0];
            }
            echo json_encode($output);
        break;

        case "get_vod_categories":
            $output = [];
            $i = 1;
            foreach (array_keys($_SESSION['types']['movies']) as $groupName)
            {
                $i++;
                $output[] = ["category_id" => $i, "category_name" => $groupName, "parent_id" => 0];
            }
            foreach (array_keys($_SESSION['types']['others']) as $groupName)
            {
                $i++;
                $output[] = ["category_id" => $i, "category_name" => $groupName, "parent_id" => 0];
            }
            echo json_encode($output);
        break;

        case "get_vod_streams":
            $i = sizeof($channels);
            $output = [];
            foreach ($movies as $movie)
            {
                $i++;
                $output[] = ["num" => $i, "name" => $movie["name"], "stream_type" => "movie", "stream_id" => $i, "stream_icon" => $movie["img"], "added" => 1518805153, "category_id" => $movie["groupId"], "direct_source" => $movie["url"], "rating" => 5, "rating_5based" => 5, "custom_sid" => null, "container_extension" => 0];
            }
            foreach ($others as $other)
            {
                $i++;
                $output[] = ["num" => $i, "name" => $other["name"], "stream_type" => "movie", "stream_id" => $i, "stream_icon" => $other["img"], "added" => 1518805153, "category_id" => $other["groupId"], "direct_source" => $other["url"], "rating" => 5, "rating_5based" => 5, "custom_sid" => null, "container_extension" => 0];
            }
            echo json_encode($output);
        break;

        default:
            echo '{"user_info":{"username":"' . $_GET['username'] . '","password":"' . $_GET['password'] . '","message":"Bem Vindo","auth":1,"status":"Active","exp_date":"15707255707","is_trial":"0","active_cons":"0","created_at":"1568902979","max_connections":"1","allowed_output_formats":["m3u8"]},"server_info":{"url":"' . preg_replace('/api.php.*/', '', (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]") .'","port":"80","https_port":"443","server_protocol":"http","rtmp_port":"25462","timezone":"America\/Sao_Paulo","timestamp_now":' . $now . ',"time_now":"' . date("Y-m-d H:i:s", $now) . '"}}';
        break;
    }
}