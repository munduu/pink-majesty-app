<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){

	$user    = anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
			$email		= $ln['email'];
		}
	}	
	
	$sql_a 		= "SELECT * FROM tb_cliente WHERE id='$id_cliente'";
	$resultado_a= mysql_query($sql_a) or die(mysql_error());
	$ln_a = mysql_fetch_assoc($resultado_a);
	//nome	tel1	tel2	email	sexo	data_nasc
	$id	 		= $ln_a['id'];
	$nome 		= $ln_a['nome'];
	$tel1 		= $ln_a['tel1'];
	$tel2 		= $ln_a['tel2'];
	$email 		= $ln_a['email'];
	$sexo 		= $ln_a['sexo'];
	$data_nasc	= $ln_a['data_nasc'];	
	
	$sqlg = "SELECT * FROM tb_bairro GROUP BY cidade";
	$qrg  = mysql_query($sqlg) or die (mysql_error());
	while($lng = mysql_fetch_assoc($qrg)){
		$g_bairro = $lng['cidade'];
		
		$sqlc2  = "SELECT * FROM tb_municipios WHERE nome = '$g_bairro' ORDER BY nome";
		$qrc2   = mysql_query($sqlc2) or die (mysql_error());
		$lnc2 	= mysql_fetch_assoc($qrc2);
		if(!empty($g_cidade)){
			$g_cidade .= ", ";
		}
		$g_cidade .= "'".$lnc2['id']."'";
	}
	$dados.= '<section>';
	$sqlc  = "SELECT * FROM tb_municipios WHERE id IN($g_cidade) ORDER BY nome";
	$qrc   = mysql_query($sqlc) or die (mysql_error());
	while($lnc = mysql_fetch_assoc($qrc)){ 
    	//<option value="$lnc['nome']"  if($lnc['nome']==$cidade_b){ echo 'selected="selected"';}>
        	//$lnc['nome']
        //</option>
    	$sqle  	= "SELECT * FROM tb_estados WHERE iduf = $lnc[iduf] ORDER BY nome";
		$qre   	= mysql_query($sqle) or die (mysql_error());
		$lne 	= mysql_fetch_assoc($qre);
		
		$uf		= $lne['uf']; 
	
        $dados.= '
            <div id="selecione">
                <div id="interna">
					<div class="alt_cidat" alt="'.$lnc['id'].'" id="alterar_cidade_atend">
						<div class="icones local">
							<i class="fa fa-map-marker" aria-hidden="true"></i>
						</div>
						<div class="textos">
							<h1><a class="estado'.$lnc['id'].'">'.$uf.'</a> - <a class="cidade'.$lnc['id'].'">'.$lnc['nome'].'</a></h1>
						</div>
					</div>
                </div>
            </div>
		';
	} 
	$dados.= '</section>';
	
	
	$x++;	
}
	echo $dados;

?>