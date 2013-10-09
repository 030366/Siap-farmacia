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
if($nivel!=3 and $nivel !=4 and $nivel !=1 and $nivel!=2){?>
	<script language="javascript">
	window.location='../Principal/index.php?Permiso=1';
	</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$path="";
include('IncludeFiles/ClasesActualizacion.php');

	if($_SESSION["IdPersonal"]!=1 and $_SESSION["IdPersonal"]!=39 and $_SESSION["IdPersonal"]!=79 and $_SESSION["IdPersonal"]!=48 and $_SESSION["IdPersonal"]!=65){?>
	<script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }

?>
<html>
<head>
<title>Actualizacion de Precios</title>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="IncludeFiles/Actualizaciones.js"></script>
</head>

<body>
<?php Menu();?>
<br>

  <table width="849">
    <tr class="MYTABLE">
      <td colspan="5" align="center"><strong>Actualizacion de Precios</strong></td>
    </tr>
    <tr>
      <td colspan="5" class="FONDO" align="center"><strong><h3>A&Ntilde;O: <?php echo date('Y')?></h3></strong></td>
    <tr>
      <td colspan="5" class="FONDO" align="right"><input type="button" id="Guardar" name="Guardar" value="Actualizar Precios" onClick=""></td>
	  
	<!-- GRUPO TERAPEUTICO -->
	<?php 
	conexion::conectar();
	$resp1=Actualizacion::IniciarPantallaP1();
	$Ano=date('Y');
	while($row1=mysql_fetch_array($resp1)){
	$NombreGrupo=$row1["GrupoTerapeutico"];
	$IdTerapeutico=$row1["IdTerapeutico"];
	?>  
    <tr class="MYTABLE">
	  <td colspan="5" align="center"><strong><h2><?php echo $NombreGrupo;?></h2></strong></td>
	</tr>
	<tr>
	  <td width="116" class="FONDO" align="center"><strong>Codigo</strong></td>
      <td width="184" class="FONDO" align="center"><strong>Nombre</strong></td>
      <td width="183" class="FONDO" align="center"><strong>Concentracion</strong></td>
      <td width="262" class="FONDO" align="center"><strong>Presentacion</strong></td>
      <td width="80" class="FONDO" align="center"><strong>Precio ($) </strong></td>
	</tr>
			<!--	MOSTAR MEDICINAS HABILITADAS POR FARMACIA PARA LA ACTUALIZACION DE PRECIOS-->
			<?php 
			$resp2=Actualizacion::IniciarPantallaP2($IdTerapeutico);
			while($row2=mysql_fetch_array($resp2)){
				$IdMedicina=$row2["IdMedicina"];
				$Codigo=$row2["Codigo"];
				$NombreMedicina=$row2["Nombre"];
				$Concentracion=$row2["Concentracion"];
				$Presentacion=$row2["FormaFarmaceutica"]."".$row2["Presentacion"];
					$Precio=Actualizacion::ObtenerPrecioActual($IdMedicina,$Ano);
			?>
			<tr>
			  <td class="FONDO"><?php echo $Codigo;?></td>
			  <td class="FONDO"><?php echo $NombreMedicina;?></td>
			  <td class="FONDO" align="center"><?php echo $Concentracion;?></td>
			  <td class="FONDO"><?php echo $Presentacion;?></td>
			  <td class="FONDO" align="center"><input type="text" id="<?php echo "Precio".$IdMedicina;?>" name="<?php echo "Precio".$IdMedicina;?>" value="<?php echo $Precio;?>" style="text-align:right;" size="5" onBlur="javascript:ActualizarPrecios(<?php echo $IdMedicina;?>,this.value,<?php echo $_SESSION["IdPersonal"];?>);" onKeyPress="return acceptNum(event);" onFocus=""></td>
			</tr>
			<?php }//while?>
			<!-- ********************************************************************************-->
	<?php }//Grupo terapeutico ?>
    <tr class="FONDO">
      <td colspan="5" align="right">&nbsp;&nbsp;
        <input type="button" id="Guardar2" name="Guardar2" value="Actualizar Precios" onClick=""></td>
    </tr>
  </table>

</body>
</html>
<?php
	conexion::desconectar();
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>