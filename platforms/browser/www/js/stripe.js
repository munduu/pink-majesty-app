function MainStripe(stripe){
    getClient();
    ListCards();
    var cardButtonAdd = document.getElementById('card-button-add');
    cardButtonAdd.addEventListener('click', function(ev) {
        NewCardStripe(stripe);
        getClientSecretSetupIntent();        
    });
}
//cria novo cartão
function NewCardStripe(stripe){
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
            clientSecret = localStorage.getItem("client_secret");
            console.log("clientSecret:"+clientSecret);
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
                    ListCards();
                    $('.loader').hide();
                    $( document ).ready(function() {
                        getSelect_forma_pg();
                        $(".add_cartao").html('<div class="buttonentrar">Adicionar Nova Forma Pagamento</div>');
                        $(".sel_cartao").show();
                        $(".cad_cartao").hide();
                        });
                }
                
            });
        }
        ,1100);
    });
}

function getClientSecretSetupIntent() {
    var response;
    var gateway_id = localStorage.getItem("gateway_id");
    if (gateway_id == undefined){

        return 'erro no gateway_id';
    }
    var user = localStorage.getItem("id_cliente");
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
                if (resultado['dados']['client_secret'] != undefined){
                    response = resultado['dados']['client_secret'];
                    localStorage.setItem("client_secret", response);
                } else {
                    response = resultado['dados'];
                }
                console.log(resultado['dados']);
            },
        error:function(resultado){
            $('.loader').hide();
            console.log(resultado);
        }
    });
    return response;
}

function getClient(){
    var response;
    var user = localStorage.getItem("id_cliente");
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
                console.log(resultado['dados']);
                response = resultado['dados']['gateway_id'];
                localStorage.setItem("gateway_id", response);
            },
        error:function(resultado){
            $('.loader').hide();
        }
    });
    return response;
}

function ListCards(){
    var response;
    var user = localStorage.getItem("id_cliente");
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
                console.log(resultado['dados']);
                //response = resultado['dados']['gateway_id'];
            },
        error:function(resultado){
            $('.loader').hide();
        }
    });
    return response;
}

