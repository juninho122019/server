<?php
session_start();
require_once("controles/usuarios.php");
require_once("controles/links.php");
require_once("controles/categorias.php");
require_once("controles/listas.php");
if (checarUsuario()) {
require_once("cabecalho.php");
$links = listarlinks();
$categorias = listarCategorias();
$categoriasNVazias = listarCategoriasNaoVazias();
$listas = listarListas();
?>
        <div id="conteudo-painel" class="container">
<?php if ($listas) { ?>
                <div class="mb-5 form-group float-left">
                    <input type="text" class="pesquisar form-control" placeholder="Pesquisar...">
                </div>   
                <table class='table table-bordered table-hover'>
                    <caption>Listas</caption>
                    <thead class="thead-light">
                        <tr>
                            <th class='nomecol' scope="col" >Nome</th>
                            <th class='nomecol' scope="col" >ID</th>
                            <?php if($_SESSION['admin']){ ?>
                            <th class='nomecol' style="width: 13%" scope="col"></th>
                            <th class='nomecol' style="width: 13%" scope="col"></th>
                            <?php } ?>
                            <th class='nomecol' style="width: 13%" scope="col"></th>
                            <th class='semresultado' scope='col'>Nenhum resultado</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo">
<?php foreach($listas as $lista) { ?>
                        <tr>
                            <td> <?=$lista['nome_lista']?> </td>
                            <td> <?=$lista['id_lista']?> </td>
                            <?php if($_SESSION['admin']){ ?>
                            <td>
                                <button class='btn btn-outline-danger' onclick="removerConfirma('<?=$lista['id_lista']?>','<?=$lista['nome_lista']?>')">Remover</button>
                            </td>
                            <td>
                                <button class='btn btn-outline-secondary' onclick="editarGlobalConfirma('<?=$lista['id_lista']?>','<?=$lista['nome_lista']?>', [<?php foreach (categoriasLista($lista['id_lista']) as $categoria) echo $categoria['id'] .',' ?> ] )">Editar</button>
                            </td>
                            <?php } ?>
                            <td>
                                <button class='btn btn-outline-primary' onclick="obterListaUsuarios('<?=$lista['id_lista']?>')">Obter Link</button>
                            </td>
                        </tr>
<?php } ?>
                    </tbody>
                </table>
        <?php
        }
        ?>
        <?php if($_SESSION['admin']) { ?>
            <div class="h3 mt-5 row align-items-center justify-content-center">
                <i onclick="$('#cadastroGlobal').modal()" class="btn btn-outline-info text-dark fas fa-plus"></i>
            </div>
        </div>
        <?php } ?>
    </div>

  </main>
  <!-- page-content" -->
</div>

<!-- Cadastro Global Inicio -->
<div class="modal fade bd-modal-lg" id="cadastroGlobal" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Adicionar Lista</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cadastro-form-global">
            <div class="container">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" name="nome" placeholder="Nome da Lista" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>  
                <div class="form-group"> 
                    <label>Categorias:</label>
                    <div class="mb-3 ml-0 row">
                        <select name="categoria[]" class="selectpicker" title="Categoria..." required multiple>
                            <?php if ($categorias) {
                                foreach($categorias as $categoria) {?>
                            <option value="<?= $categoria['id']?>"><?= $categoria['nome']?></option>
                                <?php } 
                                } ?>
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
<!-- Cadastro Global Fim-->
<!-- Editar Global Inicio -->
<div class="modal fade bd-modal-lg" id="editarGlobal" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Editar Lista</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editar-form-global">
            <input type="hidden" id="idGE" name="id">
            <div class="container">
                <div class="form-group">
                    <label>Nome:</label>
                    <input type="text" class="form-control" name="nome" id="nomeGE" placeholder="Nome da Lista" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>
                <div class="form-group"> 
                    <label>Categorias:</label>
                    <div class="mb-3 ml-0 row">
                        <select id="categoriaGE" name="categoria[]" class="selectpicker" title="Categoria..." required multiple>
                            <?php if ($categorias) {
                                foreach($categorias as $categoria) {?>
                            <option value="<?= $categoria['id']?>"><?= $categoria['nome']?></option>
                                <?php } 
                                } ?>
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
<!-- Editar Global Fim-->
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
<!-- Obter Link Inicio -->
<div class="modal fade" id="obterLinkdaLista" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Obter Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formObterLinkdaLista">
            <input type="hidden" id="idLista" name="idLista">
            <div class="form-group">
                <label>Selecione o Usuário:</label>
                <div class="select-users ml-0 row">
                </div>
            </div>
            <button type="submit" class="btn btn-secondary">Obter</button>
        </form>
      </div>
    </div>
  </div>
</div>
<!-- Obter Link Fim-->
<!-- Link Inicio -->
<div class="modal fade" id="linkLista" tabindex="-1" role="dialog" aria-labelledby="Cadastrar" aria-hidden="true">
  <div class="modal-dialog modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title"> Link</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <div>
            <input type="text" class="mb-5 form-control" id="linkIn" readonly>
            <button type="button" class="btn btn-primary" onclick="copiar()">Copiar</button>
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Link Fim-->
</body>
<script>

    $("#obterLinkdaLista").on('hidden.bs.modal', function (e) {
        $(".select-users").empty();
    });

    function obterListaUsuarios(id) {
        $('#idLista').val(id);
        $.ajax({
            type: "POST",
            url: "controles/obter-lista-usuarios.php",
            data: {id: id},
            dataType:"json",
            success: function(data) {
                var $select = $('<select/>', {
                    'class':"selectpicker",
                    'title':"Usuário...",
                    'name': "idUsuario"
                });
                for (j=0; j < data.length; j++) {
                    $select.append('<option value=' + data[j].id_usuario + '>' + data[j].nome_usuario + '</option>');
                }
                $select.appendTo('.select-users').selectpicker('refresh');
            }
        });
        $('#obterLinkdaLista').modal();
    }

    function listaGlobal() {
        $('#cadastro').modal('hide');
        $('#cadastroGlobal').modal();
    }

    function removerConfirma(id,nome) {
        $('#remover-conteudo').html('<div class="alert alert-danger" role="alert"><strong> Remover </strong>' + nome + '?</div><button onclick="remover(' + id + ')" type="submit" class="btn btn-danger float-right">Remover</button>');
        $('#remover').modal();
    }

    function editarGlobalConfirma(id,nome, lista) {
        $('#idGE').val(id);
        $('#nomeGE').val(nome);
        $('#nomeGE').val(nome);
        $('#categoriaGE').val(lista);
        $('#categoriaGE').selectpicker('render');
        $('#editarGlobal').modal();
    }

    function remover(id) {
        $.ajax({
            type: "POST",
            url: "controles/remover-lista.php",
            data: {id: id},
            success: function(data) {
              location.reload();
            }
        });
    }

    $( "#cadastro-form-global" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/adicionar-lista-global.php",
            data: $("#cadastro-form-global").serialize(),
            success: function(data) {
              location.reload();
            },
            error: function(data) {
              resultado(data.responseText);
            }
        });
        event.preventDefault();
    });

    $( "#editar-form-global" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/editar-lista-global.php",
            data: $("#editar-form-global").serialize(),
            success: function(data) {
                location.reload();
            },
            error: function(data) {
              resultado(data.responseText);
            }
        });
        event.preventDefault();
    });

    $( "#formObterLinkdaLista" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/obter-link-lista.php",
            data: $("#formObterLinkdaLista").serialize(),
            success: function(data) {
                $('#linkIn').val(data);
                $('#obterLinkdaLista').modal('hide');
                $('#linkLista').modal();
            }
        });
        event.preventDefault();
    });

    function copiar() {
        $('#linkIn').select();
        document.execCommand("copy");
    }

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