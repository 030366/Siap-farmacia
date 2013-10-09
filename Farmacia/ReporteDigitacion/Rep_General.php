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
if(($_SESSION["Nivel"]!=1)){?>
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

function generaSelect2(){ //creacioon de combo para las Regiones
	conexion::conectar();
	$consulta=mysql_query("select distinct farm_usuarios.IdPersonal, Nombre
                        from farm_usuarios
                        inner join farm_recetas
                        on farm_recetas.IdPersonalIntro=farm_usuarios.IdPersonal
			where farm_usuarios.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
			
		");
	conexion::desconectar();
	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='IdPersonal' id='IdPersonal'>";
	echo "<option value='0'>[General ...]</option>";
	while($registro=mysql_fetch_row($consulta)){
		echo "<option value='".$registro[0]."'> ".$registro[1]." </option>";
	}
	echo "</select>";
}
?>
<html>
<head>
<?php head();?>
<title>Reporte por Farmacias</title>
<script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
<script language="javascript"  src="../ReportesArchives/validaFechas.js"> </script>
<script language="javascript" src="IncludeFiles/Ajax.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />

</head>

<body>
<?php Menu();?>
<br>

  <table width="816" border="0">
    <tr class="MYTABLE">
      <td colspan="5" align="center"><strong>REPORTE DE DIGITACION</strong></td>
    </tr>
    <tr>
      <td colspan="5" class="FONDO"><br></td>
    </tr>
    <tr>
      <td width="280" class="FONDO"><strong>Digitador: </strong></td>
      <td width="673" colspan="4" class="FONDO"><?php generaSelect2(); ?></td>
    </tr>
    <tr>
      
    </tr>
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
      <td colspan="5" align="right"><input type="button" id="generar" name="generar" value="Generar Reporte" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" onClick="javascript:Valida();"></td>
    </tr>

  </table>
<br>
<div id="Reporte" align="center"></div>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>