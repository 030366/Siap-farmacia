<?php //session_start();
if(!isset($_SESSION["IdFarmacia2"])){
?>
<script language="javascript">
window.location='signIn.php';
</script>
<?php
}else{
//require('Clases/class.php');
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
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="default.css" media="screen" />
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
#Layer21 {
	position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
.style1 {color: #990000}
#Layer71 {	position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
.style2 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer31 {
	position:absolute;
	left:-1px;
	top:190px;
	width:971px;
	height:30px;
	z-index:6;
}
#Layer41 {
	position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
.style4 {font-size: 24px}
-->
</style>
</head>

<body>

<script language="javascript" src="tooltip/wz_tooltip.js"></script>
<div id="Layer71">
<div id="Layer41"><img src="images/paisanito.jpg" alt="" width="195" height="94" /></div>
<span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<div class="style2" id="Layer21" align="center">
<?php
encabezado::top($NombreDeFarmacia,$tipoUsuario,$nick,$nombre);
?>
&nbsp;<?php
if($_SESSION["primera"]==1){?><br>
<span class="style1"><a href="updateData.php" title="Actualizar Datos" style="color:#000000" onMouseOver="this.style.color='#FFCC00'" onMouseOut="this.style.color='#FFFFFF'">Usted ha iniciado Sesion por primera vez, por favor actualice sus datos personales y contraseña.-<br>
Aqui.-</a></span>
<?php }?>
</div>
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

<div id="Layer31" align="center">
<?php if($nivel==1){?>
<script webstyle4>document.write('<scr'+'ipt src="xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>

<?php }else{?>
<script webstyle4>document.write('<scr'+'ipt src="xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
<?php }?>
</div>
</body>
</html>
