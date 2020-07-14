
(function()
{
 "use strict";
 $( document ).ready(function() {
    var stripe = Stripe('pk_test_HwOzl6pIove5P3TZopMpaOsg001qQzh3P6');
    MainStripe(stripe);
    });

function MainStripe(stripe){
    var user    = localStorage.getItem("id_cliente");
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
                    });
            });

            $(document).on("click", ".cad_agendar", function(evt)
            {
                var forma_pg = localStorage.getItem('forma_pg');
                console.log(forma_pg);
                if (forma_pg){
                    setTimeout(function(){
                        if ($('#termosusoAgendamento').prop('checked') == false){	
                            return false;
                        } else {
                            getPaymentIntent(forma_pg).then(function(resultado){
                                console.log(resultado);
                            },function(erro){
                                console.log("erro getPaymentIntent");
                                console.log(erro);
                            });
                        }
                    },1000);
                }
            });

        }, error => {
            console.log("2 - ListCards fail")
            console.log(error);
        });

        }, error => {
            console.log("1 - getClient fail")
            console.log(error);
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
 *  @param {string} amount Valor de cobrança do pagamento
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
            timeout: 2000,
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
                        console.log("Erro 1");
                        reject(response);
                    }
                },
            error:function(resultado){
                $('.loader').hide();
                console.log("Erro 2");
                reject(resultado);
            }
        });
    });
}
})();
