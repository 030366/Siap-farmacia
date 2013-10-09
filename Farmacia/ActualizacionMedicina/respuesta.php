<?php 
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$querySelect="select Codigo, Nombre, Concentracion, FormaFarmaceutica,IdMedicina,Presentacion,IdUnidadMedida,IdTerapeutico
			from farm_catalogoproductos
where (Nombre like '%$Busqueda%' or Codigo ='$Busqueda') and IdEstado ='H'";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$Nombre1=$row["Nombre"];
	$IdMedicina=$row["IdMedicina"];
	$Concentracion=$row["Concentracion"];
	$Presentacion=$row["Presentacion"];
	$FormaFarmaceutica=$row["FormaFarmaceutica"];
	$IdTerapeutico=$row["IdTerapeutico"];
	$IdUnidadMedida=$row["IdUnidadMedida"];
	
	$Codigo=strtoupper($row["Codigo"]);
	
	//Informacion de Grupo Terapetico y Unidad de Medida
	$grupo=mysql_fetch_array(mysql_query("select GrupoTerapeutico from mnt_grupoterapeutico where IdTerapeutico='$IdTerapeutico'"));
	$Medida=mysql_fetch_array(mysql_query("select Descripcion from farm_unidadmedidas where IdUnidadMedida='$IdUnidadMedida'"));

?>


<li onselect="this.text.value = '<?php echo strtoupper(htmlentities($Nombre1));?>'; $('IdMedicina').value = '<?php echo $IdMedicina;?>';  $('Concentracion').value='<?php echo $Concentracion;?>'; $('Presentacion').value='<?php echo $Presentacion;?>'; $('Codigo').value='<?php echo $Codigo;?>';$('FormaFarmaceutica').value='<?php echo $FormaFarmaceutica;?>';$('Medida').innerHTML='<?php echo $Medida[0];?>';$('Grupo').innerHTML='<?php echo $grupo[0]; ?>'"> 
	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo strtoupper(htmlentities($Nombre));?></strong>
</li>
<?php
}
conexion::desconectar();
?>