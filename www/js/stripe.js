function MainStripe(stripe){
    getClient()
    .then(function(getClient){
        console.log("1 - getClient done");
        console.log(getClient);
        ListCards()
        .then(function(ListCards){
            console.log("2 - ListCards done");
            console.log(ListCards);
            var cardButtonAdd = document.getElementById('card-button-add');
            cardButtonAdd.addEventListener('click', function(cardButtonAdd) {
                getClientSecretSetupIntent(getClient)
                .then(function(getClientSecretSetupIntent){
                    console.log("3 - getClientSecretSetupIntent done");
                    console.log(getClientSecretSetupIntent);
                    NewCardStripe(stripe);
                }, error => {
                    console.log("3 - getClientSecretSetupIntent fail")
                    console.log(error);
                    });
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
                beforeSend: function(resultado){ 
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
                beforeSend: function(resultado){ 
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
 * @param {string} gateway_id Token de identificação do 
 *      usuário na API do Stripe
 * @return {Promise.<string>} Quando resolve retorna
 *      uma string com token do SetupIntent. Se falhar
 *      retorna string com o erro.
 */
function getClientSecretSetupIntent(gateway_id = false) {
    var response;
    if (!gateway_id){
        var gateway_id = localStorage.getItem("gateway_id");
    }
    var user = localStorage.getItem("id_cliente");
    if (!user){
        getClientSecretSetupIntent();
    }
    return new Promise ((resolve , reject) => {
        $.ajax({
            type:"POST",
            url:url_geral+"stripe/SetupIntent.php",
            data:{"token":"H424715433852", "user":user },
            timeout: 2000,
                beforeSend: function(resultado){ 
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
    
    cardButton.addEventListener('click', function(ev) {
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
                        updatedSelect();
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
function updatedSelect(tipo = 2){
    var user    = localStorage.getItem("id_cliente");
    $.ajax({
        type:"POST",
        async:true,
        crossDomain: true,
        url:url_geral+"lista_forma_pg.php",
        data:{"user":user,"tipo_order":tipo,"token":"H424715433852"},
        timeout: 100000,
            beforeSend: function(resultado){ 
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

function confirmPayment(stripe ,PAYMENT_INTENT_CLIENT_SECRET){
    stripe.confirmCardPayment('{PAYMENT_INTENT_CLIENT_SECRET}', {
    payment_method: '{PAYMENT_METHOD_ID}',
    })
    .then(function(result) {
        console.log(result);
    // Handle result.error or result.paymentIntent
    });

}

