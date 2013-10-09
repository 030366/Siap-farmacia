<?php session_start();
include('../../Clases/class.php');
	$IdReceta=$_GET["IdReceta"];
	$IdMedicina=$_GET["IdMedicina"]; 
	
conexion::conectar();

	$query="select Cantidad,Nombre,Concentracion,FormaFarmaceutica
			from farm_recetas
			inner join farm_medicinarecetada
			on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
			inner join farm_catalogoproductos
			on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
			
			where farm_recetas.IdReceta=$IdReceta
			and farm_medicinarecetada.IdMedicina=$IdMedicina";


	$resp=mysql_fetch_array(mysql_query($query));
conexion::desconectar();
	$Cantidad=$resp["Cantidad"];
	$Nombre=$resp["Nombre"];
	$FormaFarmaceutica=$resp["Concentracion"];
	$Concentracion=$resp["FormaFarmaceutica"];
	
?>

<html>
<head>
<title>Modificaciones ...</title>
<script language="javascript" src="../ReLoad.js"></script>
<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="../IncludeFiles/scripts/prototype.js"></script>
	<script type="text/javascript" src="../IncludeFiles/scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="../IncludeFiles/styles/autocomplete.css" />
<!-- ------------------> 
<link rel="stylesheet" type="text/css" href="../../default.css" media="screen" />
</head>

<body onLoad="document.getElementById('Cantidad').focus();">

<table width="642" height="254">
	<tr class="MYTABLE"><td colspan="2" align="center"><h2>Datos Actuales</h2></td></tr>
	<tr class="FONDO"><td><strong>Cantidad:</strong></td><td><?php echo $Cantidad;?></td></tr>
	<tr class="FONDO"><td><strong>Medicamento:</strong></td><td><?php echo $Nombre.' - '.$FormaFarmaceutica;?></td></tr>
	<tr class="FONDO"><td><strong>Concentracion:</strong></td><td><?php echo $Concentracion;?></td></tr>

<tr class="FONDO"><td colspan="2"><br><hr><br></td></tr>

	<tr class="MYTABLE"><td colspan="2" align="center"><h2>Modificaci&oacute;n de Datos</h2></td></tr>
	<tr><td width="196" class="FONDO"><strong>Cantidad de Medicamento:</strong></td><td width="434"><input type="text" id="Cantidad" name="Cantidad"></td>
	<tr><td colspan="2" class="FONDO"><br><hr><br></td></tr>
	<tr class="FONDO">
	  <td><strong>Cambio de Medicamento:</strong>
        <input type="hidden" id="IdMedicina" name="IdMedicina"></td><td><input type="text" id="NombreMedicina" name="NombreMedicina" size="55"></td></tr>
	<tr class="FONDO">
	  <td>&nbsp;</td>
	  <td>&nbsp;</td>
  </tr>
	<tr class="MYTABLE">
	  <td colspan="2" align="center"><input type="button" id="Cambiar" name="Cambiar" value="Realizar Cambios" onClick="Cambiar(<?php echo $IdReceta;?>,<?php echo $IdMedicina;?>,<?php echo $Cantidad;?>);">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="button" id="Cancelar" name="Cancelar" value="Cancelar" onClick="window.close();"></td>
  </tr>
</table>


	<script>
		new Autocomplete('NombreMedicina', function() { 
			return 'respuesta.php?Bandera=1&q=' + this.value; 
		});
	</script>
</body>
</html>
