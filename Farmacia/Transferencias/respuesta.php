<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<li onselect="this.text.value = 'Error de Sesion!'; window.location='../signIn.php'"><strong>ERROR_SESSION</strong></li>
<?php }else{
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$IdAreaOrigen=$_GET["IdAreaOrigen"];
$IdModalidad=$_SESSION["IdModalidad"];

$querySelect="select distinct Nombre, Concentracion, fcp.IdMedicina, FormaFarmaceutica,Presentacion, Descripcion
			from farm_catalogoproductos fcp
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=fcp.IdMedicina
			inner join farm_medicinaexistenciaxarea fmexa
			on fmexa.IdMedicina = fcpe.IdMedicina
			inner join farm_unidadmedidas fum
			on fum.IdUnidadMedida=fcp.IdUnidadMedida

where (Nombre like '%$Busqueda%' or Codigo='$Busqueda')
and Condicion='H'
and fmexa.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
and fmexa.IdModalidad=$IdModalidad
and IdArea = ".$IdAreaOrigen."
and IdTerapeutico is not null";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$IdMedicina=$row["IdMedicina"];
	$Descripcion="[".$row["Descripcion"]."]";

?>
<li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('IdMedicina').value='<?php echo $IdMedicina;?>';document.getElementById('Descripcion').innerHTML='<?php echo $Descripcion;?>';Habilita(<?php echo $IdMedicina; ?>);"> 
	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}
conexion::desconectar();
}//error sesion

?>