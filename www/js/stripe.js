
/*(function()
{
 "use strict";
//  $( document ).ready(function() {
//     var stripe = Stripe('pk_test_HwOzl6pIove5P3TZopMpaOsg001qQzh3P6');
//     MainStripe(stripe);
//     });
*/
function MainStripe(){
   var user    = localStorage.getItem("id_cliente");
   $.ajax({
        type:"POST",
        url:url_geral+"stripe/GetPublicToken.php",
        data:{"token":"H424715433852", "user":user },
        timeout: 1000,
            beforeSend: function(){ 
                $('.loader').show();
            },
            success: function(resultado){
                $('.loader').hide();
                console.log(resultado);
                if (resultado['erro'] != 0){
                    alert(resultado['dados']);
                } else {
                    response = resultado['dados']['token'];
                    localStorage.setItem("stripe_token", response);
                    InitStripe();
                }
            },
        error:function(resultado){
            $('.loader').hide();
            response = resultado;
            console.log(response);
            alert("Erro de comunicação, verifique sua conexão com a internet.");
        }
    });
}
function InitStripe(){
    var user    = localStorage.getItem("id_cliente");
    var stripe = Stripe(localStorage.getItem("stripe_token"));
    
    getClient(user)
    .then(function(getClient){
        console.log("1 - getClient done");
        console.log(getClient);
        ListCards(user)
        .then(function(ListCards){
            console.log("2 - ListCards done");
            console.log(ListCards);
            var cardButtonAdd = document.getElementById('card-button-add');
            cardButtonAdd.addEventListener('click', function() {
                getClientSecretSetupIntent(user)
                .then(function(getClientSecretSetupIntent){
                    console.log("3 - getClientSecretSetupIntent done");
                    console.log(getClientSecretSetupIntent);
                    NewCardStripe(stripe);
                }, error => {
                    console.log("3 - getClientSecretSetupIntent fail")
                    console.log(error);
                    alert("Não foi possível comunicar com plataforma de pagamentos. Tente logar novamente x3");
                    });
            });

            $(document).on("click", ".cad_agendar", function(evt)
            {
                $('.loader').show();
                var forma_pg = localStorage.getItem('forma_pg');
                console.log(forma_pg);
                if (forma_pg){
                    setTimeout(function(){
                        if (
                          $("#termosusoAgendamento").prop("checked") == false
                        ) {
                          alert('Você deve ler e concordar com os nossos Termos de Uso!');
                          return false;
                        } else {
                          $('.loader').show();
                          getPaymentIntent(forma_pg).then(
                            function (resultado) {
                              let payment_intent = resultado;
                              setTimeout(function () {
                                // Testativa 1
                                getWebhookResponse(user, payment_intent).then(
                                  function (resultado) {
                                    //Confirmar
                                    confirmPayment(resultado.dados.payment_intent);
                                    $('.loader').hide();
                                    console.log(resultado);
                                  },
                                  function (erro) {
                                    setTimeout(function () {
                                      //Tentativa 2
                                      getWebhookResponse(
                                        user,
                                        payment_intent
                                      ).then(
                                        function (resultado) {
                                          //Confirmar
                                          confirmPayment(resultado.dados.payment_intent);
                                          $('.loader').hide();
                                          console.log(resultado);
                                        },
                                        function (erro) {
                                          setTimeout(function () {
                                            //Tentativa 3
                                            $('.loader').hide();
                                            getWebhookResponse(
                                              user,
                                              payment_intent
                                            ).then(
                                              function (resultado) {
                                                //Confirmar
                                                confirmPayment(resultado.dados.payment_intent);
                                                console.log(resultado);
                                              },
                                              function (erro) {
                                                //Não foi possível confirmar seu pagamento
                                                $('.loader').hide();
                                                alert("Não foi possível confirmar seu pagamento");
                                                console.log(erro);
                                              }
                                            );
                                          }, 3000);
                                        }
                                      );
                                    }, 3000);
                                  }
                                );
                              }, 1000);
                              console.log(resultado);
                            },
                            function (erro) {
                              console.log("erro getPaymentIntent");
                              console.log(erro);
                              alert("Não foi possível comunicar com plataforma de pagamentos. Tente reconectar x2");
                            }
                          );
                        }
                    },1000);
                }
            });

        }, error => {
            console.log("2 - ListCards fail")
            console.log(error);
            alert("Não foi possível comunicar com plataforma de pagamentos. Tente reconectar x1");
        });

        }, error => {
            console.log("1 - getClient fail")
            console.log(error);
            //alert("Não foi possível comunicar com plataforma de pagamentos. Tente reconectar x1")
        });

}

/**
 * Busca o custumer id na API do Stripe
 * 
 * @param {string} user Token de identificação do 
 *      usuário na API do Pink Majesty
 * @return {Promise.<string>} Quando resolve retorna
 *      uma string com o custumer id na API do Stripe
 *      Se falhar retorna string com o erro.
 */
function getClient(user = false){
    var response;
    if(!user){
    var user = localStorage.getItem("id_cliente");
    }
    return new Promise ((resolve , reject) => {
        $.ajax({
            type:"POST",
            url:url_geral+"stripe/GetCustomer.php",
            data:{"token":"H424715433852", "user":user },
            timeout: 1000,
                beforeSend: function(){ 
                    $('.loader').show();
                },
                success: function(resultado){
                    $('.loader').hide();
                    response = resultado['dados']['gateway_id'];
                    localStorage.setItem("gateway_id", response);
                    resolve(response);
                },
            error:function(resultado){
                $('.loader').hide();
                response = resultado;
                reject(response);
            }
        });

    });
}

/**
 * Lista os cartões no db do Pink Majesty
 * 
 * @param {string} user Token de identificação do 
 *      usuário na API do Pink Majesty
 * @return {Promise.<array>} Quando resolve retorna
 *      uma array com todos os cartões salvos no db
 *      Se falhar retorna array com o erro.
 */
function ListCards(user = false){
    var response;
    if(!user){
    var user = localStorage.getItem("id_cliente");
    }
    return new Promise ((resolve , reject) => {
        $.ajax({
            type:"POST",
            url:url_geral+"stripe/ListCards.php",
            data:{"token":"H424715433852", "user":user },
            timeout: 5000,
                beforeSend: function(){ 
                    $('.loader').show();
                },
                success: function(resultado){
                    $('.loader').hide();
                    response = resultado['dados'];
                    resolve(response);
                },
            error:function(resultado){
                $('.loader').hide();
                response = resultado;
                reject(response);
            }
        });
    });
}

/**
 * Cria um SetupIntent para cadastro de novo cartão
 * na API do Stripe
 * 
 * @param {string} user Token de identificação do 
 *      usuário na API do Stripe
 * @return {Promise.<string>} Quando resolve retorna
 *      uma string com token do SetupIntent. Se falhar
 *      retorna string com o erro.
 */
function getClientSecretSetupIntent(user = false) {
    var response;
    if (!user){
        var user = localStorage.getItem("id_cliente");
    }
    return new Promise ((resolve , reject) => {
        $.ajax({
            type:"POST",
            url:url_geral+"stripe/SetupIntent.php",
            data:{"token":"H424715433852", "user":user },
            timeout: 2000,
                beforeSend: function(){ 
                    $('.loader').show();
                },
                success: function(resultado){
                    $('.loader').hide();
                    if (resultado['dados']['client_secret']){
                        response = resultado['dados']['client_secret'];
                        localStorage.setItem("client_secret", response);
                        resolve(response);
                    } else {
                        response = resultado['dados'];
                        reject(response);
                    }
                },
            error:function(resultado){
                $('.loader').hide();
                reject(resultado);
            }
        });
    });
}


//cria novo cartão
function NewCardStripe(stripe,clientSecret = false){
    var elements = stripe.elements();
    var elementStyles = {
        style:{
            base: {
            color: '#848484',
            fontFamily: 'inherit',
            fontSize: '1.1em',
            },
            invalid: {
            iconColor: '#FFC7EE',
            color: '#FFC7EE',
            },
        }
      };
      
    //var cardElement = elements.create('card',elementStyles);
    var cardCvc = elements.create('cardCvc',elementStyles);
    var cardExpiry = elements.create('cardExpiry',elementStyles);
    var cardNumber = elements.create('cardNumber',{
        style:{
            base: {
            color: '#848484',
            fontFamily: 'inherit',
            fontSize: '1.1em',
            },
            invalid: {
            iconColor: '#FFC7EE',
            color: '#FFC7EE',
            },
        },
        showIcon:true
    });
    //cardElement.mount('#card-element');
    cardNumber.mount('#cardNumber');
    cardExpiry.mount('#cardExpiry');
    cardCvc.mount('#cardCvc');

    var cardholderName = document.getElementById('cardholder-name');
    var cardZipCode = document.getElementById('zip-code');
    var cardButton = document.getElementById('card-button');
    
    cardButton.addEventListener('click', function() {
        $('.loader').show();
        setTimeout(function(){
            if(!clientSecret){
            clientSecret = localStorage.getItem("client_secret");
            }
            stripe.confirmCardSetup(
                clientSecret,
                {
                    payment_method: {
                        card: cardNumber,
                        billing_details: {
                        name: cardholderName.value,
                        address: {
                            postal_code: cardZipCode.value,
                        }
                        }
                    },
                }
            ).then(function(result) {
                console.log(result);
                if (result.error) {
                // Display error.message in your UI.
                    alert(result.error.message);
                    $('.loader').hide();
                } else {
                    alert("You authorise Pink-Majesty to send instructions to the financial institution that issued your card to take payments from your card account in accordance with the terms of the agreement with you.");
                    ListCards().then( function (ListCards){
                        $('.loader').hide();
                        updatedSelectCards();
                        $(".add_cartao").html('<div class="buttonentrar">Adicionar Nova Forma Pagamento</div>');
                        $(".sel_cartao").show();
                        $(".cad_cartao").hide();
                    }, error =>{
                        alert(error);
                        $('.loader').hide();
                    });
                }
            });
        }
        ,1100);
        
    });
}
//SELECT FORMA DE PAGAMENTO INICIO
function updatedSelectCards(tipo = 2){
    var user    = localStorage.getItem("id_cliente");
    $.ajax({
        type:"POST",
        async:true,
        crossDomain: true,
        url:url_geral+"lista_forma_pg.php",
        data:{"user":user,"tipo_order":tipo,"token":"H424715433852"},
        timeout: 100000,
            beforeSend: function(){ 
            $('.loader').show();
        },
        success: function(resultado){
            $('.loader').hide();
            if(resultado=='Token inválido'){
                alert('Token inválido');
            }else{
                $('.selectForma_pg').empty();
                $('.selectForma_pg').append(resultado);
            }
        },
        error: function(resultado) {
            $('.loader').hide();
            getSelect_forma_pg(tipo)
            //navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
        }
    });
    return false;
};
//SELECT FORMA DE PAGAMENTO FIM

/**
 * Cria um PaymentIntent para cadastro de novo pagamento
 * na API do Stripe com confirmação
 * 
 * @param {string} payment_method Token de identificação do 
 *      PaymentIntent na API do Stripe
 * @param {string} user Token de identificação do 
 *      usuário na API do Stripe
 * @param {string} amount Valor de cobrança do pagamento
 * @return {Promise.<string>} Quando resolve retorna
 *      uma string com token do PaymentIntent. Se falhar
 *      retorna string com o erro.
 */
function getPaymentIntent(payment_method, user = false ,amount = 500) {
    var response;
    if (!user){
        var user = localStorage.getItem("id_cliente");
    }
    return new Promise ((resolve , reject) => {
        $.ajax({
            type:"POST",
            url:url_geral+"stripe/PaymentIntent.php",
            data:{
                "token":"H424715433852",
                "user": user ,
                "amount": amount ,
                "payment_method": payment_method ,
              },
            timeout: 20000,
                beforeSend: function(){ 
                    $('.loader').show();
                },
                success: function(resultado){
                    $('.loader').hide();
                    if (resultado['dados']['payment_intent']){
                        response = resultado['dados']['payment_intent'];
                        localStorage.setItem("payment_intent", response);
                        resolve(response);
                    } else {
                        response = resultado['dados'];
                        reject(response);
                    }
                },
            error:function(resultado){
                $('.loader').hide();
                reject(resultado);
            }
        });
    });
}

/**
 * Busca no banco se há entrada do webhook relacionando o
 * cliente e o payment_intent
 * 
 * @param {string} user Token de identificação do 
 *      usuário na API do Stripe
 * @param {string} amount Valor de cobrança do pagamento
 * @return {Promise.<string>} Quando resolve retorna
 *      um JSON com os dados do webhook. Se falhar
 *      retorna string com o erro.
 */
function getWebhookResponse(user = false , payment_intent) {
    var response;
    if (!user){
        var user = localStorage.getItem("id_cliente");
    }
    return new Promise ((resolve , reject) => {
        $.ajax({
            type:"POST",
            url:url_geral+"stripe/GetWebhookResponse.php",
            data:{
                "token":"H424715433852",
                "user": user ,
                "payment_intent": payment_intent,
              },
            timeout: 2000,
                beforeSend: function(){ 
                    $('.loader').show();
                },
                success: function(resultado){
                    $('.loader').hide();
                    if (resultado['dados']['payment_intent']){
                        response = resultado['dados'];
                        resolve(resultado);
                    } else {
                        reject(resultado);
                    }
                },
            error:function(resultado){
                $('.loader').hide();
                reject(resultado);
            }
        });
    });
}

/**
 * Executa as ações de confirmação do pagamento da
 * taxa inicial. (Cadastra na agenda)
 * @param {string} user Token de identificação do 
 *      usuário na API do Pink Majesty
 * @return {Promise.<string>} Quando resolve retorna
 *      uma string com o custumer id na API do Stripe
 *      Se falhar retorna string com o erro.
 */
function confirmPayment(payment_intent){
var user   		= localStorage.getItem('id_cliente');
var servico   	= localStorage.getItem('servico');
var local   	= localStorage.getItem('id_endereco');
var data   		= localStorage.getItem('data');
var hora   		= localStorage.getItem('hora');
var forma_pg   	= localStorage.getItem('forma_pg');
var cupom   	= localStorage.getItem('cod_cupom');
var cpf_cupom	= localStorage.getItem('cpf_cupom');
var s_valor		= localStorage.getItem('s_valor');

setCadastrar_agenda(user, servico, local, data, hora, forma_pg, cupom, cpf_cupom, s_valor,payment_intent);
}


function setCadastrar_agenda(user, servico, local, data, hora, forma_pg, cupom, cpf_cupom, s_valor,payment_intent){
	
    var user		= user; 
    var servico		= servico; 
    var local		= local; 
    var data		= data; 
    var hora		= hora; 
    var forma_pg	= forma_pg; 
    var cupom		= cupom;
    var cpf_cupom	= cpf_cupom;
    var s_valor		= s_valor;
    var payment_intent = payment_intent;
    console.log({"user":user, "servico":servico, "local":local, "data":data, "hora":hora, "forma_pg":forma_pg, "cupom":cupom, "cpf":cpf_cupom, "s_valor":s_valor, "token":"H424715433852","payment_intent":payment_intent});
    $.ajax({
        type:"POST",
        dataType:"json",
        async:true,
        crossDomain: true,
        url: url_geral+"cadastrar_agenda.php",
        data:{"user":user, "servico":servico, "local":local, "data":data, "hora":hora, "forma_pg":forma_pg, "cupom":cupom, "cpf":cpf_cupom, "s_valor":s_valor, "token":"H424715433852","payment_intent":payment_intent},
        timeout: 100000, 
        beforeSend: function(resultado){
            $('.loader').show();
        },
        success: function(resultado){
            $('.loader').hide();
            if(resultado.erro==2){
                alert(resultado.dados);
                
                localStorage.setItem('id_endereco','');
                localStorage.setItem('data','');
                localStorage.setItem('hora','');
                localStorage.setItem('forma_pg','');
                localStorage.setItem('cod_cupom','');
                localStorage.setItem('cpf_cupom','');
                activate_page("#principal");
                console.log(resultado);
                //checkout_ccard(resultado.agenda,resultado.lNcartao,resultado.lNmcartao,resultado.lMesVenc,resultado.lAnoVenc,resultado.lCodigoSeg)
                
            }else{
                alert(resultado.dados);
                //activate_page("#cadastrar");
            }
        },
        error: function(resultado){
            $('.loader').hide();
            alert("Ops :( \n\nTivemos um problema ao comunicar com nosso servidor...\nVerifique sua conexão com a internet e tente novamente mais tarde, se persistir entre em contato com o suporte técnico.\nx013");
            console.log(resultado);
            //setCadastrar_agenda(user, servico, local, data, hora, forma_pg, cupom, cpf_cupom, s_valor);
            //navigator.notification.alert('Não foi poss�vel acessar!', 'CADASTRAR', 'Error', 'OK');
            //activate_page("#cadastrar");
        }			
    });
}
//})();
