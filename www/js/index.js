$(document).ready(function(){ 
    $(".cpf").mask("999.999.999-99");
    $(".cpf_titular").mask("999.999.999-99");
    $(".tel1").mask("99 9999-9999");
    $(".tel1_c").mask("99 9999-9999");
    $(".tel2").mask("99 99999-9999");
    $(".tel2_c").mask("99 99999-9999");
    $(".data_nasc").mask("99/99/9999");
    $(".data_nasc_c").mask("99/99/9999");
    $(".data_nasc_cartao").mask("99/99/9999");
    $(".numero_c").mask("9999 9999 9999 9999");
});
    function mascara(t, mask){
        var i = t.value.length;
        var saida = mask.substring(1,0);
        var texto = mask.substring(i);
        if (texto.substring(0,1) != saida){
            t.value += texto.substring(0,1);
        }
    }
    function mascara2(o,f){
        v_obj=o
        v_fun=f
        setTimeout("execmascara()",1)
    }
    function execmascara(){
        v_obj.value=v_fun(v_obj.value)
    }
    function mtel(v){
        v=v.replace(/\D/g,"");             //Remove tudo o que não Ã© dígito
        v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
        v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
        return v;
    }
    
    /* FUNÇãO SOMENTE NUMEROS INICIO */
    function somenteNumeros(num) {
        var er = /[^0-9.]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
        campo.value = "";
        }
    }
    function somenteNumerosdata(num) {
        var er = /[^0-9./ /]/;
        er.lastIndex = 0;
        var campo = num;
        if (er.test(campo.value)) {
        campo.value = "";
        }
    }
    /* FUNÇãO SOMENTE NUMEROS FIM */

    function select_tipo_banco(banco){
        console.log("Banco: "+banco);
        
        if(banco==1){
            $('#tipo_cartao_banco_select').hide();
            $('#tipo_cartao_select').show();
        }else if(banco==2){
            $('#tipo_cartao_banco_select').show();
            $('#tipo_cartao_select').hide();
            $('#tipo_dinheiro').hide();
        }else{
            $('#tipo_cartao_banco_select').hide();
            $('#tipo_cartao_select').hide();
        }
    
    }
