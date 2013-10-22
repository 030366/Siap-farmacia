<?php session_start();?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
</head>
<body>
<?php
require('../Clases/class.php');
$query=new queries;
conexion::conectar();
$page=$_REQUEST["page"];
//***Datos de Farmacia y su respectiva Area******
$IdFarmacia=$_SESSION["IdFarmacia"];
$IdArea=$_SESSION["IdAreaFarmacia"];
//***********************************************
$IdMedicina=$_REQUEST["IdMedicina"];
$cantidad=$_REQUEST["nuevaCantidad"];
$NuevoPrecio=$_REQUEST["nuevoPrecio"];
if(isset($_REQUEST["fecha"])){$ventto=$_REQUEST["fecha"];}else{$ventto='';}
	if($cantidad != 0){
	/*AUMENTO DE EXISTENCIAS*/
		$query->AumentaExistencias($IdArea,$IdMedicina,$cantidad,$ventto);//aumento de existencias
	}//aunmenta existencia
	
	if($NuevoPrecio != 0){
	/*CAMBIO DE PRECIO DE MEDICAMENTOS*/
		$query->EstableceNuevoPrecio($NuevoPrecio,$IdMedicina);
	}//verificacion de que ha sido introducido un nuevo precio
conexion::desconectar();
?>
<script language="javascript">
window.location='detalle_medicina.php?G=1&p=<?php echo"$IdMedicina";?>&page=<?php echo"$page";?>';
</script>
</body>
</html>