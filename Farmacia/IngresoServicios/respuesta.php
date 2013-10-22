<?php 
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$Busqueda2=explode(' ',$Busqueda);
$OR="";
$tamano=sizeof($Busqueda2);

for($i=0;$i<$tamano;$i++){
	$OR=" or NombreSubServicio like '%".$Busqueda2[$i]."%'";
	
}




$querySelect="select CodigoFarmacia,NombreSubServicio
			from mnt_subservicio
where (NombreSubServicio like '%$Busqueda%' ".$OR." )";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$CodigoServicio=$row["CodigoFarmacia"];
	$NombreServicio=strtoupper($row["NombreSubServicio"]);

?>
<li onselect="this.text.value = '<?php echo strtoupper(htmlentities($NombreServicio));?>'; $('CodigoServicio').value = '<?php echo $CodigoServicio;?>';"> 
	<span><?php echo $CodigoServicio;?></span>
	<strong><?php echo htmlentities($NombreServicio);?></strong>
</li>
<?php
}
conexion::desconectar();
?>