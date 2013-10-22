<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
require('../Clases/class.php');
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$IdArea=$_SESSION["IdArea"];
$query = new queries;
?>
<html>
<head>
<title>Consulta de Recetas</title>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="Include/ConsultaFechas.js"></script>
<?php head(); ?>
</head>
<body onLoad="javascript:inicio()">
<?php Menu(); ?>
<script language="javascript" src="../tooltip/wz_tooltip.js"></script>
<br>
<div align="center" id="Layer1">
<form id="form" name="form">
<table width="437">
<tr class="MYTABLE"><td colspan="2" align="center">
<strong>CONSULTA DE RECETAS REPETITIVAS </strong>
</td></tr>
<tr class="FONDO"><td width="176"><strong>No. de Expediente:</strong></td>
<td width="249"><input type="text" id="expediente" name="expediente" size="45" onKeyPress="return acceptNum(event)"></td></tr>
<tr class="MYTABLE">
  <td colspan="2" align="right"><input type="button" id="buscar" name="buscar" value="Buscar Recetas Repetitivas" onClick="verificacion()"></td>
  </tr>
</table>
</form>
</div>

<div id="Layer2" align="center">
<table width="978">
<tr><td><div id="Respuesta"></div></td></tr>
<tr><td><div id="Cambios" align="center"></div></td></tr>
</table>
</div>
</body>
</html>
<?php }//Fin de IF isset de Nivel ?>
