<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?><script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
$IdFarmacia=$_SESSION["IdFarmacia2"];
}
$nivel=$_SESSION["nivel"];

if($_SESSION["Datos"]!=1){ ?><script language="JavaScript">alert('No posee permisos para accesar!');window.location='../Principal/index.php';</script>
<?php }


$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
?>
<html>
<head>
<style type="text/css">
#MonitoreoEnLinea {
	position:absolute;
	left:950px;
	top:160px;
	width:350px;
	height:151px;
	z-index:1;
}
#ProgresoLinea {
	position:absolute;
	left:920px;
	top:142px;
	width:401px;
	height:10px;
	z-index:10;
}
</style>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Introduccion de Recetas:::...</title>

<!-- chat -->
<script language="javascript" src="../MonitoreoDigitacion/MonitoreoDigitacion.js"></script>

<script language="javascript" src="IncludeFiles/IntroRecetas.js"></script>
<script language="javascript"  src="IncludeFiles/calendar.js"> </script>
<script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>

<!-- AUTOCOMPLETAR --><script type="text/javascript" src="scripts/prototype.js"></script><script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--  -->

</head>
<body onload="MonitoreoChat();">
<?php Menu(); ?>

	<span id="MonitoreoEnLinea"></span>
	<div id="ProgresoLinea" align="center"></div>
<br>

<form action="" method="post" name="formulario">

  <table width="825" border="0">
    <tr class="MYTABLE">
      <td colspan="6" align="center"><strong>INTRODUCCI&Oacute;N DE RECETAS </strong></td>
      </tr>
			<tr><td colspan="6" class="FONDO"><div align="center">Hoy es:&nbsp;&nbsp;&nbsp;<strong><?php echo date('d-m-Y');?></strong></div></td></tr>
    <tr>
      <td class="FONDO"><strong>Fecha: </strong></td>
      <td colspan="5" class="FONDO"><input type="text" name="Fecha" id="Fecha" readonly="true" value="" onClick="scwShow (this, event);" onBlur="javascript:document.getElementById('Expediente').focus();"/>
	<input type="hidden" id="IdPersonal" value="<?php echo $_SESSION["IdPersonal"];?>">
	<input type="hidden" id="IdEstablecimiento" value="<?php echo $_SESSION["IdEstablecimiento"];?>">  
	</td>
      </tr>
	  <tr>
      <td class="FONDO"><strong>Codigo de Farmacia : </strong></td>
      <td colspan="5" class="FONDO"><input type="text" name="Expediente" id="Expediente"  onKeyPress="return Saltos(event,this.id);" disabled="disabled"/></td>
	  </tr>
      <tr>
        <td class="FONDO"><strong>Farmacia:</strong></td>
        <td colspan="5" class="FONDO"><select id="IdFarmacia" name="IdFarmacia" onChange="cargaContenido8(this.id);">
		<option value="0">[Seleccione ...]</option>
		<?php conexion::conectar();
		if($_SESSION["TipoFarmacia"]==2){
		$resp=mysql_query("select * from mnt_farmacia where IdFarmacia=4");
		}else{
		$resp=mysql_query("select * from mnt_farmacia where HabilitadoFarmacia='S'");
		}
			  conexion::desconectar();
			while($row=mysql_fetch_array($resp)){
		?>
			<option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
		<?php
			}
		?>
        </select>        </td>
      </tr>
      <tr>
        <td class="FONDO"><strong>Area Despacho:</strong></td>
        <td colspan="3" class="FONDO"><select id="IdArea" name="IdArea" disabled="disabled">
		<option value="0">[Seleccione ...]</option>
        </select></td>
		<?php if($_SESSION["TipoFarmacia"]==2){$disabled="disabled='true'";}else{$disabled="";}?>
        <td class="FONDO"><input type="button" id="CorreccionArea" name="CorrecionArea" value="Correci&oacute;n de Area" onClick="CorregirArea();" <?php echo $disabled;?> ></td>
        <td class="FONDO"><div id="ActualizacionArea">&nbsp;</div></td>
      </tr>

<!-- AREA ORIGEN SI LA RECETA VIENE DE OTRA AREA DIFERENTE -->
  <?php  if($_SESSION["TipoFarmacia"]==1){ ?>
      <tr>
        <td class="FONDO"><strong>Area Origen [Opcional]:</strong></td>
        <td colspan="3" class="FONDO">
		<div id="ComboOrigen">
		<select id="IdAreaOrigen" name="IdAreaOrigen" disabled="disabled">
		<option value="0">[Opcional ...]</option>
        	</select>
		</div>
	</td>
        <td class="FONDO"><input type="button" id="CorreccionArea" name="CorrecionArea" value="Correci&oacute;n de Area Origen" onClick="CorregirAreaOrigen();"></td>
        <td class="FONDO"><div id="ActualizacionAreaOrigen">&nbsp;</div></td>
      </tr>
    <?php }else{ ?>
	<input type="hidden" id="IdAreaOrigen" name="IdAreaOrigen" value="0" readonly="true">
    <?php } ?>
<!-- ****************************************************** -->

      <tr>
        <td class="FONDO"><strong>Codigo de M&eacute;dico: </strong></td>
        <td colspan="3" class="FONDO">&nbsp;<input id="CodigoFarmacia" name="CodigoFarmacia" type="text" maxlength="8" onBlur="javascript:ObtenerDatosMedico();" style="width:50px;" onKeyPress="return Saltos(event,this.id);"><input type="button" id="Buscador" name="Buscador" onClick="javascript:VentanaBusqueda2();" value="...">
		<!-- <input type="text" id="IdEspecialidad" name="IdEspecialidad"> -->
		<input type="hidden" id="IdMedico" name="IdMedico">		</td>
        <td class="FONDO"><input type="button" id="CorreccionMedico" name="CorreccionMedico" value="Correci&oacute;n de M&eacute;dico/Enfermera" onClick="CorregirMedico();"></td>
        <td class="FONDO"><div id="ActualizacionMedico">&nbsp;</div></td>
      </tr>
      <tr>
      <td width="233" class="FONDO"><strong>Especialidad/Servicio: </strong></td>
      <td colspan="3" class="FONDO">
      
	   &nbsp;<input id="CodigoSubServicio" name="CodigoSubServicio" type="text" maxlength="4" onBlur="javascript:CargarSubServicio(this.value);" style="width:50px;" onKeyPress="return Saltos(event,this.id);">
		<input type="button" id="Buscador2" name="Buscador2" onClick="javascript:VentanaBusqueda();" value="...">
	   <input type="hidden" id="IdSubServicio" name="IdSubServicio" ></td>
      
	  <td width="186" class="FONDO"><input type="button" id="CorreccionOrigen" name="CorreccionOrigen" value="Correci&oacute;n de Especialidad/Servicio" onClick="CorregirEspecialidad();"></td>
      <td width="187" class="FONDO"><div id="ActualizacionEspecialidad">&nbsp;</div></td>
      </tr>
      <tr>
        <td class="FONDO"><strong>Nombre de Medico:</strong></td>
        <td colspan="5" class="FONDO">&nbsp;&nbsp;<strong><div id="NombreMedico"></div></strong></td>
      </tr>
      <tr>
      <td class="FONDO"><strong>Nombre de Especialidad/Servicio</strong></td>
      <td colspan="5" class="FONDO">&nbsp;&nbsp;<strong><div id="NombreSubServicio"></div></strong></td>
      </tr>
	      <tr>
      <td class="FONDO"><strong>&nbsp;RECETA No.:&nbsp;&nbsp;&nbsp;<input type="text" id="CorrelativoAnual" name="RecetaNumero" size="10" readonly="true" style="border:thin solid #000000; color:#FF0000; text-align:right;font-family:timenewroman;font-size:large;"><input type="hidden" id="RecetaNumero" name="RecetaNumero"></strong></td>
      <td colspan="3" class="FONDO">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" id="AddReceta" name="AddReceta" value="Agregar Receta" onClick="javascript:valida();" >
<input type="button" id="Cancelar" name="Cancelar" value="Cancelar Receta" onClick="javascript:CancelarReceta();" disabled="disabled">
		<div id="IdReceta">	
		<!-- VALORES DE HISTORIAL CLINICO Y DE ID DE RECETA -->
		<input type='hidden' id='IdHistorialClinico' name='IdHistorialClinico'>
		<input type="hidden" id="IdRecetaValor" name="IdRecetaValor">
		</div>	</td>
<td class="FONDO" align="right">Recetas Digitadas Totales:</td><td class="FONDO"><strong><h3><span id="ContadorRecetas">-</span></h3></strong></td>
      </tr>

	      <tr>
      <td class="FONDO" align="center" colspan="6"><strong>MEDICAMENTO</strong>S
        <table width="757" height="62">
			<tr><td width="87" align="center">Cantidad</td>
			  <td width="202" align="center">Medicamento</td>
			  <td width="161" align="center">Dosis</td>
		      <td width="94" align="center">Insatisfecha</td>
			</tr>
			<tr><td align="right"><input type="text" id="Cantidad" name="Cantidad" size="6" disabled="disabled" onKeyPress="return Saltos(event,this.id);"></td>
	  <td align="center"><input type="hidden" id="IdMedicina" name="IdMedicina">
	  
	  <input type="text" id="NombreMedicina" name="NombreMedicina" disabled="disabled" onKeyPress="return Saltos(event,this.id); Limpieza(event,this.value);" size="55">
	  <input type="hidden" id="ExistenciaTotal" name="ExistenciaTotal"></td>
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
			<tr><td colspan="4" align="right"><input type="button" id="Agregar" name="Agregar" value="Agregar Medicamento" disabled="disabled" onClick="javascript:validaMedicina();">
			<input type="button" id="Finalizar" name="Finalizar" value="Terminar Receta" disabled="disabled" onClick="javascript:FinalizarReceta();">
			</td>
			</tr>
	  </table>      </td></tr>


	  <tr>
      <td colspan="6" class="FONDO" align="center"><div id="MedicinaNueva" align="center" style='border:solid;  overflow:scroll;  height:315; width:890;'></div></td>
      </tr>
	  <tr>
      <td colspan="6" class="FONDO"><div id="MedicinaNuevaRepetitiva" align="center"></div></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="6" align="right">&nbsp;</td>
    </tr>
  </table>
  </form><script>
		new Autocomplete('NombreMedicina', function() { 
			return 'respuesta.php?q=' + this.value; 
		});
	</script>
</body>
</html>
<?php

}//Fin de IF isset de Nivel
?>
