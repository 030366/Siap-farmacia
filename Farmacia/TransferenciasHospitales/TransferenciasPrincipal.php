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

//******Generacion del combo principal

function generaSelect(){ //creacioon de combo para las Regiones
	$conexion=new conexion;
	$conexion->conectar();
	$consulta=mysql_query("select * from mnt_areafarmacia where IdArea <> 7 and IdArea <> 12 and Habilitado='S'");
	$conexion->desconectar();
	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='IdAreaOrigen' id='IdAreaOrigen' onchange='cargaContenido8(this.value);'>";
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
<title>...:::Transferencias |--&gt;</title>
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
var resp=confirm('Desea Cancelar esta Acción?');
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
      <td colspan="5" align="center"><strong>TRANSFERENCIA DE MEDICAMENTOS </strong></td>
      </tr>
			<tr><td colspan="5" class="FONDO"><br></td></tr>
    <tr>
      <td class="FONDO"><strong>Fecha de Transferencia: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="Fecha" id="Fecha" readonly="true" value="<?php echo date('Y-m-d');?>" onClick="scwShow (this, event);"/></td>
      </tr>
	  <tr>
      <td class="FONDO"><strong>Origen de Transferencia.: </strong></td>
      <td colspan="4" class="FONDO"><strong>BODEGA</strong><input type="hidden" id="IdAreaOrigen" name="IdAreaOrigen" value="<?php echo $_SESSION["IdEstablecimiento"];?>"></td>
	  </tr>
    <tr>
      <td width="280" class="FONDO"><strong>Destino de Transferencia : </strong></td>
      <td width="673" colspan="4" class="FONDO"><input type="text" id="NombreEstablecimiento" name="NombreEstablecimiento" size="54" onkeypress="return Limpiar(event,this.id);"><input type="hidden" id="IdEstablecimiento"></td>
      </tr>
    <tr>
      <td class="FONDO"><strong>Cantidad y Medicamento a Transferir :</strong></td>
      <td colspan="4" class="FONDO"><input type="hidden" id="IdMedicina" name="IdMedicina">
	<input type="text" id="NombreMedicina" name="NombreMedicina" size="54" onkeypress="return Limpiar(event,this.id);"><br>
        <input type="text" id="Cantidad" name="Cantidad" value="" size="5" onblur="NoCero(this.id);">
        <span id="UnidadMedida"></span><input type="hidden" id="Unidades" name="Unidades">
		<br>
        
		<span id="ComboLotes" align="right"><select id="IdLote" name="IdLote" disabled="disabled"><option value="0">[Seleccione Lote...]</option></select></span>
		</td>
    </tr>
	<tr>
	<td class="FONDO"><strong>Justificaci&oacute;n de Transferencia:</strong></td> 
	<td class="FONDO">
	<textarea id="Justificacion" name="Justificacion" cols="60" rows="5"></textarea>
	</td>
	</tr>
	      <tr>
      <td class="FONDO">&nbsp;</td>
      <td colspan="4" class="FONDO">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<input type="button" id="AddTrans" name="AddTrans" value="Realizar Transferencia" onClick="javascript:valida();">
<input type="button" id="Terminar" name="Terminar" value="Finalizar Transferencias" onClick="javascript:FinalizarTransferencia();">
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
		
		   return 'respuesta.php?Bandera=1&q=' + this.value + '&IdAreaOrigen='+$('IdAreaOrigen').value; 
	   });
	   new Autocomplete('NombreEstablecimiento', function() { 
		
		   return 'respuesta.php?Bandera=2&q=' + this.value; 
	   });
		
	</script>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>