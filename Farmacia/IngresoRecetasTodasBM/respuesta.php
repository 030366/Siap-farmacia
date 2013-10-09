<?php session_start();
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$IdArea=$_GET["IdArea"];
$IdEstablecimiento=$_SESSION["IdEstablecimiento"];
$IdModalidad=$_SESSION["IdModalidad"];

$querySelect="select distinct Codigo, Nombre, Concentracion, fcp.IdMedicina, FormaFarmaceutica,Presentacion
			from farm_catalogoproductos fcp
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=fcp.IdMedicina
			inner join farm_medicinaexistenciaxarea fmexa
			on fmexa.IdMedicina=fcpe.IdMedicina
where (Nombre like '%$Busqueda%' or Codigo ='$Busqueda')
and Condicion='H'
and IdArea='$IdArea'
and fcpe.IdEstablecimiento=$IdEstablecimiento
and fcpe.IdModalidad=$IdModalidad
and IdTerapeutico is not null
order by fcp.IdMedicina";

	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$IdMedicina=$row["IdMedicina"];
	$Codigo=$row["Codigo"];

?>
<li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('IdMedicina').value='<?php echo $IdMedicina;?>';ObtenerExistenciaTotal();"> 
	<span><?php echo $Codigo;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}
conexion::desconectar();
?>