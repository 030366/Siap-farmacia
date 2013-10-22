<?php include('../Titulo/Titulo.php');

if(!isset($_SESSION["nivel"])){?>
	<script language="javascript">
	window.location='../signIn.php';
	</script>
<?php
}else{

   $nivel=$_SESSION["nivel"];
   if($_SESSION["Administracion"]!=1){?>
	<script language="javascript">
	window.location='../Principal/index.php?Permiso=1';
	</script>
<?php
   }else{

?>
<html>
<head>
<title>Descarte de Medicamentos</title>

<?php head();?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
<script language="javascript" src="IncludeFiles/DiasDes.js"></script>
<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--  -->

<script language="JavaScript" src="IncludeFiles/Descartes.js"></script>
<script language="JavaScript" src="../noCeros.js"></script>
<script language="JavaScript" src="../trim.js"></script>


</head>
<?php Menu();?>
<br>
<center>

<table width="50%">
<tr class="MYTABLE"><td colspan="2" align="center">Descarte de Existencias</td></tr>
<tr class="FONDO"><td width="30%">Fecha:</td><td><input type="text" id="Fecha" name="Fecha" size="10" readonly="true" onclick="scwShow (this, event);"></td></tr>

<tr class="FONDO"><td>Medicamento:</td><td><input type="text" id="q" name="q" value="" size="50" onKeyPress="return Limpieza(event,this.id);"><input type="hidden" id="IdMedicina"></td></tr>

<tr class="FONDO"><td>Cantidad</td><td><input type="text" id="Cantidad" name="Cantidad" size="10" onkeypress="Limpieza(event,this.id);return acceptNum(event);" onblur="NoCero(this.id);"><span id="UnidadMedida"></span></td></tr>

<tr class="FONDO"><td>Area</td><td><input type="text" id="Area" name="Area" onKeyPress="return Limpieza(event,this.id);"><input type="hidden" id="IdArea" name="IdArea"></td></tr>

<tr class="FONDO"><td>Motivos</td><td>
	<table>
	<tr><td><input type="radio" id="Vencimiento" name="Opcion" onclick="Habilitar(this.id);"> Vencimiento</td></tr>
	<tr><td><input type="radio" id="Averiados" name="Opcion" onclick="Habilitar(this.id);"> Dañado</td></tr>
	<tr><td><input type="radio" id="Otros" name="Opcion" onclick="Habilitar(this.id);"> Otro</td></tr>
	<tr><td><textarea id="MotivoOtros" name="MotivoOtros" rows="4" cols="34" disabled="true"></textarea></td></tr>
	</table>

</td></tr>

<tr><td colspan="2"><div id="Resultado"></div></td></tr>

<tr class="MYTABLE"><td colspan="2" align="right"><input type="button" id="Aceptar" name="Aceptar" value="Aceptar" onclick="Valida();"></td></tr>


</table>


</center>
	<script>
		new Autocomplete('q', function() { 
			
			return 'respuesta.php?Bandera=1&q=' + this.value; 
		});

		new Autocomplete('Area', function() { 
			var IdMedicina = document.getElementById('IdMedicina').value;
			return 'respuesta.php?Bandera=2&IdMedicina='+IdMedicina+'&q=' + this.value; 
		});

	</script>
</html>
<?php
    }//niveles
}//Si no hay session