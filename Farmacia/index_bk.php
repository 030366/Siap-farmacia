<?php session_start();
if(!isset($_SESSION["IdFarmacia2"])){
?>
<script language="javascript">
window.location='signIn.php';
</script>
<?php
}else{
require('Clases/class.php');
$NombreDeFarmacia=$_SESSION["IdFarmacia2"];
$tipoUsuario=$_SESSION["tipo_usuario"];
if(isset($_SESSION["nombre"])){
$nombre=$_SESSION["nombre"];}
else{
$nombre="<strong>Aun no esta actulizado su perfil.-</strong>";
}
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
}

if($nivel==3 or $nivel==4){?>
<script language="javascript">
window.location='index2.php';
</script>
<?php
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="default.css" media="screen" />
<title>...:::MENU PRINCIPAL:::...</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:5px;
	top:184px;
	width:996px;
	height:56px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	left:26px;
	top:46px;
	width:955px;
	height:30px;
	z-index:2;
}
.style1 {color: #990000}
#Layer7 {	position:absolute;
	left:283px;
	top:184px;
	width:385px;
	height:164px;
	z-index:5;
}
.style2 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer3 {
	position:absolute;
	left:1px;
	top:1px;
	width:951px;
	height:34px;
	z-index:6;
}
#Layer4 {
	border:#FF0000;
	position:absolute;
	left:84px;
	top:317px;
	width:349px;
	height:138px;
	z-index:0;
}
#Layer5 {
	position:absolute;
	left:540px;
	top:318px;
	width:264px;
	height:142px;
	z-index:8;
}
#Layer6 {
	position:absolute;
	left:319px;
	top:488px;
	width:310px;
	height:216px;
	z-index:9;
}
-->
</style>
<script language="javascript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=700,left = 450,top = 450');");
}//popUp

function popUp2(URL) {
day = new Date();
id = day.getTime();
//id=document.formulario.fecha.value;
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=620,height=500,left = 180,top = 200');");
}//popUp
</script>
</head>

<body >
<script language="javascript" src="tooltip/wz_tooltip.js"></script>
<div id="Layer3">
<?php 
//Codigo de Body  onLoad="popUp2('AvisoVencimiento/AvisoPrincipal.php')"
include 'top.php';?>
<br>
<br>
</div>

<div id="Layer4" align="center">

<a href="#" onClick="popUp('MonitoreoMedicamento/Monitoreo.php')" onMouseOver="Tip('Realice monitoreo en tiempo real<br>del consumo de medicamentos...')" onMouseOut="UnTip()">...::: Monitoreo de Existencias (Consulta Externa):::...</a><br>
<a href="#" onClick="popUp('MonitoreoMedicamento/Monitoreo.php')" onMouseOver="Tip('Realice monitoreo en tiempo real<br>del consumo de medicamentos...')" onMouseOut="UnTip()"><img src="images/ObtenerGraficas.jpg"></a>

</div>

<div id="Layer5" align="center">
<a href="Graficos/GraficoPorGrupo/GraficoPrincipal.php" onMouseOver="Tip('...::: Obtenci&oacute;n de Gr&aacute;ficas :::...')" onMouseOut="UnTip()">
...::: Obtenci&oacute;n de Gr&aacute;ficas Estad&iacute;sticas :::...</a><br>
<a href="Graficos/GraficoPorGrupo/GraficoPrincipal.php" onMouseOver="Tip('...::: Obtenci&oacute;n de Gr&aacute;ficas :::...')" onMouseOut="UnTip()"><img src="images/Monitoreo.png" title="Monitoreo"></a>
</div>
<div id="Layer6" align="center" style="visibility:hidden;"><a href="#" onMouseOver="Tip('...::: Obtenci&oacute;n de de Medicamentos Agotados :::...')" onMouseOut="UnTip()">
...::: Obtenci&oacute;n de Medicamentos Agotados :::...</a><a href="#" onMouseOver="Tip('...::: Obtenci&oacute;n de Medicamentos Agotados :::...')" onMouseOut="UnTip()"><img src="images/MedicamentoAgotado.jpg" title="Medicamento Agotado"></a></div>

<?php
if(isset($_REQUEST["Updated"])){?>
<script language="javascript">
alert('Su perfil ha sido actualizado satisfactoriamente.-');
</script>
<?php
}

if(isset($_REQUEST["Permiso"])){?>
<script language="javascript">
alert('No posee permisos para ingresar a la hoja seleccionada.-');
</script>
<?php
}//permisos de usuarios1

if(isset($_REQUEST["Permiso2"])){?>
<script language="javascript">
alert('El Administrador no posee el permiso de emitir Recetas.-');
</script>
<?php
}//permisos de usuario Admin
?>

</body>
</html>
