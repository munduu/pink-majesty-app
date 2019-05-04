function checkout_ccard(agenda){

    var lNcartao   = $('#lNcartao'+agenda).val();
	var lNmcartao  = $('#lNmcartao'+agenda).val();
	var lMesVenc   = $('#lMesVenc'+agenda).val();
	var lAnoVenc   = $('#lAnoVenc'+agenda).val();
    var lCodigoSeg = $('#lCodigoSeg'+agenda).val();
    
    if(lNcartao==''){
        alert('Campo obrigatório vazio: NÚMERO DO CARTÃO');
        $('.btn_troca_cartao[alt='+agenda+']').show();
        $('.btn_pagamento[alt='+agenda+']').hide();
        return false;
    }
    
    if(lNmcartao==''){
        alert('Campo obrigatório vazio: NOME COMPLETO DO TITULAR DO CARTÃO');
        $('.btn_troca_cartao[alt='+agenda+']').show();
        $('.btn_pagamento[alt='+agenda+']').hide();
        return false;
    }
    
    if(lMesVenc==''){
        alert('Campo obrigatório vazio: MÊS VENCIMENTO DO CARTÃO');
        $('.btn_troca_cartao[alt='+agenda+']').show();
        $('.btn_pagamento[alt='+agenda+']').hide();
        return false;
    }
    
    if(lAnoVenc==''){
        alert('Campo obrigatório vazio: ANO VENCIMENTO DO CARTÃO');
        $('.btn_troca_cartao[alt='+agenda+']').show();
        $('.btn_pagamento[alt='+agenda+']').hide();
        return false;
    }
    
    if(lAnoVenc.length<4){
        alert('Campo obrigatório vazio: ANO VENCIMENTO DO CARTÃO DEVE CONTAR 4 DIGITOS');
        $('.btn_troca_cartao[alt='+agenda+']').show();
        $('.btn_pagamento[alt='+agenda+']').hide();
        return false;
    }
    
    if(lCodigoSeg==''){
        alert('Campo obrigatório vazio: CÓDIGO SEGURANÇA DO CARTÃO');
        $('.btn_troca_cartao[alt='+agenda+']').show();
        $('.btn_pagamento[alt='+agenda+']').hide();
        return false;
    }

    $.post("http://igestaoweb.com.br/pinkmajesty/app_new/php/cielo_app/creatCardToken.php", 
    {
        Name: lNmcartao,
        CardNumber: lNcartao,
        Holder: lNmcartao,
        ExpirationDate: lMesVenc+"/"+lAnoVenc,
        SecurityCode: lCodigoSeg,
        Brand: "Visa"
    },

    function (result) {
        console.log(result);

        if(result.cardToken){
            checkOut(result.cardToken);
            console.log(result.cardToken)

            log_pagseguro(lNcartao, lNmcartao, lMesVenc, lAnoVenc, lCodigoSeg, result.cardToken);
        }
    },'json');
}

function checkOut(cardToken, agenda) {
    $.post("http://igestaoweb.com.br/pinkmajesty/app_new/php/cielo_app/createPaymentCreditCardWithToken.php", 
        {
            MerchantOrderId: "2014111706",
            Name: lNmcartao,
            Amount: "200",
            CardToken: cardToken,
            SecurityCode: lCodigoSeg,
            Brand: "Visa"
        },

    function (resultCard) {
        console.log(resultCard);

        if(resultCard.returnCode == '00'){
            setConfirmar_pedido(agenda, 'AGENDADO', 'Cliente');
            $('.loader').show();
            setTimeout(function(){ getListar_meusPedidos(); $('.loader').hide(); }, 10000);
            activate_page("#meusPedidos");
        } else {
            alert(resultCard.returnMessage);
            $('.btn_troca_cartao[alt='+agenda+']').show();
            $('.btn_pagamento[alt='+agenda+']').hide();
            return false;
        }
    },'json');
}

//LISTAR PEDIDO INICIO
function getListar_meusPedidos(){
		
    var user    = getCookie("id_cliente");
    
    $.ajax({
        type:"POST",
        url:url_geral+"lista_pedido.php",
        data:{"token":"H424715433852", "user":user, "bsc":"1"},
        timeout: 100000,
            beforeSend: function(resultado){ 
                $('.loader').show();
            },
            success: function(resultado){
                $('.loader').hide();
                $(".lista_pedido").html(resultado);
            },
        error:function(resultado){
            $('.loader').hide();
            getListar_meusPedidos();
        }
    });
    $.ajax({
        type:"POST",
        url:url_geral+"lista_pedido.php",
        data:{"token":"H424715433852", "user":user, "bsc":"2"},
        timeout: 100000,
            beforeSend: function(resultado){ 
                $('.loader').show();
            },
            success: function(resultado){
                $('.loader').hide();
                $(".lista_pedido2").html(resultado);
            },
        error:function(resultado){
            $('.loader').hide();
            getListar_meusPedidos();
        }
    });
    $.ajax({
        type:"POST",
        url:url_geral+"lista_pedido.php",
        data:{"token":"H424715433852", "user":user, "bsc":"3"},
        timeout: 100000,
            beforeSend: function(resultado){ 
                $('.loader').show();
            },
            success: function(resultado){
                $('.loader').hide();
                $(".lista_pedido3").html(resultado);
            },
        error:function(resultado){
            $('.loader').hide();
            getListar_meusPedidos();
        }
    });
}
//LISTAR PEDIDO FINAL

//CONFIRMAÇÃO DE PEDIDO INICIO
function setConfirmar_pedido(agenda, situacao, tipo){	

var user   			= getCookie('id_cliente');
var agenda			= agenda;
var cod_pagseguro	= $("#cod_pagseguro").val();

    if(user==""){
        var user = $("#idc"+agenda).val();
    }

    $.ajax({
        type:"POST",
        dataType:"json",
        crossDomain: true,
        url: url_geral+"confirmar_pedido.php",
        data:{"user":user, "agenda":agenda, "situacao":situacao, "tipo":tipo, "cod_pagseguro":cod_pagseguro, "token":"H424715433852"},
        timeout: 100000, 
        beforeSend: function(resultado){
            $('.loader').show();
        },
        success: function(resultado){
            $('.loader').hide();
            
            console.log(resultado);
            
            if(resultado.erro==2){
                alert('Pagamento processado, aguardando confirmação da Operadora.');
                activate_page("#meusPedidos");
            }else{
                alert('Pagamento processado, aguardando confirmação da Operadora.');
            }
            if(tipo == 'Cliente'){
                $('.loader').show();
                setTimeout(function(){ getListar_meusPedidos(); $('.loader').hide(); }, 10000);
            }
        },
        error: function(resultado) {
            $('.loader').hide();
            console.log(resultado);				
            setConfirmar_pedido(agenda, situacao, tipo);
        }			
    });
}
//CONFIRMAÇÃO DE PEDIDO FIM

function log_pagseguro(lNcartao, lNmcartao, lMesVenc, lAnoVenc, lCodigoSeg, token_card){
	$.ajax({
		type:"POST",
		dataType:"json",
		crossDomain: true,
		url: url_geral+"log_pagseguro.php",
		data:{"lNcartao": lNcartao,
		  "lNmcartao": lNmcartao,
		  "lMesVenc": lMesVenc,
		  "lAnoVenc": lAnoVenc,
		  "lCodigoSeg": lCodigoSeg,
		  "token_card": token_card
		  },
		timeout: 10000, 
		success: function(resultado){ console.log(resultado);},
		error: function(resultado) { console.log(resultado);}			
	});
}