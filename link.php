<?php
session_start();
require_once("controles/usuarios.php");
require_once("controles/links.php");
require_once("controles/categorias.php");
if (checarUsuario() && $_SESSION['admin']) {
require_once("cabecalho.php");
$links = listarlinks();
$categorias = listarCategorias();
?>
        <div id="conteudo-painel" class="container">
<?php if ($links) { ?>
                <div class="mb-5 form-group float-left">
                    <input type="text" class="pesquisar form-control" placeholder="Pesquisar...">
                </div>   
                <table class='table table-bordered table-hover'>
                    <caption>Links</caption>
                    <thead class="thead-light">
                        <tr>
                            <th class='nomecol' scope="col" >Nome</th>
                            <th class='nomecol' scope="col" >Categoria</th>
                            <th class='nomecol' scope="col" >Url</th>
                            <th class='nomecol' scope="col" >Logo</th>
                            <th class='nomecol' scope="col"></th>
                            <th class='nomecol' scope="col"></th>
                            <th class='semresultado' scope='col'>Nenhum resultado</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo">
<?php foreach($links as $link) { ?>
                        <tr>
                            <td> <?=$link['nome_link']?> </td>
                            <td> <?= nomeCategoria($link['id_categoria'])?> </td>
                            <td> <?=$link['link_link']?> </td>
                            <td> <?=$link['logo']?> </td>
                            <td>
                                <button class='btn btn-outline-danger' onclick="removerConfirma('<?=$link['id_link']?>','<?=$link['nome_link']?>')">Remover</button>
                            </td>
                            <td>
                                <button class='btn btn-outline-secondary' onclick="editarConfirma('<?=$link['id_link']?>','<?=$link['nome_link']?>', '<?=$link['link_link']?>', '<?=$link['id_categoria']?>', '<?=$link['logo']?>')">Editar</button>
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
        <h5 class="modal-title">Adicionar Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cadastro-form">
            <div class="container">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome do Link" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>
                <div class="form-group">
                    <label>Logo:</label>
                    <input type="text" class="form-control" name="logo" placeholder="Link da Imagem" required>
                </div>
                <div class="form-group">
                    <label>Url:</label>
                    <input type="text" class="form-control" name="link" placeholder="Url do Link" required>
                </div>
                <div class="form-group">
                  <label>Categoria:</label>
                  <div class="ml-0 row">
                    <select class="selectpicker" title="Categoria..." name="categoria" required>
                    <?php if ($categorias) {
                      foreach($categorias as $categoria) {?>
                      <option value="<?= $categoria['id']?>" > <?= $categoria['nome']?> </option>
                    <?php } } ?>
                    </select>
                  </div>
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
        <h5 class="modal-title">Editar Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editar-form">
            <div class="container">
                  <input type="hidden" name="id" id="idE">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" id="nomeE" name="nome" placeholder="Nome do Link" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>
                <div class="form-group">
                    <label>Logo:</label>
                    <input type="text" class="form-control" id="logoE" name="logo" placeholder="Link da Imagem" required>
                </div>
                <div class="form-group">
                    <label>Url:</label>
                    <input type="text" class="form-control" id="linkE" name="link" placeholder="Url do Link" required>
                </div>
                <div class="form-group">
                  <label>Categoria:</label>
                  <div class="ml-0 row">
                    <select class="selectpicker" title="Categoria..." id="categoriaE" name="categoria" required>
                  <?php  if ($categorias) { 
                          foreach($categorias as $categoria) {?>
                      <option value="<?= $categoria['id']?>" > <?= $categoria['nome']?> </option>
                    <?php } } ?>
                    </select>
                  </div>
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

    function editarConfirma(id,nome, link, categoria, logo) {
        $('#idE').val(id);
        $('#nomeE').val(nome);
        $('#logoE').val(logo);
        $('#linkE').val(link);
        if (categoria !== "") {
          $('#categoriaE').val(categoria);
          $('#categoriaE').selectpicker('render');
        }
        $('#editar').modal();
    }

    function remover(id) {
        $.ajax({
            type: "POST",
            url: "controles/remover-link.php",
            data: {id: id},
            success: function(data) {
                location.reload();
            }
        });
    }

    $( "#cadastro-form" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/adicionar-link.php",
            data: $("#cadastro-form").serialize(),
            success: function(data) {
                location.reload();
            },
            error: function (data) {
              resultado(data.responseText);
            }
        });
        event.preventDefault();
    });

    $( "#editar-form" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/editar-link.php",
            data: $("#editar-form").serialize(),
            success: function(data) {
                location.reload();
            },
            error: function (data) {
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