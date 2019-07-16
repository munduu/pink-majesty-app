function checkout_ccard(agenda){
    $.post("http://igestaoweb.com.br/pinkmajesty/app_new/php/getCard.php", { agenda: agenda, token: "H424715433852" },
    function (result) {
        console.log(result);
        if(result.success == true){
            identifica_bandeira(agenda, result.lNcartao, result.lNmcartao, result.lMesVenc, result.lAnoVenc, result.lCodigoSeg);
        }
    },'json');
}

function checkOut(cardToken, brand, agenda,lNmcartao,lCodigoSeg) {
    $.post("http://igestaoweb.com.br/pinkmajesty/app_new/php/cielo_app/createPaymentCreditCardWithToken.php", 
        {
            MerchantOrderId: agenda,
            Name: lNmcartao,
            Amount: "100",
            CardToken: cardToken,
            SecurityCode: lCodigoSeg,
            Brand: brand
        },

    function (resultCard) {
        console.log(resultCard);

        if(resultCard.payment.returnCode == '00'){
            alert(resultCard.payment.returnMessage);
            $("#cod_pagseguro").val(resultCard.payment.paymentId)
            setConfirmar_pedido(agenda, 'PEDIDO', 'Cliente');
            $('.loader').show();
            setTimeout(function(){ getListar_meusPedidos(); $('.loader').hide(); }, 10000);
            activate_page("#meusPedidos");
        } else {
            alert(resultCard.payment.returnMessage);
            $('.btn_troca_cartao[alt='+agenda+']').show();
            $('.btn_pagamento[alt='+agenda+']').hide();
            return false;
        }
    },'json');
}

function end_Card(agenda, lNcartao,lNmcartao,lMesVenc,lAnoVenc,lCodigoSeg, brand) {

    $.post("http://igestaoweb.com.br/pinkmajesty/app_new/php/cielo_app/creatCardToken.php", 
    {
        Name: lNmcartao,
        CardNumber: lNcartao,
        Holder: lNmcartao,
        ExpirationDate: lMesVenc+"/"+lAnoVenc,
        SecurityCode: lCodigoSeg,
        Brand: brand
    },

    function (result) {
        console.log(result);

        if(result.cardToken){
            checkOut(result.cardToken, brand, agenda,lNmcartao,lCodigoSeg);
            console.log(result.cardToken)
            setConfirmar_pedido(agenda, 'AGENDADO', '');
        }
    },'json');
}

function identifica_bandeira(agenda, lNcartao,lNmcartao,lMesVenc,lAnoVenc,lCodigoSeg){
    $.ajax({
      type:"POST", dataType:"json", cache: false, url: "http://igestaoweb.com.br/pinkmajesty/function/identifica_bandeira.php",
      data:{cartao:lNcartao},
      timeout: 200000, 
      beforeSend: function(resultado){ $('.loader').show();},
      success: function(resultado){
        $('.loader').hide();  
        $('#brandcard').val(resultado.sucesso.bandeira);

        console.log(resultado);
        
        end_Card(agenda, lNcartao,lNmcartao,lMesVenc,lAnoVenc,lCodigoSeg,resultado.sucesso.bandeira);

        if(resultado.sucesso.bandeira==''){
            alert('Campo obrigatório vazio: BANDEIRA');
            $('.btn_troca_cartao[alt='+agenda+']').show();
            $('.btn_pagamento[alt='+agenda+']').hide();
            return false;
        }
      },
      error: function(resultado) {
        $('.loader').hide();
        console.log('error');
      }     
    }); 
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

function getCookie(cname) {
    return localStorage.getItem(cname);
}

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