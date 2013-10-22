<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?><script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
$IdFarmacia=$_SESSION["IdFarmacia2"];
}

if(($_SESSION["Reportes"]!=1)){?><script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
//******Generacion del combo principal

function generaSelect2(){//creacioon de combo para las Regiones
	conexion::conectar();
	$consulta=mysql_query("select * from mnt_farmacia");
	conexion::desconectar();
	// Voy imprimiendo el primer select compuesto por los paises
	$out= "<select name='farmacia' id='farmacia' onChange='cargaContenido(this.value,this.id)'>";
	$out.= "<option value='0'>SELECCIONE UNA FARMACIA</option>";
	while($registro=mysql_fetch_row($consulta)){
		if($registro[1]!="--"){
		$out.= "<option value='".$registro[0]."'>".$registro[1]."</option>";
		}
	}
	$out.= "</select>";
	return $out;
}
?>
<html>
<head>
<?php head();?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Reporte por Grupo Terapeutico:::...</title><script language="javascript"  src="../ReportesArchives/calendar.js"> </script><script language="javascript" src="reporte.js"></script><script language="javascript">
function confirmacion(){
var resp=confirm('Desea Cancelar esta Accion?');
if(resp==1){
window.location='../IndexReportes.php';
}
}//confirmacion
function valida(form){
	var Ok = true;

		/*if(form.farmacia.value==0){
				alert('Selecciona una Farmacia');
				form.farmacia.focus();
				Ok=false;
		}*/

if(Ok==true){
   Reportes();
}

}//valida
</script>
</head>
<body>
<?php Menu();?>
<br>
<form action="Reporte_GrupoTerapeutico.php" method="post" name="formulario" onSubmit="valida(this);return false;">
  <table width="816" border="0">
    <tr class="MYTABLE">
      <td colspan="5" align="center">
            <strong></strong><strong>EXISTENCIA DE MEDICAMENTOS GENERAL</strong></td>
      </tr>
	<tr><td colspan="5" class="FONDO"><br></td></tr>
<!--           <tr>
      <td width="280" class="FONDO"><strong>Farmacia: </strong></td>
      <td width="673" colspan="4" class="FONDO"><?php //echo generaSelect2();?></td>
      </tr>
	   <tr>
      <td width="280" class="FONDO"><strong>Area: </strong></td>
      <td width="673" colspan="4" class="FONDO"><div id="ComboAreas">
	<select name="area" id="area" disabled="disabled">
        <option value="0">SELECCIONE UNA AREA</option>
      </select>
	</div>
	</td>
      </tr>-->
    <tr>
      <td width="280" class="FONDO"><strong>Grupo Terapeutico: </strong></td>
      <td width="673" colspan="4" class="FONDO"><select name="IdTerapeutico" id="IdTerapeutico" onChange='cargaContenido(this.value,this.id)'>
          <option value="0">TODOS LOS GRUPOS</option>
	  <?php 
	  	conexion::conectar();
			$consulta=mysql_query("SELECT * FROM mnt_grupoterapeutico") or die(mysql_error());
		conexion::desconectar();
		  	while($registro=mysql_fetch_row($consulta))
	{
		// Convierto los caracteres conflictivos a sus entidades HTML correspondientes para su correcta visualizacion
		$registro[1]=htmlentities($registro[1]);
		// Imprimo las opciones del select
		if($registro[1]!="--"){ ?>
			<option value='<?php echo $registro[0];?>'><?php echo $registro[1];?></option>
	<?php }	
	}		//while
	?>

		  
        </select></td>
      </tr>
    
      <td class="FONDO"><strong>Medicina:</strong></td>
      <td colspan="4" class="FONDO">
	<div id="ComboMedicinas">
	<select name="IdMedicina" id="IdMedicina" disabled="disabled">
        <option value="0">TODAS LAS MEDICINAS</option>
      </select></div>
	</td>
      </tr>
	  <tr>
      <td colspan="5" class="FONDO">&nbsp;</td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="5" align="right"><input type="submit" name="generar" value="Generar Reporte"></td>
    </tr>
  </table>

</form>
<br>
<div id="Reporte" align="center"></div>

</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
