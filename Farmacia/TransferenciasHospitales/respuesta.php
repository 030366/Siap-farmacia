<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<li onselect="this.text.value = 'Error de Sesion!'; window.location='../signIn.php'"><strong>ERROR_SESSION</strong></li>
<?php }else{
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];

switch($_GET["Bandera"]){

case 1:

$IdAreaOrigen=$_GET["IdAreaOrigen"];

$querySelect="select distinct Nombre, Concentracion, fcp.IdMedicina, FormaFarmaceutica,Presentacion, Descripcion, UnidadesContenidas
			from farm_catalogoproductos fcp
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=fcp.IdMedicina
			inner join farm_entregamedicamento fmexa
			on fmexa.IdMedicina = fcpe.IdMedicina
			inner join farm_unidadmedidas fu
			on fu.IdUnidadMedida=fcp.IdUnidadMedida

where (Nombre like '%$Busqueda%' or Codigo='$Busqueda')

and fmexa.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
and IdTerapeutico is not null";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$IdMedicina=$row["IdMedicina"];
	$Descripcion=$row["Descripcion"];
	$Unidades=$row["UnidadesContenidas"];
?>
<li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('IdMedicina').value='<?php echo $IdMedicina;?>';Habilita(<?php echo $IdMedicina; ?>);$('UnidadMedida').innerHTML='<?php echo $Descripcion;?>';$('Unidades').value='<?php echo $Unidades;?>'"> 
	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}

break;
case 2:
$querySelect="select *
		from mnt_establecimiento
		where Nombre like '%$Busqueda%'
		and IdEstablecimiento <> ".$_SESSION["IdEstablecimiento"];
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." [".$row["NOMSIBASI"]."]";
	$IdEstablecimiento=$row["IdEstablecimiento"];

?>
<li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('IdEstablecimiento').value='<?php echo $IdEstablecimiento;?>';"> 
	<span><?php echo $IdEstablecimiento;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}

break;

}
conexion::desconectar();
}//error sesion

?>