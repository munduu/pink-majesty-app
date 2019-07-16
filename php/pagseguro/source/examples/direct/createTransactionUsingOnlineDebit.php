<?php

require_once "../../../../../Connections/localhost.php";
require_once "../../../../../function/function.php";
require_once "../../PagSeguroLibrary/PagSeguroLibrary.php";

class CreateTransactionUsingOnlineDebit
{

    public static function main()
    {
		$email      = 'contato@dellasbeleza.com.br';
		$token      = 'DCB1924DCC3F42DEB810C86B2DEA2BA8';
		$token_card = $_POST['token_card'];
		$hash       = $_POST['hash'];
		$id_produto = $_POST['produto'];
		$total      = $_POST['total'];
		$id_endereco= $_POST['id_endereco'];
		$id_cliente = $_POST['cliente'];
		$agenda     = $_POST['agenda'];
		$bancodd    = $_POST['banco'];
		
		// Instantiate a new payment request
        $directPaymentRequest = new PagSeguroDirectPaymentRequest();

        // Set the Payment Mode for this payment request
        $directPaymentRequest->setPaymentMode('DEFAULT');

        // Set the Payment Method for this payment request
        $directPaymentRequest->setPaymentMethod('EFT');

        //EMAIL PAGSEGURO
        $directPaymentRequest->setReceiverEmail($email);

        // Set the currency
        $directPaymentRequest->setCurrency("BRL");
		
		//DADOS DO PRODUTO
		$produto_cod    = mostra_var_r('tb_produto','id',$id_produto,'id');
		$produto_titulo = mostra_var_r('tb_produto','titulo',$id_produto,'titulo');
		$produto_preco  = $total;

        // Add an item for this payment request
        $directPaymentRequest->addItem(
            $produto_cod ,
            $produto_titulo,
            1,
            $produto_preco
        );

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
		
		$auth = rand(100000,999999);
		$directPaymentRequest->setReference($auth);
		
		$vowels = array(".", "-", "(", ")", " ");
		
		//DADOS CLIENTE
		$cliente_nome   = mostra_var_r('tb_cliente','nome',$id_cliente,'nome');
		$cliente_email  = mostra_var_r('tb_cliente','email',$id_cliente,'email');
		$cliente_tel    = str_replace($vowels, "", mostra_var_r('tb_cliente','tel1',$id_cliente,'tel1'));
		$cliente_ddd 	= substr($cliente_tel, 0, 2);
		$cliente_tel 	= substr($cliente_tel, 2);
		$cliente_cpf    = mostra_var_r('tb_cliente','cpf',$id_cliente,'cpf');
		$cliente_cpf    = str_replace($vowels, "", $cliente_cpf);
		
		$cliente_dtnas  = mostra_var_r('tb_cliente','data_nasc',$id_cliente,'data_nasc');
		$cliente_tipo   = 'CPF';
		
        // Set your customer information.
        // If you using SANDBOX you must use an email @sandbox.pagseguro.com.br
        $directPaymentRequest->setSender(
            $cliente_nome,
            $cliente_email,
            $cliente_ddd,
            $cliente_tel,
            $cliente_tipo,
            $cliente_cpf,
            true
        );

        $directPaymentRequest->setSenderHash($hash);
		
		$end_cep    = mostra_var_r('tb_enderecos','cep',$id_endereco,'cep');
		$end_rua    = mostra_var_r('tb_enderecos','logradouro',$id_endereco,'logradouro');
		$end_num    = mostra_var_r('tb_enderecos','numero',$id_endereco,'numero');
		$end_com    = mostra_var_r('tb_enderecos','complemento',$id_endereco,'complemento');
		$cod_bairro = mostra_var_r('tb_enderecos','bairro',$id_endereco,'bairro');
		$cod_cidade = mostra_var_r('tb_enderecos','cidade',$id_endereco,'cidade');
		$cod_estado = mostra_var_r('tb_enderecos','estado',$id_endereco,'estado');
		
		if(empty($cod_bairro)){
			$end_bairro == 'CENTRO';
		}else{
			$end_bairro = mostra_var_r('tb_bairro','nome',$cod_bairro,'nome');
		}		
		$end_cidade = mostra_var_r('tb_municipios','nome',$cod_cidade,'nome');
		$end_estado = mostra_var2_r('tb_estados','uf','iduf',$cod_estado,'uf');
		
		
        // Set shipping information for this payment request
        $sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $directPaymentRequest->setShippingType($sedexCode);
        $directPaymentRequest->setShippingAddress(
            $end_cep,
            $end_rua,
            $end_num,
            $end_com,
            $end_bairro,
            $end_cidade,
            $end_estado,
            'BRA'
        );

        // Set bank for this payment request
        $directPaymentRequest->setOnlineDebit(
            array(
                "bankName" => 'BANCO_BRASIL'
            )
        );

        try {
            
			//PRODUÇÃO
			$credentials = new PagSeguroAccountCredentials("contato@dellasbeleza.com.br","DCB1924DCC3F42DEB810C86B2DEA2BA8");
            $return = $directPaymentRequest->register($credentials);
            self::printTransactionReturn($return);

        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }

    public static function printTransactionReturn($transaction)
    {

        if ($transaction) {		
			$codigo= $transaction->getCode();
			$linkpg = $transaction->getPaymentLink();
        }

	  echo '{"codigo":"'.$codigo.'","dados":"'.$dados.'","linkpg":"'.$linkpg.'"}';
    }
}

CreateTransactionUsingOnlineDebit::main();