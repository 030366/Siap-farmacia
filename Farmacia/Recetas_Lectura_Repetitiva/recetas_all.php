<?php include('../Titulo/Titulo.php');
if(isset($_SESSION["nivel"])==3){
$IdFarmacia=$_SESSION["IdFarmacia2"];
$IdArea=$_SESSION["IdArea"];
$IdPersonal=$_SESSION["IdPersonal"];
?>
<html>
<script language="javascript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=600,left = 450,top = 450');");
}//popUp
</script>
<head>
<?php head(); ?>
<script type="text/javascript" src="ReLoad.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Recetas:::...</title>

<script language="javascript">
function confirmacion(){
var valor=confirm('             Esta receta sera enviada \n¿Son los datos de esta receta correctos?');
	if(valor==1){
		return true;
	}else{
		return false;
	}
}//confirmacion

</script>
</head>
<!-- Bloqueo de Click Derecho del Mouse -->
<body>
<?php Menu(); ?>
<script type="text/javascript" src="../tooltip/wz_tooltip.js"></script>

<br>


<div id="Busqueda" align="center">
	<table width="464">
		<tr><td colspan="2" align="center" class="MYTABLE"><h3>Busqueda de Receta Repetitiva</h3></td></tr>
		<tr><td width="165" class="FONDO"><strong>Numero de Expediente:</strong></td>
		<td width="287"><input type="text" id="IdNumeroExp" name="IdNumeroExp" onKeyPress="return acceptNum(event)"></td></tr>
		<tr><td colspan="2" align="right" class="MYTABLE"><input type="button" id="Buscar" name="Buscar" value="Buscar Receta !" onClick="Procesar(0);"></td></tr>
  </table>
</div>

<div id="Layer1"></div>
</body>
</html>
<?php 

}else{?>
<script language="javascript">
window.location='../Principal/index.php?Permiso2=1';
</script>
<?php
}//fin de ELSE Nivel
?>