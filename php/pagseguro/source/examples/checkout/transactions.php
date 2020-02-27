<?php

    //Todo resto do código iremos inserir aqui.

    $email = 'alesneak@hotmail.com';
    $token = '0F356D86F9A44A59ABF6768C537CE214';
	$reference = '100000005';
	$datef = date('Y-m-d');
	$datei = date('Y-m-d', strtotime("-30 days"));

    $url = 'https://ws.pagseguro.uol.com.br/v2/transactions?initialDate='. $datei .'T00:00&finalDate=' . $datef .'T00:00&page=1&maxPageResults=100&email=alesneak@hotmail.com&token=9A7F06E24A1C4F8491CE62B6B9FA02F7';

    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $transaction= curl_exec($curl);
    curl_close($curl);

    if($transaction == 'Unauthorized'){
        print 'Unauthorized';

        exit;//Mantenha essa linha
    }
    $transaction = simplexml_load_string($transaction);
	
	print_r($transaction);
	
	$x = 0;
	
	while(($x <= $transaction->resultsInThisPage) and ($transaction->transactions->transaction[$x]->reference <> $reference)) {
		$x++;
	}
	if($transaction->transactions->transaction[$x]->reference) {
		$status = $transaction->transactions->transaction[$x]->status;
		print 'Status = ' . $status;
		
	}else{
		print 'Problemas para encontrar seu pagamento, por favor entre envie e-mail para contato@sixweb.com.br';
	}
	
