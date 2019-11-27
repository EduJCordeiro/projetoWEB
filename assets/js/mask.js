// Máscara para Telefone

function mascara(o,f){
    v_obj=o
    v_fun=f
    setTimeout("execmascara()",2)
}
function execmascara(){
    v_obj.value=v_fun(v_obj.value)
}
function mtel(v){
    v=v.replace(/\D/g,"");             //Remove tudo o que não é dígito
    v=v.replace(/^(\d{2})(\d)/g,"($1) $2"); //Coloca parênteses em volta dos dois primeiros dígitos
    v=v.replace(/(\d)(\d{4})$/,"$1-$2");    //Coloca hífen entre o quarto e o quinto dígitos
    return v;
}
function id( el ){
	return document.getElementById( el );
}
$(document).ready(function(){
    $('input[type="tel"]').on('keypress', function(){
        mascara( this, mtel );
    });
});

// Máscara para Data

function mascaraData( campo, e )
{
    var kC = (document.all) ? event.keyCode : e.keyCode;
    var data = campo.value;

    if( kC!=8 && kC!=46 )
    {
        if( data.length==2 )
        {
            campo.value = data += '/';
        }
        else if( data.length==5 )
        {
            campo.value = data += '/';
        }
        else
            campo.value = data;
    }
}

// Máscara para Hora

function mascaraHora( campo, e )
{
    var kC = (document.all) ? event.keyCode : e.keyCode;
    var hora = campo.value;

    if( kC!=8 && kC!=46 )
    {
        if( hora.length==2 )
        {
            campo.value = hora += ':';
        }
        else
            campo.value = hora;
    }
}
