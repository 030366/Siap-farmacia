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
if($_SESSION["Datos"]!=1){?><script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');

function ComboGrupoTerapeutico(){
conexion::conectar();
  $resp=mysql_query("select * from mnt_grupoterapeutico");
conexion::desconectar();
  $combo="<select id='IdTerapeutico' name='IdTerapeutico' onchange='CargarMedicina(this.value)'>
	  <option value='0'>[GENERAL...]</option>";
  while($row=mysql_fetch_array($resp)){
	$combo.="<option value='".$row[0]."'>".$row[0]." - ".$row[1]."</option>";
  }
  $combo.="</select>";
  return($combo);
}

function ComboFarmacia(){
conexion::conectar();
  $resp=mysql_query("select * from mnt_areafarmacia where Habilitado='S' and IdArea <> 7");
conexion::desconectar();
  $combo="<select id='IdFarmacia' name='IdFarmacia'>
	  <option value='0'>[GENERAL...]</option>";
  while($row=mysql_fetch_array($resp)){
	$combo.="<option value='".$row[0]."'>".$row[1]."</option>";
  }
  $combo.="</select>";
  return($combo);
}

function ComboMedicina(){
    $combo="<select id='IdMedicina' name='IdMedicina'><option value='0'>[GENERAL...]</option></select>";
    return($combo);
}

?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>Bitacoras de Despachos</title>
<script language="javascript" src="IncludeFiles/reporte.js"></script>
<script language="javascript"  src="../ReportesArchives/calendar.js"></script>
<script language="javascript"  src="../ReportesArchives/validaFechas.js"> </script>
<script language="javascript" src="../trim.js"></script>

<style type="text/css">
<!--
.style4 {font-size: 24px}
#Layer41 {position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
#Layer71 {position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
.style1 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer6 {position:absolute;
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
#Layer1 {
	position:absolute;
	left:1px;
	top:238px;
	width:1005px;
	height:319px;
	z-index:0;
}
-->
</style>
</head>

<body>
<?php Menu(); ?>

<br>
  <table width="950" border="1">
    <tr class="MYTABLE">
      <td colspan="4" align="center"><strong>BITACORA: INGRESO DE EXISTENCIAS A BODEGA</strong></td>
    </tr>
<tr class="FONDO2">
      <td><strong>Farmacia:</strong></td>
      <td>&nbsp;<?php echo ComboFarmacia();?></td>
    </tr>
<tr class="FONDO2">
      <td><strong>Grupo Terapeutico:</strong></td>
      <td>&nbsp;<?php echo ComboGrupoTerapeutico();?></td>
    </tr>
<tr class="FONDO2">
      <td><strong>Medicamento:</strong></td>
      <td>&nbsp;<span id="ComboMedicina"><?php echo ComboMedicina();?></span></td>
    </tr>
    
<tr class="FONDO2">
      <td><strong>Fecha Inicio:</strong></td>
      <td>&nbsp;<input type="text" id="fechaInicio" name="fechaInicio" readonly="true" onclick="scwShow (this, event);"></td>
    </tr>
   <tr class="FONDO2"><td><strong>Fecha Fin:</strong></td><td>&nbsp;<input type="text" id="fechaFin" name="fechaFin" readonly="true" onclick="scwShow (this, event);"></td></tr>
    <tr class="MYTABLE">
      <td colspan="4" align="right"><input type="button" id="Generar" name="Generar" value="Consultar Bitacora" onClick="valida();"></td>
    </tr>
    <tr class="FONDO2">
      <td colspan="4"><div id="Respuesta" align="center">&nbsp;</div></td>
    </tr>
  </table>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
