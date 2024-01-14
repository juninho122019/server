<?php
error_reporting(0);
if (basename($_SERVER["PHP_SELF"]) === basename(__FILE__)) {
    header('HTTP/1.0 403 Forbidden');
    header("Location: index.php");
    die();
    
}
session_start();
require_once("controles/usuarios.php");
require_once("controles/mensagens.php");
$categorias = false;
$mensagens = listarMensagensParaVendedor();
$eventos = [];
foreach($mensagens as $mensagem){
    if(array_key_exists($mensagem['evento_nome'], $eventos)){
        $eventos[$mensagem['evento_nome']][] = $mensagem;
    } else {
        $eventos[$mensagem['evento_nome']] = [];
        $eventos[$mensagem['evento_nome']][] = $mensagem;
    }
}
$notificacoes = [];
foreach(array_keys($eventos) as $nome_evento){
    $notificacoes[] = [
        "nome" => $nome_evento,
        "mensagens" => $eventos[$nome_evento]
    ];
}

?>
<!DOCTYPE html>
<html lang="pt-BR" itemscope itemtype="http://schema.org/WebPage">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel | IPTV SERVER</title>
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap.bundle.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/painel.js"></script>
    <script src="js/bootstrap-select.min.js"></script>
    <script src="js/defaults-pt_BR.min.js"></script>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/painel.css">
    <link rel="stylesheet" href="css/bootstrap-select.min.css">
    <link rel="stylesheet" href="css/editor.css">
    <link href="css/all.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/tema.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/patternfly/3.24.0/css/patternfly-additions.min.css">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
    <link href="https://transloadit.edgly.net/releases/uppy/v1.4.0/uppy.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/8b521a5df9.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/patternfly/3.24.0/js/patternfly.min.js"></script>
    <style>
      .pesquisar {
          max-width: 200px;
      }

      #conteudo tr[visible='false'], .semresultado{
          display: none;
      }

      #conteudo tr[visible='true']{
          display:table-row;
      }

      #conteudoQ td, th {
          overflow: hidden;
          text-overflow: clip;
      }
      
      table th, table td { 
        overflow: hidden; 
        max-width:100px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
      }

      #conteudoQ tr[visible='false']{
          display: none;
      }

      #conteudoQ tr[visible='true']{
          display:table-row;
      }
    </style>
</head>

<body>
<div class="page-wrapper chiller-theme toggled">
  <a id="show-sidebar" class="btn btn-sm btn-dark" href="#">
    <i class="fas fa-bars"></i>
  </a>
  <nav id="sidebar" class="sidebar-wrapper">
    <div class="sidebar-content">
        <div class="sidebar-brand">
            <a>Painel</a>
            <div style="margin-right: 10px;" class="drawer-pf-trigger" id="close-sidebar">
                <i class="fas fa-bell"></i>
            </div>
            <div class="close-sidebar" id="close-sidebar">
                <i class="fas fa-times"></i>
            </div>
        </div>
        <!--user-->
        <div style="margin: 0px; border: 0px;" class="sidebar-header">
          <div class="user-pic">
            <img class="img-responsive img-rounded" src="img/user.jpg"
              alt="User picture">
          </div>
          <div class="user-info">
            <span class="user-name">
              <?= usuarioLogado()?>
            </span>
            <span class="user-status">
              <i class="fa fa-circle"></i>
              <span>Online</span>
            </span>
          </div>
      </div>
      <!-- USER-->
      <div class="sidebar-menu">
        <ul>
          <li id="lista.php">
            <a href="lista.php">
              <i class="fas fa-list-ul"></i>
              <span>Listas</span>
            </a>
          </li>
          <?php if($_SESSION['admin']) { ?> 
          <li id="link.php">
            <a href="link.php">
              <i class="fas fa-link"></i>
              <span>Links</span>
            </a>
          </li>
          <li id="categoria.php">
            <a href="categoria.php">
              <i class="fas fa-list-alt"></i>
              <span>Categorias</span>
            </a>
          </li>
          <?php } ?>
          <li id="usuario.php">
            <a href="usuario.php">
              <i class="fas fa-users"></i>
              <span>Usuários</span>
            </a>
          </li>
          <?php if ($_SESSION['admin']) { ?>
          <li id="eventos.php">
            <a href="eventos.php">
              <i class="fas fa-briefcase"></i>
              <span>Eventos</span>
            </a>
          </li>
          <li id="mensagens.php">
            <a href="mensagens.php">
              <i class="fas fa-envelope"></i>
              <span>Mensagens</span>
            </a>
          </li>
          <li>
            <a id="importar" style="cursor: pointer;">
              <i class="fas fa-file-import"></i>
              <span>Importar</span>
            </a>
          </li>
          <?php } ?>
      <!-- sidebar-menu  -->
    </div></div>
    <!-- sidebar-content  -->
    <div style="position: relative;" class="sidebar-footer">
      <a href="sair.php">
        <i class="fa fa-power-off"></i>
      </a>
    </div>
  </nav>
  <!-- sidebar-wrapper  -->

  <div class="note-page">
   <div style="height: 100%" class="drawer-pf hide drawer-pf-notifications-non-clickable">
      <div class="drawer-pf-title">
         <a style="top: 3px; left: 6px;" class="drawer-pf-toggle-expand fa fa-angle-double-left hidden-xs"></a>
         <a style="float: right;right: 7px;position: relative;color: black;top: 6px" class="drawer-pf-close pficon pficon-close"></a>
         <h3 class="text-center">Area de Notificações</h3>
      </div>
      <div class="panel-group eventos" id="notification-drawer-accordion">
         <?php foreach ($notificacoes as $notificacao) { ?>
         <?php 
            $eventoNome = preg_replace("/[^a-zA-Z]+/", "", $notificacao['nome']); 
            $eventoNome =str_replace(' ', '', $eventoNome); 
         ?>
         <div id="event_<?php echo strtolower($eventoNome); ?>" class="panel panel-default">
            <div class="panel-heading" data-component="collapse-heading">
               <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#notification-drawer-accordion" href="#fixedCollapse<?php echo strtolower($eventoNome) ?>">
                  <?php echo $notificacao['nome']; ?>
                  </a>
               </h4>
               <span class="panel-counter novas_mensagens"><?php echo sizeof($notificacao['mensagens']); ?> Novas mensagens</span>
            </div>
            <div id="fixedCollapse<?php echo strtolower($eventoNome); ?>" class="panel-collapse collapse in">
               <div class="panel-body todas_mensagens">
                  <?php foreach($notificacao['mensagens'] as $mensagem){ ?>
                  <div mensagem-id="<?php echo $mensagem['id_mensagem']; ?>" class="drawer-pf-notification <?php echo $mensagem['lida'] == 'sim' ? '' : 'unread'; ?>">
                     <div class="dropdown pull-right dropdown-kebab-pf">
                        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownKebabRight11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="fa fa-ellipsis-v"></span>
                        </button>
                        <ul style="cursor: pointer;" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownKebabRight11">
                           <li><a onclick="marcarComoLida('<?php echo $mensagem['id_mensagem']; ?>'); alert('<?php echo $mensagem['mensagem']; ?>');">Ler</a></li>
                           <li role="separator" class="divider"></li>
                           <li><a onclick="removerMensagem('<?php echo $mensagem['id_mensagem']; ?>')">Remover</a></li>
                        </ul>
                     </div>
                     <span class="pficon pficon-info pull-left"></span>
                     <div class="drawer-pf-notification-content">
                        <span class="drawer-pf-notification-message"><?php echo $mensagem['titulo']; ?></span>
                        <div class="drawer-pf-notification-info">
                           <span class="date"><?php echo $mensagem['data']; ?></span>
                        </div>
                     </div>
                  </div>
                  <?php } ?>
               </div>
               <div class="blank-slate-pf hidden">
                  <div class="blank-slate-pf-icon">
                     <span class="pficon-info"></span>
                  </div>
                  <h1>Sem Mensagens.</h1>
               </div>
               <div class="drawer-pf-action">
                  <div onclick="removerTodas('<?php echo $notificacao['nome']; ?>')" style="text-align: center;" class="drawer-pf-action-link" data-toggle="clear-all">
                     <button class="btn btn-link">
                     <span class="pficon pficon-close"></span> Remover Todas
                     </button>
                  </div>
               </div>
            </div>
         </div>
         <?php } ?>
      </div>
   </div>
   <script>
      function reload(){
          // Initialize to unread notifications
          // TODO: add badge for unread notifications
      
          // Show/Hide Notifications Drawer
          $('.drawer-pf-trigger').click(function() {
              var $drawer = $('.drawer-pf');
      
              $(this).toggleClass('open');
              if ($drawer.hasClass('hide')) {
                  $drawer.removeClass('hide');
                  setTimeout(function() {
                      if (window.dispatchEvent) {
                          window.dispatchEvent(new Event('resize'));
                      }
                      // Special case for IE
                      if ($(document).fireEvent) {
                          $(document).fireEvent('onresize');
                      }
                  }, 100);
              } else {
                  $drawer.addClass('hide');
              }
              // Special case, close navigation menu in mobile mode
              if ($('.container-pf-nav-pf-vertical').hasClass('hidden-nav')) {
                  $('.nav-pf-vertical').removeClass('show-mobile-nav');
              }
          });
          $('.drawer-pf-close').click(function() {
              var $drawer = $('.drawer-pf');
      
              $('.drawer-pf-trigger').removeClass('open');
              $drawer.addClass('hide');
          });
          $('.drawer-pf-toggle-expand').click(function() {
              var $drawer = $('.drawer-pf');
              var $drawerNotifications = $drawer.find('.drawer-pf-notification');
      
              if ($drawer.hasClass('drawer-pf-expanded')) {
                  $drawer.removeClass('drawer-pf-expanded');
                  $drawerNotifications.removeClass('expanded-notification');
              } else {
                  $drawer.addClass('drawer-pf-expanded');
                  $drawerNotifications.addClass('expanded-notification');
              }
          });
      
          // Mark All Read / Clear All
          $('.panel-collapse').each(function(index, panel) {
              var $panel = $(panel);
              var unreadCount = $panel.find('.drawer-pf-notification.unread').length;
              $(panel.parentElement).find('.panel-counter').text(unreadCount + ' Novas Mensage' + (unreadCount !== 1 ? 'ns' : 'm'));
      
              if ($('.drawer-pf .panel-collapse .unread').length === 0) {
                  // TODO: remove badge for unread indicator
              }
      
              $panel.on('click', '.drawer-pf-action [data-toggle="mark-all-read"] .btn', function() {
                  $panel.find('.unread').removeClass('unread');
                  $panel.find('.drawer-pf-action [data-toggle="mark-all-read"]').remove();
                  $(panel.parentElement).find('.panel-counter').text('0 Mensagens Lidas');
                  if ($('.drawer-pf .panel-collapse .unread').length === 0) {
                      $('.drawer-pf-trigger').removeClass('unread');
                  }
              });
              $panel.on('click', '.drawer-pf-action [data-toggle="clear-all"] .btn', function() {
                  $panel.find('.panel-body .drawer-pf-notification').remove();
                  $panel.find('.drawer-pf-action').remove();
                  $panel.find('.blank-slate-pf').removeClass('hidden');
                  $panel.find('.drawer-pf-loading').addClass('hidden');
                  $(panel.parentElement).find('.panel-counter').text('0 Mensagens Lidas');
                  if ($('.drawer-pf .panel-collapse .unread').length === 0) {
                      // TODO: remove badge for unread indicator
                  }
              });
      
              $panel.find('.drawer-pf-notification').each(function(index, notification) {
                  var $notification = $(notification);
                  $notification.on('click', '.drawer-pf-notification-content', function() {
                      $notification.removeClass('unread');
                      var unreadCount = $panel.find('.drawer-pf-notification.unread').length;
                      $(panel.parentElement).find('.panel-counter').text(unreadCount + ' Novas Mensage' + (unreadCount !== 1 ? 'ns' : 'm'));
                      if (unreadCount === 0) {
                          $panel.find('.drawer-pf-action [data-toggle="mark-all-read"]').remove();
                          if ($('.drawer-pf .panel-collapse .unread').length === 0) {
                              // TODO: remove badge for unread indicator
                          }
                      }
                  });
              });
          });
      
          $('#notification-drawer-accordion').initCollapseHeights('.panel-body');
      };
      reload();
   </script>
  </div>
  <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

  <audio style="height: 10000px;opacity: 0;width: 10000px;z-index: 10000;position: absolute;" controls id="new_msg">
      <source src="9ec12ac76ddc166fe19691e15374bc5f_pt-br.mp3" type="audio/mp3">
  </audio>
  <div id="log.php" class="none"></div>
  <script>
    document.getElementById("new_msg").playbackRate = 1.5;
    
    function marcarComoLida(id, remover = 0){
        if(remover == 1){
             $('div[mensagem-id="'+id+'"]').remove();
        }
        $('div[mensagem-id="'+id+'"] span').click();
        $.get('/controles/ler-mensagem.php?id_mensagem=' + id + '&remover=' + remover, function(data) {});
    }
    var msg = 0;
    var eventos = [];
    <?php foreach (array_keys($eventos) as $nome) { ?>
    eventos['<?php echo $nome; ?>'] = [];
    <?php foreach($eventos[$nome] as $mensagem) { ?>
    msg++;
    eventos['<?php echo $nome; ?>'].push('<?php echo $mensagem['id_mensagem']; ?>');
    <?php } ?>
    <?php } ?>
    function removerTodas(evento){
        eventos[evento].forEach((id) => {
            removerMensagem(id);
        });
    }
    function removerMensagem(id){
        marcarComoLida(id, 1);
    }
    if (location.pathname.includes("<?= $paginaCorrente = basename($_SERVER['SCRIPT_NAME']);?>")) {
      document.getElementById('<?= $paginaCorrente = basename($_SERVER['SCRIPT_NAME']);?>').classList.add("ativado");
    }
    window.mobilecheck = function() {
      var check = false;
      (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
      return check;
    };
    
    $(document).on('mousemove click', function(event){
	if($('#new_msg').is(':visible')){
	   var x = event.clientX;
	   var y = event.clientY;
	   $("#new_msg").hide();
	   if(event.type == 'click'){
		document.elementFromPoint(x, y).click();
	   } else {
		$(document.elementFromPoint(x, y)).trigger('mouseenter');
		$(document.elementFromPoint(x, y)).trigger('hover');
		$(document.elementFromPoint(x, y)).trigger('mouseover');
	   }
	}
    });
    
    if(window.mobilecheck()){
        $('#new_msg').hide();
    }
    
    setInterval(() => {
        $('.note-page .panel-body').css('max-height', '100%');
    }, 100);
    
    var mensagemHTML = `<div mensagem-id="id_mensagem" class="drawer-pf-notification foilida">
                     <div class="dropdown pull-right dropdown-kebab-pf">
                        <button class="btn btn-link dropdown-toggle" type="button" id="dropdownKebabRight11" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <span class="fa fa-ellipsis-v"></span>
                        </button>
                        <ul style="cursor: pointer;" class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownKebabRight11">
                           <li><a onclick="marcarComoLida('id_mensagem'); alert('texto_mensagem');">Ler</a></li>
                           <li role="separator" class="divider"></li>
                           <li><a onclick="removerMensagem('id_mensagem')">Remover</a></li>
                        </ul>
                     </div>
                     <span class="pficon pficon-info pull-left"></span>
                     <div class="drawer-pf-notification-content">
                        <span class="drawer-pf-notification-message">titulo_mensagem</span>
                        <div class="drawer-pf-notification-info">
                           <span class="date">data_mensagem</span>
                        </div>
                     </div>
                  </div>`;
    var eventoHTML = `<div id="event_nome_evento" class="panel panel-default">
            <div class="panel-heading" data-component="collapse-heading">
               <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#notification-drawer-accordion" href="#fixedCollapsenome_evento">
                  nome_evento
                  </a>
               </h4>
               <span class="panel-counter novas_mensagens">Novas mensagens</span>
            </div>
            <div id="fixedCollapsenome_evento" class="panel-collapse collapse in">
               <div class="panel-body todas_mensagens">
                  
               </div>
               <div class="blank-slate-pf hidden">
                  <div class="blank-slate-pf-icon">
                     <span class="pficon-info"></span>
                  </div>
                  <h1>Sem Mensagens.</h1>
               </div>
               <div class="drawer-pf-action">
                  <div onclick="removerTodas('nome_evento')" style="text-align: center;" class="drawer-pf-action-link" data-toggle="clear-all">
                     <button class="btn btn-link">
                     <span class="pficon pficon-close"></span> Remover Todas
                     </button>
                  </div>
               </div>
            </div>
         </div>`;
    String.prototype.replaceAll = function(search, replacement) {
        var target = this;
        return target.replace(new RegExp(search, 'g'), replacement);
    };
    function novasMensagens(msgs){
        msg = parseInt(msgs.length);
        setTimeout(() => {
            var eventosT = [];
            msgs.forEach((msg) => {
                if(eventosT[msg.evento_nome] == undefined){
                    eventosT[msg.evento_nome] = [];
                    eventos[msg.evento_nome] = [];
                }
                eventosT[msg.evento_nome].push(msg);
                eventos[msg.evento_nome].push(msg.id_mensagem);
            })
            var notificacoes = [];
            Object.keys(eventosT).forEach((evento_nome) => {
                notificacoes.push({
                    nome: evento_nome,
                    mensagens: eventosT[evento_nome]
                });
            });
            notificacoes.forEach((notificacao) => {
               var nome = notificacao.nome.toLowerCase().replace(/[^a-zA-z]/g, '');
               nome = nome.replaceAll(' ', '');
               nome = nome.toLowerCase();
               var novas_mensagens = [];
               notificacao.mensagens.forEach((mensagem) => {
                   if($('div[mensagem-id="'+mensagem.id_mensagem+'"]').length == 0){
                       novas_mensagens.push(mensagem);
                   }
               });
               if($('#event_' + nome).length == 0){
                   var eHTML = eventoHTML + '';
                   eHTML = eHTML.replaceAll('event_nome_evento', 'event_'+nome+'');
                   eHTML = eHTML.replaceAll('fixedCollapsenome_evento', 'fixedCollapse'+nome+'');
                   eHTML = eHTML.replaceAll('nome_evento', notificacao.nome);
                   $('.note-page .eventos').prepend(eHTML);
               }
               if($('#event_' + nome).length > 0){
                   var mHTML = '';
                   novas_mensagens.forEach((mensagem) => {
                       var ihtml = mensagemHTML + '';
                       ihtml = ihtml.replaceAll('foilida', mensagem.lida == 'sim' ? '' : 'unread')
                       ihtml = ihtml.replaceAll('id_mensagem', mensagem.id_mensagem);
                       ihtml = ihtml.replaceAll('titulo_mensagem', mensagem.titulo);
                       ihtml = ihtml.replaceAll('texto_mensagem', mensagem.mensagem);
                       ihtml = ihtml.replaceAll('data_mensagem', mensagem.data);
                       mHTML += ihtml + '\n';
                   });
                   $('#event_' + nome + ' .todas_mensagens').prepend(mHTML);
               }
               if(novas_mensagens.length > 0){
                   var oldNotRead = 0;
                   $('div[mensagem-id][class*="unread"]').each(function (){
                    	var found = false;
                    	var mid = $(this).attr('mensagem-id');
                    	novas_mensagens.forEach((mensagem) => {
                    	    if(mensagem.id_mensagem == mid)
                    	        found = true;
                    	});
                    	if(!found)
                    	    oldNotRead++;
                   });
                   var total_not_read = oldNotRead + novas_mensagens.length;
                   $('#event_' + nome + ' .novas_mensagens').text(total_not_read + ' Novas mensagens');
               }
            });
            reload(); reload();
            swal("Nova mensagem", " Você recebeu uma nova mensagem!", "success");
            document.getElementById("new_msg").play();
        }, 2000);
    }
    setInterval(function(){
        $.get('/controles/verificar-mensagens.php', function(msgs){
            msgs = JSON.parse(msgs);
            if(parseInt(msgs.length) > msg)
                novasMensagens(msgs);
        });
    }, 1000);
    $.get('/controles/verificar-mensagens.php', function(msgs){
        msgs = JSON.parse(msgs);
        var naolida = 0;
        msgs.forEach((msg) => {
            if(msg.lida !== 'sim')
                naolida++;
                
            if(naolida > 0){
                reload(); reload();
                swal("Nova mensagem", " Você recebeu uma nova mensagem!", "success");
            }
        })
    });
  </script>
  <div class="DashboardContainer"></div>

 <script src="uppy.io.js"></script>
 
  <main class="page-content">
    <div class="container-fluid">
