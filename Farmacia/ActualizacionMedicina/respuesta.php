<?php 
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$querySelect="select Codigo, Nombre, Concentracion, FormaFarmaceutica,IdMedicina,Presentacion,IdUnidadMedida,IdTerapeutico
			from farm_catalogoproductos
where (Nombre like '%$Busqueda%' or Codigo ='$Busqueda') and IdEstado ='H'";
	$resp=pg_query($querySelect);
while($row=pg_fetch_array($resp,null,PSQL_ASSOC)){
	$Nombre=$row["nombre"]." - ".$row["concentracion"]." - ".$row["formafarmaceutica"]." - ".$row["presentacion"];
	$Nombre1=$row["nombre"];
	$IdMedicina=$row["idmedicina"];
	$Concentracion=$row["concentracion"];
	$Presentacion=$row["presentacion"];
	$FormaFarmaceutica=$row["formafarmaceutica"];
	$IdTerapeutico=$row["idterapeutico"];
	$IdUnidadMedida=$row["idunidadmedida"];
	
	$Codigo=strtoupper($row["codigo"]);
	
	//Informacion de Grupo Terapetico y Unidad de Medida
	$grupo=pg_fetch_array(pg_query($cnx,"select GrupoTerapeutico from mnt_grupoterapeutico where IdTerapeutico='$IdTerapeutico'"),null,PSQL_ASSOC);
	$Medida=pg_fetch_array(pg_query($cnx,"select Descripcion from farm_unidadmedidas where IdUnidadMedida='$IdUnidadMedida'"),null,PSQL_ASSOC);

?>


<li onselect="this.text.value = '<?php echo strtoupper(htmlentities($Nombre1));?>'; $('IdMedicina').value = '<?php echo $IdMedicina;?>';  $('Concentracion').value='<?php echo $Concentracion;?>'; $('Presentacion').value='<?php echo $Presentacion;?>'; $('Codigo').value='<?php echo $Codigo;?>';$('FormaFarmaceutica').value='<?php echo $FormaFarmaceutica;?>';$('Medida').innerHTML='<?php echo $Medida[0];?>';$('Grupo').innerHTML='<?php echo $grupo[0]; ?>'"> 
	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo strtoupper(htmlentities($Nombre));?></strong>
</li>
<?php
}
conexion::desconectar();
?>