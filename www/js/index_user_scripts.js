/*jshint browser:true */
/*global $ */(function () {
	"use strict";
	/*
	  hook up event handlers 
	*/
	function register_event_handlers() {
		/* COLOQUE AS FUN��ES INTERNAS AQUI */

		//Fun��es do COOKIE INICIO
		function setCookie(cname, cvalue) {
			localStorage.setItem(cname, cvalue);
		}
		function getCookie(cname) {
			return localStorage.getItem(cname);
		}
		//Fun��es do COOKIE FINAL 

		$(".menu_inferior").hide();

		//VERIFICA SE JA ESTA LOGADO INICIO
		function getVer_Login() {

			var token_id = $("#token_id").val();

			if (getCookie("id_cliente")) {

				$.ajax({
					type: "POST",
					dataType: "json",
					async: true,
					crossDomain: true,
					url: url_geral + "login.php",
					data: { "c_acesso": getCookie("id_cliente"), "token_id": token_id, "token": "H424715433852" },
					timeout: 100000,
					beforeSend: function (resultado) {
						$('.loader').show();
					},
					success: function (resultado) {
						$('.loader').hide();
						if (resultado.erro == 1) {
							var id_cliente = getCookie('id_cliente');
							if (getCookie("tipo") == 'Cliente') {
								$(".menu_colab").hide();
								$(".listar_categorias_").show();
								$(".menu_inferior").show();
								getListar_categorias();
							} else if (getCookie("tipo") == 'Profissional') {
								$(".menu_inferior").hide();
								$(".listar_categorias_").hide();
								$(".menu_colab").show();
							}
							$(".nome_user").html(resultado.nome);
							$(".n_cidadeSel").html('Uberaba');
							activate_page("#principal");
						} else {
							activate_page("#mainpage");
						}
					},
					error: function (resultado) {
						$('.loader').hide();
						alert('Não foi poss�vel acessar! #001');
						getVer_Login();
					}
				});

			} else {
				$('.loader').hide();
				$(".menu_inferior").hide();
				activate_page("#mainpage");
				console.log('Cheguemos!');
			}

		}

		//VERIFICA SE JA ESTA LOGADO FIM

		function getVer_Login_off() {
			if (getCookie("tipo") == 'Cliente') {
				activate_page("#principal");
				$(".menu_colab").hide();
				$(".menu_inferior").show();
				$(".listar_categorias_").show();
				getListar_categorias();
			} else if (getCookie("tipo") == 'Profissional') {
				activate_page("#principal");
				$(".menu_inferior").hide();
				$(".listar_categorias_").hide();
				$(".menu_colab").show();
				$('.loader').hide();
			} else {
				activate_page("#principal");
				$(".menu_colab").hide();
				$(".menu_inferior").show();
				$(".listar_categorias_").show();
				getListar_categorias();
			}
		}

		getVer_Login_off();

		//LOGIN INICIO
		function getLogin(login_email, login_senha, token_id) {

			var login_email = login_email;
			var login_senha = login_senha;
			var token_id = token_id;

			if (login_email == '') {
				alert('Campo obrigatõrio vazio: E-mail');
				return false;
			}
			if (login_senha == '') {
				alert('Campo obrigatõrio vazio: Senha');
				return false;
			}
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "login.php",
				data: { "login_email": login_email, "login_senha": login_senha, "token_id": token_id, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
					$(".esquerda").hide();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 1) {
						setCookie('id_cliente', resultado.id_cliente);
						setCookie('tipo', resultado.tipo);
						var id_cliente = getCookie('id_cliente');

						if (getCookie("tipo") == 'Cliente') {
							//$(".esquerda").show();
							$(".menu_colab").hide();
							$(".listar_categorias_").show();
							$(".menu_inferior").show();
						} else if (getCookie("tipo") == 'Profissional') {
							//$(".esquerda").hide();
							$(".menu_inferior").hide();
							$(".listar_categorias_").hide();
							$(".menu_colab").show();
						}
						$(".nome_user").html(resultado.nome);
						$(".n_cidadeSel").html('Uberaba');
						activate_page("#principal");
					} else {
						alert(resultado.dados);
						activate_page("#mainpage");
					}

				},
				error: function (resultado) {
					$('.loader').hide();
					getLogin(login_email, login_senha, token_id);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					activate_page("#mainpage");
				}
			});
		}
		//LOGIN FINAL

		//VERIFICA��O DE SOLICITACAO INICIO
		function getVer_solicitacao() {
			var id_user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "verificar_solicitacao.php",
				data: { "user": id_user, "token": "H424715433852" },
				timeout: 100000,
				success: function (resultado) {

					if (resultado.chat > 0) {
						$('.nova_conversa').show();
						//$('.som').html("<audio src='"+url_geral+"alerta.mp3' preload='auto' autoplay></audio>");
					} else {
						$('.nova_conversa').hide();
					}
					if (resultado.erro == 2) {
						if (resultado.tipo == 'Profissional') {
							$('.novo_pedido').show();
							//navigator.notification.alert(resultado.dados, '', 'Parabéns', 'OK'); 
						}
						if (resultado.tipo == 'Cliente') {
							$('.novo_pedido2').show();
							//navigator.notification.alert(resultado.dados, '', 'Aten�ão', 'OK'); 
						}
						//$('.som').html("<audio src='"+url_geral+"alerta.mp3' preload='auto' autoplay></audio>");*/
					} else {
						if (resultado.tipo == 'Profissional') {
							$('.novo_pedido').hide();
						}
						if (resultado.tipo == 'Cliente') {
							$('.novo_pedido2').hide();
						}
						//$('.som').html("");
					}
					//$(".listar_servicos").html(resultado);
				},
				error: function (resultado) {
					//$('.loader').hide();
					getListar_servicos(servico);
				}
			});
		}
		getVer_solicitacao();
		setInterval(function () {
			var id_user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				dataType: "json",
				url: url_geral + "verificar_solicitacao.php",
				data: { "user": id_user, "token": "H424715433852" },
				timeout: 100000,
				success: function (resultado) {

					if (resultado.chat > 0) {
						$('.nova_conversa').show();
						//$('.som').html("<audio src='"+url_geral+"alerta.mp3' preload='auto' autoplay></audio>");
					} else {
						$('.nova_conversa').hide();
					}
					if (resultado.erro == 2) {
						if (resultado.tipo == 'Profissional') {
							$('.novo_pedido').show();
							//navigator.notification.alert(resultado.dados, '', 'Parabéns', 'OK'); 
						}
						if (resultado.tipo == 'Cliente') {
							$('.novo_pedido2').show();
							//navigator.notification.alert(resultado.dados, '', 'Aten�ão', 'OK'); 
						}
						$('.som').html("<audio src='" + url_geral + "alerta.mp3' preload='auto' autoplay></audio>");
					} else {
						if (resultado.tipo == 'Profissional') {
							$('.novo_pedido').hide();
						}
						if (resultado.tipo == 'Cliente') {
							$('.novo_pedido2').hide();
						}
						$('.som').html("");
					}
					//$(".listar_servicos").html(resultado);
					//console.log('aki: '+resultado)
				},
				error: function (resultado) {
					$('.loader').hide();
					//getListar_servicos(servico);
				}
			});
			//navigator.notification.alert('Hello', 'CADASTRAR', 'Error', 'OK'); 
		}, 10000);//10 segundos
		//VERIFICA��O DE SOLICITACAO FIM

		//LISTAR CATEGORIA INICIO
		function getListar_categorias() {

			$(".swiper-wrapper").empty();

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_categorias.php",
				data: { "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".swiper-wrapper").html('');
					$(".swiper-wrapper").append(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_categorias();
				}
			});
		}
		//LISTAR CATEGORIA FINAL
		//LISTAR SERVI�OS INICIO
		function getListar_servicos(servico) {
			var servico = servico;
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_servicos.php",
				data: { "servico": servico, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".listar_servicos").html(resultado);
				//	console.log('aki: ' + resultado + ' s:' + servico);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_servicos(servico);
					//navigator.notification.alert('ERRO, ler lista de servicos! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//LISTAR SERVI�OS FINAL
		//SERVI�O DETALHADO INICIO
		function getServico_detalhado(servico, det) {
			var servico = servico;
			var det = det;
			var user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "servico_detalhado.php",
				data: { "user": user, "servico": servico, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".servico_detalhado").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getServico_detalhado(servico, det);
					//navigator.notification.alert('ERRO, ler lista de servicos! #002', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//SERVI�O DETALHADO FINAL
		//SELECT ENDERECO INICIO
		function getSelect_enderecos() {
			var user = getCookie("id_cliente");
			var cidade = $(".cidade_atend").html();

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_enderecos.php",
				data: { "cliente": user, "token": "H424715433852", "cidade": cidade },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado == 'Token inválido') {
						alert('Token inválido');
					} else {
						$('.selectEndereco').empty();
						$('.selectEndereco').append(resultado);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getSelect_enderecos();
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			return false;
		};
		//SELECT ENDERECO FIM

		//SELECT ENDERECO INICIO
		function getSelect_enderecos_r(id) {
			var user = getCookie("id_cliente");
			var cidade = $(".cidade_atend").html();
			var id = id;

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_enderecos.php",
				data: { "cliente": user, "token": "H424715433852", "cidade": cidade },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado == 'Token inválido') {
						alert('Token inválido');
					} else {
						$('.selectEndereco').empty();
						$('.selectEndereco').append(resultado);
						$('#selectEndereco').val(id);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getSelect_enderecos()
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			return false;
		};
		//SELECT ENDERECO FIM


		//SELECT FORMA DE PAGAMENTO INICIO
		function getSelect_forma_pg(tipo) {

			var user = getCookie("id_cliente");
			var tipo = tipo;

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_forma_pg.php",
				data: { "user": user, "tipo_order": tipo, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado == 'Token inválido') {
						alert('Token inválido');
					} else {
						$('.selectForma_pg').empty();
						$('.selectForma_pg').append(resultado);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getSelect_forma_pg(tipo)
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			return false;
		};
		//SELECT FORMA DE PAGAMENTO FIM
		//SELECT ESTADO INICIO
		function getEstados() {
			var estado = $(".estado_atend").html();
			var cidade = $(".cidade_atend").html();
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "estados.php",
				data: { "token": "H424715433852", "estado": estado },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado == 'Token inválido') {
						alert('Token inválido');
					} else {
						$('#estado').empty();
						$('#estado').append(resultado);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getEstados();
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "municipios.php",
				data: { "token": "H424715433852", "estado": estado, "cidade": cidade },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado == 'Token inválido') {
						alert('Token inválido');
					} else {
						$('#cidade').empty();
						$('#cidade').append(resultado);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getEstados();
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "bairros.php",
				data: { "token": "H424715433852", "cidade": cidade },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado == 'Token inválido') {
						alert('Token inválido');
					} else {
						$('#bairro').empty();
						$('#bairro').append(resultado);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getEstados();
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			return false;
		}
		//SELECT ESTADO FIM
		//CADASTRO ENDERE�O INICIO
		function setEndereco(cep, logradouro, numero, bairro, estado, cidade, complemento, referencia) {

			var user = getCookie("id_cliente");
			var cep = cep;
			var logradouro = logradouro;
			var numero = numero;
			var bairro = bairro;
			var estado = estado;
			var cidade = cidade;
			var complemento = complemento;
			var referencia = referencia;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_endereco.php",
				data: { "user": user, "cep": cep, "logradouro": logradouro, "numero": numero, "bairro": bairro, "estado": estado, "cidade": cidade, "complemento": complemento, "referencia": referencia, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 1) {
						activate_page("#local");
					} else {
						alert(resultado.dados);
						$(".sel_endereco").show();
						$(".cad_endereco").hide();
						getSelect_enderecos_r(resultado.id);
						//console.log('Resultado: '+resultado.id);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					setEndereco(cep, logradouro, numero, bairro, estado, cidade, complemento, referencia);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#local");
				}
			});
		}
		//CADASTRO ENDERE�O FIM
		//CADASTRO CART�O INICIO
		function setCartao(user, numero_c, cod_seg, mes, ano, nome_impresso, data_nasc_cartao, cpf_titular, tipo_cartao, banco) {

			var user = user;
			var cpf_titular = cpf_titular;
			var numero_c = numero_c;
			var cod_seg = cod_seg;
			var mes = mes;
			var ano = ano;
			var nome_impresso = nome_impresso;
			var data_nasc_cartao = data_nasc_cartao;
			var tipo_cartao = tipo_cartao;
			var banco = banco;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_cartao.php",
				data: { "user": user, "cpf_titular": cpf_titular, "numero_c": numero_c, "cod_seg": cod_seg, "mes": mes, "ano": ano, "nome_impresso": nome_impresso, "data_nasc_cartao": data_nasc_cartao, "tipo_cartao": tipo_cartao, "banco": banco, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 1) {
						activate_page("#pagamento");
					} else {
						alert(resultado.dados);
						$(".sel_cartao").show();
						$(".cad_cartao").hide();
						getSelect_forma_pg(2);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					setCartao(user, numero_c, cod_seg, mes, ano, nome_impresso, data_nasc_cartao, cpf_titular, tipo_cartao, banco);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#local");
				}
			});
		}
		//CADASTRO CART�O FIM
		//SELECIONANDO ENDERECO DE ATENDIMENTO INICIO
		function setSelect_endereco(id_end) {

			var user = getCookie("id_cliente");
			var id_end = id_end;

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "endereco.php",
				data: { "id_end": id_end, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					setCookie('id_endereco', id_end);
					$(".localSelect").html('<p>' + resultado + '</p>');
					$(".menu_inferior").show();
					activate_page("#agendamento");
				},
				error: function (resultado) {
					$('.loader').hide();
					setSelect_endereco(id_end)
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
			return false;
		};
		//SELECIONANDO ENDERECO DE ATENDIMENTO FIM
		//LISTAR HORARIOS DE ATENDIMENTO INICIO
		function getListar_horarios() {

			var user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_horarios.php",
				data: { "token": "H424715433852", "user": user },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".horarios_atend").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_horarios();
					//navigator.notification.alert('ERRO, ler lista de horarios! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//LISTAR HORARIOS DE ATENDIMENTO  FINAL

		//LISTAR AGENDA INICIO
		function getListar_agenda(data_busca1, data_busca2) {
			var user = getCookie("id_cliente");

			var today = new Date();
			var dd = today.getDate();
			var mm = today.getMonth() + 1; //January is 0!

			var yyyy = today.getFullYear();
			if (dd < 10) {
				dd = '0' + dd;
			}
			if (mm < 10) {
				mm = '0' + mm;
			}
			//var today = yyyy+'-'+mm+'-'+dd;
			var today = dd + '/' + mm + '/' + yyyy;
			var ini = '01/' + mm + '/' + yyyy;
			var end = '31/' + mm + '/' + yyyy;
			if (!data_busca1) {
				data_busca1 = ini;
			}
			if (!data_busca2) {
				data_busca2 = end;
			}

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_agenda.php",
				data: { "token": "H424715433852", "user": user, "bsc": "1", "data1": data_busca1, "data2": data_busca2 },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();

					$(".lista_agenda").html(resultado);
					if (!data_busca1) {
						$('.data_busca1').val(ini);
					} else {
						$('.data_busca1').val(data_busca1);
					}
					if (!data_busca2) {
						$('.data_busca2').val(end);
					} else {
						$('.data_busca2').val(data_busca2);
					}
					$('.novo_pedido').hide();
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_agenda(data_busca1, data_busca2);
					//navigator.notification.alert('ERRO, ler lista agenda! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_agenda.php",
				data: { "token": "H424715433852", "user": user, "bsc": "2", "data1": data_busca1, "data2": data_busca2 },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".lista_agenda2").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_agenda(data_busca1, data_busca2);
					//navigator.notification.alert('ERRO, ler lista agenda! #002', 'CADASTRAR', 'Error', 'OK');
				}
			});
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_agenda.php",
				data: { "token": "H424715433852", "user": user, "bsc": "3", "data1": data_busca1, "data2": data_busca2 },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".lista_agenda3").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_agenda(data_busca1, data_busca2);
					//navigator.notification.alert('ERRO, ler lista agenda! #003', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//LISTAR AGENDA FINAL
		//LISTAR PEDIDO INICIO
		function getListar_meusPedidos() {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_pedido.php",
				data: { "token": "H424715433852", "user": user, "bsc": "1" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".lista_pedido").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					//getListar_meusPedidos();
					//navigator.notification.alert('ERRO, ler lista pedidos! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_pedido.php",
				data: { "token": "H424715433852", "user": user, "bsc": "2" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".lista_pedido2").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_meusPedidos();
					//navigator.notification.alert('ERRO, ler lista pedidos! #002', 'CADASTRAR', 'Error', 'OK');
				}
			});
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "lista_pedido.php",
				data: { "token": "H424715433852", "user": user, "bsc": "3" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".lista_pedido3").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getListar_meusPedidos();
					//navigator.notification.alert('ERRO, ler lista pedidos! #003', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//LISTAR PEDIDO FINAL
		//CADASTRAR HORARIOS DE ATENDIMENTO INICIO
		function setCadastrar_horarios(user, dia, in_h, fm_h) {
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_horarios.php",
				data: { "token": "H424715433852", "user": user, "dia": dia, "in_h": in_h, "fm_h": fm_h },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					getListar_horarios();
				},
				error: function (resultado) {
					$('.loader').hide();
					setCadastrar_horarios(user, dia, in_h, fm_h);
					//navigator.notification.alert('ERRO, cad horario! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//CADASTRAR HORARIOS DE ATENDIMENTO FINAL
		//CADASTRO CLIENTE INICIO
		function setCad_cliente(cpf, nome, tel1, tel2, sexo, data_nasc, email, senham, token_id) {

			var cpf = cpf;
			var nome = nome;
			var tel1 = tel1;
			var tel2 = tel2;
			var sexo = sexo;
			var data_nasc = data_nasc;
			var email = email;
			var senham = senham;
			var token_id = token_id;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_cliente.php",
				data: { "cpf": cpf, "nome": nome, "tel1": tel1, "tel2": tel2, "sexo": sexo, "data_nasc": data_nasc, "email": email, "senham": senham, "token": "H424715433852", "token_id": token_id },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#mainpage");
						$("#login").val(email);
					} else {
						alert(resultado.dados);
						activate_page("#cadastrar");
					}
				},
				error: function (resultado) {
					console.log(resultado);
					$('.loader').hide();
					setCad_cliente(cpf, nome, tel1, tel2, sexo, data_nasc, email, senham, token_id);
					alert('Não foi poss�vel acessar! #002');
					//activate_page("#cadastrar");
				}
			});
		}
		//CADASTRO CLIENTE FIM
		//CADASTRO COLABORADOR INICIO
		function setCad_colaborador(nome_c, tel1_c, tel2_c, data_nasc_c, email_c, logradouro_c, numero_c, cidade_c, estado_c, bairro_c, complemento_c, referencia_c, sexo_c, servico_c, experiencia_c) {

			var nome = nome_c;
			var tel1 = tel1_c;
			var tel2 = tel2_c;
			var data_nasc = data_nasc_c;
			var email = email_c;

			var logradouro_c = logradouro_c;
			var numero_c = numero_c;
			var cidade_c = cidade_c;
			var estado_c = estado_c;
			var bairro_c = bairro_c;
			var complemento_c = complemento_c;
			var referencia_c = referencia_c;

			var sexo = sexo_c;
			var servico = servico_c;
			var experiencia = experiencia_c;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_colaborador.php",
				data: {
					"nome": nome,
					"tel1": tel1,
					"tel2": tel2,
					"data_nasc": data_nasc,
					"email": email,
					"logradouro_c": logradouro,
					"numero_c": numero,
					"cidade_c": cidade,
					"estado_c": estado,
					"bairro_c": bairro,
					"complemento_c": complemento,
					"referencia_c": referencia,
					"sexo": sexo,
					"servico": servico,
					"experiencia": experiencia,
					"token": "H424715433852"
				},
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#mainpage");
					} else {
						alert(resultado.dados);
						activate_page("#trabalhe_conosco");
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					setCad_colaborador(nome_c, tel1_c, tel2_c, data_nasc_c, email_c, logradouro_c, numero_c, cidade_c, estado_c, bairro_c, complemento_c, referencia_c, sexo_c, servico_c, experiencia_c);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#trabalhe_conosco");
				}
			});
		}
		//CADASTRO COLABORADOR FIM
		//CONFERIR SE A HORA ESTA LIVRE INICIO
		function getHora_atendimento(data, data_f, hora) {

			var servico = getCookie("servico");
			var hora = hora;
			var data = data;
			//navigator.notification.alert(hora);
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				dataType: "json",
				url: url_geral + "hora_atendimento.php",
				data: { "servico": servico, "hora": hora, "data": data, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro != 1) {
						if (resultado.erro == 5) {
							//document.getElementById("data").style.backgroundColor = "#93f788";
							//document.getElementById("hora").style.backgroundColor = "#ffa5a5";
						} else {
							//document.getElementById("data").style.backgroundColor = "#ffa5a5";
							//document.getElementById("hora").style.backgroundColor = "#ffa5a5";
						}
						if (data == '') {
							//document.getElementById("data").style.backgroundColor = "#ffffff";
						}
						if (hora == '') {
							//document.getElementById("hora").style.backgroundColor = "#ffffff";
						}
						alert(resultado.dados);
					} else {
						//document.getElementById("data").style.backgroundColor = "#93f788";
						//document.getElementById("hora").style.backgroundColor = "#93f788";
						/*
						var toMmDdYy = function(input) {
							var ptrn = /(\d{4})\-(\d{2})\-(\d{2})/;
							if(!input || !input.match(ptrn)) {
								return null;
							}
							setCookie('data',input.replace(ptrn, '$1/$2/$3'));
							return input.replace(ptrn, '$3/$2/$1');
						};
						*/
						setCookie('data', data);
						//navigator.notification.alert(data);
						$(".dataSelect").html('<p>' + data_f + ' - ' + hora + '</p>');
						$(".menu_inferior").show();
						activate_page("#agendamento");

						setCookie('hora', hora);
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getHora_atendimento(data, data_f, hora);
					//navigator.notification.alert('ERRO, ler Hora de atendimento! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//CONFERIR SE A HORA ESTA LIVRE FINAL
		//CADASTRO AGENDA INICIO
		function setCadastrar_agenda(user, servico, local, data, hora, forma_pg, cupom, cpf_cupom, s_valor) {

			var user = user;
			var servico = servico;
			var local = local;
			var data = data;
			var hora = hora;
			var forma_pg = forma_pg;
			var cupom = cupom;
			var cpf_cupom = cpf_cupom;
			var s_valor = s_valor;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_agenda.php",
				data: { "user": user, "servico": servico, "local": local, "data": data, "hora": hora, "forma_pg": forma_pg, "cupom": cupom, "cpf": cpf_cupom, "s_valor": s_valor, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						setCookie('id_endereco', '');
						setCookie('data', '');
						setCookie('hora', '');
						setCookie('forma_pg', '');
						setCookie('cod_cupom', '');
						setCookie('cpf_cupom', '');
						activate_page("#principal");
						console.log(resultado);
						//checkout_ccard(resultado.agenda);
						checkout_ccard(resultado.agenda, resultado.lNcartao, resultado.lNmcartao, resultado.lMesVenc, resultado.lAnoVenc, resultado.lCodigoSeg)

					} else {
						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					setCadastrar_agenda(user, servico, local, data, hora, forma_pg, cupom, cpf_cupom, s_valor);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}
		//CADASTRO AGENDA FIM
		//CADASTRO AGENDA COLABORADOR INICIO
		function setCadastrar_agenda_colab(agenda) {

			var user = getCookie('id_cliente');
			var agenda = agenda;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_agenda_colab.php",
				data: { "user": user, "agenda": agenda, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#agenda");
					} else {
						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_agenda('', '');
				},
				error: function (resultado) {
					$('.loader').hide();
					alert('Não foi poss�vel acessar! #003');
					//setCadastrar_agenda_colab(agenda);
					//activate_page("#cadastrar");
				}
			});
		}
		//CADASTRO AGENDA COLABORADOR FIM
		//CONFIRMA��O DE PEDIDO INICIO
		function setConfirmar_pedido(agenda, situacao, tipo) {

			var user = getCookie('id_cliente');
			var agenda = agenda;

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "confirmar_pedido.php",
				data: { "user": user, "agenda": agenda, "situacao": situacao, "tipo": tipo, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
					} else {
						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					if (tipo == 'Profissional') {
						getListar_agenda('', '');
					}
					if (tipo == 'Cliente') {
						getListar_meusPedidos();
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					//setConfirmar_pedido(agenda, situacao, tipo);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}
		//CONFIRMA��O DE PEDIDO FIM
		//CADASTRO AVALIACAO INICIO
		function setCadastrar_avaliacao(ava, avaliacao, desc_serv) {

			var user = getCookie('id_cliente');
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "cadastrar_avaliacao.php",
				data: { "user": user, "agenda": ava, "avaliacao": avaliacao, "descricao": desc_serv, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						getListar_meusPedidos();
						$(".realizar_avaliacao" + ava).show();
						$(".avaliacao" + ava).hide();
						activate_page("#meusPedidos");
					} else {
						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_agenda('', '');
				},
				error: function (resultado) {
					$('.loader').hide();
					setCadastrar_avaliacao(ava, avaliacao, desc_serv);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}
		//CADASTRO AVALIACAO FIM
		//INFORMA��ES CONTA INICIO
		function getInfo_Conta() {

			var user = getCookie("id_cliente");

			console.log(user);

			//if(user){

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "info_conta.php",
				data: { "token": "H424715433852", "user": user },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".info_conta").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getInfo_Conta();
					/*
					$('.error').show();
					$('.error').html('ERRO, ler lista de servicos! #001');
					*/
				}
			});

			//}else{
			//getVer_Login();
			//}
		}
		//INFORMA��ES CONTA FINAL
		//ALTERA��ES DO CLIENTE INICIO
		function getAlteracoes_cliente(tipo) {

			var user = getCookie("id_cliente");
			var tipo = tipo;
			var mascara = "## ####-####";
			var mascara2 = "## #####-####";
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "alteracao_cliente.php",
				data: { "token": "H424715433852", "user": user, "tipo": tipo, "mascara": mascara, "mascara2": mascara2 },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".alt_cliente").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getAlteracoes_cliente(tipo);
					/*
					$('.error').show();
					$('.error').html('ERRO, ler lista de servicos! #001');
					*/
				}
			});
		}
		//ALTERA��ES DO CLIENTE FINAL
		//ALTERAR ENDERE�O PRINCIPAL INICIO
		function setAlterar_endereco(end) {

			var user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "alterar_end_principal.php",
				data: { "token": "H424715433852", "user": user, "end": end },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					console.log(resultado);
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#minhaConta");
						getInfo_Conta();
					} else {

						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAlterar_endereco(end);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}
		//ALTERAR ENDERE�O PRINCIPAL FINAL
		//DELETAR ENDERE�O INICIO
		function setDeletar_endereco(end) {

			var user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "deletar_end.php",
				data: { "token": "H424715433852", "user": user, "end": end },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#minhaConta");
						getInfo_Conta();
					} else {

						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setDeletar_endereco(end);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});

		}
		//DELETAR ENDERE�O FINAL
		//ALTERAR CARTAO PRINCIPAL INICIO
		function setAlterar_cartao(crt) {

			var user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "alterar_cartao_principal.php",
				data: { "token": "H424715433852", "user": user, "crt": crt },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#minhaConta");
						getInfo_Conta();
					} else {

						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAlterar_cartao(crt);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}
		//ALTERAR CARTAO PRINCIPAL FINAL
		//DELETAR CARTAO INICIO
		function setDeletar_cartao(crt) {

			var user = getCookie("id_cliente");
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "deletar_cartao.php",
				data: { "token": "H424715433852", "user": user, "crt": crt },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						activate_page("#minhaConta");
						getInfo_Conta();
					} else {

						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setDeletar_cartao(crt);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});

		}
		//DELETAR CARTAO FINAL
		//ALTERAR EMAIL INICIO
		function setAlterar_email(email) {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "alterar_email.php",
				data: { "token": "H424715433852", "user": user, "email": email },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						setCookie('id_cliente', '');
						setCookie('tipo', '');
						setCookie('id_endereco', '');
						setCookie('data', '');
						setCookie('hora', '');
						setCookie('forma_pg', '');
						setCookie('cod_cupom', '');
						setCookie('cpf_cupom', '');
						setCookie('servico', '');
						setCookie('s_valor', '');
						$(".menu_inferior").hide();
						activate_page("#mainpage");
					} else {
						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAlterar_email(email);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}

		//ALTERAR EMAIL FINAL
		//ALTERAR EMAIL INICIO
		function setAlterar_pass(pass) {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "alterar_pass.php",
				data: { "token": "H424715433852", "user": user, "pass": pass },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						setCookie('id_cliente', '');
						setCookie('tipo', '');
						setCookie('id_endereco', '');
						setCookie('data', '');
						setCookie('hora', '');
						setCookie('forma_pg', '');
						setCookie('cod_cupom', '');
						setCookie('cpf_cupom', '');
						setCookie('servico', '');
						setCookie('s_valor', '');
						$(".menu_inferior").hide();
						activate_page("#mainpage");
					} else {
						alert(resultado.dados);
						//activate_page("#cadastrar");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAlterar_email(email);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
					//activate_page("#cadastrar");
				}
			});
		}

		//ALTERAR EMAIL FINAL
		//ALTERAR TELEFONE INICIO
		function setAlterar_telefone(tel1, tel2) {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "alterar_telefone.php",
				data: { "token": "H424715433852", "user": user, "tel1": tel1, "tel2": tel2 },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						$(".menu_inferior").show();
						activate_page("#minhaConta");
						getInfo_Conta();
					} else {
						alert(resultado.dados);
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAlterar_telefone(tel1, tel2);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//ALTERAR TELEFONE FINAL
		//ALTERAR NOME INICIO
		function setAlterar_nome(nome) {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "alterar_nome.php",
				data: { "token": "H424715433852", "user": user, "nome": nome },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						$(".menu_inferior").show();
						activate_page("#minhaConta");
						getInfo_Conta();
					} else {
						alert(resultado.dados);
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAlterar_nome(nome);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//ALTERAR NOME FINAL

		//ALTERAR CARTAO DA AGENDA INICIO
		function setAtualizar_Cartao(agenda, forma_pg) {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "atualizar_cartao.php",
				data: { "token": "H424715433852", "user": user, "agenda": agenda, "forma_pg": forma_pg },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 2) {
						alert(resultado.dados);
						$(".menu_inferior").show();
						activate_page("#meusPedidos");
					}
					getListar_meusPedidos();
				},
				error: function (resultado) {
					$('.loader').hide();
					setAtualizar_Cartao(agenda, forma_pg);
					//navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//ALTERAR CARTAO DA AGENDA FINAL

		//PORCENTAGEM DO CUPOM HORARIOS DE ATENDIMENTO INICIO
		function getPerc_cupom(cod_cupom, cpf_cupom) {

			var user = getCookie("id_cliente");
			var servico = getCookie("servico");
			//var cod_cupom   = getCookie("cod_cupom");
			//if(cod_cupom != ''){
			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				dataType: "json",
				url: url_geral + "calc_cupom.php",
				data: { "token": "H424715433852", "user": user, "servico": servico, "cod_cupom": cod_cupom, "cpf_cupom": cpf_cupom },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 0) {
						alert('Cupom Valido!');

						var mascara = "mascara(this, '###.###.###-##')";
						setCookie('cod_cupom', cod_cupom);
						setCookie('cpf_cupom', cpf_cupom);

						if (cod_cupom != '') {
							$(".cupomSelect").html('<p>' + cod_cupom + '</p>');
						} else {
							$(".cupomSelect").html('<p>Insira seu cupom de desconto</p>');
						}
						setCookie('s_valor', resultado.valor);
						$(".v_total").html('<h1>Total</h1><p id="valor">R$ ' + resultado.valor_ant + '</p><p style="font-size:15px">Desconto de: ' + resultado.desconto + '% </p><p>Valor com Desconto: R$ ' + getCookie("s_valor") + '</p>');

					} else if (resultado.erro == 1) {
						alert('Cupom Invalido!');
					} else if (resultado.erro == 2) {
						$(".cupomSelect").html('<p>Insira seu cupom de desconto</p>');
						$(".v_total").html('<h1>Total</h1><p id="valor">R$ ' + resultado.valor_ant + '</p>');
						setCookie('s_valor', resultado.valor);
					} else if (resultado.erro == 3) {
						alert('Cupom Ja Utilizado!');
					}/*else if(resultado.erro == 5){
						alert('CPF Inválido!');
					}*/

				},
				error: function (resultado) {
					$('.loader').hide();
					getPerc_cupom(cod_cupom, cpf_cupom);
					//navigator.notification.alert('ERRO, calculo de cupom! #001', 'CADASTRAR', 'Error', 'OK');
				}


			});
			//}
		}
		//PORCENTAGEM DO CUPOM HORARIOS DE ATENDIMENTO FIM
		//AREAS DE ATENDIMENTO INICIO
		function getCidades_atend() {
			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				url: url_geral + "cidades_atend.php",
				data: { "token": "H424715433852", "user": user },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$(".lst_cidadesAtend").html(resultado);
				},
				error: function (resultado) {
					$('.loader').hide();
					getCidades_atend();
					//navigator.notification.alert('ERRO, calculo de cupom! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//AREAS DE ATENDIMENTO FIM
		//VERIFICAR CPF INICIO
		function getVerif_cpf() {

			var user = getCookie("id_cliente");

			$.ajax({
				type: "POST",
				async: true,
				crossDomain: true,
				dataType: "json",
				url: url_geral + "verif_cpf.php",
				data: { "token": "H424715433852", "user": user },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 1) {
						var mascara = "mascara(this, '###.###.###-##')";
						if (!getCookie('cpf_cupom')) {
							$(".cpf_cup").html('<input type="text" class="apg_campo cpf_cupom" maxlength="14" onkeypress="' + mascara + '" placeholder="Digite o CPF">');
						} else {
							$(".cpf_cup").html('<input type="text" class="apg_campo cpf_cupom" maxlength="14" onkeypress="' + mascara + '" placeholder="Digite o CPF" value="' + getCookie('cpf_cupom') + '">');
						}
					} else if (resultado.erro == 2) {
						$(".cpf_cup").html('<input type="text" class="apg_campo cpf_cupom" maxlength="14" onkeypress="' + mascara + '" placeholder="Digite o CPF" disabled value="' + resultado.dados + '">');
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					getVerif_cpf();
					//navigator.notification.alert('ERRO, verifica�ão de cpf! #001', 'CADASTRAR', 'Error', 'OK');
				}
			});
		}
		//VERIFICAR CPF FIM
		/* COLOQUE AS FUN��ES INTERNAS AQUI */

		/* button  ESQUECI INICIO */
		$(document).on("click", ".btn_esqueci", function (evt) {
			var login_email = $("#login").val();
			if (login_email == '') {
				alert('Campo obrigatõrio vazio: E-mail');
				return false;
			}
			$.ajax({
				type: "POST",
				dataType: "json",
				async: true,
				crossDomain: true,
				url: url_geral + "esqueci.php",
				data: { "login_email": login_email, "token": "H424715433852" },
				timeout: 100000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					if (resultado.erro == 1) {
						alert('Confira seu e-mail com as instruções!');
					} else {
						alert(resultado.dados);
						activate_page("#login");
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					alert('Não foi possível acessar! #004');
					return false;
				}
			});
			return false;
		});
		/* button  ESQUECI FINAL */

		/* button  BUSCAS VALORES DO ALT */
		/*
		$(document).on("click", ".btn", function(evt)
		{
			var link = $(this).attr('alt');
			activate_page("#"+link); 
		    
			if(link == 'detalhado'){
				$('.partes_descricao').show();
			}
			return false;
		}); 
		*/
		/*
		$(document).on("blur", ".apg_campo", function(evt)
		{
			var campo   = $(this).attr('type');
			var id   	= $(this).attr('id');
			var alt   	= $(this).attr('alt');
			if(id != 'login' && id != 'senha' && alt != 'no'){
				if(campo == 'text' || campo == 'tel' || campo == 'email' || campo == 'password' || campo == 'number'){
					$( ".menu_inferior" ).show();
				}
			}
		});
		$(document).on("click", ".apg_campo", function(evt)
		{
			var campo   = $(this).attr('type');
			var id   	= $(this).attr('id');
			var alt   	= $(this).attr('alt');
			//alert(alt);
			if(id != 'login' && id != 'senha' && alt != 'no'){
				if(campo == 'text' || campo == 'tel' || campo == 'email' || campo == 'password' || campo == 'number'){
					$( ".menu_inferior" ).hide();
				}
			}
		});
		$(document).on("click", ".desc_serv", function(evt)
		{
			$( ".menu_inferior" ).hide();
		});
		$(document).on("blur", ".desc_serv", function(evt)
		{
			$( ".menu_inferior" ).show();
		});
		*/
		$(document).on("click", ".btn_cat", function (evt) {
			var funcao = $(this).attr('id');
			var link = $(this).attr('alt');
			activate_page("#" + link);
			if (link == 'servicos') {
				if (funcao != 'srv') {
					getListar_servicos(funcao);
				}
			}
		});
		$(document).on("click", ".btn2", function (evt) {
			//navigator.notification.alert('asas', '', '', '');
			//navigator.notification.alert($.mobile.activePage.attr('id'), '', '', '');
			var user = getCookie("id_cliente");
			if (user) {
				var funcao = $(this).attr('id');
				var link = $(this).attr('alt');
				//navigator.notification.alert($("#principal").length, '', '', 'OK');
				activate_page("#" + link);
				if (link == 'servicos') {
					if (funcao != 'srv') {
						getListar_servicos(funcao);
					}
				}
				if (link == 'local') {
					$(".menu_inferior").hide();
					getSelect_enderecos();
					getEstados();
				}
				if (link == 'data_hora') {
					$(".menu_inferior").hide();
					var d1 = new Date();
					var d2 = new Date(d1);
					var h1 = d1.getMinutes();

					if (h1 == 0) { var minuto = 60; }
					if (h1 > 0 && h1 <= 15) { var minuto = 60 + (15 - h1); }
					if (h1 > 15 && h1 <= 30) { var minuto = 60 + (30 - h1); }
					if (h1 > 30 && h1 <= 45) { var minuto = 60 + (45 - h1); }
					if (h1 > 45) { var minuto = 60 + (60 - h1) }

					d2.setMinutes(d1.getMinutes() + minuto);

					//navigator.notification.alert(d2.getMinutes(), '', '', 'OK');
					$('#date_format').bootstrapMaterialDatePicker
						({
							format: 'dddd DD MMMM YYYY',
							lang: 'pt-br',
							switchOnClick: true,
							minDate: d2,
							currentDate: d2,
							time: false
						});
					$('.dtp-btn-cancel').hide();
				}
				if (link == 'pagamento') {
					$(".menu_inferior").hide();
					$('.select_forma_pg').show();
					$('.novo_pgt').html('');
					getSelect_forma_pg(2);
				}
				if (link == 'horarios') {
					getListar_horarios();
				}
				if (link == 'cupom') {
					$(".menu_inferior").hide();
					getVerif_cpf();
				}
				if (link == 'detalhado') {
					$('.partes_descricao').show();
					if (funcao != 'dtl') {
						getServico_detalhado(funcao, link);
					}
				}
				if (link == 'agendamento') {
					var user = getCookie("id_cliente");
					if (user) {

						$(".menu_inferior").show();
						if (funcao != 'agd') {
							var valor = $(".valor_" + funcao).attr('alt');
							$('.partes_descricao').hide();
							setCookie('servico', funcao);
							setCookie('s_valor', valor);

							setCookie('id_endereco', '');
							setCookie('data', '');
							setCookie('hora', '');
							setCookie('forma_pg', '');
							setCookie('cod_cupom', '');
							setCookie('cpf_cupom', '');

							$(".data").val('');
							$(".hora").val('');
							//document.getElementById("data").style.backgroundColor = "#ffffff";
							//document.getElementById("hora").style.backgroundColor = "#ffffff";
							$(".cod_cupom").val('');

							$(".localSelect").html('<p>Selecione o local de atendimento</p>');
							$(".dataSelect").html('<p>Selecione a data de atendimento</p>');
							$(".pgSelect").html('<p>Selecione uma forma de pagamento</p>');
							$(".cupomSelect").html('<p>Insira seu cupom de desconto</p>');

							$(".v_total").html('<h1>Total</h1><p id="valor">R$ ' + getCookie("s_valor") + '</p>');
						}
					} else {
						getVer_Login();
					}
				}
				if (link == 'agenda') {
					getListar_agenda('', '');
				}
				if (link == 'meusPedidos') {

					$(".rodape_principal").attr("src", "images/agende_aqui.png");
					$(".rodape_meusPedidos").attr("src", "images/historico2.png");
					$(".rodape_minhaConta").attr("src", "images/minha_conta.png");
					$(".rodape_ajuda").attr("src", "images/ajuda.png");

					$(".rodape_principal").css("color", "#333333");
					$(".rodape_meusPedidos").css("color", "#ec268f");
					$(".rodape_minhaConta").css("color", "#333333");
					$(".rodape_ajuda").css("color", "#333333");

					//var user    = getCookie("id_cliente");	
					getListar_meusPedidos();
					/*if(user){
						getListar_meusPedidos();
					}else{
						getVer_Login();
					}*/
				}
				if (link == 'minhaConta') {

					$(".rodape_principal").attr("src", "images/agende_aqui.png");
					$(".rodape_meusPedidos").attr("src", "images/historico.png");
					$(".rodape_minhaConta").attr("src", "images/minha_conta2.png");
					$(".rodape_ajuda").attr("src", "images/ajuda.png");

					$(".rodape_principal").css("color", "#333333");
					$(".rodape_meusPedidos").css("color", "#333333");
					$(".rodape_minhaConta").css("color", "#ec268f");
					$(".rodape_ajuda").css("color", "#333333");

					$(".menu_inferior").show();
					getInfo_Conta();
				}
				if (link == 'facebook') { }
				if (link == 'ajuda') {

					$(".rodape_principal").attr("src", "images/agende_aqui.png");
					$(".rodape_meusPedidos").attr("src", "images/historico.png");
					$(".rodape_minhaConta").attr("src", "images/minha_conta.png");
					$(".rodape_ajuda").attr("src", "images/ajuda2.png");

					$(".rodape_principal").css("color", "#333333");
					$(".rodape_meusPedidos").css("color", "#333333");
					$(".rodape_minhaConta").css("color", "#333333");
					$(".rodape_ajuda").css("color", "#ec268f");

					$(".menu_inferior").show();
				}

				if (link == 'principal') {

					$(".rodape_principal").attr("src", "images/agende_aqui2.png");
					$(".rodape_meusPedidos").attr("src", "images/historico.png");
					$(".rodape_minhaConta").attr("src", "images/minha_conta.png");
					$(".rodape_ajuda").attr("src", "images/ajuda.png");

					$(".rodape_principal").css("color", "#ec268f");
					$(".rodape_meusPedidos").css("color", "#333333");
					$(".rodape_minhaConta").css("color", "#333333");
					$(".rodape_ajuda").css("color", "#333333");

					if (getCookie("tipo") == 'Cliente' || getCookie("tipo") == null) {
						$(".esquerda").show();
						$(".menu_colab").hide();
						$(".listar_categorias_").show();
						$(".menu_inferior").show();
						getListar_categorias();
					} else if (getCookie("tipo") == 'Profissional') {
						$(".menu_inferior").hide();
						$(".listar_categorias_").hide();
						$(".menu_colab").show();
					}
				}

				if (funcao == 'alterar_pass') {
					getAlteracoes_cliente('6');
					$(".menu_inferior").hide();
				}
				if (funcao == 'alterar_crt') {
					getAlteracoes_cliente('5');
					$(".menu_inferior").hide();
				}
				if (funcao == 'alterar_end') {
					getAlteracoes_cliente('4');
					$(".menu_inferior").hide();
				}
				if (funcao == 'alterar_email') {
					getAlteracoes_cliente('3');
					$(".menu_inferior").hide();
				}
				if (funcao == 'alterar_tel') {
					getAlteracoes_cliente('2');
					$(".menu_inferior").hide();
				}
				if (funcao == 'alterar_nome') {
					getAlteracoes_cliente('1');
					$(".menu_inferior").hide();
				}
				/*
				var pagina 	= window.location.href;
				var tags 	= pagina.split("#");
				var tag 	= tags[1];
				navigator.notification.alert(tag, '', '', 'OK');
				*/
			} else {
				getVer_Login();
			}
				return false;
		});

		$(document).on("click", ".sair", function (evt) {
			navigator.app.exitApp();
		})

		$(document).on("click", ".busca-data", function (evt) {
			var data_busca1 = $(".data_busca1").val();
			var data_busca2 = $(".data_busca2").val();

			getListar_agenda(data_busca1, data_busca2);

			return false;
		});

		$(document).on("click", ".enviar", function (evt) {
			if (getCookie('id_cliente') <= 0) {
				var login_email = $("#login").val();
				var login_senha = $("#senha").val();
				var token_id = $("#token_id").val();

				getLogin(login_email, login_senha, token_id);
			} else {
				var id_cliente = getCookie('id_cliente');
			}
			getListar_categorias();
			return false;
		});

		$(document).on("click", ".logout", function (evt) {
			setCookie('id_cliente', '');
			setCookie('tipo', '');
			setCookie('id_endereco', '');
			setCookie('data', '');
			setCookie('hora', '');
			setCookie('forma_pg', '');
			setCookie('cod_cupom', '');
			setCookie('cpf_cupom', '');
			setCookie('servico', '');
			setCookie('s_valor', '');

			$(".apg_campo").val('');
			$(".localSelect").html('<p>Selecione o local de atendimento</p>');
			$(".dataSelect").html('<p>Selecione a data de atendimento</p>');
			$(".pgSelect").html('<p>Selecione uma forma de pagamento</p>');
			$(".cupomSelect").html('<p>Insira seu cupom de desconto</p>');
			$(".menu_inferior").hide();

			getVer_Login();

			return false;
		});

		var x = 0;
		$(document).on("click", ".add_endereco", function (evt) {
			if (x == 0) {
				$(".add_endereco").html('<div class="buttonentrar">Adicionar Endereço ja Existente</div>');
				$(".sel_endereco").hide();
				$(".cad_endereco").show();

				var estado = $(".estado_atend").html();

				x = 1;
			} else {
				$(".add_endereco").html('<div class="buttonentrar">Adicionar Novo Endereço</div>');
				$(".sel_endereco").show();
				$(".cad_endereco").hide();
				x = 0;
			}
			return false;
		});

		var y = 0;
		$(document).on("click", ".add_cartao", function (evt) {
			if (y == 0) {
				$(".add_cartao").html('<div class="buttonentrar">Adicionar Forma Pagamento ja Existente</div>');
				$(".sel_cartao").hide();
				$(".cad_cartao").show();
				y = 1;
			} else {
				$(".add_cartao").html('<div class="buttonentrar">Adicionar Novo Forma Pagamento</div>');
				$(".sel_cartao").show();
				$(".cad_cartao").hide();
				y = 0;
			}
			return false;
		});

		$(document).on("change", ".estado", function (evt) {
			var estado = $(this).val();
			$(".cidade").html('<option value="0">Carregando...</option>');
			$.post(url_geral + "municipios.php", { estado: $(this).val() }, function (valor) {
				$(".cidade").html(valor);
			}
			)
		});

		$(document).on("change", ".cidade", function (evt) {
			var cidade = $(this).val();
			$(".bairro").html('<option value="0">Carregando...</option>');
			$.post(url_geral + "bairros.php", { cidade: $(this).val() }, function (valor) {
				$(".bairro").html(valor);
			}
			)
		});

		$(document).on("click", ".btn-endereco", function (evt) {
			var user = getCookie('id_cliente');
			var cep = $("#cep").val();
			var logradouro = $("#logradouro").val();
			var numero = $("#numero").val();
			var bairro = $("#bairro").val();
			var estado = $("#estado").val();
			var cidade = $("#cidade").val();
			var complemento = $("#complemento").val();
			var referencia = $("#referencia").val();

			$(".add_endereco").html('<div class="buttonentrar">Adicionar Novo Endereço</div>');
			$(".sel_endereco").show();
			$(".cad_endereco").hide();
			x = 0;

			setEndereco(cep, logradouro, numero, bairro, estado, cidade, complemento, referencia);
		});

		$(document).on("click", ".btn-cartao", function (evt) {
			var user = getCookie('id_cliente');
			var numero_c = $(".numero_c").val();
			var cod_seg = $(".cod_seg").val();
			//var validade		= $(".validade").val();
			var mes = $(".mes").val();
			var ano = $(".ano").val();
			var nome_impresso = $(".nome_impresso").val();
			var data_nasc_cartao = $(".data_nasc_cartao").val();
			var cpf_titular = $(".cpf_titular").val();

			var tipo_cartao = document.getElementsByName("tipo_cartao");
			var tipo = null;
			// for (var i = 0; i < tipo_cartao.length; i++) {
			// 	if (tipo_cartao[i].checked) {
			// 		tipo = tipo_cartao[i].value;
			// 	}
			// }
			if( $('input[name=tipo_cartao]:radio:checked').length > 0 ) {
				tipo = $('input[name=myradiobutton]:radio:checked').val();
			}
			else {
				tipo = 0;
			}
			//alert(tipo);
			//var bandeira = null;

			// var tipo_cartao_banco = document.getElementsByName("tipo_cartao_banco");

			// for (var i = 0; i < tipo_cartao_banco.length; i++) {
			// 	if (tipo_cartao_banco[i].checked) {
			// 		var banco = tipo_cartao_banco[i].value;
			// 	}
			// }
			/*var bandeira = null;
			$.ajax({
				type: "POST", dataType: "json", cache: false, url: "https://igestaoweb.com.br/pinkmajesty/function/identifica_bandeira.php",
				data: { cartao: numero_c },
				timeout: 200000,
				beforeSend: function (resultado) {
					$('.loader').show();
				},
				success: function (resultado) {
					$('.loader').hide();
					$('#brandcard').val(resultado.sucesso.bandeira);
					bandeira = resultado.sucesso.bandeira;
					console.log(resultado);
					if (bandeira == null) {
						//alert('Campo obrigatório vazio: BANDEIRA');
						bandeira = null;
						return false;
					}
				},
				error: function (resultado) {
					$('.loader').hide();
					console.log('error');
					bandeira = null;
				}
			});*/

			$(".add_cartao").html('<div class="buttonentrar">Adicionar Nova Forma Pagamento</div>');
			$(".sel_cartao").show();
			$(".cad_cartao").hide();
			y = 0;

			if (tipo == '1') {
				if (cpf_titular == '') {
					alert('CPF do Titular Não Informado!');
					return false;
				} else if (numero_c == '') {
					alert('Numero Não Informado!');
					return false;
				} else if (cod_seg == '') {
					alert('Codigo de Segurança Não Informado!');
					return false;
				} else if (mes == '') {
					alert('Mes Não Informado!');
					return false;
				} else if (ano == '') {
					alert('Ano Não Informado!');
					return false;
				} else if (nome_impresso == '') {
					alert('Nome Impresso Não Informado!');
					return false;
				} else if (data_nasc_cartao == '') {
					alert('Data Nacimento Não Informado!');
					return false;
				} else {
					//bandeira = $('#brandcard').val();
					alert('Enviando cartão...');
					setCartao(user, numero_c, cod_seg, mes, ano, nome_impresso, data_nasc_cartao, cpf_titular, tipo, null);
				}
			} else {
				// if (banco == '') {
				// 	alert('SELECIONE um banco para D�bito em conta!');
				// 	return false;
				// } else {
				// 	setCartao(user, numero_c, cod_seg, mes, ano, nome_impresso, data_nasc_cartao, cpf_titular, tipo, bandeira);
				// }
				setCartao(user, numero_c, cod_seg, mes, ano, nome_impresso, data_nasc_cartao, cpf_titular, tipo, null);
			}

		});

		$(document).on("click", ".select_endereco", function (evt) {
			var user = getCookie('id_cliente');
			var end = $(".selectEndereco").val();

			setSelect_endereco(end);
		});

		$(document).on("click", ".btn-data-hora", function (evt) {
			var user = getCookie('id_cliente');
			var date_format = $("#date_format").val() + " " + $("#hour_format").val();
			console.log(date_format);
			var info_data = date_format.split(" ");
			//navigator.notification.alert(info_data[2]);

			if (info_data[2] == 'Janeiro') { var mes = '01'; }
			if (info_data[2] == 'Fevereiro') { var mes = '02'; }
			if (info_data[2] == 'Março') { var mes = '03'; }
			if (info_data[2] == 'Abril') { var mes = '04'; }
			if (info_data[2] == 'Maio') { var mes = '05'; }
			if (info_data[2] == 'Junho') { var mes = '06'; }
			if (info_data[2] == 'Julho') { var mes = '07'; }
			if (info_data[2] == 'Agosto') { var mes = '08'; }
			if (info_data[2] == 'Setembro') { var mes = '09'; }
			if (info_data[2] == 'Outubro') { var mes = '10'; }
			if (info_data[2] == 'Novembro') { var mes = '11'; }
			if (info_data[2] == 'Dezembro') { var mes = '12'; }
			var data = info_data[3] + '/' + mes + '/' + info_data[1];
			var data_f = info_data[1] + '/' + mes + '/' + info_data[3];
			var hora = info_data[4] + ':' + info_data[6];

			//navigator.notification.alert(hora);

			//var data    	= $(".data").val();
			//var hora  	= $(".hora").val();
			//console.log(data,data_f,hora);
			getHora_atendimento(data, data_f, hora);
		});

		$(document).on("click", ".select_forma_pg", function (evt) {
			var user = getCookie('id_cliente');
			var forma_pg = $(".selectForma_pg").val();
			//console.log({ 'forma_pg': forma_pg });
			var titulo_pg = $(".pg_" + forma_pg).html();

			setCookie('forma_pg', forma_pg);
			$(".pgSelect").html('<p>' + titulo_pg + '</p>');
			$(".menu_inferior").show();
			activate_page("#agendamento");
		});

		$(document).on("click", ".btn_cupom", function (evt) {
			var user = getCookie('id_cliente');
			var cod_cupom = $(".cod_cupom").val();
			var cpf_cupom = $(".cpf_cupom").val();

			getPerc_cupom(cod_cupom, cpf_cupom);
			$(".menu_inferior").show();
			activate_page("#agendamento");
		});

		$(document).on("click", ".alt_hora", function (evt) {
			var user = getCookie('id_cliente');
			var dia = $(this).attr('alt');
			var data_i = $(".data_i_" + dia).attr('alt');
			var data_f = $(".data_f_" + dia).attr('alt');

			if (data_i == '--:--') {
				data_i = '08:00';
			}
			if (data_f == '--:--') {
				data_f = '08:00';
			}

			$(".in_" + dia).html('De <br /><input type="text" name="in_h_' + dia + '" id="in_h_' + dia + '" value="' + data_i + '" style="width:70px" />');
			$(".fm_" + dia).html('Ate <br /><input type="text" name="fm_h_' + dia + '" id="fm_h_' + dia + '" value="' + data_f + '" style="width:70px" />');
			$(".btn_" + dia).html('<a class="alt_hora_c" alt="' + dia + '"><div class="buttonentrar">Editar</div></a>');

			//navigator.notification.alert('#in_h_'+dia, '', 'ALERTA', 'OK');
			$('#in_h_' + dia).bootstrapMaterialDatePicker
				({
					date: false,
					shortTime: false,
					format: 'HH:mm',
					switchOnClick: true,
					minDate: '08:00',
					maxDate: '22:00',
					lang: 'pt-br',
					clearButton: true,
					clearText: 'Limpar'
				});
			$('#fm_h_' + dia).bootstrapMaterialDatePicker
				({
					date: false,
					shortTime: false,
					format: 'HH:mm',
					switchOnClick: true,
					minDate: '08:00',
					maxDate: '22:00',
					lang: 'pt-br',
					clearButton: true,
					clearText: 'Limpar'
				});
			$('.dtp-btn-cancel').hide();
		});

		$(document).on("click", ".alt_hora_c", function (evt) {
			var user = getCookie('id_cliente');
			var dia = $(this).attr('alt');
			var in_h = $("#in_h_" + dia + "").val();
			var fm_h = $("#fm_h_" + dia + "").val();

			setCadastrar_horarios(user, dia, in_h, fm_h);
		});

		$(document).on("click", ".cad_cliente", function (evt) {
			var cpf = $(".cpf").val();
			var nome = $(".nome").val();
			var tel1 = $(".tel1").val();
			var tel2 = $(".tel2").val();
			var data_n = $(".data_nasc").val();
			var email = $(".email").val();
			var senham = $(".senham").val();
			var token_id = $(".token_id").val();
			/*var toMmDdYy = function(input) {
				var ptrn = /(\d{4})\-(\d{2})\-(\d{2})/;
				if(!input || !input.match(ptrn)) {
					return null;
				}
				return input.replace(ptrn, '$1/$2/$3');
			};
			var data_nasc	= toMmDdYy(data_n); */
			var sexo_cont = document.getElementsByName("sexo");

			for (var i = 0; i < sexo_cont.length; i++) {
				if (sexo_cont[i].checked) {
					var sexo = sexo_cont[i].value;
				}
			}

			if (nome == '') { alert('Nome Completo Não Informado!'); }
			else if (tel2 == '') { alert('Celular Não Informado!'); }
			else if (sexo == '') { alert('Sexo Não Informado!'); }
			else if (data_n == '') { alert('Data de Nascimento Não Informado!'); }
			else if (email == '') { alert('Email Não Informado!'); }
			else if (senham == '') { alert('Nenhuma Senha Informada!'); }
			else if (senham.length < 5 || senham.length > 10) {
				alert('A Senha deve ter entre 5 e 10 caracteres!');
			}
			else {
				setCad_cliente(cpf, nome, tel1, tel2, sexo, data_n, email, senham, token_id);
			}
		});

		$(document).on("click", ".cad_colab", function (evt) {

			if ($('#termosuso_c').prop('checked') == false) {
				alert('Você deve ler e concordar com os nossos Termos de Uso!');
				return false;
			} else {
				var nome_c = $(".nome_c").val();
				var tel1_c = $(".tel1_c").val();
				var tel2_c = $(".tel2_c").val();
				var data_n_c = $(".data_nasc_c").val();
				var email_c = $(".email_c").val();

				var logradouro_c = $(".logradouro_c").val();
				var numero_c = $(".numero_c").val();
				var cidade_c = $(".cidade_c").val();
				var estado_c = $(".estado_c").val();
				var bairro_c = $(".bairro_c").val();
				var complemento_c = $(".complemento_c").val();
				var referencia_c = $(".referencia_c").val();

				var sexo_c = sexo_c.toString();
				var sexo_c = $("input[name='sexo']:checked").val();

				var servico_c = [];
				$.each($("input[name='servico_c']:checked"), function () { servico_c.push($(this).val()); });

				var servico_c = servico_c.toString();
				var experiencia_c = $("input[name='experiencia_c']:checked").val();

				var data_nasc_c = data_n_c;

				setCad_colaborador(nome_c, tel1_c, tel2_c, data_nasc_c, email_c, logradouro_c, numero_c, cidade_c, estado_c, bairro_c, complemento_c, referencia_c, sexo_c, servico_c, experiencia_c);
			}
		});

		$(document).on("click", ".cad_agendar", function (evt) {
			if ($('#termosusoAgendamento').prop('checked') == false) {
				alert('Você deve ler e concordar com os nossos Termos de Uso!');
				return false;
			} else {
				var user = getCookie('id_cliente');
				var servico = getCookie('servico');
				var local = getCookie('id_endereco');
				var data = getCookie('data');
				var hora = getCookie('hora');
				var forma_pg = getCookie('forma_pg');
				var cupom = getCookie('cod_cupom');
				var cpf_cupom = getCookie('cpf_cupom');
				var s_valor = getCookie('s_valor');

				setCadastrar_agenda(user, servico, local, data, hora, forma_pg, cupom, cpf_cupom, s_valor);
			}
		});

		$(document).on("click", ".btn_agenda", function (evt) {
			$(".busca_data").show();
			$(".lista_agenda3").hide();
			$(".lista_agenda2").hide();
			$(".lista_agenda").show();

			return false;
		});

		$(document).on("click", ".btn_pedidos", function (evt) {

			$(".busca_data").hide();
			$(".lista_agenda3").hide();
			$(".lista_agenda2").show();
			$(".lista_agenda").hide();

			return false;
		});

		$(document).on("click", ".btn_concluidos", function (evt) {
			$(".busca_data").show();
			$(".lista_agenda3").show();
			$(".lista_agenda2").hide();
			$(".lista_agenda").hide();

			return false;
		});

		$(document).on("click", ".btn_listarPedidos1", function (evt) {
			$(".lista_pedido3").hide();
			$(".lista_pedido2").hide();
			$(".lista_pedido").show();
			$(".agenda_button").css("color", "#333333");
			$(".agenda_button1").css("color", "#ec268f");
			return false;
		});

		$(document).on("click", ".btn_listarPedidos2", function (evt) {
			$(".lista_pedido3").hide();
			$(".lista_pedido2").show();
			$(".lista_pedido").hide();
			$(".agenda_button").css("color", "#333333");
			$(".agenda_button2").css("color", "#ec268f");
			return false;
		});

		$(document).on("click", ".btn_listarPedidos3", function (evt) {
			$(".lista_pedido3").show();
			$(".lista_pedido2").hide();
			$(".lista_pedido").hide();
			$(".agenda_button").css("color", "#333333");
			$(".agenda_button3").css("color", "#ec268f");
			return false;
		});

		$(document).on("click", ".btn_aceitar", function (evt) {
			var agenda = $(this).attr('alt');
			setCadastrar_agenda_colab(agenda);
			//checkout_ccard(agenda);
			setConfirmar_pedido(agenda, 'AGENDADO', 'Profissional');
			return false;
		});

		$(document).on("click", ".btn_pagamento", function (evt) {
			var agenda = $(this).attr('alt');
			//checkout_ccard(agenda);
			$('.btn_pagamento').hide();
			return false;
		});

		$(document).on("click", ".btn_troca_cartao", function (evt) {
			var agenda = $(this).attr('alt');
			$('.select_forma_pg').hide();
			$('.novo_pgt').html('<button class="btn2 btn_troca_cartao2" alt="' + agenda + '"><div class="buttonentrar">Trocar Forma Pagamento</div></button>');
			$(".menu_inferior").hide();
			$(".voltar").html('<a class="btn2" alt="meusPedidos" id="agd"><img src="images/iconesetavoltar.png"></a><p>Pagamento</p>');

			activate_page("#pagamento");
			getSelect_forma_pg(2);
			return false;
		});

		$(document).on("click", ".btn_troca_cartao2", function (evt) {
			$(".menu_inferior").show();
			var agenda = $(this).attr('alt');
			var forma_pg = $(".selectForma_pg").val();
			setAtualizar_Cartao(agenda, forma_pg);
			return false;
		});

		$(document).on("click", ".btn_concluir", function (evt) {
			var agenda = $(this).attr('alt');
			setConfirmar_pedido(agenda, 'CONCLUIDO', 'Profissional');
			activate_page("#agenda");
			return false;
		});

		$(document).on("click", ".alt_endereco_p", function (evt) {
			var end = $(this).attr('alt');
			setAlterar_endereco(end);
			return false;
		});

		$(document).on("click", ".del_endereco_p", function (evt) {
			var end = $(this).attr('alt');
			setDeletar_endereco(end);
			return false;
		});

		$(document).on("click", ".alt_cartao_p", function (evt) {
			var crt = $(this).attr('alt');
			setAlterar_cartao(crt);
			return false;
		});

		$(document).on("click", ".del_cartao_p", function (evt) {
			var crt = $(this).attr('alt');
			setDeletar_cartao(crt);
			return false;
		});

		$(document).on("click", ".alt_email", function (evt) {
			var email = $('.email_alterar').val();
			setAlterar_email(email);
			return false;
		});

		$(document).on("click", ".alt_pass", function (evt) {
			var pass = $('.pass_alterar').val();
			setAlterar_pass(pass);
			return false;
		});

		$(document).on("click", ".alt_tel", function (evt) {
			var tel1 = $('.tel1_alterar').val();
			var tel2 = $('.tel2_alterar').val();
			setAlterar_telefone(tel1, tel2);
			return false;
		});

		$(document).on("click", ".alt_nome", function (evt) {
			var nome = $('.nome_alterar').val();
			setAlterar_nome(nome);
			return false;
		});

		$(document).on("click", ".btn_avaliar", function (evt) {
			var ava = $(this).attr('alt');
			$(".realizar_avaliacao" + ava).hide();
			$(".avaliacao" + ava).show();
			return false;
		});

		$(document).on("click", ".btn_avaliacao", function (evt) {
			var ava = $(this).attr('alt');
			var avaliacao = $("#rateYo" + ava).rateYo("option", "rating");
			var desc_serv = $("#desc_serv" + ava).val();
			//navigator.notification.alert(avaliacao, '', 'ALERTA', 'OK');
			setCadastrar_avaliacao(ava, avaliacao, desc_serv);
			return false;
		});

		$(document).on("click", ".desc_serv", function (evt) {
			$(".menu_inferior").hide();
		});

		$(document).on("blur", ".desc_serv", function (evt) {
			$(".menu_inferior").show();
		});

		$(document).on("click", ".chat", function (evt) {
			var user = getCookie("id_cliente");
			if (user) {
				$(".menu_inferior").hide();
				var url_geral2 = "https://igestaoweb.com.br/pinkmajesty/app_new/chat/index.php";
				var token_id = localStorage.getItem("token_id");
				var idu = getCookie('id_cliente');
				//navigator.notification.alert('sasasas', '', 'ALERTA', 'OK');
				$("#frame_list").attr("src", url_geral2 + "?token_id=" + token_id + "&idu=" + idu);
				activate_page("#frame");
			} else {
				getVer_Login();
			}
		});

		$(document).on("change", ".senham", function (evt) {
			var senha = $(this).val();
			if (senha.length < 5 || senha.length > 10) {
				$('.msg_pass_c').html('A Senha deve ter entre 5 e 10 caracteres!!');
			} else {
				$('.msg_pass_c').html('');
			}
		});

		$(document).on("click", ".cidade_atend", function (evt) {
			var menu = $(this).attr('id');
			//if(menu == 'menu_volt'){
			$(".menu_inferior").show();
			//}

			getCidades_atend();
			activate_page("#area_atend");
		});

		$(document).on("click", ".lista_servicos_logo", function (evt) {
			$(".menu_colab").hide();
			$(".listar_categorias_").show();
			$(".menu_inferior").show();
			getListar_categorias();
			activate_page("#area_atend");
			activate_page("#principal");
			$(".rodape_principal").attr("src", "images/agende_aqui2.png");
			$(".rodape_meusPedidos").attr("src", "images/historico.png");
			$(".rodape_minhaConta").attr("src", "images/minha_conta.png");
			$(".rodape_ajuda").attr("src", "images/ajuda.png");

			$(".rodape_principal").css("color", "#ec268f");
			$(".rodape_meusPedidos").css("color", "#333333");
			$(".rodape_minhaConta").css("color", "#333333");
			$(".rodape_ajuda").css("color", "#333333");
		});

		$(document).on("click", ".alt_cidat", function (evt) {
			var cod_cidade = $(this).attr('alt');
			var nome_cidade = $(".cidade" + cod_cidade).html();
			var nome_estado = $(".estado" + cod_cidade).html();
			$(".cidade_atend").html(nome_cidade);
			$(".estado_atend").html(nome_estado);
			activate_page("#principal");

			//navigator.notification.alert(nome_estado);
			//setCidades_atend();
		});

		$(document).on("blur", ".mes", function (evt) {
			var mes = $(this).val();

			if (mes <= 0) {
				$(this).val('1');
			}
			if (mes > 12) {
				$(this).val('12');
			}
		});

		$(document).on("blur", ".ano", function (evt) {
			var ano = $(this).val();
			var dataAtual = new Date();
			var ano_atual = dataAtual.getFullYear();
			if (ano < ano_atual) {
				$(this).val(ano_atual);
			}
			if (ano > 2999) {
				$(this).val('2999');
			}
		});

		$('#data_busca1').bootstrapMaterialDatePicker
			({
				weekStart: 0, format: 'DD/MM/YYYY',
				time: false,
				switchOnClick: true,
				lang: 'pt-br'
			});

		$('#data_busca2').bootstrapMaterialDatePicker
			({
				weekStart: 0, format: 'DD/MM/YYYY',
				time: false,
				switchOnClick: true,
				lang: 'pt-br'
			});

		$('.dtp-btn-cancel').hide();

	}
	document.addEventListener("app.Ready", register_event_handlers, false);
})();