<?php include('../Titulo/Titulo.php');

if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2 and $_SESSION["nivel"]!=4 and $_SESSION["nivel"]!=5){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$IdFarmacia2=0;
if($IdFarmacia2!=0){?>
<script language="javascript">window.location='estableceArea.php';</script>
<?php }else{
include('../Clases/class.php');
include('include/ClaseNuevoMedicamento.php');
conexion::conectar();
$new=new NuevoMedicamento;
$IdFarmacia2=$_SESSION["IdFarmacia2"];
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];

	if($_SESSION["Administracion"]!=1){?>
	<script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }

	$Correlativo="099".$new->Correlativo();


?>

<html>
<head>
<?php head(); ?>
<title>Introduccion de Medicamento...</title>
<script language="javascript" src="include/NuevaMedicina.js"></script>
<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!-- ---------------- -->

<script language="JavaScript" src="../noCeros.php"></script>
<script language="JavaScript" src="../trim.php"></script>


<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
</head>
<body onLoad="javascript:document.getElementById('CodigoMedicamento').focus();">
<?php Menu(); ?>
<br>
<form id="formulario" name="formulario">
  <table width="65%" border="0">
    <tr class="MYTABLE">
      <td colspan="2" align="center"><strong>Datos del Nuevo Medicamento</strong></td>
    </tr>
    <tr class="FONDO">
      <td width="25%">Codigo [8 digitos]: </td>
      <td><input type="text" id="CodigoMedicamento" name="CodigoMedicamento" size="10" maxlength="8"><input type="hidden" id="IdMedicina" name="IdMedicina">
		<input type="hidden" id="IdHospital" name="IdHospital" value="<?php echo $_SESSION["IdEstablecimiento"];?>"></td>
    </tr>

    <tr class="FONDO">
      <td>Nombre de Medicamento: </td>
      <td><input type="text" id="NombreMedicamento" name="NombreMedicamento" size="70"></td>
    </tr>
    <tr class="FONDO">
      <td>Unidad de Medida: </td>
      <td><div id="ComboUnidadMedida"><select id="UnidadMedida" name="UnidadMedida">
	  <option value="0">[Seleccione...]</option>
	  <?php $resp=mysql_query("select * from farm_unidadmedidas where IdUnidadMedida=1 or IdUnidadMedida=2 or IdUnidadMedida=7 or IdUnidadMedida = 17");
	  while($row=mysql_fetch_array($resp)){?>
	  <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
	  <?php }?>
	  </select></div>	  </td>
    </tr>
    <tr class="FONDO">
      <td>Grupo Terapeutico: </td>
      <td><select id="GrupoTerapeutico" name="GrupoTerapeutico">
	  <option value="0">Seleccione un Grupo Terapeutico</option>
	  <?php $resp=$new->ComboGrupoTerapeutico();
  	  while($row=mysql_fetch_array($resp)){
	  	$IdGrupo=$row["IdTerapeutico"];
		$Grupo=$row["GrupoTerapeutico"];
		if($Grupo != "--"){
	  ?>
		<option value="<?php echo $IdGrupo;?>"><?php echo $IdGrupo;?> --- <?php echo $Grupo;?></option>	
<?php	}	  
	  }//while grupoterapeutico
	  
	  ?>
	  </select></td>
    </tr>
    <tr class="FONDO">
      <td>Concentraci&oacute;n:</td>
      <td><input type="text" id="concentracion" name="concentracion" size="15"></td>
    </tr>
    <tr class="FONDO">
      <td height="27">Presentaci&oacute;n:</td>
      <td><input type="text" id="presentacion" name="presentacion"></td>
    </tr>
    <tr class="FONDO">
      <td height="27">&nbsp;</td>
      <td><input type="hidden" id="Precio" name="Precio" size="10" value="0"></td>
    </tr>

    <tr class="FONDO">
      <td align="center">&nbsp;</td>
      <td align="right"><input type="button" id="guardar" name="Guardar" value="Guardar Medicamento" onClick="javascript:valida()"></td>
    </tr>
    <tr class="FONDO">
      
      <td colspan="2"><div id="CodigoNuevaMedicina" align="center">&nbsp;</div></td>
    </tr>
  </table>
</form>

<div id="Resultados"></div>

</body>
</html>
	<script>
		new Autocomplete('NombreMedicamento', function() { 
			return 'respuesta.php?q=' + this.value; 
		});
	</script>
<?php conexion::desconectar();
}//Else $IdFarmacia!=0
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
