<?php 
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$querySelect="select IdTerapeutico,Codigo, Nombre, Concentracion, FormaFarmaceutica,IdMedicina,Presentacion, fum.IdUnidadMedida, fum.Descripcion
			from farm_catalogoproductos
			inner join farm_unidadmedidas fum
			on fum.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
where (Nombre like '%$Busqueda%' or Codigo='$Busqueda') and (IdTerapeutico = 0)";

	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$IdTerapeutico=$row["IdTerapeutico"];
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$Nombre1=$row["Nombre"];
	$IdMedicina=$row["IdMedicina"];
	$Concentracion=$row["Concentracion"];
	$Presentacion=$row["Presentacion"];
	$Codigo=strtoupper($row["Codigo"]);
	$IdUnidadMedida=$row["IdUnidadMedida"];
	$Descripcion=$row["Descripcion"];
?>


<li onselect="this.text.value = '<?php echo htmlentities($Nombre1);?>'; $('CodigoMedicamento').value = '<?php echo $Codigo;?>';$('IdMedicina').value = '<?php echo $IdMedicina;?>';  $('concentracion').value='<?php echo $Concentracion;?>'; $('presentacion').value='<?php echo $Presentacion;?>';PegaCombo(<?php echo $IdMedicina;?>,<?php echo $IdUnidadMedida;?>,'<?php echo $Descripcion;?>'),ComboTerapeutico(<?php echo $IdTerapeutico;?>);" > 

	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}
conexion::desconectar();
?>
