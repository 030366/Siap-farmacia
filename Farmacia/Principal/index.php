<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["IdFarmacia2"])){
?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
require('../Clases/class.php');
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
<?php head();?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::MENU PRINCIPAL:::...</title>
<script language="javascript" src="../MonitoreoDigitacion/MonitoreoDigitacion.js"></script>
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
	left:7px;
	top:200px;
	width:320px;
	height:138px;
	z-index:0;
}
#Layer5 {
	position:absolute;
	left:41px;
	top:380px;
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
#Monitoreo {
	position:absolute;
	left:348px;
	top:293px;
	width:442px;
	height:151px;
	z-index:1;
}
#MonitoreoEnLinea {
	position:absolute;
	left:810px;
	top:293px;
	width:350px;
	height:151px;
	z-index:1;
}
#Progreso {
	position:absolute;
	left:370px;
	top:342px;
	width:401px;
	height:10px;
	z-index:10;
}
#ProgresoLinea {
	position:absolute;
	left:830px;
	top:342px;
	width:401px;
	height:10px;
	z-index:10;
}
-->
</style>
<script language="JavaScript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=700,left = 450,top = 450');");
}//popUp
</script>
</head>

<body onLoad="MonitoreoDigitacion();MonitoreoEnLinea();">

<?php Menu();?>

<div id="Monitoreo"></div>
<div id="Progreso" align="center"></div>
<script language="javascript" src="../tooltip/wz_tooltip.js"></script>
<div id="ProgresoLinea" align="center"></div>
<div id="MonitoreoEnLinea"></div>

<div id="Layer4" align="center">

<a href="#" onClick="popUp('../MonitoreoMedicamento/Monitoreo.php')">...::: Monitoreo de Existencias :::...<br><br>
<img src="../images/ObtenerGraficas.jpg"></a>

</div>

<div id="Layer5" align="center">
<a href="../Graficos/GraficoPrincipal.php">
...::: Gr&aacute;ficas Estad&iacute;sticas :::...<br><br>
<img src="../images/Monitoreo.png" title="Monitoreo"></a>
</div>
<div id="Layer6" align="center" style="visibility:hidden;"><a href="#">
...::: Obtenci&oacute;n de Medicamentos Agotados :::...</a><a href="#" ><img src="../images/MedicamentoAgotado.jpg" title="Medicamento Agotado"></a></div>

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

<div>
<?php
$ok=MedicamentoVencimiento::ExisteVencimiento();
if($rowV=mysql_fetch_array($ok)){
	echo "<br><table><tr><td rowspan=2><img src='../images/aviso.png'></td><td><strong>Existen medicamentos proximos a vecer <br> Desea ver el listado de estos?<br><br><strong></td></tr>
		<tr><td align='center'><input type='button' id='' name='' value='Ver Listado' onClick='VentanaBusqueda();'></td></tr>";
}
?>

</div>

<span id="dummy"></span>


</body>
</html>
