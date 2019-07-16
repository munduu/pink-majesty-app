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
	
	//$tel1ddd = explode(' ',$tel1);
	//$tel2ddd = explode(' ',$tel2);
	
	//$telefone	= '('.$tel1ddd[0].') '.$tel1ddd[1];
	//$celular	= '('.$tel2ddd[0].') '.$tel2ddd[1];		
			
	$sql_e 		= "SELECT * FROM tb_enderecos WHERE id_cliente='$id' AND principal='1' ORDER BY id ASC";
	$resultado_e= mysql_query($sql_e) or die(mysql_error());
	$ln_e 		= mysql_fetch_assoc($resultado_e);
			
	$logradouro 	= $ln_e['logradouro'];
	$numero 		= $ln_e['numero'];
	$complemento 	= $ln_e['complemento'];
	$bairro 		= $ln_e['bairro'];
	$cidade 		= $ln_e['cidade'];
	$estado 		= $ln_e['estado'];
	$referencia 	= $ln_e['referencia'];
	
	$sql_c 		= "SELECT * FROM tb_cartoes WHERE id_cliente='$id' AND principal='1' ORDER BY id ASC";
	$resultado_c= mysql_query($sql_c) or die(mysql_error());
	$ln_c 		= mysql_fetch_assoc($resultado_c);
			
	$numero_cartao	= $ln_c['numero'];
			
	$sql_b 		= "SELECT * FROM tb_bairro WHERE id = '$bairro' ORDER BY id ASC";
	$resultado_b= mysql_query($sql_b) or die(mysql_error());
	$ln_b 		= mysql_fetch_assoc($resultado_b);
			
	$nome_bairro 	= $ln_b['nome'];
		
	$sql_ci 		= "SELECT * FROM tb_municipios WHERE id = '$cidade' ORDER BY id ASC";
	$resultado_ci	= mysql_query($sql_ci) or die(mysql_error());
	$ln_ci 			= mysql_fetch_assoc($resultado_ci);
	
	$cidade 		= $ln_ci['nome'];
	
	$sql_es 		= "SELECT * FROM tb_estados WHERE iduf = '$estado'";
	$resultado_es	= mysql_query($sql_es) or die(mysql_error());
	$ln_es 			= mysql_fetch_assoc($resultado_es);		
	
	$estado 		= $ln_es['uf'];

	$dados.= '
	<section>
		<!--<div class="imageperfilusuario">
			<img src="images/makeup.png">
		</div>-->
		<div id="selecione" style="margin-top:10px;">
			<a class="btn2" alt="alt_cli" id="alterar_nome">
				<div id="interna">
					<div class="icones local">
						<i class="fa fa-user-circle-o" aria-hidden="true"></i>
					</div>
					<div class="textos">
						<h1>Nome</h1>
						<p style="padding:10px 0;">'.$nome.'</p>
					</div>
					<div class="editar">
						<img src="images/iconelapis.png">
					</div>
				</div>
			</a>
			
			<a class="btn2" alt="alt_cli" id="alterar_tel">	
				<div id="interna">
					<div class="icones telefone">
						<i class="fa fa-phone" aria-hidden="true"></i>
					</div>
					<div class="textos">
						<h1>Telefone</h1>
						<p style="padding:10px 0;">'.$tel1.' <br /> '.$tel2.'</p>
					</div>
					<div class="editar">
						<img src="images/iconelapis.png">
					</div>
				</div>
			</a>
			
			<div id="interna">
				<div class="icones email">
					<i class="fa fa-envelope" aria-hidden="true"></i>
				</div>
				<div class="textos">
					<h1>E-mail</h1>
					<p style="padding:10px 0;">'.$email.'</p>
				</div>';
				/*
				<div class="editar">
					<a class="btn2" alt="alt_cli" id="alterar_email"><img src="images/iconelapis.png"></a>
				</div>
				*/
			$dados.= '
			</div>

			<a class="btn2" alt="alt_cli" id="alterar_pass">	
			<div id="interna">
				<div class="icones senha">
					<i class="fa fa-envelope" aria-hidden="true"></i>
				</div>
				<div class="textos">
					<h1>Senha</h1>
					<p style="padding:10px 0;">******</p>
				</div>
				<div class="editar">
				 <img src="images/iconelapis.png">
				</div>';
			$dados.= '
			</div></a>
			
			<a class="btn2" alt="alt_cli" id="alterar_end">	
				<div id="interna">
					<div class="icones enderecoconta">
						<i class="fa fa-map-marker" aria-hidden="true"></i>
					</div>
					<div class="textos">
						<h1>Endereço</h1>
						<p style="padding:10px 0;">'.$logradouro.', '.$numero.'. Bairro '.$nome_bairro.' - '.$cidade.'/'.$estado.'</p>
					</div>
					<div class="editar">
						<img src="images/iconelapis.png">
					</div>
				</div>
			</a>
			
			<a class="btn2" alt="alt_cli" id="alterar_crt">
				<div id="interna">
					<div class="icones enderecoconta">
						<i class="fa fa-credit-card" aria-hidden="true"></i>
					</div>
					<div class="textos">
						<h1>Forma de Pagamento Principal</h1>
						<p style="padding:10px 0;">****'.substr($numero_cartao, -4).'</p>
					</div>
					<div class="editar">
						<img src="images/iconelapis.png">
					</div>
				</div>
			</a>
						
			<a class="btn2 partes_descricao" alt="agendamento" id="21">
				<div class="buttonentrar logout" style="color:white; width: 75%">
					Sair
				</div>
			</a>

		</div>
	</section>
	';
	$x++;	
}
	echo $dados;

?>