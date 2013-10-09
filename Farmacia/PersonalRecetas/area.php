<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$IdFarmacia2=0;
if($IdFarmacia2!=0){?>
<script language="javascript">window.location='estableceArea.php';</script>
<?php }else{
unset($_SESSION["IdFarmacia"]);
$IdFarmacia2=$_SESSION["IdFarmacia2"];
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('../Clases/class.php');
$conexion=new conexion;
?>
<html>
<head>
<?php head(); ?>
<script language="javascript" src="procesos/Filtro.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::SELECCION DE FARMACIA:::...</title>
<script language="javascript">
function confirmacion(){
var resp=confirm('Desea Cancelar esta Acción?');
if(resp==1){
window.location='../area.php';
}
}//confirmacion

function valida(form){
if(form.farmacia.value==0){
alert('Seleccione una Farmacia.-');
form.farmacia.focus();
return(false);
}//
}//valida
</script>
</head>
<body>
<?php Menu(); ?>
<br>
<form action="buscador.php" name="formulario" method="post" onSubmit="return valida(this)">
  
  <table width="453">
  <tr class="MYTABLE">
  <td colspan="3" align="center"><strong>Recetas por Personal </strong></td>
  </tr>
    <tr class="MYTABLE">
      <td colspan="3" align="center"><strong>&nbsp;Selección de Farmacia </strong></td>
    </tr>
    <tr>
      <td width="80" class="FONDO">&nbsp;Farmacia:</td>
      <td colspan="2" class="FONDO">&nbsp;<select id="farmacia" name="farmacia" onChange="cargaContenido8(this.id)">
	  <option value="0">Seleccione una Farmacia</option>
	  <?php
	  $conexion->conectar();
	  $resp=mysql_query("select * from mnt_farmacia");
	  $conexion->desconectar();
	   while($row=mysql_fetch_array($resp)){
	  $IdFarmacia=$row["IdFarmacia"];
	  $Farmacia=$row["Farmacia"];
	  ?>
	  <option value="<?php echo"$IdFarmacia";?>"><?php echo"$Farmacia";?></option>
	  <?php }//fin de while?>
      </select>      </td>
    </tr>
	    <tr>
		     <td width="80" class="FONDO">&nbsp;&Aacute;rea:</td>
             <td colspan="2" class="FONDO">&nbsp;<select id="area" name="area" disabled="disabled">
	  <option value="0">Seleccione una Area</option>
      </select>      </td>
		</tr>
      <td colspan="3" class="FONDO" align="right"><input name="guardar" type="submit" value="Acceder" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="3" align="right">&nbsp;</td>
      </tr>
  </table>

</form>
</body>
</html>
<?php 
}//Else $IdFarmacia!=0
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>