<?php include('../Titulo/Titulo.php'); ?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...::: INICIO DE SESION</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:342px;
	top:138px;
	width:348px;
	height:37px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	left:367px;
	top:209px;
	width:325px;
	height:181px;
	z-index:2;
        -o-border-radius: 8px;
-icab-border-radius: 8px;
-khtml-border-radius: 8px;
-moz-border-radius: 8px;
-webkit-border-radius: 8px;
-border-radius: 8px;
}
#Layer3 {
	position:absolute;
	left:8px;
	top:6px;
	width:31px;
	height:30px;
	z-index:3;
}
#Layer4 {
	position:absolute;
	left:272px;
	top:195px;
	width:25px;
	height:31px;
	z-index:3;
}
#Layer5 {
	position:absolute;
	left:87px;
	top:76px;
	width:51px;
	height:24px;
	z-index:4;
}
#Layer6 {
	position:absolute;
	left:285px;
	top:400px;
	width:454px;
	height:29px;
	z-index:4;
}
.style1 {color: #FF0000}
#Layer7 {
	position:absolute;
	left:361px;
	top:18px;
	width:306px;
	height:102px;
	z-index:5;
}
#Layer8 {
	position:absolute;
	left:669px;
	top:295px;
	width:109px;
	height:17px;
	z-index:6;
}
#Layer9 {
	position:absolute;
	left:66px;
	top:177px;
	width:23px;
	height:34px;
	z-index:7;
}
.borders{
    
    -o-border-radius: 8px;
    -icab-border-radius: 8px;
    -khtml-border-radius: 8px;
    -moz-border-radius: 8px;
    -webkit-border-radius: 8px;
    -border-radius: 8px;
    
}
-->
</style>

<script type="text/javascript">
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
var key = nav4 ? evt.which : evt.keyCode;	
return ((key < 13)||(key > 13));
}

function validar(datos) {
	if(datos.usuario.value==""){
	alert('No ha escrito su Usuario');
	datos.usuario.focus();
	return(false);	
	}
	
	if(datos.contra.value==""){
	alert('Debe digitar su contraseña');
	datos.contra.focus();
	return(false);
	}
	
	
}//valida
	
function cambia(objeto){ 
objeto.style.background='#FFCC66';
}

function cambia2(objeto){ 
objeto.style.background='#FFFFFF';
}

function fijar(){
document.form1.usuario.focus();
document.form1.usuario.value="";
document.form1.contra.value="";
}

function reinicio(){
return(true);
}//reinicio

</script>
</head>

<body onLoad="fijar()">
<?php Encabezado(); ?>
<div id="Layer6">
  <div align="center">
<?php

if (isset($_SESSION["conteo"])){
$_SESSION["conteo"]=$_SESSION["conteo"]+1;
$conteo=$_SESSION["conteo"];
}else{
$_SESSION["conteo"]=1;
$conteo=0;
}
if($conteo>0){ echo '<span style="color:#0000CC">'."Usuario y/o Password Incorrectos.-\n</span>";}
if($conteo>=4){
echo '<span class="style1" style="color:#0000CC">Si tiene problemas con su cuenta Contacte al Administrador o de click en <span  >Reiniciar Pantalla</span></span>';
}
?>

</div>
</div>

<form id="form1" name="form1" method="post" action="../test_con.php" onSubmit="return validar(this)">
<div id="Layer1">
<h1 align="center">Inicio de Sesi&oacute;n </h1>
</div>
<div id="Layer2" style="background-color:#CCCCCC;" align="center">
<div id="Layer3" align="center"><img src="../images/signin.gif" width="35" height="40" /></div>
<br>
<br>
<br>
<table align="center">
<tr class="MYTABLE"><td>Usuario: </td><td class="FONDO"><input type="text" name="usuario" onFocus="javascript:cambia(this)" autocomplete="off" onBlur="javaescript:cambia2(this)" onKeyPress="return acceptNum(event)" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"/></td></tr>
<tr class="MYTABLE"><td>Contrase&ntilde;a: </td><td class="FONDO"><input name="contra" type="password" onFocus="javascript:cambia(this)" onBlur="javaescript:cambia2(this)" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"/></td></tr>
<tr>
<td colspan="2" align="right">
<?php 
if($conteo<= 3){?>
<input name="entrar" type="submit" value="Entrar" style="width:125px; height:37px;"/>
<?php }
else{?>
<input name="entrar" type="submit" value="Entrar" disabled="disabled" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099; border-style:dashed"/>
<?php } ?>
</td>
</tr>
</table>

</div>

<div id="Layer8">
<?php if($conteo>=4){?><a href="../des.php" onClick="return reinicio()" title="Reiniciar">Reiniciar Pantalla</a></div>
<?php } ?>
</form>
<?php 
if(isset($_REQUEST["succ"])){?>
<script language="javascript">
alert('La contraseña ha sido cambiada satisfactoriamente\n Ahora puede iniciar sesion con su nueva contrase�a.-');
</script>
<?php } 
if(isset($_REQUEST["Cuenta"])){?>
<script language="javascript">
alert('Su cuenta ha sido deshabilitada por el administrador \n no puede ser utilizada');
</script>
<?php } ?>


</body>
</html>
