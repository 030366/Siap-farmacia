<?php session_start();
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];


switch($_GET["Bandera"]){

case 1:
$querySelect="select * 
		from farm_catalogoproductos fcp
		inner join farm_catalogoproductosxestablecimiento fcpe
		on fcpe.IdMedicina=fcp.IdMedicina
		inner join farm_unidadmedidas fum
		on fum.IdUnidadMedida = fcp.IdUnidadMedida
		where (Nombre like '%$Busqueda%' or Codigo='$Busqueda') and IdEstablecimiento=".$_SESSION["IdEstablecimiento"];
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=htmlentities($row["Nombre"])." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"];
	$IdMedicina=$row["IdMedicina"];
	

?>
<li onselect="this.text.value = '<?php echo $Nombre;?>'; $('IdMedicina').value = '<?php echo $IdMedicina;?>';$('UnidadMedida').innerHTML='<?php echo $row["Descripcion"];?>'; "> 
	<span><?php echo "<i>".$IdMedicina."</i>";?></span>
	<strong><?php echo $Nombre;?></strong>
</li>
<?php
}

break;
case 2:

if($_GET["IdMedicina"]==0){$comp="";}else{$comp="and IdMedicina=".$_GET["IdMedicina"];}

$querySelect="select distinct maf.IdArea,Area 
		from mnt_areafarmacia maf
		inner join farm_medicinaexistenciaxarea fmexa
		on fmexa.IdArea=maf.IdArea
		where Area like '%$Busqueda%' 
		and Habilitado='S'
		and Existencia <> 0
		$comp";
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=htmlentities($row["Area"]);
	$IdMedicina=$row["IdArea"];

?>
<li onselect="this.text.value = '<?php echo $Nombre;?>'; $('IdArea').value = '<?php echo $IdMedicina;?>';"> 
	<span><?php echo "<i>".$IdMedicina."</i>";?></span>
	<strong><?php echo $Nombre;?></strong>
</li>
<?php
}

break;

}


conexion::desconectar();
?>