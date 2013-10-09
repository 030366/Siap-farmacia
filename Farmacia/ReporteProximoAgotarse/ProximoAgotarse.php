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
if(($_SESSION["Reportes"]!=1)){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
$conexion=new conexion;
//******Generacion del combo principal
?>
<html>
<head>
<script type="text/javascript" src="IncludeMonitoreo/Monitoreo.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<?php head();?>
<style>
@media print{
   #BotonesPrinter {display: none;}
}
</style>
<title>Medicamento proximo a Agotarse :::...</title>

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
<body onLoad="CargarCombos(<?php echo $_SESSION["TipoFarmacia"];?>);" >
<input type="hidden" id="TipoFarmacia" name="TipoFarmacia" value="<?php echo $_SESSION["TipoFarmacia"];?>">
<div id="Menu"><?php Menu();?></div>
<br>
<center>
<div id='Combos'></div>
<br>


</center>
</body>
</html>
<?php 

}//Si posee permisos de generar reportes
}//Si la sesion sigue activa
?>