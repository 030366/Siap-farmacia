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
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="IncludeFiles/Cierre.js"></script>
<title>Cierre de Operaciones....</title>
</head>

<body>
<?php Menu(); ?>
<br>
	<table width="528" align="center">
		<tr class="MYTABLE"><td colspan="2" align="center"><strong><h2>Cierre de Operaciones</h2></strong></td>
		</tr>
		<tr class="FONDO">
		  <td width="246" align="right">Periodo:</td>
		  <td width="435"><input type="text" maxlength="7" id="Periodo" name="Periodo" onKeyPress="return acceptNum(event,this.id);"><input type="hidden" id="IdPersonal" name="IdPersonal" value="<?php echo $_SESSION["IdPersonal"];?>"></td></tr>
		<tr class="FONDO"><td colspan="2" align="right"><input type="button" id="Cerrar" name="Cerrar" style="width:150; height:60" value="Cerrar Mes" onClick="valida2();"></td></tr>
		<tr class="MYTABLE"><td colspan="2"><p>*Importante: El periodo deber ser digitado Mes-A&ntilde;o Ejemplo 02-1999. El Mes debe contener dos digitos. El guion (-) es automatico.- </p>
		  </td></tr>
  </table>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>