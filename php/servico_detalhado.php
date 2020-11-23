<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));
$cliente  = anti_sql_injection(strip_tags(trim($_REQUEST['cliente'])));
$url_img = "https://igestaoweb.com.br/pinkmajesty/";

if($token=='H424715433852'){

	$user    = anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	$servico = anti_sql_injection(strip_tags(trim($_REQUEST['servico'])));
	
	$dados.= '
	<div class="paginaservico">
		<div class="infoservico">
	';
		
	$sql = "SELECT * FROM tb_produto WHERE id='$servico' AND categoria='SERVICOS' AND situacao='ATIVO' ORDER BY titulo ASC";
	$resultado   = mysql_query($sql) or die(mysql_error());
	$linha       = mysql_num_rows($resultado);
	if($linha > 0){
	$x=1;
		while($ln = mysql_fetch_assoc($resultado)){
			$id	 		= $ln['id'];
			$titulo 	= $ln['titulo'];
			$descricao  = $ln['descricao'];
			$estimado   = $ln['estimado'];
			$venda  	= $ln['venda'];
			$img  		= $ln['img'];
			//$status	    = $ln['status'];
			$titulo_exp = explode(' ',ucfirst(mb_strtolower($titulo,'UTF-8')));
			if($titulo_exp[0] == 'Pacote'){
				$fonte = 'precoservico_pacote';
			}else{
				$fonte = 'precoservico';
			}
			
			//<img src="'.$url_img.'igestao/imagem_serv/'.$img.'">
			$url_bsc = $url_img."imagem_serv/".$img;
			$dados.= '
			<div class="imageservico" style="background-image:url('.$url_bsc.'); margin-bottom: 16px;">
				<div class="'.$fonte.'">
					<h1 class="titulo_fotos" style="color:white; text-shadow: 0 0 20px #333333;">'.ucfirst(mb_strtolower($titulo,'UTF-8')).'</h1>
				</div>	
			</div>
			<div class="'.$fonte.'">
				<p>R$ '.str_replace(".", ",", $venda).'</p>
				<p class="sobreservico partes_descricao" style="margin: 0 20px 20px 20px;">'.$descricao.'</p>
				<p class="sobreservico partes_descricao" style="margin: 20px; text-align:center;">'.$estimado.' minuto(s) <br> (tempo estimado)</p>
			</div>	
			<a class="btn2 partes_descricao" alt="agendamento" id="'.$id.'">
				<div class="buttonentrar dellas" style="color:white; width: 75%">
					<i class="fa fa-calendar-check-o" aria-hidden="true" style="color:white;"></i> 
					Agendar
				</div>
			</a>
			';
			$x++;
		}
	}
	$dados.= '
		</div>
	</div>
	';
}
	echo $dados;

?>