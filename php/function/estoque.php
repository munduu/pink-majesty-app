<?

					//quantidade entrada
			       $qtdentrada = 0;
				   $precocusto = 0;
				   $precovenda = 0;
				   
  	         $sqle = "SELECT * FROM tb_baixaestoque WHERE id_produto = '$id' AND tipo='IN' ORDER BY data ASC "; 
             $qre  = mysql_query($sqle) or die (mysql_error());
			 $conte = mysql_num_rows($qre);
			 
		    if ($resulte['qtd'] >= 0){		   
	         while($resulte = mysql_fetch_array($qre))
	             {
		           $qtdentrada += $resulte['qtd'];
				   $precocusto += $resulte['pc'];
				   $precovenda = $resulte['pv'];
				   
 		          }
				  
			}
			
				  if(($precocusto >= 0) and ($precovenda >= 0) and ($conte > 0))
				  {
				    $precocustomedio = $precocusto / $conte;
				    //$precocustomedio = number_format($precocustomedio,2,',','.');				 
				  }
				  
				  //verifica se o preco sugerido e menor que o de venda
				  if($precovendasug < $precovenda)
				     {
						 $precovendasug = $precovenda;
						 //$precovendasug   = number_format($precovendasug,2,',','.');	
					  }
            //quantidade saida
	        $sqls = "SELECT * FROM tb_baixaestoque WHERE id_produto = '$id' AND tipo='OUT' "; 
            $qrs  = mysql_query($sqls) or die (mysql_error());
			$conts = mysql_num_rows($qrs);
			
		if($conts > 0)
		  {
	        while($results = mysql_fetch_array($qrs))
	           {
	              $qtdsaida += $results['qtd'];
				  $precovenda += $results['p_venda'];
				   
		       }
			   $precovendamedio = $precovenda / $conts;
			  // $precovendamedio = number_format($precovendamedio,2,',','.');
		  }
?>