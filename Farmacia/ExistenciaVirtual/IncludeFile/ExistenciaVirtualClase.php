<?php
require('../../Clases/class.php');

class ExistenciaVirtualProceso{
/*INTRODUCCION DE NUEVA TRANSFERENCIA*/

	function ObtenerExistenciaTotal($IdArea){
	/*Se usa para obtener su existencia y para obtener el codigo de lote[despliegue del detalle]*/
		$querySelect="select sum(farm_medicinaexistenciaxarea.Existencia)as Existencia,farm_medicinaexistenciaxarea.IdMedicina
						from farm_medicinaexistenciaxarea
						where farm_medicinaexistenciaxarea.IdMedicina in(select mnt_areamedicina.IdMedicina from mnt_areamedicina where mnt_areamedicina.IdArea='$IdArea')
						group by farm_medicinaexistenciaxarea.IdMedicina";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerExistenciaTotal
	

	function ObtenerRepetitivas($IdArea,$IdMedicina){
		$querySelect="select sum(farm_medicinarecetada.Cantidad)as TotalRepetitiva,farm_medicinarecetada.IdMedicina
					from farm_medicinarecetada
					inner join farm_recetas
					on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
					where farm_recetas.IdEstado='RE'
					and left(farm_recetas.Fecha,7)=left(curdate(),7)
					and farm_recetas.IdArea='$IdArea'
					and farm_medicinarecetada.IdMedicina='$IdMedicina'
					group by farm_medicinarecetada.IdMedicina";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);	
	}//OntenerRepetitivas
	
	
	function ExistenDatos($IdArea,$IdMedicina){
		$querySelect="select *
					from farm_existenciavirtual
					where farm_existenciavirtual.IdArea='$IdArea'
					and farm_existenciavirtual.IdMedicina='$IdMedicina'";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp[0]);	
	}//ExistenDatos($IdArea,$IdMedicina);
	
	
	function ObtenerExistenciaVirtual($IdArea){
		$querySelect="select farm_existenciavirtual.Existencia,farm_existenciavirtual.IdMedicina
					from farm_existenciavirtual
					where farm_existenciavirtual.IdArea='$IdArea'";
		$resp=mysql_query($querySelect);
		return($resp);
	}//ObtenerExistenciavirtual
	
	
	function ObtencionMedicamentoNoReclamado($IdArea,$IdMedicina){
	$FechaAtras=ExistenciaVirtualProceso::ObtenerFechaAtras();
		$querySelect="select sum(farm_medicinarecetada.Cantidad) as TotalNoEntregada,farm_medicinarecetada.IdMedicina,
					farm_recetas.IdReceta
					from farm_recetas
					inner join farm_medicinarecetada
					on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
where (farm_recetas.IdEstado='L' OR farm_recetas.IdEstado='N' OR farm_recetas.IdEstado='RL' OR farm_recetas.IdEstado='RN')
and farm_recetas.IdArea='$IdArea'
and farm_medicinarecetada.IdMedicina='$IdMedicina'
and farm_recetas.Fecha not in(select farm_recetas.Fecha
								from farm_recetas
								where  (farm_recetas.IdEstado='N' OR farm_recetas.IdEstado='L' OR farm_recetas.IdEstado='RL' OR farm_recetas.IdEstado='RN')
								and (farm_recetas.Fecha BETWEEN '$FechaAtras' and curdate())
								and farm_recetas.IdArea='$IdArea')
group by farm_medicinarecetada.IdMedicina";
		$resp=mysql_fetch_array(mysql_query($querySelect));
		return($resp);
	}//ObtencionMedicamentoNoReclamado
	
	
	
		function ObtenerFechaAtras(){
			$NombreFecha=mysql_fetch_array(mysql_query("select dayname(curdate()) as Nombre"));
			switch($NombreFecha["Nombre"]){
					case "Monday":
					$querySelect="select adddate(curdate(), interval -5 day) as FechaAtras";
					$dates = mysql_query($querySelect);
					$rowFechaA=mysql_fetch_array($dates);
					$FechaAtras=$rowFechaA["FechaAtras"];
					break;
					
					case "Tuesday":
					$querySelect="select adddate(curdate(), interval -4 day) as FechaAtras";
					$dates = mysql_query($querySelect);
					$rowFechaA=mysql_fetch_array($dates);
					$FechaAtras=$rowFechaA["FechaAtras"];
					break;
					
					default:
					$querySelect="select adddate(curdate(), interval -3 day) as FechaAtras";
					$dates = mysql_query($querySelect);
					$rowFechaA=mysql_fetch_array($dates);
					$FechaAtras=$rowFechaA["FechaAtras"];
					break;
					
			}//fin switch
		return($FechaAtras);
	}//funcion
	
	
	
	
	
	
}//Clase ExistenciaVirtualProceso


?>