<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["Administracion"]!=1 and $_SESSION["Datos"]!=1){?>
<script language="javascript">
alert('No posee permisos para accesar a esta opcion!');
window.location='../Principal/index.php';
</script>
<?php
}else{
$IdFarmacia2=0;
if($IdFarmacia2!=0){?>
<script language="javascript">window.location='estableceArea.php';</script>
<?php }else{
	$IdFarmacia=$_SESSION["IdFarmacia2"];
	$nivel=$_SESSION["nivel"];
	$tipoUsuario=$_SESSION["tipo_usuario"];
	$nombre=$_SESSION["nombre"];
	$nivel=$_SESSION["nivel"];
	$nick=$_SESSION["nick"];
	require('../Clases/class.php');
	conexion::conectar();
        $IdModalidad=$_SESSION["IdModalidad"];

function ComboTerapeutico(){
    $SQL="select * from mnt_grupoterapeutico where GrupoTerapeutico <>'--'";
    $resp=mysql_query($SQL);
	$combo="<select Id='Terapeutico' name='Terapeutico' onchange='MedicamentoPorGrupo();'>
		<option value='0'>[SELECCIONE UN GRUPO TERAPEUTICO]</option>";
	while($row=mysql_fetch_array($resp)){
	   $combo.="<option value='".$row["IdTerapeutico"]."'>".$row["IdTerapeutico"].' - '.$row["GrupoTerapeutico"]."</option>";
	}
	$combo.="</select>";
	
	return($combo);

}

?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Actualizacion de Existencias:::...</title>
<script language="javascript" src="guardar.js"></script>
<script language="javascript" src="procesos/calendar.js"></script>
<script language="JavaScript" src="../noCeros.js"></script>
<script language="JavaScript" src="../trim.js"></script>
<script language="javascript">
<!--
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, 46 = '.'
var key = nav4 ? evt.which : evt.keyCode;	
return ((key < 13) || (key >= 48 && key <= 57) || key==46);
}

function acceptNum2(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
var key = nav4 ? evt.which : evt.keyCode;
return ((key < 13) || (key >= 48 && key <= 57 ||(key==46)));
}

function PosicionTerapeutico(IdTerapeutico){
   var Opciones=document.getElementById('Terapeutico').length;
   for(var i=0;i<Opciones;i++){
	if(document.getElementById('Terapeutico')[i].value==IdTerapeutico){
	    document.getElementById('Terapeutico')[i].selected=true;
		MedicamentoPorGrupo(IdTerapeutico);
	    return(false);
	}
   }
}

</script>
</head>
<?php $IdTerapeutico=0;
	$area=$_REQUEST["area"];
	$farmacia=$_REQUEST["farmacia"];

	if(isset($_GET["IdTerapeutico"])){$IdTerapeutico=$_GET["IdTerapeutico"];}else{$IdTerapeutico=0;}
	?>

<body onload="PosicionTerapeutico(<?php echo $IdTerapeutico;?>);">

<?php Menu();?>

<?php


$querySelectFarmacia="select mnt_farmacia.Farmacia from mnt_farmacia where mnt_farmacia.IdFarmacia='$farmacia'";
$querySelectArea="select mnt_areafarmacia.Area from mnt_areafarmacia where mnt_areafarmacia.IdArea='$area'";
$selectGrupo="select * from mnt_grupoterapeutico where GrupoTerapeutico <> '--'";
$Dfarmacia=mysql_query($querySelectFarmacia);
$Darea=mysql_query($querySelectArea);
$Grupo=mysql_query($selectGrupo);

$dataFarmacia=mysql_fetch_array($Dfarmacia);
$dataArea=mysql_fetch_array($Darea);
$NomFarmacia=$dataFarmacia[0];
$NomArea=$dataArea[0];
$count=0;
?>
<form id="formulario" name="formulario" action="saving.php" method="post" onsubmit="return validaForm();">
<table width="994" border="1">
  <tr class="MYTABLE">
    <th height="78" colspan="6" scope="col">INTRODUCCI&Oacute;N DE EXISTENCIAS <br>
	FARMACIA: <?php echo $NomFarmacia;?><br>
	AREA: <?php echo $NomArea;?><br>
	<div id="botones" align="right">
      <input type="submit" name="guardar2" value="Guardar" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">

	  <input type="button" name="cancelar2" value="Cancelar" onClick="javascript:window.location='area.php'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
	  </div></th>
  </tr>
<tr><td align="center" class="FONDO">GRUPO TERAPEUTICO: <?php echo ComboTerapeutico();?><input type="hidden" id="area" name="area" value="<?php echo $area;?>"><input type="hidden" id="farmacia" name="farmacia" value="<?php echo $farmacia;?>"></td></tr>
<tr><td align='center' class="FONDO">Busqueda => Codigo/Nombre: <input type='text' id='Nombre' name='Nombre' size='40' onkeyup='MedicamentoPorGrupo2();'></td></tr>
<tr><td class="FONDO">&nbsp;</td></tr>
  
<tr><td class="FONDO"><div id="Medicamentos" align="center"></div></td></tr>

  <tr class="MYTABLE">
    <td colspan="7" align="right">
	<input type="hidden" id="farmacia" name="farmacia" value="<?php echo $farmacia;?>">
	<input type="hidden" id="area" name="area" value="<?php echo $area;?>">
	<input type="submit" name="guardar" value="Guardar" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
    <input type="button" name="cancelar" value="Cancelar" onClick="javascript:window.location='area.php'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"></td>
    </tr>
</table>
   
</form>
</body>
</html>
<?php
conexion::desconectar();
		}//Else $IdFarmacia!=0
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel

?>