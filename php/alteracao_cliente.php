<?php
ob_start();

require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$user   	= anti_sql_injection(strip_tags(trim($_POST['user'])));
$tipo   	= anti_sql_injection(strip_tags(trim($_POST['tipo'])));
$token      = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$mascara    = anti_sql_injection(strip_tags(trim($_REQUEST['mascara'])));
$mascara2   = anti_sql_injection(strip_tags(trim($_REQUEST['mascara2'])));

if($token=='H424715433852'){
	
	$linha = 0;
	$sql 		= "SELECT * FROM tb_login WHERE tipo = 'Cliente' ORDER BY id";
	$resultado 	= mysql_query($sql) or die(mysql_error());
	while($ln = mysql_fetch_assoc($resultado)){
		$c_verif = md5($ln['email'].$ln['senha']);
		if($user == $c_verif){
			$linha ++;
			$id_cliente = $ln['id_cliente'];
			$email = $ln['email'];
		}
	}
	
	$dados .='<section id="paginacadastro">';
	
	$sql2 		= "SELECT * FROM tb_cliente WHERE id = '$id_cliente'";
	$resultado2 = mysql_query($sql2) or die(mysql_error());
	$ln2 		= mysql_fetch_assoc($resultado2);
	$nome 		= $ln2['nome'];
	$tel1 		= $ln2['tel1'];
	$tel2 		= $ln2['tel2'];
		
	if($linha > 0){
		if($tipo == 5){
			$dados.='
			<div class="paginainicial">
				<div class="formulariocadastro">';
			$sql = "SELECT * FROM tb_cartoes WHERE id_cliente='$id_cliente' ORDER BY principal DESC";
			$resultado   = mysql_query($sql) or die(mysql_error());
			$linha       = mysql_num_rows($resultado);
			$dados.='<h4 align="center">Selecione o cartão principal</h4>';
			if($linha == 0){
				$dados .= '<p class="list-group-item allow-badge widget" data-uib="twitter%20bootstrap/list_item" data-ver="1">Não há cartao cadastrados</p>'; 
			}else{
				$x=1;	
				while($ln = mysql_fetch_assoc($resultado)){
					$id	 			= $ln['id'];
					$numero_cartao 	= $ln['numero'];
					
					$dados.= 
					'
					<a class="list-group-item allow-badge widget" data-uib="twitter%20bootstrap/list_item" data-ver="1">
						<div class="alt_cartao_p" id="alt_cartao_p" alt="'.$id.'" style="display:inline-block;width:88%;" >
							****'.substr($numero_cartao, -4).
						'</div>
						<div class="del_cartao_p" id="del_cartao_p" alt="'.$id.'" style="display:inline-block;width:10%; text-align:center;">'
							.'<p align="right"><img src="images/delete.png" /></p>'.
						'</div>
					</a>
					';
					$x++;
				}
			}
			$dados.='
				</div>
			</div>';
		}
		if($tipo == 4){
			$dados.='
			<div class="paginainicial">
				<div class="formulariocadastro">';
			$sql = "SELECT * FROM tb_enderecos WHERE id_cliente='$id_cliente' ORDER BY principal DESC";
			$resultado   = mysql_query($sql) or die(mysql_error());
			$linha       = mysql_num_rows($resultado);
			$dados.='<h4 align="center">Selecione o endereço principal</h4>';
			if($linha == 0){
				$dados .= '<p class="list-group-item allow-badge widget" data-uib="twitter%20bootstrap/list_item" data-ver="1">Não há enderecos cadastrados</p>'; 
			}else{
				$x=1;	
				while($ln = mysql_fetch_assoc($resultado)){
					$id	 		= $ln['id'];
					$cep 		= $ln['cep'];
					$logradouro = $ln['logradouro'];
					$complemento= $ln['complemento'];
					$bairro 	= $ln['bairro'];
					$numero 	= $ln['numero'];
					$cidade 	= $ln['cidade'];
					
					$sql_m 		= "SELECT * FROM tb_municipios WHERE id='$cidade'";
					$resultado_m= mysql_query($sql_m) or die(mysql_error());
					$ln_m 		= mysql_fetch_assoc($resultado_m);
					$dados.= 
					'
					<a class="list-group-item allow-badge widget" data-uib="twitter%20bootstrap/list_item" data-ver="1">
						<div class="alt_endereco_p" id="alt_endereco_p" alt="'.$ln['id'].'" style="display:inline-block;width:88%;" >'
							.$logradouro.', nº '.$numero.', '.$complemento.' - '.$ln_m['nome'].
						'</div>
						<div class="del_endereco_p" id="del_endereco_p" alt="'.$ln['id'].'" style="display:inline-block;width:10%; text-align:center;">'
							.'<p align="right"><img src="images/delete.png" /></p>'.
						'</div>
					</a>
					';
					$x++;
				}
			}
			$dados.='
				</div>
			</div>';
		}
		if($tipo == 3){
			$dados.='
			<div class="paginainicial">
				<div class="formulariocadastro">
					<div class="apeanascadastro">
						<h4 align="center">Email</h4>
						<div>
							<input class="apg_campo email_alterar" disabled="disabled" placeholder="E-mail" type="email" size="30" maxlength="50" value="'.$email.'">
						</div>
					</div>
				</div>
			</div>
			';
			//<div class="entrar">
			//	<a class="alt_email" alt="'.$ln['id'].'"><div class="buttonentrar">Alterar</div></a>
			//</div>
		}
		if($tipo == 2){?>
			<script>
			$(document).ready(function(){ $(".tel1_alterar").mask("(99) 9999-9999");});
			$(document).ready(function(){ $(".tel2_alterar").mask("(99) 99999-9999");});
    		</script>
		<?
            $dados.='
			<div class="paginainicial">
				<div class="formulariocadastro">
					<div class="apeanascadastro">
						<h4 align="center">Atualizar Telefones</h4>
						<div align="center" style="display:inline-block;width:75%;height:50%;">
							<input class="apg_campo tel1_alterar" placeholder="Telefone" type="text" size="30" maxlength="15" alt="telefone2" value="'.$tel1.'">
						</div>
						
						<div align="center" style="display:inline-block;width:75%;height:50%;">
							<input class="apg_campo tel2_alterar" placeholder="Celular" type="text" size="30" maxlength="15" alt="telefone" value="'.$tel2.'">
						</div>
						<div>(apenas números com DDD)</div>
					</div>
					<div class="entrar">
						<a class="alt_tel" alt="'.$ln['id'].'"><div class="buttonentrar">Alterar</div></a>
					</div>
				</div>
			</div>
			';
		}
		if($tipo == 1){
			$dados.='
			<div class="paginainicial">
				<div class="formulariocadastro">
					<div class="apeanascadastro">
						<h4 align="center">Nome do Usuário</h4>
						<div>
							<input class="apg_campo nome_alterar" placeholder="Nome" type="text" size="50" maxlength="150" value="'.$nome.'">
						</div>
						<div class="entrar">
							<a class="alt_nome" alt="'.$ln['id'].'"><div class="buttonentrar">Alterar</div></a>
						</div>
					</div>
				</div>
			</div>
			';
		}
	}
	$dados.='</section>';
}
echo $dados;
?>