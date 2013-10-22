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
window.location='../index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$IdFarmacia=$_SESSION["IdFarmacia2"];
require('../Clases/class.php');
$conexion=new conexion;

$IdModalidad=$_SESSION["IdModalidad"];

//******Generacion del combo principal

function generaSelect(){ //creacioon de combo para las Regiones
	$conexion=new conexion;
	$conexion->conectar();
	if($_SESSION["TipoFarmacia"]==1){$comp=" and mafe.IdArea<>12";}else{$comp="";}
	$consulta=mysql_query("select maf.IdArea,Area 
                               from mnt_areafarmacia maf 
                               inner join mnt_areafarmaciaxestablecimiento mafe
                               on mafe.IdArea=maf.IdArea
                                where maf.IdArea <> 7 ".$comp." 
                                and mafe.Habilitado='S'
                                and mafe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]." 
                                and mafe.IdModalidad=".$_SESSION["IdModalidad"]);
	$conexion->desconectar();
	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='IdAreaOrigen' id='IdAreaOrigen' onchange='LimpiaDatos();'>";
	echo "<option value='0'>[Seleccione Area Origen...]</option>";
	while($registro=mysql_fetch_row($consulta)){
		if($registro[1]!="--"){
		echo "<option value='".$registro[0]."'>".$registro[1]."</option>";
		}
	}
	echo "</select>";
}

function generaSelect2(){ //creacioon de combo para las Regiones
	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='IdAreaDestino' id='IdAreaDestino'>";
	echo "<option value='0'>[Seleccione Area Destino...]</option>";
	echo "</select>";
}
?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...::: Medicamento Vencido :::...</title>
<script language="javascript" src="IncludeFiles/IntroTransferencias.js"></script>
<script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
<script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>
<script language="JavaScript" src="../noCeros.js"></script>

<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--  -->

<?php head();?>


<script language="javascript">
function confirmacion(){
var resp=confirm('Desea Cancelar esta Accion?');
if(resp==1){
window.location='../IndexReportes.php';
}
}//confirmacion
</script>
</head>
<body>
<?php Menu(); ?>
<br>

<form action="" method="post" name="formulario">

  <table width="816" border="0">
    <tr class="MYTABLE">
      <td colspan="5" align="center"><strong>DESCARGO DE EXISTENCIAS VENDIDAS/AVERIADAS</strong></td>
      </tr>
			<tr><td colspan="5" class="FONDO"><br></td></tr>
    <tr>
      <td class="FONDO"><strong>Fecha de Registro: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="Fecha" id="Fecha" readonly="true" value="<?php echo date('Y-m-d');?>" onClick="scwShow (this, event);"/></td>
      </tr>
	  <tr>
      <td class="FONDO"><strong>Area Origen de Vencimiento: </strong></td>
      <td colspan="4" class="FONDO"><?php generaSelect(); ?></td>
	  </tr>
	  <tr>
      <td class="FONDO"><strong>Motivo: </strong></td>
      <td colspan="4" class="FONDO">
		<input type="radio" id="Vencimiento" name="Options" checked="true" onclick="CambioMotivo(this.id);"> Por vencimiento <br>
		<input type="radio" id="Averiado" name="Options" onclick="CambioMotivo(this.id);"> Por averias
	</td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Cantidad y Medicamento Vencido :</strong></td>
      <td colspan="4" class="FONDO">
	<input type="text" id="NombreMedicina" name="NombreMedicina" size="54" onfocus="ValidaArea();"><br>
	<input type="hidden" id="IdMedicina" name="IdMedicina">
        <input type="text" id="Cantidad" name="Cantidad" value="" size="5" onblur="NoCero(this.id);" onfocus="ValidaArea();">
        <span id="UnidadMedida">[unidades]</span>
		<br>
        	<input type="hidden" id="Divisor" name="Divisor">
		<input type="hidden" id="UnidadesContenidas" name="UnidadesContenidas">
		<span id="ComboLotes" align="right"><select id="IdLote" name="IdLote" disabled="disabled"><option value="0">[Seleccione Lote...]</option></select></span>
		</td>
    </tr>
	<tr>
	<td class="FONDO"><strong>Justificaci&oacute;n de Descarga por averia:</strong></td> 
	<td class="FONDO">
	<textarea id="Justificacion" name="Justificacion" cols="60" rows="5" disabled="true"></textarea>
	</td>
	</tr>
	      <tr>
      <td class="FONDO">&nbsp;</td>
      <td colspan="4" class="FONDO" align="right">

<input type="button" id="AddTrans" name="AddTrans" value="Realizar Descargo" onClick="javascript:valida();">

<div id="IdReceta"></div></td>
      </tr>
<tr><td colspan="5" class="FONDO"><div id='restante' align="center"></div></td></tr>
	  <tr>
      <td colspan="5" class="FONDO"><div id="NuevaTransferencia" align="center"></div></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="5" align="right">&nbsp;</td>
    </tr>
  </table>
  </form>

	<script>
	   new Autocomplete('NombreMedicina', function() { 
		
		   return 'respuesta.php?q=' + this.value +'&IdAreaOrigen='+document.getElementById("IdAreaOrigen").value; 
	   });
	</script>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>