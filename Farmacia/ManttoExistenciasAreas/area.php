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
		unset($_SESSION["IdFarmacia"]);
		$IdFarmacia2=$_SESSION["IdFarmacia2"];
		$tipoUsuario=$_SESSION["tipo_usuario"];
		$nombre=$_SESSION["nombre"];
		$nivel=$_SESSION["nivel"];
		$nick=$_SESSION["nick"];

if($_SESSION["TipoFarmacia"]==1 and $nivel!=1){?>
	<script language="javascript">
		alert('La configuracion del sistema no permite esta accion!');
		window.location='../Principal/index.php';	
	</script>
<?php }

		require('../Clases/class.php');
		$conexion=new conexion;
                $IdModalidad=$_SESSION["IdModalidad"];
?>
<html>
<head>
<?php head();?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="procesos/Filtro.js"></script>
<title>...:::SELECCION DE FARMACIA:::...</title>

<script language="javascript">
function confirmacion(){
var resp=confirm('Desea Cancelar esta Accion?');
if(resp==1){
window.location='../area.php';
}
}//confirmacion

function valida(form){
if(form.area.value==0){
alert('Seleccione una area.-');
form.area.focus();
return(false);
}//
}//valida
</script>
</head>
<body>
<?php Menu(); ?>
<br>
<form action="existencia.php" name="formulario" method="post" onSubmit="return valida(this)">

  <table width="453">
  <tr class="MYTABLE">
  <td colspan="3" align="center"><strong>INTRODUCCI&Oacute;N DE EXISTENCIAS</strong></td>
  </tr>
    <tr class="MYTABLE">
      <td colspan="3" align="center"><strong>&nbsp;Seleccion de &Aacute;rea </strong></td>
    </tr>
    <tr>
      <td width="80" class="FONDO">&nbsp;Farmacia:</td>
      <td colspan="2" class="FONDO">&nbsp;<select id="farmacia" name="farmacia" onChange="cargaContenido8(this.id)">
	  <option value="0">Seleccione una Farmacia</option>
	  <?php
	  $conexion->conectar();
		  $resp=mysql_query("select mfe.IdFarmacia,Farmacia
                                     from mnt_farmacia 
                                     inner join mnt_farmaciaxestablecimiento mfe
                                     on mfe.IdFarmacia=mnt_farmacia.IdFarmacia
                                     where mfe.HabilitadoFarmacia='S' 
                                     and mfe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
                                     and mfe.IdModalidad=$IdModalidad
                                     and mfe.IdFarmacia <> 4");
	  $conexion->desconectar();
	  while($row=mysql_fetch_array($resp)){
		  $IdFarmacia=$row["IdFarmacia"];
		  $Farmacia=$row["Farmacia"]; ?>
	  <option value="<?php echo"$IdFarmacia";?>"><?php echo"$Farmacia";?></option>
<?php }//fin de while ?>
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