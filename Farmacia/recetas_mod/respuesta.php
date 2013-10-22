<?php 
include('../Clases/class.php');
conexion::conectar();

switch($_GET["Bandera"]){

	case 1:
		
		$Busqueda=$_GET['q'];
		$IdArea=$_GET['IdArea'];
		
		$querySelect="select distinct mnt_expediente.IdNumeroExp,
		concat_ws(', ',CONCAT_WS(' ',mnt_datospaciente.PrimerApellido,mnt_datospaciente.SegundoApellido),
CONCAT_WS(' ',mnt_datospaciente.PrimerNombre,mnt_datospaciente.SegundoNombre))as NOMBRE,
		farm_recetas.IdReceta

		from sec_historial_clinico
		inner join mnt_expediente
		on mnt_expediente.IdNumeroExp=sec_historial_clinico.IdNumeroExp
		inner join mnt_datospaciente
		on mnt_datospaciente.IdPaciente=mnt_expediente.IdPaciente
		inner join farm_recetas
		on farm_recetas.IdHistorialClinico=sec_historial_clinico.IdHistorialClinico
		
		where (farm_recetas.IdEstado='L' or farm_recetas.IdEstado='R') 
		and (mnt_expediente.IdNumeroExp LIKE '%$Busqueda%')
		and farm_recetas.Fecha BETWEEN '2011-01-10' and curdate()
		and farm_recetas.IdArea='$IdArea'";

			$resp=mysql_query($querySelect);
		while($row=mysql_fetch_array($resp)){
			$IdNumeroExp=$row["IdNumeroExp"];
			$Nombre=$row["NOMBRE"];
			$IdReceta=$row["IdReceta"];
		
		?>
		<li onselect="this.text.value = '<?php echo strtoupper(htmlentities($IdNumeroExp));?>';$('NombrePaciente').innerHTML='<?php echo $Nombre;?>';$('IdReceta').value=<?php echo $IdReceta;?>;"> 
			<span><?php echo $IdNumeroExp;?></span>
			<strong><?php echo strtoupper(htmlentities($Nombre));?></strong>
		</li>
		<?php
		}
		
		
	break;
	
	case 2:
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