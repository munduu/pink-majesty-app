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
		
	$dados.= '<div class="paginaservico">';
	
	$sql = "SELECT * FROM tb_produto WHERE departamento='$servico' AND categoria='SERVICOS' AND situacao='ATIVO' ORDER BY titulo ASC";
	$resultado   = mysql_query($sql) or die(mysql_error());
	$linha       = mysql_num_rows($resultado);
	if($linha > 0){
	$x=1;
		while($ln = mysql_fetch_assoc($resultado)){
			$id	 		= $ln['id'];
			$titulo 	= $ln['titulo'];
			$descricao  = $ln['descricao'];
			$venda  	= $ln['venda'];
			$img  		= $ln['img'];
			//$status	    = $ln['status'];
			/*
			<a href="sobreoservico.html"><div class="infoservico">
				<div class="imageservico">
					<img src="images/004.png">
				</div>
				<div class="precoservico">
					<h1>Maquiagem</h1>
					<p>R$ 50,00</p>
				</div>	
			</div></a>
			<img src="https://192.168.1.200:8080/inova/dellas/atributos/categoria_img/'.$img.'">
			*/
			$titulo_exp = explode(' ',ucfirst(mb_strtolower($titulo,'UTF-8')));
			if($titulo_exp[0] == 'Pacote'){
				$fonte = 'precoservico_pacote';
			}else{
				$fonte = 'precoservico';
			}
			//<img src="'.$url_img.'igestao/imagem_serv/'.$img.'">
			$url_bsc = $url_img."imagem_serv/".$img;
			$dados.= '
			<a class="btn2" alt="detalhado" id="'.$id.'" style="margin-bottom:5px;"><div class="infoservico">
				<div class="imageservico" style="background-image:url('.$url_bsc.')">	
					<div class="'.$fonte.'">
						<h1 class="titulo_fotos" style="color:white; text-shadow: 0 0 20px #333333;">'.ucfirst(mb_strtolower($titulo,'UTF-8')).'</h1>
					</div>
					<div class="valor_'.$id.'" alt="'.str_replace(".", ",", $venda).'"></div>
				</div>
			</div></a>
			';
			$x++;
		}
	}else{
		$dados.= '<h3 align="center" style="margin-top:50%">Nenhum Serviço Nessa Categoria !</h3>';
	}
	$dados.= '</div>';
}
	echo $dados;

?>