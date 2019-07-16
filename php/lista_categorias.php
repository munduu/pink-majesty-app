<?
require_once('Connections/localhost.php');
require_once('function/function.php');
require_once('function/log.php');

$url_img = "http://igestaoweb.com.br/pinkmajesty/";

$token  = anti_sql_injection(strip_tags(trim($_REQUEST['token'])));

if($token=='H424715433852'){

	$user    = anti_sql_injection(strip_tags(trim($_REQUEST["user"])));
	/*
	$sql_l         = "SELECT * FROM tb_cliente WHERE email='$user' AND senha='$senha'";
	$resultado_l   = mysql_query($sql_l) or die(mysql_error());
	$linha_l       = mysql_num_rows($resultado_l);
	$ln_l          = mysql_fetch_assoc($resultado_l);
	$cod_preco = $ln_l['cod_preco'];
	*/
		
	$sql = "SELECT * FROM tb_categoria_serv WHERE status='ATIVO' ORDER BY titulo ASC";
	$resultado   = mysql_query($sql) or die(mysql_error());
	$linha       = mysql_num_rows($resultado);
	if($linha > 0){
	$x=1;
		while($ln = mysql_fetch_assoc($resultado)){
			$id	 		= $ln['id'];
			$titulo 	= $ln['titulo'];
			$sigla  	= $ln['sigla'];
			$img  		= $ln['img'];
			$status	    = $ln['status'];
			
			//<li class="swiper-slide btn_cat" alt="servicos" id="'.$id.'" style="background-image:url(http://dellasbeleza.com.br/igestao/atributos/categoria_img/'.$img.')">	
			$dados.= '		
			<li class="btn_cat" alt="servicos" id="'.$id.'" style="list-style-type: none; margin-bottom:5px;">
				<div class="imageservico" style="background-image:url('.$url_img.'atributos/categoria_img/'.$img.')">	
					<div class="'.$fonte.'">
						<h1 class="titulo_fotos" style="color:white; text-shadow: 0 0 20px #333333;">'.ucfirst(mb_strtolower($titulo,'UTF-8')).'</h1>
					</div>
				</div>
			</div></a>
			</li>
			';
			$x++;
		}
		/*$dados.= '
		<div style="height:8%"></div>
		';*/
	}
}
	echo $dados;

?>