<?php
session_start();
require_once("controles/usuarios.php");
require_once("controles/categorias.php");
if (checarUsuario()) {
require_once("cabecalho.php");
$categorias = false;
if($_SESSION['admin']){
    $categorias = listarCategorias();
}
?>
        <div id="conteudo-painel" class="container">
<?php if ($categorias) { ?>
                <div class="mb-5 form-group float-left">
                    <input type="text" class="pesquisar form-control" placeholder="Pesquisar...">
                </div>   
                <table class='table table-bordered table-hover'>
                    <caption>Categorias</caption>
                    <thead class="thead-light">
                        <tr>
                            <th class='nomecol' scope="col" >Nome</th>
                            <th class='nomecol' style="width: 13%" scope="col"></th>
                            <th class='nomecol' style="width: 13%" scope="col"></th>
                            <th class='semresultado' scope='col'>Nenhum resultado</th>
                        </tr>
                    </thead>
                <tbody id="conteudo">
<?php foreach($categorias as $categoria) { ?>
                        <tr>
                            <td> <?=$categoria['nome']?> </td>
                            <td>
                                <button class='btn btn-outline-danger' onclick="removerConfirma('<?=$categoria['id']?>','<?=$categoria['nome']?>')">Remover</button>
                            </td>
                            <td>
                                <button class='btn btn-outline-secondary' onclick="editarConfirma('<?=$categoria['id']?>','<?=$categoria['nome']?>')">Editar</button>
                            </td>
                        </tr>
<?php } ?>
                    </tbody>
                </table>
        <?php
        }
        ?>
            <div class="h3 mt-5 row align-items-center justify-content-center">
                <i onclick="$('#cadastro').modal()" class="btn btn-outline-info text-dark fas fa-plus"></i>
            </div>
        </div>
    </div>

  </main>
  <!-- page-content" -->
</div>
<!-- Cadastro Inicio -->
<div class="modal fade" id="cadastro" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Adicionar Categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cadastro-form">
            <div class="container">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome da categoria" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>
                <button type="submit" class="btn btn-primary">Adicionar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Cadastro Fim-->
<!-- Remove Inicio -->
<div class="modal fade" id="remover" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tem certeza?</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div id="remover-conteudo" class="modal-body"></div>
    </div>
  </div>
</div>
<!-- Remove Fim-->
<!-- Edita Inicio -->
<div class="modal fade" id="editar" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Categoria</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editar-form">
            <div class="container">
                <input type="hidden" id="idE" name="id">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" id="nomeE" name="nome" placeholder="Nome da Categoria" required autofocus>
                </div>
                <button type="submit" class="btn btn-primary">Salvar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Edita Fim-->
</body>
<script>
    function removerConfirma(id,nome) {
        $('#remover-conteudo').html('<div class="alert alert-danger" role="alert"><strong> Remover </strong>' + nome + '?</div><button onclick="remover(' + id + ')" type="submit" class="btn btn-danger float-right">Remover</button>');
        $('#remover').modal();
    }

    function editarConfirma(id,nome) {
        $('#idE').val(id);
        $('#nomeE').val(nome);
        $('#editar').modal();
    }

    function remover(id) {
        $.ajax({
            type: "POST",
            url: "controles/remover-categoria.php",
            data: {id: id},
            success: function(data) {
                location.reload();
            }
        });
    }

    $( "#cadastro-form" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/adicionar-categoria.php",
            data: $("#cadastro-form").serialize(),
            success: function(data) {
                location.reload();
            },
            error: function(data) {
              resultado(data.responseText);
            }
        });
        event.preventDefault();
    });

    $( "#editar-form" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/editar-categoria.php",
            data: $("#editar-form").serialize(),
            success: function(data) {
                location.reload();
            },
            error: function(data) {
              resultado(data.responseText);
            }
        });
        event.preventDefault();
    });

</script>
<?php require_once("comum.php");
require_once("alerta.php"); ?>
</html>
<?php 
} else {
    header("Location: index.php");
    die();
}
?>