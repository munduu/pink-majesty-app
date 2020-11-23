$(document).ready(function(){
		
	$('.div-ajax-carregamento-pagina').fadeOut('fast');
 	$('#todo').fadeIn('fast');
	
	jQuery(function(){
		jQuery('ul.sf-menu').superfish();
	});
 		
 	//Tooltips
	$(".tip_trigger").hover(function(){
		tip = $(this).find('.tip');
		tip.show(); //Show tooltip
	}, function() {
		tip.hide(); //Hide tooltip		  
	}).mousemove(function(e) {
		var mousex = e.pageX + 20; //Get X coodrinates
		var mousey = e.pageY + 20; //Get Y coordinates
		var tipWidth = tip.width(); //Find width of tooltip
		var tipHeight = tip.height(); //Find height of tooltip
		
		//Distance of element from the right edge of viewport
		var tipVisX = $(window).width() - (mousex + tipWidth);
		//Distance of element from the bottom of viewport
		var tipVisY = $(window).height() - (mousey + tipHeight);
		  
		if ( tipVisX < 20 ) { //If tooltip exceeds the X coordinate of viewport
			mousex = e.pageX - tipWidth - 20;
		} if ( tipVisY < 20 ) { //If tooltip exceeds the Y coordinate of viewport
			mousey = e.pageY - tipHeight - 20;
		} 
		tip.css({  top: mousey, left: mousex });
	});
	//Tooltips
	
	//CIDADE-ESTADO
	$("select[name=estado]").change(function(){
    	$("select[name=cidade]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	
	 //CIDADE-ESTADO
	$("select[name=estado2]").change(function(){
    	$("select[name=cidade2]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade2]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	  //CIDADE-ESTADO
	$("select[name=estado_nfe]").change(function(){
    	$("select[name=cidade_nfe]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_nfe]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	//CIDADE-ESTADO
	$("select[name=estado_emit]").change(function(){
    	$("select[name=cidade_emit]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_emit]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	 //CIDADE-ESTADO
	$("select[name=estado_dest]").change(function(){
    	$("select[name=cidade_dest]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_dest]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	 //CIDADE-ESTADO
	$("select[name=estado_ret_dest]").change(function(){
    	$("select[name=cidade_ret_dest]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_ret_dest]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	  //CIDADE-ESTADO
	$("select[name=estado_ent_dest]").change(function(){
    	$("select[name=cidade_ent_dest]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_ent_dest]").html(valor);
            }
        )
            
     });
	 
	//CIDADE-ESTADO
	$("select[name=estado_trans]").change(function(){
    	$("select[name=cidade_trans]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_trans]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	 $("select[name=atributo_grade]").change(function(){
     	$.post("function/grade.php", {atributo_grade:$(this).val()}, function(valor){
        	$(".dados_grade").html(valor);
        }
     )       
     });

 //CIDADE-ESTADO
	$("select[name=estado_ent]").change(function(){
    	$("select[name=cidade_ent]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_ent]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 //CIDADE-ESTADO
	$("select[name=estado_ent2]").change(function(){
    	$("select[name=cidade_ent2]").html('<option value="0">Carregando...</option>');
        	$.post("function/municipios.php", {estado_nfe:$(this).val()}, function(valor){
            	$("select[name=cidade_ent2]").html(valor);
            }
        )
            
     });
	 //CIDADE-ESTADO
	 
	//CLASSE-FLUIDO
	$("select[name=classe]").change(function(){
    	$("select[name=fluido]").html('<option value="0">Carregando...</option>');
        	$.post("function/fluido.php", {classe:$(this).val()}, function(valor){
            	$("select[name=fluido]").html(valor);
            }
        )
            
     });
	 //CLASSE-FLUIDO
	 
	 //CONTRATANTE-VASO
	$("select[name=contratante]").change(function(){
    	$("select[name=vaso]").html('<option value="0">Carregando...</option>');
        	$.post("function/vaso.php", {contratante:$(this).val()}, function(valor){
            	$("select[name=vaso]").html(valor);
            }
        )
            
     });
	 //CONTRATANTE-VASO
	 
	 /* MENU FLUTUANTE LATERAL ESQUERDA */
	 var y_fixo = $("#menu2").offset().top;
     $(window).scroll(function () {
     $("#menu2").animate({
     	top: y_fixo+$(document).scrollTop()+"px"
        	},{duration:500,queue:false}
        );
      });
		
	  /* MENU FLUTUANTE LATERAL DIREITA */
	  var y_fixo = $("#menu3").offset().top;
      $(window).scroll(function () {
      $("#menu3").animate({
      	top: y_fixo+$(document).scrollTop()+"px"
        	},{duration:1000,queue:false}
        );
      });	
		
	  /* BARRA DE ROLAGEM PARA O TOPO */
	  $('#ir_topo').click(function(){
	  	$('html,body').animate({scrollTop: 0},'slow');
	  });
	  $('.incluir_produto').click(function(){
	  	$('html,body').animate({scrollTop: 0},'slow');
	  });
		
	  /* BOTÃO PARA BARRA DE ROLAGEM */
	  $('#ir_topo').click(function () {
	  $('body,html').animate({
		scrollTop: 0 }, 800);
		return false;
	  });		 
	
}); 



// POP-UP
function popup(pagina) {
	var left = (screen.width-1000)/2;
	var top = (screen.height-650)/2;
	var newwindow = window.open('?p='+pagina+'&v=2&ha=2','','width=1000,height=650,left='+left+',top='+top+',scrollbars=yes,resizable=no');
	newwindow.moveTo(left, top);
	newwindow.focus();
};

// RELÓGIO
function moveRelogio(){ 
   	var momentoAtual = new Date() 
   	var hora = momentoAtual.getHours() 
   	var minuto = momentoAtual.getMinutes() 
   	var segundo = momentoAtual.getSeconds() 
   	var horaImprimivel = hora + " : " + minuto + " : " + segundo 
	if (document.form_relogio) {
   		document.form_relogio.relogio.value = horaImprimivel; 
   		setTimeout("moveRelogio()",1000) 
	}
};

// FOCO NA BUSCA
function focusbusca(){  
	if (document.getElementById('busca')) {
    	document.getElementById('busca').focus();  
	}
};

// FORMATAR CAMPOS
function formatar_mascara(src, mascara) {
	var campo = src.value.length;
	var saida = mascara.substring(0,1);
	var texto = mascara.substring(campo);
	if(texto.substring(0,1) != saida) {
		src.value += texto.substring(0,1);
	}
};

// MENU COLORIDO
function move_i1(what) { what.style.background='#CCCCCC'; }
function move_o1(what) { what.style.background='#FFFFFF'; }
function move_i2(what) { what.style.background='#CCCCCC'; }
function move_o2(what) { what.style.background='#EEEEEE'; }

// DELETAR
function confirmation(dd,id) {
	var answer = confirm("Deseja realmente Deletar esses dados? asasas")
	if (answer){
		window.location = "?p="+dd+"_alterar&action=del&id="+id;
	}
};

// CNPJ
function makeCnpj(id){  
   var obj = document.getElementById(id);  
   var vl = obj.value;  
   var l = vl.toString().length;  
   switch(l){  
	   case 2: obj.value = vl + "."; break;  
	   case 6: obj.value = vl + "."; break; 	
	   case 10: obj.value = vl + "/"; break;
	   case 15: obj.value = vl + "-";
   }
};

// CPF
function makeCpf(id){  
	var obj = document.getElementById(id);  
	var vl = obj.value;  
	var l = vl.toString().length;  
    switch(l){  
 	   case 3: obj.value = vl + "."; break;  
	   case 7: obj.value = vl + "."; break; 	
	   case 11: obj.value = vl + "-";
	}
};

//INSCRIÇÃO ESTADUAL
function makeInsc(id){  
	var obj = document.getElementById(id);  
	var vl = obj.value;  
	var l = vl.toString().length;  
	switch(l){
		case 2: obj.value = vl + "."; break;
		case 6: obj.value = vl + "."; break;
		case 10: obj.value = vl + "-"; 
	}
};

// CEP
function makeCep(id){
	var obj = document.getElementById(id);
	var vl = obj.value;
	var l = vl.toString().length;
	switch(l){
		case 5: obj.value = vl + "-";
	}
};

// CEP
function makePlaca(id){
	var obj = document.getElementById(id);
	var vl = obj.value;
	var l = vl.toString().length;
	switch(l){
		case 3: obj.value = vl + "-";
	}
};

<!-- INICIO FORMATAR MASCARA DE PRECO--> 
function Limpar(valor, validos) { 
	var result = ""; 
	var aux; 
	for (var i=0; i < valor.length; i++) { 
		aux = validos.indexOf(valor.substring(i, i+1)); 
		if (aux>=0) { result += aux; } 
	} 
	return result; 
}

//data
function makeDate(id){  
   obj = document.getElementById(id);  
     vl = obj.value;  
    l = vl.toString().length;  
    switch(l){  
        case 2:  
            obj.value = vl + "/";  
         break;  
        case 5:  
            obj.value = vl + "/";  
        break;  
		   }  
 } 
 
 //hora
 function makeTime(id){  
   obj = document.getElementById(id);  
     vl = obj.value;  
    l = vl.toString().length;  
    switch(l){  
        case 2:  
            obj.value = vl + ":";  
         break;  
		   }  
 } 
 
 function mostrarRef(){
		var ref = document.form1.ref.value;
			  if(ref=='1'){    
		         document.getElementById('ass').style.display = '';
				 document.getElementById('seg').style.display = 'none';
				 document.getElementById('vend').style.display = 'none';
			  }
			  if(ref=='2'){    
		         document.getElementById('seg').style.display = '';
				 document.getElementById('ass').style.display = 'none';
				  document.getElementById('vend').style.display = 'none';
			  }
			   if(ref=='3'){    
		         document.getElementById('vend').style.display = '';
				 document.getElementById('seg').style.display = 'none';
				 document.getElementById('ass').style.display = 'none';
			  }
			   if(ref=='0'){    
		         document.getElementById('ass').style.display = 'none';
				 document.getElementById('seg').style.display = 'none';
				 document.getElementById('vend').style.display = 'none';
			  }
	}

function Formata(campo,tammax,teclapres,decimal) { 
	var tecla = teclapres.keyCode; 
	var vr = Limpar(campo.value,"0123456789"); 
	var tam = vr.length; 
	var dec = decimal;
	 
	if (tam < tammax && tecla != 8){ tam = vr.length + 1; } 
	if (tecla == 8 ){ tam = tam - 1 ; } 
    if (tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ) { 
        if (tam <= dec ){ campo.value = vr ; } 
        if ((tam > dec)&&(tam <= 5)){campo.value = vr.substr(0,tam - 2) + "," + vr.substr(tam - dec,tam);} 
        if ((tam >= 6)&&(tam <= 8)){campo.value = vr.substr(0,tam - 5) + "." + vr.substr(tam - 5,3) + "," + vr.substr(tam - dec, tam);} 
        if ((tam >= 9)&&(tam <= 11)){campo.value = vr.substr(0,tam - 8) + "." + vr.substr(tam - 8, 3) + "." + vr.substr(tam - 5, 3) + "," + vr.substr(tam - dec, tam);} 
        if ((tam >= 12)&&(tam <= 14)){campo.value = vr.substr(0,tam - 11) + "." + vr.substr(tam - 11,3) + "." + vr.substr(tam - 8, 3) + "." + vr.substr(tam - 5,3) + "," + 
vr.substr(tam - dec,tam);} 
        if ((tam >= 15)&&(tam <= 17)){campo.value = vr.substr(0, tam - 14) + "." + vr.substr(tam - 14,3) + "." + vr.substr(tam - 11, 3) + "." + vr.substr(tam - 8, 3) + "." + 
vr.substr(tam - 5, 3) + "," + vr.substr(tam - 2, tam);} 
    } 
} ;
<!-- FIM FORMATAR MASCARA DE PRECO-->



	// Função única que fará a transação
	function getEndereco() {
			if($.trim($("#cep").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
					if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						if(resultadoCEP["tipo_logradouro"]){ $("#logradouro").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));}
						if(resultadoCEP["bairro"]){ $("#bairro").val(unescape(resultadoCEP["bairro"]));}
						if(resultadoCEP["uf"]){ $("#estado").val(unescape(resultadoCEP["uf"]));}
						if(resultadoCEP["cidade"]){ $("#cidade").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));}
						$("#numero").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}
	}
	
	function getEndereco2() {
			if($.trim($("#cep2").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep2").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
					if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						if(resultadoCEP["tipo_logradouro"]){ $("#logradouro2").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));}
						if(resultadoCEP["bairro"]){ $("#bairro2").val(unescape(resultadoCEP["bairro"]));}
						if(resultadoCEP["uf"]){ $("#estado2").val(unescape(resultadoCEP["uf"]));}
						if(resultadoCEP["cidade"]){ $("#cidade2").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));}
						$("#numero2").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}
	}
	
	function getEndereco_orc() {
			if($.trim($("#cep_ent").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep_ent").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
			  		if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						if(resultadoCEP["tipo_logradouro"]){$("#end_ent").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));}
						if(resultadoCEP["bairro"]){$("#bairro_ent").val(unescape(resultadoCEP["bairro"]));}
						if(resultadoCEP["uf"]){$("#estado_ent").val(unescape(resultadoCEP["uf"]));}
						if(resultadoCEP["cidade"]){$("#cidade_ent").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));}
						$("#numero_ent").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}
	}
	
	function getEndereco_orc2() {
			if($.trim($("#cep_ent2").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep_ent2").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
			  		if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						$("#end_ent2").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
						$("#bairro_ent2").val(unescape(resultadoCEP["bairro"]));
						$("#estado_ent2").val(unescape(resultadoCEP["uf"]));
						$("#cidade_ent2").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));
						$("#numero_ent2").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}
	}
	
	function getEndereco_dest() {
			if($.trim($("#cep_dest").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep_dest").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
			  		if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						$("#logradouro_dest").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
						$("#bairro_dest").val(unescape(resultadoCEP["bairro"]));
						$("#estado_dest").val(unescape(resultadoCEP["uf"]));
						$("#cidade_dest").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));
						$("#numero_dest").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}
	}
	function getEndereco_ret_dest() {
			if($.trim($("#cep_ret_dest").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep_ret_dest").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
			  		if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						$("#logradouro_ret_dest").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
						$("#bairro_ret_dest").val(unescape(resultadoCEP["bairro"]));
						$("#estado_ret_dest").val(unescape(resultadoCEP["uf"]));
						$("#cidade_ret_dest").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));
						$("#numero_ret_dest").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}
	}
	function getEndereco_ent_dest() {
			if($.trim($("#cep_ent_dest").val()) != ""){
				$.getScript("https://cep.republicavirtual.com.br/web_cep.php?formato=javascript&cep="+$("#cep_ent_dest").val(), function(){
					// o getScript dá um eval no script, então é só ler!
					//Se o resultado for igual a 1
			  		if(resultadoCEP["resultado"]){
						// troca o valor dos elementos
						$("#logradouro_ent_dest").val(unescape(resultadoCEP["tipo_logradouro"])+" "+unescape(resultadoCEP["logradouro"]));
						$("#bairro_ent_dest").val(unescape(resultadoCEP["bairro"]));
						$("#estado_ent_dest").val(unescape(resultadoCEP["uf"]));
						$("#cidade_ent_dest").append(new Option(unescape(resultadoCEP["cidade"]), unescape(resultadoCEP["cidade"]), true, true));
						$("#numero_ent_dest").focus();
					}else{
						alert("Endereço não encontrado");
					}
				});				
			}	
	}
	
		function focusFirstField(){
    var f_form = window.document.forms[0];

    if(f_form){
        var n_fields = f_form.length;
        for(var i = 0; i < n_fields; i++){
            var ro = f_form[i].readOnly;
            if(ro == true){
                var sf = i+1;
            }
        }

        if(sf){
            f_form[sf].focus();
        }else{
            f_form[0].focus();
        }
        return true;
    }
    else{
        var tags = document.getElementsByTagName('input');

        if(tags.length > 0){
            tags[0].focus();
        }else{
            return false;
        }
    }
}

function voltap() { 
	history.back();
}

function confirmationpagar(idp) {
	var id = idp;
	var answer = confirm("Deseja Confirmar a baixa deste Pagamento?")
	if (answer){
		window.location = "?p=pagamento_baixa&id="+id;
	}
}

function confirmationdelpagar(idp) {	
	var id = idp;
	var answer = confirm("Deseja Confirmar a exclusão da baixa deste Pagamento?")
	if (answer){
		window.location = "?p=pagamento_desfazer_baixa&id="+id;
	}
}

function confirmationlancapagar(id) {
	var answer = confirm("Atenção! Esta ação ira classificar como PAGO todas parcelas restantes!")
	if (answer){
		window.location = "principal.php?p=contas_pagar_deletar_parcelas&id="+id;;
	}
}
