<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<li onselect="this.text.value = 'Error de Sesion!'; window.location='../signIn.php'"><strong>ERROR_SESSION</strong></li>
<?php }else{
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$IdArea=$_GET["IdArea"];

$querySelect="select distinct Nombre, Concentracion, fcp.IdMedicina, 
                    FormaFarmaceutica,Presentacion, Descripcion,UnidadesContenidas,
                    DivisorMedicina
			from farm_catalogoproductos fcp
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=fcp.IdMedicina
			inner join mnt_areamedicina
                        on mnt_areamedicina.IdMedicina=fcp.IdMedicina
			inner join farm_unidadmedidas fum
			on fum.IdUnidadMedida=fcp.IdUnidadMedida
                        left join farm_divisores fd
                        on fcp.IdMedicina=fd.IdMedicina

where (Nombre like '%$Busqueda%' or Codigo='$Busqueda')
and Condicion='H'
and fcpe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
and fcpe.IdModalidad=".$_SESSION["IdModalidad"]."
and IdArea = ".$IdArea."
and IdTerapeutico is not null";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$IdMedicina=$row["IdMedicina"];
	$Descripcion="[".$row["Descripcion"]."]";
        $UnidadesContenidas=$row["UnidadesContenidas"];
        $Divisor=$row["DivisorMedicina"];
?>
<li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('IdMedicina').value='<?php echo $IdMedicina;?>';$('Descripcion').innerHTML='<?php echo $Descripcion;?>';$('UnidadesContenidas').value='<?php echo $UnidadesContenidas;?>';$('Divisor').value='<?php echo $Divisor;?>';"> 
	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}
conexion::desconectar();
}//error sesion

?>