<?php session_start();
if(isset($_SESSION["nivel"])){
/*$IdArea='2';      // Valores para el monitoreo
$IdFarmacia='2';  // de Consulta Externa
*/
?>
<html>
<head>
<script type="text/javascript" src="IncludeMonitoreo/Monitoreo.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>MONITOREO :::...</title>

<script language="javascript">
function confirmacion(){
var resp=confirm('Desea salir de los detalles de receta?');
	if(resp==1){
		window.location='recetas_all.php';
	}

	else{
		window.location='recetas_all.php';
	}//si ya imprimieron
}//confirmacion

</script>
</head>
<!-- Bloqueo de Click Derecho del Mouse -->
<body onLoad="CargarCombos();" >
<center>
<div id='Combos'></div>
<br>

<div id="Monitoreo"></div>
</center>
</body>
</html>
<?php 

}else{?>
<script language="javascript">
alert('No posee permisos de Monitoreo');
this.close();
</script>
<?php
}//fin de ELSE Nivel*/
?>