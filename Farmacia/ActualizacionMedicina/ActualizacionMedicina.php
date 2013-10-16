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
$nivel=$_SESSION["nivel"];
if(($nivel!=1 and $nivel!=2 and $nivel!=4)){?>
<script language="javascript">
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

?>
<html>
<head>
<title>Actualizacion de Datos</title>
<?php head(); ?>
<script language="javascript" src="include/NuevaMedicina.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
</head>

<body onLoad="NombreMedicina.focus();">
<?php Menu(); ?>
<br>
  <table width="60%" border="0">
    <tr class="MYTABLE">
      <td colspan="5" align="center"><strong>ACTUALIZACION DE DATOS</strong></td>
    </tr>
    <tr>
      <td colspan="5" class="FONDO"><br></td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Codigo: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="Codigo" id="Codigo" maxlength="8"/><input type="hidden" id="IdMedicina" name="IdMedicina"></td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Nombre Medicamento: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="NombreMedicina" id="NombreMedicina" size="55"/></td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Concentracion:</strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="Concentracion" id="Concentracion" size="55"/></td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Forma Farmaceutica: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="FormaFarmaceutica" id="FormaFarmaceutica" size="55"/></td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Presentacion:</strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="Presentacion" id="Presentacion" size="55"/></td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Unidad de Medida:</strong> <br></td>
      <td colspan="4" class="FONDO"><strong><div id="Medida"></div></strong>
        <select name="select" id="IdUnidadMedida">
          <option value="0">[Seleccione ...]</option>
          <?php conexion::conectar();
		$resp=queries::UnidadMedidas();
		while($row=pg_fetch_array($resp,null,PGSQL_ASSOC)){
		?>
          <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
          <?php }?>
        </select>
     </td>
    </tr>
    <tr>
      <td class="FONDO"><strong>Grupo Terapeutico: </strong></td>
      <td colspan="4" class="FONDO"><strong><div id="Grupo"></div></strong>
        <select name="IdTerapeutico" id="IdTerapeutico">
          <option value="0">[Seleccione ...]</option>
          <?php conexion::conectar();
		$resp=queries::ComboGrupoTerapeutico();
		while($row=mysql_fetch_array($resp)){
		?>
          <option value="<?php echo $row[0];?>"><?php echo $row[1];?></option>
          <?php }?>
        </select>
       </td>
    </tr>
    <tr>
      <td class="FONDO">&nbsp;</td>
      <td colspan="4" class="FONDO">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5" class="FONDO"><div id="Progreso" align="center">&nbsp;</div></td>
    </tr>
    <tr>
      <td colspan="5" class="FONDO">&nbsp;</td>
    </tr>
    <tr class="MYTABLE">
      <td colspan="5" align="right"><input type="button" id="generar" name="generar" value="Actualizar Datos" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" onClick="javascript:Valida();"></td>
    </tr>
    <!-- <tr class="MYTABLE">
      <td colspan="5" align="right">	
<input type="button" id="Imprimir" name="imprimir" value="Imprimir" onClick="javascript:Imprimir();">
		</td>
    </tr> -->
  </table>

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