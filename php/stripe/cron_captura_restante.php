<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<body>
<style>
table, th, td {
  border: 1px solid black;
}
table {
  width: 100%;
}
</style>
<?php
ini_set('display_errors', true);
ini_set('display_startup_errors', true);
error_reporting(E_ERROR);
ob_start();
require_once('../Connections/localhost.php');
require_once('../function/log.php');
require_once('../function/function.php');
require_once('config.php');
header('Content-type:text/html',TRUE);
echo "<b> ATUALIZAÇÃO DA AGENDA PELO WEBHOOK </b><br>";
echo 
"<table>
    <thead>
        <th> id_webhook </th>
        <th> payment_intent </th>
        <th> valor </th>
        <th> status </th>
        <th> id_agenda </th>
        <th> pagamento </th>
    </thead>
";
//ATUALIZA O STATUS DO PAGAMENTO NA AGENDA PELA TABELA WEBHOOK
$sql 		= "SELECT `id`, `cliente_id`, `customer`, `payment_intent`, `payment_method`, `valor`, `status` FROM `tb_webhook` WHERE `launched_at` IS NULL AND `payment_method` != ''";
$resultado 	= mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($resultado) > 0){
    while($ln = mysql_fetch_assoc($resultado)){
        $id = $ln['id'];
        $cliente_id = $ln['cliente_id'];
        $customer = $ln['customer'];
        $payment_intent = $ln['payment_intent'];
        $payment_method = $ln['payment_method'];
        $valor = $ln['valor'];
        $status = $ln['status'];

        //TAXA DE PAGAMENTO
        $sql2 		= "SELECT `id`, `pagamento` FROM `tb_agenda` WHERE `id_cliente`='$cliente_id' AND `payment_intent` ='$payment_intent' LIMIT 1";
        $resultado2 	= mysql_query($sql2) or die(mysql_error());
        if(mysql_num_rows($resultado2) > 0){
            while($ln2 = mysql_fetch_assoc($resultado2)){
                $pagamento = $ln2['pagamento'];
                if($status == 'paid'){
                    $pagamento = 1;
                } elseif($status == 'succeeded') {
                    $pagamento = 1;
                } elseif($status == 'pending') {
                    $pagamento = 0;
                } elseif($status == 'failed') {
                    $pagamento = 3;
                }
                $id2 = $ln2['id'];
                $sql3 		= "UPDATE `tb_agenda` SET `pagamento` = '$pagamento' WHERE `id` = $id2";
                $resultado3 	= mysql_query($sql3) or die(mysql_error());
                if($resultado3){
                    $sql4 		= "UPDATE `tb_webhook` SET `launched_at`=NOW() WHERE `id` = '$id'";
                    $resultado4 	= mysql_query($sql4) or die(mysql_error());
                }
            }
        } else {
            //PAGAMENTO RESTANTE
            $sql2 		= "SELECT `id`, `pagamento` FROM `tb_agenda` WHERE `id_cliente`='$cliente_id' AND `payment_intent_remaining` ='$payment_intent' LIMIT 1";
            $resultado2 	= mysql_query($sql2) or die(mysql_error());
            if(mysql_num_rows($resultado2)  > 0){
                while($ln2 = mysql_fetch_assoc($resultado2)){
                    $pagamento = $ln2['pagamento'];
                    if($status == 'paid'){
                        $pagamento = 2;
                    } elseif($status == 'succeeded') {
                        $pagamento = 2;
                    } elseif($status == 'pending') {
                        $pagamento = $ln2['pagamento'];
                    } elseif($status == 'failed') {
                        $pagamento = 4;
                    }
                    $id2 = $ln2['id'];
                    $sql3 		= "UPDATE `tb_agenda` SET `pagamento` = '$pagamento' WHERE `id` = $id2";
                    $resultado3 	= mysql_query($sql3) or die(mysql_error());
                    if($resultado3){
                        $sql4 		= "UPDATE `tb_webhook` SET `launched_at`=NOW() WHERE `id` = '$id'";
                        $resultado4 	= mysql_query($sql4) or die(mysql_error());
                    }
                }
            } else {
                $id2 = "Não encontrado";
            }
        }
        echo 
            "<tr>
                <td> $id </td>
                <td> $payment_intent </td>
                <td> $valor </td>
                <td> $status </td>
                <td> $id2 </td>
                <td> $pagamento </td>
            </tr>
            ";
    }
} else {
    echo 
    "<tr>
        <td colspan='6'> Nenhum webhook não lançado </td>
    </tr>
    ";
}
echo "</table><br><hr>";
echo "<b> CAPTURA DE PAGAMENTO RESTANTE </b><br>";
echo 
"<table>
    <thead>
        <th> id_agenda </th>
        <th> payment_intent </th>
        <th> situacao </th>
        <th> valor_total </th>
        <th> valor_cobrado </th>
        <th> valor_restante </th>
        <th> envio </th>
    </thead>
";

$sql 		= "SELECT * FROM `tb_agenda` WHERE `pagamento`='1' AND `situacao`='CONCLUIDO' AND `payment_intent_remaining` IS NULL";
$resultado 	= mysql_query($sql) or die(mysql_error());
if (mysql_num_rows($resultado) > 0){
    while($ln = mysql_fetch_assoc($resultado)){
        $id_agenda = $ln['id'];
        $payment_intent = $ln['payment_intent'];
        $situacao = $ln['situacao'];
        $valor_total = $ln['valor'];
        $sql2 		= "SELECT * FROM `tb_webhook` WHERE `payment_intent` ='$payment_intent'";
        $resultado2 	= mysql_query($sql2) or die(mysql_error());
        if (mysql_num_rows($resultado2) > 0){
            while($ln2 = mysql_fetch_assoc($resultado2)){
                $customer = $ln2['customer'];
                $valor_cobrado = $ln2['valor'];
                $payment_method = $ln2['payment_method'];
                $valor_restante = floatval($valor_total-$valor_cobrado);
                if (!empty($payment_method)){
                    $amount = number_format($valor_restante,2,"","");
                    $intent = \Stripe\PaymentIntent::create([
                        'customer' => $customer,
                        'payment_method' => $payment_method,
                        'amount' => $amount,
                        'currency' => 'BRL',
                        'confirm' => true,
                      ]);
                    if (!empty($intent->id)){
                        $payment_intent_remaining = $intent->id;
                        $erro = $log = "New PaymentIntent:".$payment_intent_remaining;
                        salvaLog($log,$email);
                        $sql3= "UPDATE `tb_agenda` SET `payment_intent_remaining`='$payment_intent_remaining' WHERE `id`='$id_agenda'";
                        $resultado3 	= mysql_query($sql3) or die(mysql_error());
                    } else {
                      $erro = "Erro ao criar novo PaymentIntent: <br><pre>".json_encode($intent,JSON_PRETTY_PRINT)."</pre>";
                    }
                } else {
                    $erro = "nenhum cartão anterior encontrado";
                }
            }
        } else {
            $erro = "nenhum webhook anterior encontrado";
        }
        echo 
        "<tr>
            <td> $id_agenda </td>
            <td> $payment_intent </td>
            <td> $situacao </td>
            <td> $valor_total </td>
            <td> $valor_cobrado </td>
            <td> $valor_restante </td>
            <td> $erro </td>
        </tr>
        ";
    }
} else {
    echo 
    "<tr>
        <td colspan='6'> Nenhum pagamento não capturado </td>
    </tr>
    ";
}
?>

</body>
</html>