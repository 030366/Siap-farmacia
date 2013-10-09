<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2){?>
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
$query=new queries;
$page=$_REQUEST["page"]; 
//echo"$page";
?>
<html>
<head>
<title>Mantenimiento</title>
<script language="javascript">
function fijar(){
document.formulario.terapeutico.focus();
}

function valida(form){
if(form.terapeutico.value=='0'){
alert('Debe seleccionar un Grupo Terapeutico valido');
form.terapeutico.focus();
return(false);
}//If terapeutico

if(form.hospital.value=='0'){
alert('Debe seleccionar un Hospital valido');
form.hospital.focus();
return(false);
}//Hospital

}//fin valida

</script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:71px;
	top:144px;
	width:154px;
	height:31px;
	z-index:1;
}
#Layer6 {position:absolute;
	left:40px;
	top:17px;
	width:417px;
	height:34px;
	z-index:5;
}
@media print {
* { background: #fff; color: #000; }
html { font: 100%/1.2 Arial, Helvetica, sans-serif; }
#nav, #nav2, #about { display: none; }
#footer { display:none;}
#span{ color:#FFFFFF}
}
-->
</style>
</head>
<body onLoad="fijar()">
<?php   
//Obtencion del IDMEDICINA
$idMedicina=$_REQUEST["p"];
$info=$query->ObtenerDatosMedicina($idMedicina);//obtencion de datos

$med=mysql_fetch_array($info);
$IdMedicina=$med["IDMEDICINA"];
$CodigoMedicina=$med["CODIGO"];
$NombreMedicina=$med["NOMBRE"];
$Concentracion=$med["CONCENTRACION"];
$Presentacion=$med["FORMAFARMACEUTICA"].", ".$med["PRESENTACION"];
$PrecioActual=$med["PRECIOACTUAL"];
$existencia=$med["EXISTENCIAACTUAL"];
$terapeutico=$med["terapeutico"];

//*************************

?>

<form action="establecer_terapeutico.php" method="post" name="formulario" onSubmit="return valida(this)">
<div id="Layer1">
<table width="769" border="0">
  <tr>
    <td colspan="4" align="center">&nbsp;<strong>DETALLE DE MEDICINAS</strong></td>
    </tr>
  <tr>
  <tr>
    <td width="101"><strong>Cod.Medicina:</strong></td>
    <td width="245"><?php echo"$CodigoMedicina"; ?></td>
    <td><strong>Grupo Terapeutico:</strong></td>
    <td>&nbsp;<?php echo"$terapeutico";?></td>
  </tr>
  <tr>
    <td><strong>Nombre:</strong></td>
    <td><?php echo"$NombreMedicina"; ?></td>
    <td><strong>Existencia a la Fecha: </strong></td>
    <td>&nbsp;<span <?php if($existencia<100){echo"style=\"color:#FF0000\"";}else{echo"style=\"color:#FF0000\"";}?>><?php echo"$existencia";?></span></td>
  </tr>
  <tr>
    <td><strong>Concentracion:</strong></td>
    <td><?php echo"$Concentracion"; ?></td>
    <td><strong>Precio Actual: </strong></td>
    <td>$&nbsp;<?php echo"$PrecioActual"; ?></td>
  </tr>
  <tr>
    <td><strong>Presentacion:</strong></td>
    <td><?php echo"$Presentacion"; ?></td>
    <td width="152">&nbsp;</td>
    <td width="243">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" align="right"><input type="hidden" name="IdMedicina" id="IdMedicina" value="<?php echo"$IdMedicina"; ?>">
      <div id="nav"><input type="button" name="imprimir" value="Imprimir" tabindex="2" onClick="javascript:print()"></div></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td colspan="2" align="right">
<div id="nav2">
      <input type="button" name="cancelar" value="Cancelar" onClick="javascript:window.location='buscador_codigo.php'">
 </div></td>
  </tr>
</table>
</div>
</form>
<div id="Layer6">
  <?php
echo"<strong>Nombre de Usuario:</strong>&nbsp;&nbsp; $nombre </br>
<strong>Tipo de Usuario:</strong>&nbsp;&nbsp;$tipoUsuario<br>
<strong>Nick:</strong>&nbsp;&nbsp;$nick<br>";
?>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
</body>
</html>
<?php 
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel ?>