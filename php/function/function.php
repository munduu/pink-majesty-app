<?
function sendMessage($msg,$token_id){
	$content = array(
		"en" => $msg,
		);
	
	$fields = array(
		'app_id' => "9e1f5f88-069a-44f5-8671-a3ef18a15439",
		'include_player_ids' => array($token_id),
		'data' => array("foo" => "bar"),
		'big_picture' => "http://dellasbeleza.com.br/site/sites/default/files/dellaspanel.png",
		'buttons' => array(array("id" => "id1", "text" => "Pedidos")),
		'contents' => $content
	);
	
	$fields = json_encode($fields);
	//print("\nJSON sent:\n");
	//print($fields);
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
											   'Authorization: Basic YzA2ZGY0ZTQtMzNjYS00MzliLTkzNTAtN2U5NjQ0YzEyYzc0'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
	curl_setopt($ch, CURLOPT_HEADER, FALSE);
	curl_setopt($ch, CURLOPT_POST, TRUE);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

	$response = curl_exec($ch);
	curl_close($ch);
	
	//return $response;
}

// SE VAZIO RECEBE ZERO
function sevazio($valor){
	if(empty($valor)){ return "0.00"; }	
	else{ return $valor; }
}

function sezero($valor){
	if($valor==0){ return ""; }	
	else{ return $valor; }
}

//UPDATE GERAL
function update_nota($bd,$tipo,$var2,$var){	
	$updateSQL = "UPDATE $bd SET $var2 WHERE $var";
	mysql_select_db($database_localhost, $localhost);
  	$Result1 = mysql_query($updateSQL, $localhost) or die(mysql_error());
	
    return $Result1;	
}

//SELECT 
function select_atributo_dado($id_atributo,$tipo,$id){
	if ($tipo==1){ $tipo = 'titulo';}
	if ($tipo==2){ $tipo = 'sigla';}
	$sql = "SELECT * FROM tb_atributo_dado WHERE id_atributo='$id_atributo' AND status='ATIVO' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

function select_atributo_dado_motivo($id_atributo,$tipo,$id){
	if ($tipo==1){ $tipo = 'titulo';}
	if ($tipo==2){ $tipo = 'sigla';}
	$sql = "SELECT * FROM tb_atributo_dado WHERE id_atributo='$id_atributo' AND status='ATIVO' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	echo '<option value=""> Todos </option>';
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}


function select_atributo_dado_var($id_atributo,$tipo,$var,$id){
	if ($tipo==1){ $tipo = 'titulo';}
	if ($tipo==2){ $tipo = 'sigla';}
	$sql = "SELECT * FROM tb_atributo_dado WHERE id_atributo='$id_atributo' AND status='ATIVO' AND $var ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

function mostra_atributo_dado($id_atributo,$tipo,$id,$var){
	if ($tipo==1){ $tipo = 'titulo';}
	if ($tipo==2){ $tipo = 'sigla';}
	$sql = "SELECT $var FROM tb_atributo_dado WHERE id_atributo='$id_atributo' AND id = '$id' AND status='ATIVO' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	$ln = mysql_fetch_assoc($qr); 
    echo $ln["$var"];	
}

function mostra_atributo_dado_r($id_atributo,$tipo,$id,$var){
	if ($tipo==1){ $tipo = 'titulo';}
	if ($tipo==2){ $tipo = 'sigla';}
	$sql = "SELECT $var FROM tb_atributo_dado WHERE id_atributo='$id_atributo' AND id = '$id' AND status='ATIVO' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	$ln = mysql_fetch_assoc($qr); 
    return $ln["$var"];	
}

function mostra_var_r($bd,$tipo,$id,$var){
	$sql = "SELECT $var FROM $bd WHERE id = '$id' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	$ln = mysql_fetch_assoc($qr); 
    return $ln["$var"];	
}

function mostra_var2_r($bd,$tipo,$var2,$id,$var){
	$sql = "SELECT * FROM $bd WHERE $var2 = '$id' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	$ln = mysql_fetch_assoc($qr); 
    return $ln["$var"];	
}

function mostra_var_where_r($bd,$tipo,$var2,$var){
	$sql = "SELECT $var FROM $bd WHERE $var2 ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	$ln = mysql_fetch_assoc($qr); 
    return $ln["$var"];	
}

function mostra_var_where_r2($bd,$tipo,$var2,$var,$var3){
	$sql = "SELECT $var FROM $bd WHERE $var2 ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	$ln = mysql_fetch_assoc($qr); 
    return $ln["$var3"];	
}

function select_atributo($tipo,$id){
	if ($tipo==1){ $tipo = 'titulo';}
	if ($tipo==2){ $tipo = 'sigla';}
	$sql = "SELECT * FROM tb_atributo WHERE status='ATIVO' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

function select_var($bd,$tipo,$var,$id){
	$sql = "SELECT * FROM $bd WHERE status='$var' ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

function select_var2($bd,$tipo,$var,$id){
	$sql = "SELECT * FROM $bd WHERE $var ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

function select_var3($bd,$tipo,$var,$id){
	$sql = "SELECT * FROM $bd WHERE $var";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; } else { $id_select = ''; }
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

function select_var_r($var,$tipo,$condicao,$id){
	$sql = "SELECT * FROM $var WHERE $condicao ORDER BY $tipo";
	$qr  = mysql_query($sql) or die (mysql_error());
	while($ln = mysql_fetch_assoc($qr)){ 
		if ($id == $ln['id']){ $id_select = 'selected="selected"'; }else{$id_select='';}
    	echo '<option value="'.$ln['id'].'" '.$id_select.'>'.$ln[$tipo].'</option>';
    }	
}

// ANTI SQL INJECTION
function anti_sql_injection($str) {
    if (!is_numeric($str)) {
        $str = get_magic_quotes_gpc() ? stripslashes($str) : $str;
        $str = function_exists('mysql_real_escape_string') ? mysql_real_escape_string($str) : mysql_escape_string($str);
    } return $str; 
}

function validaemail($email) {
    if(!ereg("^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([a-z,A-Z]){2,3}([0-9,a-z,A-Z])?$", $email)) { 
        return false;
    } else {
        return true;
    }
}

//DATA 	BKP
function databkp($data) {
	$data = explode ('-', $data);
	return $data = $data[2].'-'.$data[1].'-'.$data[0];	
}

//DATA 	BKP
function databr($data) {
	$data = explode ('-', $data);
	return $data = $data[2].'-'.$data[1].'-'.$data[0];	
}

//DATA BARRAS
function databar($data) {
	$data = explode ('/', $data);
	return $data = $data[2].'/'.$data[1].'/'.$data[0];	
}

// RESUMO DE TEXTOS
function resumo($frase,$num) {  
     if (strlen($frase) > $num) {  
        while (substr($frase,$num,1) <> ' ' && ($num < strlen($frase))){  
             $num++;  
        };  
     };  
  return substr($frase,0,$num);  
}; 

function resumo_corta($frase,$num) {   
  return substr($frase,0,$num);  
}; 

// FUNCTION MINIATURA
function gerar_mini($dir,$img,$nome,$w,$h) {
	if (!empty($img) and (!file_exists($dir.$nome.$img))){									
		$imgsize = getimagesize($dir.$img);		
			
		if ($imgsize[0] > $imgsize[1]){	$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_w = $w; $img_h = $img_y * $img_w / $img_x;
		}else{$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_h = $h+130; $img_w = $img_x * $img_h / $img_y;}
		
		$image_p = imagecreatetruecolor($img_w, $img_h);
		$extensao = end(explode('.', $dir.$img));
		
		if ($extensao == 'jpg' || $extensao == 'jpeg' || $extensao == 'JPG' || $extensao == 'JPEG') {$image = @imagecreatefromjpeg($dir.$img);} 
		if ($extensao == 'png' || $extensao == 'PNG') {$image = @imagecreatefrompng($dir.$img);} 
		if ($extensao == 'gif' || $extensao == 'GIF') {$image = @imagecreatefromgif($dir.$img);} 
		if ($extensao == 'bmp' || $extensao == 'BMP') {$image = @imagecreatefromwbmp($dir.$img);}	
		@imagecopyresampled($image_p, $image, 0, 0, 0, 0, $img_w, $img_h, $img_x, $img_y);
		@imagejpeg($image_p, $dir.$nome.$img, 100);
		@imagedestroy($image_p);	
	}
} 

// FUNCTION MINIATURA
function mostrar_mini($dir,$img,$nome,$w,$h) {
	if (!empty($img) and (file_exists($dir.$nome.$img))){									
		$imgsize = getimagesize($dir.$nome.$img);
		if ($imgsize[0] > $imgsize[1]){	$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_w = $w; $img_h = $img_y * $img_w / $img_x;
		}else{$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_h = $h+50; $img_w = $img_x * $img_h / $img_y;}
		echo '<img style="max-width:'.$img_w.'px;" align="absmiddle" border="0" src="'.$dir.$nome.$img.'" width="'.$img_w.'" height="'.$img_h.'" />';
	} elseif (!file_exists($dir.$img)) {
		$dir     = "img/";
		$img     = "imagem_teste.gif";
		$imgsize = getimagesize($dir.$img);
		if ($imgsize[0] > $imgsize[1]){	$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_w = $w; $img_h = $img_y * $img_w / $img_x;
		}else{$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_h = $h; $img_w = $img_x * $img_h / $img_y;}
		echo '<img style="max-width:'.$img_w.'px;" align="absmiddle" border="0" src="'.$dir.$img.'" width="'.$img_w.'" height="'.$img_h.'" />';
	} elseif (empty($img)){
		$dir     = "img/";
		$img     = "imagem_teste.gif";
		$imgsize = getimagesize($dir.$img);
		if ($imgsize[0] > $imgsize[1]){	$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_w = $w; $img_h = $img_y * $img_w / $img_x;
		}else{$img_x = $imgsize[0]; $img_y = $imgsize[1]; $img_h = $h; $img_w = $img_x * $img_h / $img_y;}
		echo '<img style="max-width:'.$img_w.'px;" align="absmiddle" border="0" src="'.$dir.$img.'" width="'.$img_w.'" height="'.$img_h.'" />';
	}
}

// CALCULAR HORA A MAIS
function calcula_hora_mais($inicio,$fim) {
	if (!is_array($inicio)) { $inicio = explode(":",$inicio); }
	if (!is_array($fim)) { $fim = explode(":",$fim); }
		$time_inicio     = (($inicio[0]*60)*60) + ($inicio[1]*60) + $inicio[2];
		$time_fim        = (($fim[0]*60)*60) + ($fim[1]*60) + $fim[2];
		$t[0] = floor(($time_fim + $time_inicio) / 60);
		$t[1] = floor((($time_fim + $time_inicio) / 60) / 60);
		$t[2] = $time_fim + $time_inicio;
		$h = $t[1];
		$m = $t[0] - ($t[1]*60);
		if ($m < 10) $m = "0$m";
			$s = $t[2] - (($h*60) + $m) * 60;
		if ($s < 10) $s = "0$s";
			$t[3] = "$h:$m:$s";
return $t[3];
}

function calcula_hora_menos($inicio,$fim) {
	if (!is_array($inicio)) { $inicio = explode(":",$inicio); }
	if (!is_array($fim)) { $fim = explode(":",$fim); }
		$time_inicio    = (($inicio[0]*60)*60) + ($inicio[1]*60) + $inicio[2];
		$time_fim        = (($fim[0]*60)*60) + ($fim[1]*60) + $fim[2];
		$t[0] = floor(($time_fim - $time_inicio) / 60);
		$t[1] = floor((($time_fim - $time_inicio) / 60) / 60);
		$t[2] = $time_fim - $time_inicio;
		$h = $t[1];
		$m = $t[0] - ($t[1]*60);
		if ($m < 10) $m = "0$m";
			$s = $t[2] - (($h*60) + $m) * 60;
		if ($s < 10) $s = "0$s";
			$t[3] = "$h:$m:$s";
return $t[3];
}
?>
