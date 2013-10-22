<?php session_start();
$path="../";
include('ClasesReporteGeneral.php');
$IdSubEspecialidad=$_GET["IdSubEspecialidad"];
$FechaInicial=$_GET["FechaInicial"];
$FechaFinal=$_GET["FechaFinal"];
	$proceso=new ReporteGeneral;
conexion::conectar();
$resp=mysql_fetch_array($proceso->SubEspecialidad($IdSubEspecialidad,$FechaInicial,$FechaFinal));

?>
<html>
<head>
<title>Informacion  <?php echo $resp[0];?></title>
<link rel="stylesheet" type="text/css" href="../../default.css" media="screen" />
<style type="text/css">
<!--
.style1 {
	font-size: 18pt;
	font-style: italic;
	font-weight: bold;
}
-->
</style>
</head>

<body>
	<table width="667" align="center">
		<tr class="MYTABLE"><td colspan="3" align="center"><h2>Especialidad: <strong><?php echo $resp["NombreSubServicio"];?></strong></h2></td></tr>
<?php 
	$respFarm=$proceso->Farmacias($_SESSION["TipoFarmacia"]);
	while($rowFarm=mysql_fetch_array($respFarm)){
		
	$respFarmacias=$proceso->Titulos($rowFarm["IdFarmacia"],$IdSubEspecialidad,$FechaInicial,$FechaFinal);
		

	    if($rowFarmacias=mysql_fetch_array($respFarmacias)){
			$rowspan=mysql_num_rows($respFarmacias);
		$resp=mysql_fetch_array($proceso->MonitoreoRecetas2($rowFarmacias["IdFarmacia"],$rowFarmacias["IdAreaOrigen"],$IdSubEspecialidad,$FechaInicial,$FechaFinal)); ?>
		<tr class="FONDO">
		  <td width="259" rowspan="<?php echo $rowspan;?>"><span class="style1"><?php echo $rowFarmacias["Farmacia"];?></span></td>
	      <td width="207"><?php echo $rowFarmacias["Area"];?></td>
	      <td width="185"><strong><?php echo $resp["Total"];?></strong></td>
	  </tr>
	<?php while($rowFarmacias=mysql_fetch_array($respFarmacias)){
		$resp=mysql_fetch_array($proceso->MonitoreoRecetas2($rowFarmacias["IdFarmacia"],$rowFarmacias["IdAreaOrigen"],$IdSubEspecialidad,$FechaInicial,$FechaFinal));
	?>
		
	<tr class="FONDO">
		  <td><?php echo $rowFarmacias["Area"];?></td>
		  <td><strong><?php echo $resp["Total"];?></strong></td>
	  </tr>

	<?php

		}
	    }
	}	

conexion::desconectar();
	?>

</table>
</body>
</html>
