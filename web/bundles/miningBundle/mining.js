$(function () {
    var btnMining = $('#btn-mining');
    var timeline = $('ul#ul-timeline');
    var data = new Date();
    var hora = data.getHours() + ':' + data.getMinutes() + ':' + data.getSeconds();
    var quantidadePagina = 0;

    btnMining.click(function () {
        var $btn = $(this).button('loading')
        getPaginasQuantidade();
        $btn.button('reset')

    });//click


    // 00 - capturar a quantidade de paginas 
    function getPaginasQuantidade() {
        var inicio = data.getMilliseconds();
        $.ajax({
            type: 'POST',
            url: "/api/mfrural/get/paginas/quantidade",
            data: {termos:['milho','soja','feijao']},
            dataType: 'json',
            beforeSend: function () {
                $('h1#h1-timeline').html('Timeline <small class="text-info" >carregando...</small>');
            },

            success: function (retorno) {
                //Handle your JSON data to update the DOM
                $.each(retorno, function (index, element) {
                    retorno = retorno.retorno;
                    quantidadePagina = retorno.quantidade;//quantidade de paginas
                    timelineAdd(retorno.tipo, retorno.detalhes, retorno.tempo, retorno.hora);
                    getPaginasLista();
                }); //each
            } //success
        }); //ajax

    }

    // 01 - capturar titulo e link de todos os anuncios
    function getPaginasLista() {
        if (quantidadePagina > 0) {
            $.ajax({
                type: 'POST',
                url: "/api/mfrural/get/paginas/listas/" + quantidadePagina,
                dataType: 'json',
                beforeSend: function () {
                    $('h1#h1-timeline').html('Timeline <small class="text-info" >carregando...</small>');
                    timelineAdd('analizando', 'Verificando a quantidade de anuncios a serem capturados.', '0 s', hora);
                },

                success: function (retorno) {
                    $.each(retorno, function (index, element) {
                        retorno = retorno.retorno;
                        quantidadePagina = retorno.quantidade;//quantidade de paginas
                        timelineAdd(retorno.tipo, retorno.detalhes, retorno.tempo, retorno.hora);
                        getPaginasDetalhes();
                    }); //each
                } //success
            }); //ajax

        } else {
            timelineAdd('falha', 'Não foi possivel obter a quantidade de páginas, tente novamente mais tarde.', '0 s', hora);
        }
    }
    // 02 - capturar e salvar detalhes de cada página
    function getPaginasDetalhes(){
         $.ajax({
                type: 'POST',
                url: "/api/mfrural/get/paginas/detalhes",
                dataType: 'json',
                beforeSend: function () {
                    $('h1#h1-timeline').html('Timeline <small class="text-info" >carregando...</small>');
                    timelineAdd('capturando dados', 'Iniciando a captura dos dados, esta ira demorar alguns minutos', '0 s', hora);

                },

                success: function (retorno) {
                    $.each(retorno, function (index, element) {
                        retorno = retorno.retorno;
                        timelineAdd(retorno.tipo, retorno.detalhes, retorno.tempo, retorno.hora);

                    }); //each
                } //success
            }); //ajax
    }




    //escrever na timeline
    function timelineAdd(tipo, detalhes, tempoGasto, hora) {
        var tmp = parseFloat(tempoGasto).toFixed(2);
        tempoGasto = tmp;
        var alt = '';
        var horafloat =''; 
        if (timeline.hasClass('esq')) {
            alt = '';
            horafloat='style="float:right"';
            timeline.addClass('dir').removeClass('esq');
        } else {
            horafloat='';
            alt = 'opposite-side';
            timeline.addClass('esq').removeClass('dir');
        }

        $('h1#h1-timeline').text('Timeline');

        timeline.html(
            timeline.html()
            + ' <li id="li-timeline" class="esq '+alt+' ">'
            + '<div class="border-line"></div><div class="timeline-description"><p>'
            + '<span>'
            + '<small '+horafloat+'>'+hora+'</small><br><strong>' + tipo + '</strong></br>'
            + '<span class="type">' + detalhes + '</span>'
            + '<br><small>tempo gasto '
            + '<strong>' + tempoGasto + '</strong>'
            + '</small>'
            + '</span>'
            + '</div></li>' 
        ); //append
    }
});
