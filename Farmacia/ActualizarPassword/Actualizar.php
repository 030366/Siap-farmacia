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
<?php head();?>
<title>Consulta de Recetas</title>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="Include/ConsultaFechas.js"></script>

</head>
<body>
<?php Menu(); ?>
<br>
<table width="437">
<tr class="MYTABLE"><td colspan="2" align="center">
<strong>CONSULTA DE RECETAS REPETITIVAS </strong>
</td></tr>
<tr class="FONDO">
  <td><strong>Nombre:</strong></td>
  <td><em><?php echo $nombre;?></em></td>
</tr>
<tr class="FONDO">
  <td width="176"><strong>Nueva Constrase&ntilde;a:</strong></td>
  <td width="249"><input type="password" id="contra" name="contra" size="45"></td></tr>
<tr class="MYTABLE">
  <td colspan="2" align="right"><input type="button" id="buscar" name="buscar" value="Actualizar Constraseña" onClick="verificacion()"></td>
  </tr>
<tr class="MYTABLE">
  <td colspan="2" align="right"><div id='Respuesta' align="center">&nbsp;</div></td>
</tr>
</table>

</div>

</body>
</html>
<?php }//Fin de IF isset de Nivel ?>
