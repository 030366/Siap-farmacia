<?php include('../Titulo/Titulo.php');

if(!isset($_SESSION["nivel"])){?><script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
$IdFarmacia=$_SESSION["IdFarmacia2"];
}
$nivel=$_SESSION["nivel"];
if($_SESSION["Datos"]!=1){?><script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

	/*if($_SESSION["IdPersonal"]!=1 and $_SESSION["IdPersonal"]!=39 and $_SESSION["IdPersonal"]!=79 and $_SESSION["IdPersonal"]!=48 and $_SESSION["IdPersonal"]!=65){?><script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }*/

?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>Ingreso de Empleados</title><script language="javascript" src="IncludeFiles/IntroEmpleado.js"></script><script language="javascript" src="../trim.js"></script><style type="text/css">
<!--
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
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
#Layer1 {
	position:absolute;
	left:1px;
	top:238px;
	width:1005px;
	height:319px;
	z-index:0;
}
-->
</style>
</head>

<body onLoad="javascript:document.getElementById('IdTipoEmpleado').focus();CargarEmpleados();">
<?php Menu(); ?>

<br>
  <table width="830" border="1">
    <tr class="MYTABLE">
      <td colspan="4" align="center"><strong>Ingreso de Empleados</strong></td>
    </tr>
    <tr class="FONDO2">
      <td><strong>Tipo de Empleado: </strong></td>
      <td colspan="3">&nbsp;<select id="IdTipoEmpleado" name="IdTipoEmpleado" onChange="GenerarCorrelativo(this.value);">
          <option value="0">[Seleccione ...]</option>
          <option value="MED">Medico</option>
          <option value="ENF">Enfermeria</option>
        </select></td>
</tr>
<tr class="FONDO2">
      <td><strong>Codigo de Empleado:</strong></td>
      <td>&nbsp;<input type="text" id="IdEmpleado" name="IdEmpleado" disabled="disabled"></td>
    </tr>
    <tr class="FONDO2">
      <td height="31"><strong>Nombre:</strong></td>
      <td colspan="3">&nbsp;<input type="text" id="Apellidos" name="Apellidos" size="40" style="border:#000000 solid thin;"  onkeyup="CargarEmpleados();">

	<input type="hidden" id="PrimerNombre" name="PrimerNombre" size="40" style="border:#000000 solid thin;">
	<input type="hidden" id="SegundoNombre" name="SegundoNombre" size="40" style="border:#000000 solid thin;">
	</td>

    </tr>
    <tr class="FONDO2">
      <td height="34"><strong>Codigo de Farmacia [JVPM]:</strong></td>
      <td>&nbsp;<input type="text" id="CodigoFarmacia" name="CodigoFarmacia" size="5" style="border:#000000 solid thin;"></td>
    </tr>

   <!-- <tr class="FONDO2">
      <td height="31"><strong>Primer Nombre: </strong></td>
      <td>&nbsp;</td>
      <td><strong>Segundo Nombre:</strong> </td>
      <td>&nbsp;</td>
    </tr>
   
    <tr class="FONDO2">
      <td height="23">&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
    -->
    <tr class="MYTABLE">
      <td height="33" colspan="4" align="right"><input type="button" id="Guardar" name="Guardar" value="Guardar" onClick="valida();">&nbsp;&nbsp;&nbsp;<input type="button" id="Limpiar" name="Limpiar" value="Limpiar" onClick="window.location=window.location;"></td>
    </tr>
    <tr class="FONDO2">
      <td height="26" colspan="4"><div id="Respuesta" align="center">&nbsp;</div></td>
    </tr>
    <tr class="FONDO2"><td colspan="4"><div id="Medicos" align="center">&nbsp;</div></td></tr>
  </table>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
