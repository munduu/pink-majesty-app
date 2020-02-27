<?php 
ob_start();
//

/*
 * ***********************************************************************
 Copyright [2011] [PagSeguro Internet Ltda.]

 Licensed under the Apache License, Version 2.0 (the "License");
 you may not use this file except in compliance with the License.
 You may obtain a copy of the License at

 http://www.apache.org/licenses/LICENSE-2.0

 Unless required by applicable law or agreed to in writing, software
 distributed under the License is distributed on an "AS IS" BASIS,
 WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 See the License for the specific language governing permissions and
 limitations under the License.
 * ***********************************************************************
 */

require_once "../../PagSeguroLibrary/PagSeguroLibrary.php";
require_once('../../../../../Connections/localhost.php');
require_once('../../../../../function/function.php');
require_once('../../../../../function/log.php');

$id_produto  = anti_sql_injection(strip_tags(trim($_REQUEST['id_produto'])));
$id_cliente  = anti_sql_injection(strip_tags(trim($_REQUEST['id_cliente'])));
$id_endereco = anti_sql_injection(strip_tags(trim($_REQUEST['id_endereco'])));

$sqlp       = "SELECT * FROM tb_produto WHERE id='$id_produto'";		
$qrp        = mysql_query($sqlp) or die (mysql_error());	
$lnp        = mysql_fetch_assoc($qrp);
$titulo_produto = $lnp['titulo'];
$valor_produto  = $lnp['venda'];

$sqle       = "SELECT * FROM tb_cliente_endereco WHERE id='$id_endereco'";		
$qre        = mysql_query($sqle) or die (mysql_error());	
$lne        = mysql_fetch_assoc($qre);
$cep		= $lne['cep'];
$rua        = $lne['endereco'];
$numero     = $lne['numero'];
$complemento= $lne['complemento'];
$bairro     = $lne['bairro'];
$cidade     = $lne['cidade'];
$estado     = $lne['estado'];

$sqlc       = "SELECT * FROM tb_cliente WHERE id='$id_cliente'";		
$qrc        = mysql_query($sqlc) or die (mysql_error());	
$lnc        = mysql_fetch_assoc($qrc);
$nome		= $lnc['razao_social'];
$ddd        = $lnc['ddd'];
$tel        = $lnc['tel1'];
$cpf        = $lnc['cpfcnpj'];

/***
 * Provides for user a option to configure their credentials without changes in PagSeguroConfigWrapper.php file.
 */ 
class PagSeguroConfigWrapper
{
    public static function getConfig()
    {
        $PagSeguroConfig = array();

        $PagSeguroConfig['environment'] = "sandbox"; // production, sandbox

        $PagSeguroConfig['credentials'] = array();
        $PagSeguroConfig['credentials']['email'] = "v78090945776245659477@sandbox.pagseguro.com.br";
        $PagSeguroConfig['credentials']['token']['production'] = "";
        $PagSeguroConfig['credentials']['token']['sandbox'] = "PUB659C1587AEC54766969504719C886FBD";
        $PagSeguroConfig['credentials']['appId']['production'] = "";
        $PagSeguroConfig['credentials']['appId']['sandbox'] = "app7850676728";
        $PagSeguroConfig['credentials']['appKey']['production'] = "";
        $PagSeguroConfig['credentials']['appKey']['sandbox'] = "D2E94129373733B774E48F9584269FB2";

        $PagSeguroConfig['application'] = array();
        $PagSeguroConfig['application']['charset'] = "UTF-8"; // UTF-8, ISO-8859-1

        $PagSeguroConfig['log'] = array();
        $PagSeguroConfig['log']['active'] = false;
        // Informe o path completo (relativo ao path da lib) para o arquivo, ex.: ../PagSeguroLibrary/logs.txt
        $PagSeguroConfig['log']['fileLocation'] = "../PagSeguroLibrary/logs.txt";

        return $PagSeguroConfig;
    }
}

/**
 * Class with a main method to illustrate the usage of the domain class PagSeguroPaymentRequest
 */
class CreatePaymentRequest
{

    public static function main()
    {
        // Instantiate a new payment request
        $paymentRequest = new PagSeguroPaymentRequest();

        // Set the currency
        $paymentRequest->setCurrency("BRL");

        // Add an item for this payment request

        //$paymentRequest->addItem($id_produto, $titulo_produto, 2, $valor_produto);


        // Add another item for this payment request
        $paymentRequest->addItem('0002', 'Notebook rosa', 2, 560.00);

        // Set a reference code for this payment request. It is useful to identify this payment
        // in future notifications.
        $paymentRequest->setReference($_GET['nid']);

        // Set shipping information for this payment request
        //$sedexCode = PagSeguroShippingType::getCodeByType('SEDEX');
        $paymentRequest->setShippingType(3);
        $paymentRequest->setShippingAddress(
            $cep,
            $rua,
            $numero,
            $complemento,
            $bairro,
            $cidade,
            $estado,
            'BRA'
        );

        // Set your customer information.
        $paymentRequest->setSender(
            $nome,
            'c75469865356722157803@sandbox.pagseguro.com.br',
            $ddd,
            $tel,
            'CPF',
            $cpf
        );

        // Set the url used by PagSeguro to redirect user after checkout process ends
        $paymentRequest->setRedirectUrl("http://www.vistatshirt.com");

        // Add checkout metadata information
        /*$paymentRequest->addMetadata('PASSENGER_CPF', '15600944276', 1);
        $paymentRequest->addMetadata('GAME_NAME', 'DOTA');
        $paymentRequest->addMetadata('PASSENGER_PASSPORT', '23456', 1);*/

        // Another way to set checkout parameters
        $paymentRequest->addParameter('notificationURL', 'http://www.vistatshirt.com');
        $paymentRequest->addParameter('senderBornDate', '21/05/1986');
        /*$paymentRequest->addIndexedParameter('itemId', '0003', 3);
        $paymentRequest->addIndexedParameter('itemDescription', 'Notebook Preto', 3);
        $paymentRequest->addIndexedParameter('itemQuantity', '1', 3);
        $paymentRequest->addIndexedParameter('itemAmount', '200.00', 3);*/

        // Add discount per payment method
        /*$paymentRequest->addPaymentMethodConfig('CREDIT_CARD', 1.00, 'DISCOUNT_PERCENT');
        $paymentRequest->addPaymentMethodConfig('EFT', 2.90, 'DISCOUNT_PERCENT');
        $paymentRequest->addPaymentMethodConfig('BOLETO', 10.00, 'DISCOUNT_PERCENT');
        $paymentRequest->addPaymentMethodConfig('DEPOSIT', 3.45, 'DISCOUNT_PERCENT');
        $paymentRequest->addPaymentMethodConfig('BALANCE', 0.01, 'DISCOUNT_PERCENT');*/

        // Add installment without addition per payment method
        /*$paymentRequest->addPaymentMethodConfig('CREDIT_CARD', 6, 'MAX_INSTALLMENTS_NO_INTEREST');

        // Add installment limit per payment method
        $paymentRequest->addPaymentMethodConfig('CREDIT_CARD', 8, 'MAX_INSTALLMENTS_LIMIT');

        // Add and remove a group and payment methods
        $paymentRequest->acceptPaymentMethodGroup('CREDIT_CARD', 'DEBITO_ITAU');      
        $paymentRequest->excludePaymentMethodGroup('BOLETO', 'BOLETO');*/

        try {

            /*
             * #### Credentials #####
             * Replace the parameters below with your credentials
             * You can also get your credentials from a config file. See an example:
             * $credentials = new PagSeguroAccountCredentials("vendedor@lojamodelo.com.br",
             * "E231B2C9BCC8474DA2E260B6C8CF60D3");
             */

            // seller authentication
            $credentials = PagSeguroConfig::getAccountCredentials();

            // application authentication
            //$credentials = PagSeguroConfig::getApplicationCredentials();

            //$credentials->setAuthorizationCode("E231B2C9BCC8474DA2E260B6C8CF60D3");

            // Register this payment request in PagSeguro to obtain the payment URL to redirect your customer.
            $url = $paymentRequest->register($credentials);

            self::printPaymentUrl($url);

        } catch (PagSeguroServiceException $e) {
            die($e->getMessage());
        }
    }
	
	

    public static function printPaymentUrl($url)
    {
        if ($url) {

            header('Location: ' . $url);
        }
    }
}

CreatePaymentRequest::main();