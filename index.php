<?php
session_start();
require_once('controles/usuarios.php');
if (!checarUsuario()) {
?>
<!DOCTYPE html>

<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login | IPTV SERVER</title>
    <link href="css/login.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <form id="login" class="form-signin">
        <img class="mx-auto d-block mb-4" src="img/logo.jpg" alt="LOGO" height="100">
        <label for="inputEmail" class="sr-only">Usuário</label>
        <input name="usuario" type="text" id="inputEmail" class="form-control" placeholder="Usuário" required autofocus>
        <label for="inputPassword" class="sr-only">Senha</label>
        <input name="senha" type="password" id="inputPassword" class="form-control" placeholder="Senha" required>
        <!--<div class="checkbox mb-3">
        <label>
            <input type="checkbox" value="remember-me"> Lembre-se
        </label>
        </div> -->
        <button class="btn btn-lg btn-primary btn-block" type="submit">Entrar</button>
       <div class="separator" style="clear: both; text-align: center;">
</div>
<div class="separator" style="clear: both; text-align: center;">
</div>
<div class="separator" style="clear: both; text-align: center;">
</div>
<div style="text-align: center;">
<br />
&copy; Painel V3.0 Feito por <a href="//youtube.com/matamataxiterbr" target="_blank">PJT</a></div>
</form>
<!-- Alerta Inicio -->
<div class="modal fade" id="alerta" tabindex="-1" role="dialog" aria-labelledby="Alerta" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">

      <div class="modal-body">
      <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
        <div class="text-center">
        <h5 id="textoAlerta" class="h5"></h5> 
        </div>
      </div>
    </div>
  </div>
</div>
<!-- Alerta Fim-->
</body>
<script>
if (location.protocol != 'http:')
{
   location.href = 'http:' + window.location.href.substring(window.location.protocol.length);
}

    $( "#login" ).submit(function( event ) {
        $.ajax({
            type: "POST",
            url: "controles/login.php",
            data: $("#login").serialize(),
            success: function(data) {
                location = "lista.php";
            },
            error: function(data) {
                $("#textoAlerta").text(data.responseText);
                $("#alerta").modal();
            }
        });
        event.preventDefault();
    });
</script>
</html>
<?php
} else {
    header("Location: lista.php");
    die();
}
?>