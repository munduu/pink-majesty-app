// Função única que fará a transação
//CIDADE-ESTADO
	//$("select[name=cep]").change(function(){
					
    //});
function getEndereco() {
		// Se o campo CEP não estiver vazio
					
		if($.trim($("#cep").val()) != ""){
			$.getScript("http://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val(), function(){
			
				if(resultadoCEP["resultado"]){
					$("#logradouro").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
					$("#bairro").val(unescape(resultadoCEP["bairro"]));
					$("#estado").val(unescape(resultadoCEP["uf"]));
					$("#cidade").val(unescape(resultadoCEP["cidade"]));
					$("#numero").focus();
					
					//navigator.notification.alert(resultadoCEP["uf"]+' - '+resultadoCEP["cidade"], 'CADASTRAR', 'Error', 'OK');
					$("select[name=cidade]").html('<option value="0">Carregando...</option>');	
					$.post(
						url_geral+"/function/cidade.php", 
						{estado:$("select[name=estado]").val(), cidade:resultadoCEP["cidade"]},
						function(valor){
							$("select[name=cidade]").html(valor);
							var cidade = $('select[name=cidade]').val();
							
							$("select[name=bairro]").html('<option value="0">Carregando...</option>');	
							$.post(
								url_geral+"/bairros.php", 
								{cidade:$("select[name=cidade]").val(), bairro:unescape(resultadoCEP["bairro"])},
								function(valor){
									$("select[name=bairro]").html(valor);
								}
							)
						}
					)
					
					
				
				}else{
					navigator.notification.alert('Endereço não encontrado!', 'title', 'Error', 'OK');
				}
			});				
		}
}