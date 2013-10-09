<?php include('../Titulo/Titulo.php');

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
if($_SESSION["Datos"]!=1){?>
	<script language="javascript">
	window.location='../Principal/index.php?Permiso=1';
	</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

	if($_SESSION["Datos"]!=1){?>
	<script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }

?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>Actualizacion de Codigos</title>
<script language="javascript" src="IncludeFiles/Actualizaciones.js"></script>

</head>

<body onLoad="javascript:FillGrid(0);">
<?php Menu(); ?>
<br>
  <table width="296">
		<tr class="MYTABLE">
		  <td colspan="2" align="center"><strong>BUSQUEDA DE EPECIALIDAD / SERVICIO </strong></td>
		</tr>
		<tr><td class="FONDO">Codigo: </td><td class="FONDO"><input type="text" id="CodigoFarmacia" name="CodigoFarmacia" size="9"></td>
		<tr><td class="FONDO">Nombre: </td><td class="FONDO"><input type="text" id="NombreEmpleado" name="NombreEmpleado"></td>
		<tr class="FONDO"><td colspan="2" align="right"><input type="button" id="Buscar" name="Buscar" value="Buscar" onClick="javascript:FillGridBusqueda();">
		&nbsp;&nbsp;
		<input type="button" id="Limpiar" name="Limpiar" value="Limpiar" onClick="javascript:FillGrid(0);" disabled="disabled"></td></tr>
</table>
<br>
<div id="Medicos"></div>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>