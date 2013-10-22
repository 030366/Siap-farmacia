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
if($_SESSION["Administracion"]!=1 or $_SESSION["nivel"]!=1){?>
	<script language="javascript">
	alert('No posee permisos para ingresar a esta opcion!');
	window.location='../Principal/index.php';
	</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

	?>
<html>
<head>
<?php head(); ?>
<script language="javascript" src="IncludeFiles/Cierre.js"></script>
<title>Inicializacion de Precios Anuales</title>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />

</head>

<body onload="CargarCierres();">
<?php Menu();?>
<br>
  <table width="528" align="center">
    <tr class="MYTABLE">
      <td colspan="2" align="center"><strong>
        <h2>Eliminaci&oacute;n de Cierres </h2>
      </strong></td>
    </tr>
    <tr class="FONDO">
        <td width="435"><div id="Periodos" align="center">&nbsp;</div></td>
    </tr>
    <tr class="FONDO"><TD align="center"><div id="Operaciones"></div></TD></tr>
    <tr class="FONDO">
      <td align="right"><input type="button" id="Eliminar" name="Eliminar" style="width:150; height:60" value="Eliminar Cierre(s)" onClick="valida();"></td>
    </tr>
    <tr class="MYTABLE">
      <td><p>*Si desea realizar una eliminacion de cierres anuales contactar al administrador del sistema.</p></td>
    </tr>
    <tr class="MYTABLE">
      <td colspan="2"><div id="Respuesta">&nbsp;</div></td>
    </tr>
  </table>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>