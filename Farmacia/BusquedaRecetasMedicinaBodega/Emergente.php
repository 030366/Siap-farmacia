<?php session_start();
$IdReceta=$_GET['IdReceta'];
$IdMedicinaOrigen=$_GET["IdMedicina"];

?>
<html>
<head>
<title>Emergente...</title>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Introduccion de Recetas:::...</title><script language="javascript" src="IncludeFiles/IntroRecetas.js"></script><script language="javascript"  src="IncludeFiles/calendar.js"> </script><script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>

<!-- AUTOCOMPLETAR --><script type="text/javascript" src="scripts/prototype.js"></script><script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!-- -->

<script language="JavaScript" src="../noCeros.js"></script>

</head>

<body onLoad="CargarDetalle('<?php echo $IdReceta;?>',<?php echo $IdMedicinaOrigen;?>);document.getElementById('Cantidad').focus();">
<table width="643" border="1">
	<tr class="MYTABLE"><td align="center" colspan="4"><strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Detalle de Receta No. <em><?php echo $IdReceta;?></em></strong></td><td align="right"><a style="color:#CCFF00; cursor:move;" onClick="window.close();">Cerrar &nbsp;<img src="../images/cerrar.jpg"></a></td></tr>
	
	<tr class="FONDO2">
	<td width="241" align="center" colspan="5">
		<table width="100%">
		<tr class="FONDO2">
		<td><strong>Cantidad:</strong></td>
		<td><input type="text" id="Cantidad" name="Cantidad" size="6" onKeyPress="return Saltos(event,this.id);" onblur="NoCero(this.id);"></td>
		</tr>
		<tr>
		<td><strong>Medicamento</strong>
		<input type="hidden" id="IdMedicina" name="IdMedicina">
		<input type="hidden" id="IdMedicinaOrigen" name="IdMedicinaOrigen" value="<?php echo $IdMedicinaOrigen;?>">
		<input type="hidden" id="IdReceta" name="IdReceta" value="<?php echo $IdReceta;?>">
		</td>
		<td>
		<input type="text" id="NombreMedicina" name="NombreMedicina" onKeyPress="return Saltos(event,this.id); Limpieza(event,this.value);" size="80">
		<input type="hidden" id="ExistenciaTotal" name="ExistenciaTotal">
		</td></tr>
		<tr><td colspan="2" align="right"><input type="button" id="Agregar" name="Agregar" value="Agregar Medicamento" onClick="valida2();"></td></tr>
		</table>
	</td></tr>
	<tr><td colspan="5">

<div id="<?php echo $IdReceta;?>" style='border:solid;  overflow:scroll;  height:315; width:850;'></div>

</td></tr>
	<tr class="MYTABLE"><td colspan="5"><div id="Respuesta">&nbsp;</div></td></tr>
</table><script>
			new Autocomplete('NombreMedicina', function() { 
				return 'respuesta2.php?q=' + this.value; 
			});

	</script>
</body>
</html>
