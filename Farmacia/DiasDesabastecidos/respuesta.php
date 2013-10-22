<?php session_start();
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$querySelect="select * 
		from farm_catalogoproductos fcp
		inner join farm_catalogoproductosxestablecimiento fcpe
		on fcpe.IdMedicina=fcp.IdMedicina
		where (Nombre like '%$Busqueda%' or Codigo='$Busqueda') 
                and IdEstablecimiento=".$_SESSION["IdEstablecimiento"]." 
                and IdModalidad=".$_SESSION["IdModalidad"];
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=htmlentities($row["Nombre"])." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"];
	$IdMedicina=$row["IdMedicina"];

?>
<li onselect="this.text.value = '<?php echo $Nombre;?>'; $('IdMedicina').value = '<?php echo $IdMedicina;?>';"> 
	<span><?php echo "<i>".$IdMedicina."</i>";?></span>
	<strong><?php echo $Nombre;?></strong>
</li>
<?php
}
conexion::desconectar();
?>