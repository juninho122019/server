<?php
session_start();
if($_GET['acesso'] == "limpa.tudo"){
    require_once("controles/conexao.php");
    global $conexao;
    $query = "DELETE FROM categoria;";
    mysqli_query($conexao, $query);

    $query = "DELETE FROM eventos;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM lidas;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM link;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM lista;";
    mysqli_query($conexao, $query);
    
    
    $query = "DELETE FROM lista_global_categoria;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM lista_usuario;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM logs;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM mensagens;";
    mysqli_query($conexao, $query);
    
    $query = "DELETE FROM usuario;";
    mysqli_query($conexao, $query);
    
    $query = "INSERT INTO `usuario` (`id_usuario`, `nome_usuario`, `senha_usuario`, `login_usuario`, `admin`, `estado_usuario`, `contato_usuario`, `acesso`) VALUES (1, 'admin', 'abed1846cf853495d747c4029a9f56aa', 'admin', 1, 1, '', 'bddf35069c88dcad8925f56ad43d8680');";
    mysqli_query($conexao, $query);
}
?>
<?php 
    //ENTER THE RELEVANT INFO BELOW
    $mysqlUserName      = $usuario;
    $mysqlPassword      = $senha;
    $mysqlHostName      = $endereco;
    $DbName             = $banco;
    $backup_name        = "mybackup.sql";
    $tables             = false;

   //or add 5th parameter(array) of specific tables:    array("mytable1","mytable2","mytable3") for multiple tables

    Export_Database($mysqlHostName,$mysqlUserName,$mysqlPassword,$DbName,  $tables=false, $backup_name=false );

    function Export_Database($host,$user,$pass,$name,  $tables=false, $backup_name=false )
    {
        $mysqli = new mysqli($host,$user,$pass,$name); 
        $mysqli->select_db($name); 
        $mysqli->query("SET NAMES 'utf8'");

        $queryTables    = $mysqli->query('SHOW TABLES'); 
        while($row = $queryTables->fetch_row()) 
        { 
            $target_tables[] = $row[0]; 
        }   
        if($tables !== false) 
        { 
            $target_tables = array_intersect( $target_tables, $tables); 
        }
        foreach($target_tables as $table)
        {
            $result         =   $mysqli->query('SELECT * FROM '.$table);  
            $fields_amount  =   $result->field_count;  
            $rows_num=$mysqli->affected_rows;     
            $res            =   $mysqli->query('SHOW CREATE TABLE '.$table); 
            $TableMLine     =   $res->fetch_row();
            $content        = (!isset($content) ?  '' : $content) . "\n\n".$TableMLine[1].";\n\n";

            for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) 
            {
                while($row = $result->fetch_row())  
                { //when started (and every after 100 command cycle):
                    if ($st_counter%100 == 0 || $st_counter == 0 )  
                    {
                            $content .= "\nINSERT INTO ".$table." VALUES";
                    }
                    $content .= "\n(";
                    for($j=0; $j<$fields_amount; $j++)  
                    { 
                        $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
                        if (isset($row[$j]))
                        {
                            $content .= '"'.$row[$j].'"' ; 
                        }
                        else 
                        {   
                            $content .= '""';
                        }     
                        if ($j<($fields_amount-1))
                        {
                                $content.= ',';
                        }      
                    }
                    $content .=")";
                    //every after 100 command cycle [or at last line] ....p.s. but should be inserted 1 cycle eariler
                    if ( (($st_counter+1)%100==0 && $st_counter!=0) || $st_counter+1==$rows_num) 
                    {   
                        $content .= ";";
                    } 
                    else 
                    {
                        $content .= ",";
                    } 
                    $st_counter=$st_counter+1;
                }
            } $content .="\n\n\n";
        }
        //$backup_name = $backup_name ? $backup_name : $name."___(".date('H-i-s')."_".date('d-m-Y').")__rand".rand(1,11111111).".sql";
        $backup_name = $backup_name ? $backup_name : 'sem_dados.sql';
        header('Content-Type: application/octet-stream');   
        header("Content-Transfer-Encoding: Binary"); 
        header("Content-disposition: attachment; filename=\"".$backup_name."\"");  
        echo $content; exit;
    }
?>