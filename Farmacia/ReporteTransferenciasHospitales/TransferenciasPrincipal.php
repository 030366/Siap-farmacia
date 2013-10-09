<?php include('../Titulo/Titulo.php');

if(!isset($_SESSION["Administracion"])){?>
<script language="JavaScript">
alert('Debe iniciar sesion!');
window.location='../signIn.php';
</script>
<?php 
}

if($_SESSION["Administracion"]!=1){
?>
<script language="JavaScript">
alert('No posee suficientes privilegios para utilizar esta herramienta!');
window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
</script>
<?php	
}
include('../Clases/class.php');
function ComboFarmacias(){
   conexion::conectar();
	$SQL="select IdFarmacia,Farmacia from mnt_farmacia where HabilitadoFarmacia ='S'";
	$resp=mysql_query($SQL);
   conexion::desconectar();

	$combo="<select id='IdFarmacia' name='IdFarmacia' onchange='CargarAreas(this.value);'>
		<option value='0'>[GENERAL...]</option>";
	    while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdFarmacia"]."'>".$row["Farmacia"]."</otion>";
	    }
	$combo.="</select>";
	return($combo);
}

function ComboTerapeutico(){
   conexion::conectar();
	$SQL="select IdTerapeutico,GrupoTerapeutico from mnt_grupoterapeutico where GrupoTerapeutico <> '--'";
	$resp=mysql_query($SQL);
   conexion::desconectar();

	$combo="<select id='IdTerapeutico' name='IdTerapeutico' onchange='CargarMedicinas(this.value);'>
		<option value='0'>[GENERAL...]</option>";
	    while($row=mysql_fetch_array($resp)){
		$combo.="<option value='".$row["IdTerapeutico"]."'>".$row["IdTerapeutico"].' - '.$row["GrupoTerapeutico"]."</otion>";
	    }
	$combo.="</select>";
	return($combo);
}

?>

<html>

<head><title>Reporte de medicamento proximo a vencer</title>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="JavaScript" src="../trim.js"></script>
<script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
<script language="javascript"  src="../ReportesArchives/validaFechas.js"> </script>
<script language="JavaScript" src="IncludeFiles/reporte.js"></script>

<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--  -->

<?php head();?>
</head>
<?php Menu();?>
<br><br>

<center>
<form id="formulario">
<table width="55%">
   <tr class="MYTABLE"><td align="center" colspan="2"><strong>REPORTE DE TRANSFERENCIAS EXTERNAS</strong></td></tr>

<tr><td class="FONDO"><strong>Hospital:</strong> </td><td class="FONDO"><input type="text" id="Nombre" name="Nombre" size="40" value="[GENERAL...]" onfocus="if(this.value=='[GENERAL...]'){this.value='';}" onblur="if(this.value==''){this.value='[GENERAL...]';}" onkeypress="return Teclas(event,this.id);"><input type="hidden" id="IdEstablecimiento"></td></tr>
    <tr><td class="FONDO"><strong>Grupo Terapeutico:</strong> </td><td class="FONDO"><?php echo ComboTerapeutico();?></td></tr>
     <tr><td class="FONDO"><strong>Medicina:</strong> </td><td class="FONDO"><div id="ComboMedicina"><select id="IdMedicina" name="IdMedicina"><option value="0">[GENERAL...]</option></select></div></td></tr>
  <tr>
      <td class="FONDO"><strong>Fecha de Inicio: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="fechaInicio" id="fechaInicio" readonly="true" onClick="scwShow (this, event);" /></td>
      </tr>
	  <tr>
      <td class="FONDO"><strong>Fecha de Finalizaci&oacute;n: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="fechaFin" id="fechaFin" readonly="true" onClick="scwShow (this, event);"/></td>
      </tr>
	  <tr>
      <td colspan="5" class="FONDO">&nbsp;</td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="5" align="right"><input type="button" name="generar" value="Generar Reporte" onclick="valida();"></td>
    </tr>
</table>

<div id="Reporte"></div>

</form>
	<script>
	   new Autocomplete('Nombre', function() { 
		
		   return 'respuesta.php?Bandera=2&q=' + this.value; 
	   });
		
	</script>

</center>
</html>