<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');
// seleciono todos com a taxa paga ok
$sql_a             = "SELECT * FROM `tb_agenda` WHERE `pagamento`=1 ORDER BY `id` LIMIT 1";
$resultado_a       = mysql_query($sql_a) or die(mysql_error());
$linha_a           = mysql_num_rows($resultado_a);
while ($ln_a        = mysql_fetch_assoc($resultado_a)) {
    $agenda = $ln_a['id'];
    $cod_cupom                = $ln_a['cupom'];
    $id_cliente_des           = $ln_a['id_cliente'];
    $id_colaborador_des       = $ln_a['id_colaborador'];
    $forma_pg                 = $ln_a['forma_pg'];
    $valor                    = $ln_a['valor'];
    $data                     = $ln_a['data'];
    $hora_fim                 = $ln_a['hora_fim'];
    $hora_ini                 = $ln_a['hora_ini'];
    if (!empty($data) && !empty($hora_fim) && !empty($hora_ini)) {
        $data_ini = str_replace("/", "-", $data) . " " . $hora_ini . ":00";
        $data_fim = str_replace("/", "-", $data) . " " . $hora_fim . ":59";
        $data_fim = strtotime($data_fim . " +1hour");
        //executo apenas se passar uma hora do final
        if (strtotime("now") >= $data_fim) {
            /*
            ENVIAR REQUEST DE CAPTURAR O RESTANTE.
            */
            $sql_forma_pg             = "SELECT * FROM `tb_cartoes` WHERE `id` = '$forma_pg'";
            $resultado_forma_pg       = mysql_query($sql_forma_pg) or die(mysql_error());
            $linha_forma_pg           = mysql_num_rows($resultado_forma_pg);
            if ($linha_forma_pg > 0) {
                $ln_forma_pg              = mysql_fetch_assoc($resultado_forma_pg);
                if (!empty($ln_forma_pg['numero'])) {
                    $valor  = floatval(str_replace(",", ".", $valor));
                    $valor  = intval(100 * $valor);
                    $amount = $valor - 100.00; //valor total menos a taxa
                    $curl = curl_init();
                    curl_setopt_array($curl, array(
                        CURLOPT_URL => 'https://igestaoweb.com.br/pinkmajesty/app_new/php/cielo_app/api.php',
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => '',
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => 'POST',
                        CURLOPT_POSTFIELDS =>
                        array(
                            'action' => 'chargeWithCard',
                            'MerchantOrderId' => $agenda,
                            'Name' => $ln_forma_pg['nome_impresso'],
                            'CardNumber' => $ln_forma_pg['numero'],
                            'Holder' => $ln_forma_pg['nome_impresso'],
                            'ExpirationDate' => "$ln_forma_pg[mes_val]/$ln_forma_pg[ano_val]",
                            'SecurityCode' => $ln_forma_pg['cod_seg'],
                            'Amount' => intval($amount)
                        ),
                    ));
                    $response = curl_exec($curl);
                    curl_close($curl);
                    $response = substr($response, 3);
                    $response = trim($response);
                    $json = json_decode($response, TRUE);
                    if (!empty($json)) {
                        if (!empty($json['Payment']['ReturnMessage'])) {
                            $message_pagamento = $json['Payment']['ReturnMessage'];
                        } else {
                            $message_pagamento = "Erro ao processar o pagamento";
                        }
                        $sqlT = "SELECT token_id FROM tb_login WHERE id_cliente = '$id_cliente_des' ";
                        $qrT  = mysql_query($sqlT) or die(mysql_error());
                        $lnT  = mysql_fetch_assoc($qrT);
                        $tokens = $lnT['token_id'];
                        //PUSH
                        if (!empty($tokens)) {
                            $title    = "Pink Majesty";
                            $message  = "Pagamento do pedido #$agenda: $message_pagamento";
                            sendMessage($message, $tokens);
                        } else {
                            $error = true;
                            $msg_return = "Sem Token push agenda($agenda)";
                        }
                    } else {
                        $error = true;
                        $msg_return = "Sem numero de cartao agenda($agenda)";
                    }
                } else {
                    $error = false;
                    $msg_return = "Sem numero de cartao agenda($agenda)";
                }
            } else {
                $error = true;
                $msg_return = "Sem forma de pagamento agenda($agenda)";
            }
            $error = false;
            $msg_return = "Cobranca completa (" . strtotime("now") . ">$data_fim) agenda($agenda)";
        } else {
            $error = false;
            $msg_return = "Fora do horario (" . strtotime("now") . "<$data_fim) agenda($agenda)";
        }
    } else {
        $error = true;
        $msg_return = "Erros nos campos data, hora_ini ou hora_fim agenda($agenda)";
    }
    echo json_encode(array('error' => $error, 'msg' => $msg_return));
    $msg = $msg_return;
    $now = date('Y-m-d H:i:s');
    $ip  = $_SERVER['REMOTE_ADDR'];
    $sql = "INSERT INTO `logs` (`hora`, `ip`, `mensagem`, `user`) VALUES ('$now', '$ip', '$msg', 'CRON')";
    mysql_query($sql) or die(mysql_error());
}
