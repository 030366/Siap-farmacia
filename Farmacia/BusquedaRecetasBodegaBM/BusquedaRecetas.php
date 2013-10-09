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

if($_SESSION["Datos"]!=1){ ?>
<script language="JavaScript">window.location='../Principal/index.php?Permiso=1';</script>
<?php }else{
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
<title>...:::Introduccion de Recetas:::...</title>
<script language="javascript" src="IncludeFiles/IntroRecetas.js"></script>
<script language="javascript"  src="IncludeFiles/calendar.js"> </script>
<script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>

<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!-- -->
<script language="JavaScript" src="../noCeros.js"></script>

</head>
<body onLoad="javascript:document.getElementById('CodigoReceta').focus();">
<?php Menu(); ?>
<br>
<div id="Layer1">
<form action="" method="post" name="formulario">

  <table width="825" border="0">
    <tr class="MYTABLE">
      <td colspan="6" align="center"><strong>INTRODUCCI&Oacute;N DE RECETAS </strong></td>
      </tr>
			<tr><td colspan="6" class="FONDO"><div align="center">Hoy es:&nbsp;&nbsp;&nbsp;<strong><?php echo date('d-m-Y');?></strong></div></td></tr>
    <tr>
      <td colspan="2"  align="right" class="FONDO"><strong>Numero de Receta : </strong></td>
      <td colspan="4" class="FONDO">
<input type="text" name="CodigoReceta" id="CodigoReceta" value="" onBlur="javascript:document.getElementById('Buscar').focus();" onKeyPress="return Saltos(event,this.id);"/>

<input type="hidden" name="IdRecetaValor" id="IdRecetaValor"/>

        <input type="hidden" id="IdArea" name="IdArea"><input type="hidden" id="IdAreaOrigen" name="IdAreaOrigen"><input type="hidden" id="IdHistorialClinico" name="IdHistorialClinico"></td>
      </tr>
      </tr>
<tr><td class="FONDO" align="right">No. Expediente:</td><td class="FONDO"><input type="text" id="Expediente" name="Expediente" disabled="true" size="10"></td>
	<td class="FONDO" colspan="4"><input type="button" id="CambiarExp" name="CambiarExp" value="Correci&oacute;n de Expediente" disabled="disabled" onClick="CorregirExpediente();">
	<span id="CambioExpediente"></span></td></tr>
	  <tr>
	  <tr>
      <td width="112" class="FONDO" align="right"> Farmacia:</td>
      <td width="215" class="FONDO"><div id="NombreFarmacia"></div></td>
      <td colspan="2" class="FONDO" align="right">Fecha: </td>
	  <td width="308" class="FONDO"><input type="text" id="Fecha" name="Fecha" readonly="true" onClick="scwShow (this, event);" disabled="disabled">&nbsp;&nbsp;&nbsp;<input type="button" id="CambiarFecha" name="CambiarFecha" value="Correci&oacute;n de Fecha" disabled="disabled" onClick="CorregirFecha();"></td>
	  <td width="28" class="FONDO">&nbsp;</td>
	  </tr>
      
	      <tr>
	        <td class="FONDO" align="right"> Area Despacho: </td>
	        <td class="FONDO"><strong>
        <div id="NombreArea">&nbsp;</div></strong></td>
	        <td colspan="2" class="FONDO" align="right">Medico:</td>
            <td class="FONDO"><strong>
              <div id="NombreMedico">&nbsp;</div>
            </strong></td>
            <td class="FONDO">&nbsp;</td>
      </tr>

<tr>
	        <td class="FONDO" align="right"> Area Origen: </td>
	        <td class="FONDO"><strong>
        <div id="NombreAreaOrigen">&nbsp;</div></strong></td>
	        <td colspan="2" class="FONDO" align="right"> &nbsp;</td>
            <td class="FONDO"><strong>
             &nbsp;
            </strong></td>
            <td class="FONDO">&nbsp;</td>
      </tr>

	      <tr>
      <td class="FONDO" align="right">Especialidad/Servicio:</td>
      <td class="FONDO"><strong><div id="Especialidad">&nbsp;</div></strong></td>
      <td colspan="4" class="FONDO">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" id="Buscar" name="Buscar" value="Buscar Receta" onClick="javascript:valida();" >&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" id="Limpiar" name="Limpiar" value="Finalizar Modificacion" onClick="javascript:FinalizarReceta();">
<div id="IdReceta">	
		<!-- VALORES DE HISTORIAL CLINICO Y DE ID DE RECETA -->
</div>	</td>
      </tr>
	      <tr>
      <td class="FONDO" align="center" colspan="6"><strong>MEDICAMENTO</strong>S
        <table width="599" height="62">
			<tr><td width="86" align="center">Cantidad</td>
			  <td width="220" align="center">Medicamento</td>
			  <td width="143" align="center">Dosis</td>
		      <td width="95" align="center">Insatisfecha</td>
			</tr>
			<tr><td align="right"><input type="text" id="Cantidad" name="Cantidad" size="6" disabled="disabled" onKeyPress="return Saltos(event,this.id);" onblur="NoCero(this.id);"></td>
	  <td align="center"><input type="hidden" id="IdMedicina" name="IdMedicina">
	  
	  <input type="text" id="NombreMedicina" name="NombreMedicina" disabled="disabled" onKeyPress="return Saltos(event,this.id); Limpieza(event,this.value);" size="40">
	  <input type="hidden" id="ExistenciaTotal" name="ExistenciaTotal">		</td>
		<td><input type="text" id="Dosis" name="Dosis" disabled="disabled" value="-"></td>
		<td align="center">&nbsp;
		  <input id="Insatisfecha" name="Insatisfecha" type="checkbox" value="I" disabled="disabled"></td>
			</tr>
		<tr>
		  <td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		<td>&nbsp;</td>
		</tr>
			<tr><td colspan="4" align="right"><input type="button" id="Agregar" name="Agregar" value="Agregar Medicamento" disabled="disabled" onClick="javascript:validaMedicina();"></td>
			</tr>
	  </table>      </td></tr>


	  <tr>
      <td colspan="6" class="FONDO"><div id="MedicinaNueva" align="center" style='border:solid;  overflow:scroll;  height:315; width:890;'></div></td>
      </tr>
	  <tr>
      <td colspan="6" class="FONDO"><div id="MedicinaNuevaRepetitiva" align="center"></div></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="6" align="right">&nbsp;</td>
    </tr>
  </table>
  </form>
</div>
	<script>
		new Autocomplete('NombreMedicina', function() { 
			return 'respuesta.php?q=' + this.value; 
		});
	</script>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>