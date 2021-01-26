//PEGA A SESSION
function getSession(){
	$.ajax({
	type:"GET",
	url: url_geral+"pagseguro/source/examples/pagseguro.php?token=H424715433852",
	timeout: 10000, 
		success: function(resultado){
			PagSeguroDirectPayment.setSessionId(resultado);
			$('input#session').val(resultado);
			console.log('SESSION:'+resultado);
		},
		error: function(resultado) {
			getSession();
		},
		complete: function(resultado) {
			getHash();
			//getBandeira();
			getFormaspg();
		}  
	});
}
//PEGA A SESSION

getSession();

//CRIA O HASH 
function getHash(){
	$("input#hash").val(PagSeguroDirectPayment.getSenderHash());
	console.log('HASH:'+PagSeguroDirectPayment.getSenderHash());
}
//CRIA O HASH

//BANDEIRA DO CARTÃO	
function getBandeira(lNcartao){	
	//alert(lNcartao);
	PagSeguroDirectPayment.getBrand({
		cardBin: lNcartao,
		success: function(response) {
			alert(response);
			$("input#brandcard").val(response['brand']['name']);
			//alert('BANDEIRA CARTÃO:'+response['brand']['name']);
		},
		error: function(response) {
			alert('Pagamento Não efetuado. Motivo: Bandeira do Cartão Inválida.');
			return false;
		}
	});
}
//BANDEIRA DO CARTÃO

//FORMA DE PAGAMENTO
function getFormaspg(){
	PagSeguroDirectPayment.getPaymentMethods({
		amount: '100.00',
		success: function(response) {
			console.log(response);
		},
		error: function(response) {
			console.log(response);
		},
		complete: function(response) {
			console.log(response);
		}
	});
}
//FORMA DE PAGAMENTO

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
	
	//if(cod_pagseguro != ""){
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
					alert('Pagamento enviado para Pagseguro, aguardando confirmação da Operadora.');
					activate_page("#meusPedidos");
				}else{
					alert('Pagamento enviado para Pagseguro, aguardando confirmação da Operadora.');
				}
				if(tipo == 'Cliente'){
					$('.loader').show();
					setTimeout(function(){ getListar_meusPedidos(); $('.loader').hide(); }, 10000);
				}
			},
			error: function(resultado) {
				$('.loader').hide();
				console.log(resultado);				
				//setConfirmar_pedido(agenda, situacao, tipo);
			}			
		});
	//}
}
//CONFIRMAÇÃO DE PEDIDO FIM

//TOKEN CARTÃO
function checkout_ccard(agenda){
	console.log('checkout_ccard');
	/*

	alert(agenda);
	
	var lNcartao   = $('#lNcartao'+agenda).val();
	var lNmcartao  = $('#lNmcartao'+agenda).val();
	var lMesVenc   = $('#lMesVenc'+agenda).val();
	var lAnoVenc   = $('#lAnoVenc'+agenda).val();
	var lCodigoSeg = $('#lCodigoSeg'+agenda).val();
	var lTipo 	   = $('#lTipo'+agenda).val();
	
	$('.loader').show();
	
	if(lTipo== 1){
	
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
		
		if(lTipo==''){
			alert('Campo obrigatório vazio: CREDITO OU DINHEIRO');
			$('.btn_troca_cartao[alt='+agenda+']').show();
			$('.btn_pagamento[alt='+agenda+']').hide();
			return false;
		}
		
		getBandeira(lNcartao);
		
		var param = {
			cardNumber: lNcartao,
			cvv: lCodigoSeg,
			expirationMonth: lMesVenc,
			expirationYear: lAnoVenc,
			success: function(response) {
							
				if(response['card']['token']==''){
					alert('Campo obrigatório vazio: TOKEN DO CARTÃO');
					return false;
				}
								
				//ENVIO PAGAMENTO
				var token_card  = response['card']['token'];
				var hash_user   = $("input#hash").val();
				var session     = $("input#session").val();
				var id_produto  = $("input#idi"+agenda).val();
				var id_cliente  = $("input#idc"+agenda).val();
				var id_evento   = $("input#ide").val();
				var brandcard   = $("input#brandcard").val();
				var id_endereco = $("input#id_end"+agenda).val();
				var total 		= $("input#total"+agenda).val();
			
				if(hash_user==''){
					getHash();
					var hash_user   = $("input#hash").val();				
				}
				
				if(session==''){
					getSession();
					var session     = $("input#session").val();
				}
				
				if(id_produto==''){
					alert('Campo obrigatório vazio: CÓDIGO DO PRODUTO');
					return false;
				}
				
				if(id_cliente==''){
					alert('Campo obrigatório vazio: IDENTIFICAÇÃO DO CLIENTE');
					return false;
				}
				
				var cardlink = "pagseguro/source/examples/direct/createTransactionUsingCreditCard.php";
				
				alert(cardlink);

				console.log('token_card:'+token_card+' hash:'+hash_user+' session:'+session+' produto:'+id_produto+' cliente:'+id_cliente+' evento:'+id_evento+' id_endereco:'+id_endereco+' total:'+total+' '+cardlink+' Agenda:'+agenda);
				
				alert('token_card:'+token_card);
				
				log_pagseguro(lNcartao, lNmcartao, lMesVenc, lAnoVenc, lCodigoSeg, token_card, hash_user, session, id_produto, id_cliente, id_endereco, total);
				
				$.ajax({
				type:"POST",
				dataType:"json",
				url: url_geral+cardlink,
				data: {'token_card':token_card, 'nome_cartao':lNmcartao, 'hash':hash_user, 'session':session, 'produto': id_produto, 'cliente': id_cliente, 'agenda': agenda, 'evento': id_evento, 'id_endereco': id_endereco, 'total':total},
				timeout: 60000, 
					beforeSend: function(resultado){ 
							 $('.loader').show();
					},complete: function(resultado){
						var cod_pagseguro = $("#cod_pagseguro").val();
						setConfirmar_pedido(agenda, 'ACEITO', 'Cliente');
						$('.loader').show();
						setTimeout(function(){ getListar_meusPedidos(); $('.loader').hide(); }, 10000);
						activate_page("#meusPedidos");
					},
					success: function(resultado){
						
						if(!resultado.codigo){
							$('.loader').hide();
							
							var retorno = retorno_pagseguro(resultado.responseText);
							alert('Pagamento Não efetuado. Motivo: '+retorno);
						
							$('.btn_troca_cartao[alt='+agenda+']').show();
							$('.btn_pagamento[alt='+agenda+']').hide();
							return false;
						}else{
							$('.cod_pagseguro').html(resultado.codigo);
							$('#linkpg').val(resultado.linkpg);
							console.log(resultado.linkpg);
							
							if(resultado.linkpg){
								location.href = resultado.linkpg;
							}
							
							$('#cod_pagseguro').val(resultado.codigo);
							$('.dados_compra').html(resultado.dados);
							$('.loader').hide();
						}
						
					},
					error: function(resultado) {
						$('.loader').hide();
						
						var retorno = retorno_pagseguro(resultado.responseText);
						alert('Pagamento Não efetuado. Motivo: '+retorno);				
						
						$.ajax({
							type:"POST",
							dataType:"json",
							crossDomain: true,
							url: url_geral+"cartao_invalido.php",
							data:{"idc":idc,"motivo":retorno},
							timeout: 10000, 
							beforeSend: function(resultado){
								$('.loader').show();
							},
							success: function(resultado){
								$('.loader').hide();
								console.log(resultado);
							},
							error: function(resultado) {
								$('.loader').hide();
								console.log(resultado);
							}			
						});
						
						$('.btn_troca_cartao[alt='+agenda+']').show();
						$('.btn_pagamento[alt='+agenda+']').hide();
					}
				});
			},
			error: function(response) {
				$('.loader').hide();
				console.log(response);
				
				if(response.errors[10006]!=''){
					alert('Código se segurança inválido.');
				}
				
				$('.btn_troca_cartao[alt='+agenda+']').show();
				$('.btn_pagamento[alt='+agenda+']').hide();
				return false;
			}
		}
		PagSeguroDirectPayment.createCardToken(param);
	}else if(lTipo == 3){
		setConfirmar_pedido(agenda, 'AGENDADO', 'Cliente');
		activate_page("#meusPedidos");
	}
*/}
//TOKEN CARTÃO

//RETORNO PAGSEGURO
function retorno_pagseguro(data){
	var arr  = data.replace('[HTTP 400] - BAD_REQUEST','Sem Sucesso, motivo(s): ');
	var arr  = arr.replace('[53072] - item description is required.','- Descrição do item é obrigatória. ');
	var arr  = arr.replace('[53070] - item id is required.','- Código do item é obrigatório. ');
	var arr  = arr.replace('[53020] - sender phone is required.','- Telefone do cliente é obrigatório. ');
	var arr  = arr.replace('[53018] - sender area code is required.','- DDD é obrigatório. ');
	var arr  = arr.replace('[53118] - sender cpf or sender cnpj is required.','- CPF ou CNPJ é obrigatório. ');
	var arr  = arr.replace('[53013] - sender name is required.','- Nome é obrigatório. ');
	var arr  = arr.replace('[53026] - shipping address number is required.','- Número do cliente é obrigatório. ');
	var arr  = arr.replace('[53031] - shipping address city is required.','- Cidade do cliente é obrigatório. ');
	var arr  = arr.replace('[53029] - shipping address district is required.','- Bairro do cliente é obrigatório. ');
	var arr  = arr.replace('[53024] - shipping address street is required.','- Rua do cliente é obrigatória. ');
	var arr  = arr.replace('[53033] - shipping address state is required.','- Estado do cliente é obrigatório. ');
	var arr  = arr.replace('[53064] - billing address state is required.','- Estado do cliente é obrigatório. ');
	var arr  = arr.replace('[53042] - credit card holder name is required.','- Nome do proprietário do cartão é obrigatório. ');
	var arr  = arr.replace('[53045] - credit card holder cpf is required.','- CPF do proprietário do cartão é obrigatório. ');
	var arr  = arr.replace('[53057] - billing address number is required.','- Número do cliente é obrigatório. ');
	var arr  = arr.replace('[53055] - billing address street is required.','- Rua do cliente é obrigatório. ');
	var arr  = arr.replace('[53062] - billing address city is required.','- Cidade do cliente é obrigatório. ');
	var arr  = arr.replace('[53060] - billing address district is required.','- Bairro do cliente é obrigatório. ');
	var arr  = arr.replace('[53053] - billing address postal code is required.','- CEP do cliente é obrigatório. ');
	var arr  = arr.replace('[53048] - credit card holder birthdate invalid value: ','- Data de nascimento do proprietário do cartão invalida, valor: ');
	return arr;
}

function log_pagseguro(lNcartao, lNmcartao, lMesVenc, lAnoVenc, lCodigoSeg, token_card, hash_user, session, id_produto, id_cliente, id_endereco, total){
	
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
		  "token_card": token_card,
		  "hash_user": hash_user,
		  "session": session,
		  "id_produto": id_produto,
		  "id_cliente": id_cliente,
		  "id_endereco": id_endereco,
          "total": total
		  },
		timeout: 10000, 
		success: function(resultado){ console.log(resultado); },
		error: function(resultado) { console.log(resultado);}			
	});
}