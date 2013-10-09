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
if($nivel!=3 and $nivel !=4 and $nivel !=1 and $nivel!=2){?><script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

	if($_SESSION["IdPersonal"]!=1 and $_SESSION["IdPersonal"]!=39 and $_SESSION["IdPersonal"]!=79 and $_SESSION["IdPersonal"]!=48 and $_SESSION["IdPersonal"]!=65){?><script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }


?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>Ingreso de Empleados</title><script language="javascript" src="IncludeFiles/IntroServicios.js"></script>
<!-- AUTOCOMPLETAR --><script type="text/javascript" src="scripts/prototype.js"></script><script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />

</head>

<body onLoad="javascript:document.getElementById('CodigoServicio').focus(); CargarUltimo();CargarCombo();">
<?php Menu(); ?>
<br>
  <table width="572" border="1">
    <tr class="MYTABLE">
      <td colspan="2" align="center">
          <strong></strong><strong>Ingreso de Servicios</strong><strong></strong></td>
    </tr>
    <tr class="FONDO2">
      <td width="187" align="right"><strong>Ultimo Codigo Ingresado:</strong></td>
	<td><div id='Ultimo'></div></td>
</tr>
    <tr class="FONDO2">
      <td width="187" align="right"><strong>Servicio:</strong></td>
	<td><div id='ComboServicio'></div></td>
</tr>
    <tr class="FONDO2">
      <td width="187" align="right"><strong>Codigo de Servicio:</strong></td>
      <td width="627">&nbsp;
      <input type="text" id="CodigoServicio" name="CodigoServicio" style="border:#000000 solid thin;"></td>
      
     
    </tr>
    <tr class="FONDO2">
      <td height="34" align="right">
          <strong></strong><strong>Nombre de Servicio:</strong> </td>
      <td>&nbsp;
      <input type="text" id="NombreServicio" name="NombreServicio" size="40" style="border:#000000 solid thin;"></td>

    </tr>
    <tr class="MYTABLE">
      <td height="33" colspan="2" align="right"><input type="button" id="Guardar" name="Guardar" value="Guardar" onClick="valida();">&nbsp;&nbsp;&nbsp;<input type="button" id="Limpiar" name="Limpiar" value="Limpiar" onClick="window.location=window.location;"></td>
    </tr>
    <tr class="FONDO2">
      <td height="26" colspan="4"><div id="Respuesta" align="center">&nbsp;</div></td>
    </tr>
  </table><script>
		new Autocomplete('NombreServicio', function() { 
			return 'respuesta.php?q=' + this.value; 
		});
	</script>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
