<script>
    $(document).ready(function() {
    $(".pesquisar").keyup(function () {
        var termoBusca = $(".pesquisar").val();
        var listaItem = $('#conteudo').children('tr');
        var splitPesquisa = termoBusca.replace(/ /g, "'):containsi('");
        
        $.extend($.expr[':'], {
            'containsi': function(elem, i, match, array){
                return (elem.innerText.replace("Editar","").replace("Remover","") || '').toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
            }
        });
        $("#conteudo tr").not(":containsi('" + splitPesquisa + "')").each(function(e){
            $(this).attr('visible','false');
        });
        $("#conteudo tr:containsi('" + splitPesquisa + "')").each(function(e){
            $(this).attr('visible','true');
        });
        var nAchados = $('#conteudo tr[visible="true"]').length;
        if(nAchados == '0') {
            $('.semresultado').show();
            $('.nomecol').hide();
        } else {
            $('.semresultado').hide();
            $('.nomecol').show();
        }  
    });
  });

    function resultado(data) {
        $("#textoAlerta").text(data);
        $("#alerta").modal();
    }
</script>