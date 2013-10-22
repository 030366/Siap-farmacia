<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<li onselect="this.text.value = 'Error de Sesion!'; window.location='../signIn.php'"><strong>ERROR_SESSION</strong></li>
<?php }else{
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$IdAreaOrigen=$_GET['IdAreaOrigen'];
$IdModalidad=$_SESSION["IdModalidad"];

$querySelect="select Nombre, Concentracion, fcp.IdMedicina, FormaFarmaceutica,Presentacion,Descripcion, DivisorMedicina,UnidadesContenidas
			from farm_catalogoproductos fcp
			inner join farm_catalogoproductosxestablecimiento fcpe
			on fcpe.IdMedicina=fcp.IdMedicina
			inner join farm_unidadmedidas fu
			on fu.IdUnidadMedida = fcp.IdUnidadMedida
			left join farm_divisores fd
			ON ( fd.IdMedicina = fcp.IdMedicina AND fd.IdModalidad =".$_SESSION["IdModalidad"]." )

                        where (Nombre like '%$Busqueda%' or Codigo='$Busqueda')
                        and Condicion='H'
                        and fcpe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]."
                        and fcpe.IdModalidad=$IdModalidad
                        and IdTerapeutico is not null";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
	$IdMedicina=$row["IdMedicina"];

	$UnidadMedida=$row["Descripcion"];
	$UnidadesContenidas=$row["UnidadesContenidas"];

	if(($row["DivisorMedicina"]!=NULL and $row["DivisorMedicina"]!='') and ($IdAreaOrigen!=12 and $IdAreaOrigen!=0)){
		$Divisor=$row["DivisorMedicina"];
		$UnidadMedida="[unidades]";
	}else{$Divisor=0;}

?>
<li onselect="this.text.value = '<?php echo htmlentities($Nombre);?>';$('IdMedicina').value='<?php echo $IdMedicina;?>';Habilita(<?php echo $IdMedicina; ?>);$('UnidadMedida').innerHTML='<?php echo $UnidadMedida;?>'; $('Divisor').value=<?php echo $Divisor;?>; $('UnidadesContenidas').value=<?php echo $UnidadesContenidas;?>"> 
	<span><?php echo $IdMedicina;?></span>
	<strong><?php echo htmlentities($Nombre);?></strong>
</li>
<?php
}
conexion::desconectar();
}//error sesion

?>