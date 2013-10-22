<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["IdFarmacia2"])){
?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{

if($_SESSION["nivel"]==4){?>
<script language="javascript">
window.location="../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php";
</script>
<?php }

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
$IdArea=$_SESSION["IdArea"];
?>
<html>
<head>
<?php head();?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::MENU PRINCIPAL:::...</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:-16px;
	top:164px;
	width:996px;
	height:56px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	left:25px;
	top:105px;
	width:703px;
	height:30px;
	z-index:2;
}
.style1 {color: #990000;}
#Layer7 {	position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
.style2 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer3 {	position:absolute;
	left:-2px;
	top:173px;
	width:836px;
	height:34px;
	z-index:6;
}
#Layer4 {
	position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
.style4 {font-size: 24px;}
#Layer5 {
	position:absolute;
	left:14px;
	top:313px;
	width:235px;
	height:169px;
	z-index:7;
}
#Layer6 {
	position:absolute;
	left:220px;
	top:313px;
	width:235px;
	height:169px;
	z-index:8;
}
#Layer8 {
	position:absolute;
	left:601px;
	top:313px;
	width:235px;
	height:163px;
	z-index:9;
}
#Layer9 {
	position:absolute;
	left:809px;
	top:63px;
	width:108px;
	height:65px;
	z-index:10;
}
-->
</style>
</head>

<body>
<?php Menu(); ?>

<div id="Layer6" align="center"><a href="../Recetas_Dia_Lectura/recetas_all.php">Lectura y Preparaci&oacute;n de Recetas<br>
<img src="../images/Preparacion.png" ></a></div>
<div id="Layer8" align="center"><a href="../recetas/buscador_recetas.php">Entrega de Recetas Listas<br>
<img src="../images/entrega.png"></a></div>
<div id="Layer9" align="center" style="visibility:hidden;"><?php if($IdArea==1){?><a href="../Farmacia_Altas/index2.php" title="Cambio de area"><img src="../images/cambioArea.jpg" alt="Cambio de Area"><br>
Area de Altas</a><?php }?></div>

<?php

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
