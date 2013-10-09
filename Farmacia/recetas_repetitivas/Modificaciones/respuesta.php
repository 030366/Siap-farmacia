<?php 
include('../../Clases/class.php');
include('../IncludeFiles/DiasClase.php');
$proceso= new Lotes;

conexion::conectar();

switch($_GET["Bandera"]){

	case 1:
		
		$Busqueda=$_GET['q'];
		$querySelect="select Nombre, Concentracion, IdMedicina, FormaFarmaceutica,Presentacion
					from farm_catalogoproductos
		where Nombre like '%$Busqueda%'
		and IdHospital=1 
		and IdEstado<>'I'
		and IdTerapeutico is not null";
			$resp=mysql_query($querySelect);
		while($row=mysql_fetch_array($resp)){
			$Nombre=$row["Nombre"]." - ".$row["Concentracion"]." - ".$row["FormaFarmaceutica"]." - ".$row["Presentacion"];
			$IdMedicina=$row["IdMedicina"];
		
		?>
		<li onselect="this.text.value = '<?php echo strtoupper(htmlentities($Nombre));?>';$('IdMedicina').value='<?php echo $IdMedicina;?>';"> 
			<span><?php echo $IdMedicina;?></span>
			<strong><?php echo strtoupper(htmlentities($Nombre));?></strong>
		</li>
		<?php
		}
		
		
	break;
	
	case 2: 
	//modificacion de cantidades de medicamento a despachar
	
		$IdReceta=$_GET["IdReceta"];
		$IdMedicina=$_GET["IdMedicina"];
		$IdMedicinaNueva=$_GET["IdMedicinaNueva"];
		$Cantidad=$_GET["Cantidad"];
		$CantidadNueva=$_GET["CantidadNueva"];
		
	
		//echo "IdReceta=$IdReceta  ,   IdMedicina= $IdMedicina,    Nueva= $IdMedicinaNueva,   Cantidad= $Cantidad , CantidadNueva= $CantidadNueva";
		
		if(($Cantidad != $CantidadNueva) and $CantidadNueva!=''){
			mysql_query("update farm_medicinarecetada set Cantidad='$CantidadNueva' where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'");
			echo $Cantidad.', '.$CantidadNueva;
		}
		
		
		if(($IdMedicinaNueva != $IdMedicina) and ($IdMedicinaNueva!='')){
			mysql_query("update farm_medicinarecetada set IdMedicina='$IdMedicinaNueva' where IdMedicina='$IdMedicina' and IdReceta='$IdReceta'");
			
		}
		
		
	
	break;

}
conexion::desconectar();
?>