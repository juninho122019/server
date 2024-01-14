<?php
session_start();
require_once("controles/usuarios.php");
require_once("controles/listas.php");
if (checarUsuario()) {
require_once("cabecalho.php");
$logs = listarLogs($_GET['id_usuario']);
?>
        <div id="conteudo-painel" class="container">
<?php if ($logs) { ?>
                <div class="mb-5 form-group float-left">
                    <input type="text" class="pesquisar form-control" placeholder="Pesquisar...">
                </div>  
                <table class='table table-bordered table-hover'>
                    <caption>Logs</caption>
                    <thead class="thead-light">
                        <tr>
                            <th class='nomecol' scope="col" >Assistindo</th>
                            <th class='nomecol' scope="col" >Data e Hora</th>
                        </tr>
                    </thead>
                <tbody id="conteudo">
<?php foreach($logs as $log) { ?>
                        <tr>
                            <td > <?=$log['canal']?> </td>
                            <td> <?=$log['data']?> </td>
                        </tr>
<?php } ?>
                    </tbody>
                </table>
        <?php
        }
        ?>
            </div>
        </div>
    </div>

  </main>
  <!-- page-content" -->
</div>
</body>
<?php require_once("comum.php"); 
require_once("alerta.php");?>

</html>
<?php 
} else {
    header("Location: index.php");
    die();
}
?>