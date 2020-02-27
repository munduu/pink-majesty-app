<?php
$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/sessions?email=financeiro@vistatshirt.com&token=8E878550D14C4A80B9272CFBFEC75DC7';

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, Array('Content-Type: application/xml; charset=ISO-8859-1'));
curl_setopt($curl, CURLOPT_POSTFIELDS, $xml);
$xml= curl_exec($curl);

if($xml == 'Unauthorized'){
    //Insira seu código avisando que o sistema está com problemas, sugiro enviar um e-mail avisando para alguém fazer a manutenção 

    header('Location: paginaDeErro.php');
    exit;//Mantenha essa linha
}

curl_close($curl);

$xml= simplexml_load_string($xml);

if(count($xml -> error) > 0){
    //Insira seu código avisando que o sistema está com problemas, sugiro enviar um e-mail avisando para alguém fazer a manutenção, talvez seja útil enviar os códigos de erros.
    header('Location: paginaDeErro.php');
    exit;
}else{
	$id_session = $xml->id;
}
?>
<html>
<header>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>

<script type="text/javascript" src="https://stc.sandbox.pagseguro.uol.com.br/pagseguro/api/v2/checkout/pagseguro.directpayment.js"></script>

<script type="text/javascript">
//SETA O ID SESSION
PagSeguroDirectPayment.setSessionId('<?=$id_session?>');

//PEGA AS BANDEIRAS DOS CARTÕES
PagSeguroDirectPayment.getPaymentMethods({
    amount: 500.00,
    success: function(response) {
        //alert(response.paymentMethods.CREDIT_CARD.options.VISA.images.SMALL.path);
		$('.forma_pg').append('<li><img src="https://stc.pagseguro.uol.com.br'+response.paymentMethods.BOLETO.options.BOLETO.images.SMALL.path+'" /> '+response.paymentMethods.BOLETO.options.BOLETO.displayName+'</li>');
		$('.forma_pg').append('<li><img src="https://stc.pagseguro.uol.com.br'+response.paymentMethods.CREDIT_CARD.options.VISA.images.SMALL.path+'" /> '+response.paymentMethods.CREDIT_CARD.options.VISA.displayName+'<ul class="parcelas_visa"></ul></li>');
		$('.forma_pg').append('<li><img src="https://stc.pagseguro.uol.com.br'+response.paymentMethods.CREDIT_CARD.options.MASTERCARD.images.SMALL.path+'" /> '+response.paymentMethods.CREDIT_CARD.options.MASTERCARD.displayName+'<ul class="parcelas_master"></ul></li>');
    },
    error: function(response) {
        $('.error').html(response);
    },
    complete: function(response) {

    }
});

//PEGA O TOKEN DO CARTÃO
PagSeguroDirectPayment.createCardToken({
    cardNumber: 4111111111111111,
    brand: 'visa',
    cvv: 123,
    expirationMonth: 12,
    expirationYear: 2030,
    success: function(response) {
        //alert(response.card.token);
    },
    error: function(response) {
        alert(response);
    },
    complete: function(response) {

    }
});

//PEGA CASO CARTÃO DE CREDITO O PARCELAMENTO
PagSeguroDirectPayment.getInstallments({
    amount: 500.50,
    brand: 'visa',
    maxInstallmentNoInterest: 3,
    success: function(response) {
        var conta = 3;
		var x;
		for(x=0; x<conta; x++){
			$('.parcelas_visa').append('<li>'+response.installments['visa'][x]['quantity']+' x '+response.installments['visa'][x]['installmentAmount']+'</li>');
			$('.parcelas_master').append('<li>'+response.installments['mastercard'][x]['quantity']+' x '+response.installments['mastercard'][x]['installmentAmount']+'</li>');
		}
    },
    error: function(response) {
        //tratamento do erro
    },
    complete: function(response) {
        //tratamento comum para todas chamadas
    }
});
</script>

</header>
<body>
<div class="errors"></div>
<ul class="forma_pg"></ul>
</body>
</html>