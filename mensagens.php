<?php
session_start();
require_once("controles/usuarios.php");
require_once("controles/mensagens.php");
require_once("controles/eventos.php");
if (checarUsuario() && $_SESSION['admin']) {
require_once("cabecalho.php");
$mensagens = listarMensagens();
$eventos = listarEventos();
?>
        <div id="conteudo-painel" class="container">
<?php if ($mensagens) { ?>
                <div class="mb-5 form-group float-left">
                    <input type="text" class="pesquisar form-control" placeholder="Pesquisar...">
                </div>   
                <table class='table table-bordered table-hover'>
                    <caption>Links</caption>
                    <thead class="thead-light">
                        <tr>
                            <th class='nomecol' scope="col" >Titulo</th>
                            <th class='nomecol' scope="col" >Evento</th>
                            <th class='nomecol' scope="col" >Mensagem</th>
                            <th class='nomecol' scope="col" >Criador</th>
                            <th class='nomecol' scope="col" >Editar</th>
                            <th class='nomecol' scope="col" >Remover</th>
                            <th class='semresultado' scope='col'>Nenhum resultado</th>
                        </tr>
                    </thead>
                    <tbody id="conteudo">
<?php foreach($mensagens as $mensagem) { ?>
                        <tr>
                            <td> <?=$mensagem['titulo']?> </td>
                            <td> <?=obterEvento($mensagem['id_evento'])[0]['nome'];?> </td>
                            <td> <?=$mensagem['mensagem']?> </td>
                            <td> <?=$mensagem['titulo']?> </td>
                            <td>
                                <button class='btn btn-outline-danger' onclick="removerConfirma('<?=$mensagem['id_mensagem']?>','<?=$mensagem['titulo']?>')">Remover</button>
                            </td>
                            <td>
                                <button class='btn btn-outline-secondary' onclick="editarConfirma('<?=$mensagem['id_mensagem']?>','<?=$mensagem['titulo']?>', '<?=$mensagem['mensagem']?>', '<?=$mensagem['id_evento']?>')">Editar</button>
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
        <h5 class="modal-title">Adicionar Mensagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="cadastro-form">
            <div class="container">
                <div class="form-group">
                    <label>Titulo:</label>
                    <input type="text" class="form-control" name="titulo" placeholder="Titulo" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>
                <div class="form-group">
                    <label>Mensagem:</label>
                    <input type="text" class="form-control" name="mensagem" placeholder="Mensagem" required>
                </div>
                <div class="form-group">
                  <label>Evento:</label>
                  <div class="ml-0 row">
                    <select class="selectpicker" title="Evento..." name="id_evento" required>
                    <?php if ($eventos) {
                      foreach($eventos as $evento) {?>
                      <option value="<?= $evento['id_evento']?>" > <?= $evento['nome']?> </option>
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
        <h5 class="modal-title">Editar Mensagem</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="editar-form">
            <div class="container">
                  <input type="hidden" name="id" id="idE">
                <div class="form-group">
                    <label>Titulo:</label>
                    <input type="text" class="form-control" id="tituloE" name="titulo" placeholder="Titulo" required autofocus>
                    <small class="form-text text-muted">Campo único!</small>
                </div>
                <div class="form-group">
                    <label>Mensagem:</label>
                    <input type="text" class="form-control" id="mensagemE" name="mensagem" placeholder="Mensagem" required>
                </div>
                <div class="form-group">
                  <label>Categoria:</label>
                  <div class="ml-0 row">
                    <select class="selectpicker" title="Evento..." id="id_eventoE" name="id_evento" required>
                  <?php  if ($eventos) { 
                          foreach($eventos as $evento) {?>
                      <option value="<?= $evento['id_evento']?>" > <?= $evento['nome']?> </option>
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

    function editarConfirma(id, titulo, mensagem, id_evento) {
        $('#idE').val(id);
        $('#tituloE').val(titulo);
        $('#mensagemE').val(mensagem);
        $('#id_eventoE').val(id_evento);
        $('#editar').modal();
    }

    function remover(id) {
        $.ajax({
            type: "POST",
            url: "controles/remover-mensagem.php",
            data: {id: id},
            success: function(data) {
                location.reload();
            }
        });
    }

    $( "#cadastro-form" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/adicionar-mensagem.php",
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
            url: "controles/editar-mensagem.php",
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