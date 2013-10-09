<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{

if($_SESSION["Administracion"]!=1){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$IdFarmacia2=0;
if($IdFarmacia2!=0){?>
<script language="javascript">window.location='estableceArea.php';</script>
<?php }else{
	
	$nombre=$_SESSION["nombre"];
	$nivel=$_SESSION["nivel"];
	$nick=$_SESSION["nick"];
	$IdFarmacia=$_SESSION["IdFarmacia2"];
	$tipoUsuario=$_SESSION["tipo_usuario"];

if($_SESSION["TipoFarmacia"]==1 and $nivel!=1){?>
	<script language="javascript">
		alert('La configuracion del sistema no permite esta accion!');
		window.location='../Principal/index.php';	
	</script>
<?php }

	require('../Clases/class2.php');
	conexion::conectar();
?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Actualizacion de Existencias:::...</title>
<script language="javascript" src="IncludeFiles/AsignacionMedicamento.js"></script>
</head>
<body onload="Inicio();">
<?php MEnu(); ?>
<br>

<table width="995px" border="1">
  <tr class="MYTABLE">
    <th colspan="2" scope="col"><p>ASIGNACION DE MEDICAMENTOS POR AREA DE FARMACIA</p></th>
  </tr>
  <tr><td width="30%" class="FONDO"><strong>Farmacia:</strong></td><td class="FONDO"><div id="ComboFarmacia"></div></td></tr>
  <tr><td class="FONDO"><strong>Area:</strong></td><td class="FONDO"><div id="ComboArea"><select id="IdFarmacia"><option value="0">[SELECCIONE]</option></select></div></td></tr>
  <tr><td class="FONDO"><strong>Grupo Terapeutico:</strong></td><td class="FONDO"><div id="ComboGrupoTerapeutico"></div></td></tr>
  <tr><td colspan="2" style='border:solid;'  valign='top' width='995px' height='325' align='center'>
			<div id='Farmacos' style='overflow:scroll; width:995px; height:360; '></div>
		</td></tr>
</table>

</body>
</html>
<?php
conexion::desconectar();

}

}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel

?>