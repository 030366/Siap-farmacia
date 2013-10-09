<?php session_start();
if(!isset($_SESSION["nivel"])){?>
	<script language="javascript">
	window.location='../signIn.php';
	</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
	$IdFarmacia=$_SESSION["IdFarmacia2"];
}
$nivel=$_SESSION["nivel"];
if($nivel!=3 and $nivel !=4 and $nivel !=1 and $nivel!=2){?>
	<script language="javascript">
	window.location='../index.php?Permiso=1';
	</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

	if($_SESSION["Administracion"]!=1){?>
	<script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>Actualizacion de Estados</title>
<script language="javascript" src="IncludeFiles/Actualizaciones.js"></script>
<style type="text/css">
<!--
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
.style4 {font-size: 24px}
#Layer41 {position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
#Layer71 {position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer6 {position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
#Medicos {
	position:absolute;
	left:2px;
	top:365px;
	width:996px;
	height:140px;
	z-index:7;
}
#Layer1 {
	position:absolute;
	left:7px;
	top:248px;
	width:975px;
	height:87px;
	z-index:0;
}
-->
</style>
</head>

<body onLoad="javascript:FillGrid(0);">
<div id="Layer3" align="center">
  <?php if($nivel==1){?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
  <?php }elseif($nivel==4){?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menudigitador.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
  <?php }else{?>
  <script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
  <?php }?>
</div>
<div id="Layer71">
  <div id="Layer41"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
  <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
<div class="style1" id="Layer6" align="center">
  <?php
encabezado::top($IdFarmacia,$tipoUsuario,$nick,$nombre);

if($_SESSION["primera"]==1){?>
  <br>
  <a href="../updateData.php" title="Actualizar Datos" style="color:#FF0000" onMouseOver="this.style.color='#000099'" onMouseOut="this.style.color='#FF0000'">Usted ha iniciado Sesion por primera vez, por favor actualice sus datos personales y contrase&ntilde;a.-<br>
    Aqui.-</a>
  <?php }?>
</div>
<div id="Layer1" align="center" style="border:#999999 thin dashed;">
  <table width="296">
		<tr class="MYTABLE"><td colspan="2" align="center"><strong>BUSQUEDA DE MEDICOS</strong></td></tr>
		<tr><td class="FONDO">&nbsp;</td>
		<td class="FONDO"><input type="hidden" id="CodigoFarmacia" name="CodigoFarmacia" size="9"></td>
		<tr><td class="FONDO">Nombre: </td><td class="FONDO"><input type="text" id="NombreEmpleado" name="NombreEmpleado"></td>
		<tr class="FONDO"><td colspan="2" align="right"><input type="button" id="Buscar" name="Buscar" value="Buscar" onClick="javascript:FillGridBusqueda();">
		&nbsp;&nbsp;
		<input type="button" id="Limpiar" name="Limpiar" value="Limpiar" onClick="javascript:FillGrid(0);" disabled="disabled"></td></tr>
</table></div>
<div id="Medicos"></div>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>