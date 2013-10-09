<html>
<head>
<title>Untitled Document</title>
<script language="javascript">
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
var key = nav4 ? evt.which : evt.keyCode;
if(key==13){
establecer();
}
return(key);
}


function establecer(){
window.opener.document.form1.nick.value=document.form.nick.value;
window.close();
}

function habilitar(){
window.opener.document.body.style.backgroundColor = "white";
window.opener.document.body.disabled=false;
window.opener.document.form.nick.disabled=false;
window.opener.document.form.enviar.disabled=false;
window.opener.document.form.respuesta.disabled=false;
}

function deshabilitar(){
window.opener.document.body.style.backgroundColor = "grey";
window.opener.document.form.nick.disabled=true;
window.opener.document.form.enviar.disabled=true;
window.opener.document.form.respuesta.disabled=true;
window.opener.document.body.disabled=true;
document.form.nick.focus();
}
</script>
</head>
<body onLoad="deshabilitar()" onUnload="habilitar()">
<form action="" method="post" name="form">
<table width="200" border="1">
  <tr>
    <td>Nick:</td>
  </tr>
  <tr>
    <td><input type="text" name="nick" onKeyPress="return acceptNum(event)" maxlength="15" size="30"></td>
    </tr>
  <tr>
    <td><input type="button" name="cerrar" value="Establecer" onClick="javascript:establecer()"></td>
  </tr>
</table>
</form>

</body>
</html>
