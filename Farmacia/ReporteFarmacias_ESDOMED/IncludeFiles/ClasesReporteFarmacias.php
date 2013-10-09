<?php
include($path.'../../Clases/class.php');
class ReporteFarmacias{

/*COMBOS*/
	function GruposTerapeuticos($IdTerapeutico){
		$Complemento="";
		if($IdTerapeutico!=0){$Complemento="and IdTerapeutico='$IdTerapeutico'";}
		$query="select IdTerapeutico, GrupoTerapeutico
				from mnt_grupoterapeutico
				where GrupoTerapeutico <> '--'
				".$Complemento;
		$resp=mysql_query($query);
		return($resp);				
	}//GruposTerapeutics
	
	function MedicamentosPorGrupo($IdTerapeutico){

		$query="select IdMedicina, Nombre, Concentracion,FormaFarmaceutica,Presentacion
				from farm_catalogoproductos
				where IdTerapeutico='$IdTerapeutico'
				";
		$resp=mysql_query($query);
		return($resp);
	}
	
/************************************/

/*		CUERPO DEL REPORTE		*/
	
	function DatosMedicamentosPorGrupo($IdTerapeutico,$IdFarmacia,$IdMedicina){
		$Complemento="";
		//pasar por farm_medicinarecetada para obtener exactamente el medicamento en la base de datos
		if($IdMedicina!=0){$Complemento="and IdMedicina='$IdMedicina'";}
		$query="select IdMedicina,Codigo, Nombre, Concentracion,FormaFarmaceutica,Presentacion,Descripcion,UnidadesContenidas
				from farm_catalogoproductos
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where IdTerapeutico='$IdTerapeutico'
				
				".$Complemento."
				order by Codigo
				";
		$resp=mysql_query($query);
		return($resp);
	}

	function ConsumoMedicamento($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,$Bandera){
		switch($IdFarmacia){
			case 0:
				$Complemento="";
			break;
			case 1:
				$Complemento="and IdFarmacia=1";
			break;
			case 2:
				$Complemento="and IdFarmacia=2";
			break;
			case 3:
				$Complemento="and IdFarmacia=3";
			break;
		}//switch
		if($Bandera==1){$ConsumoReal="and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')";}else{$ConsumoReal="";}
		$query="select sum(farm_medicinarecetada.Cantidad) as Total
				from farm_recetas
				inner join farm_medicinarecetada
				on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
				where Fecha between '$FechaInicial' and '$FechaFinal'
				and farm_recetas.IdEstado<>'D'
				".$Complemento."
				".$ConsumoReal."				
				and IdMedicina='$IdMedicina'
				and IdPersonalIntro is null
				group by IdMedicina";
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);		
	}
	
	function ObtenerPrecio($IdMedicina,$Ano){
		$query="select Precio
				from farm_preciosxano
				where IdMedicina='$IdMedicina'
				and Ano	='$Ano'";
		$resp=mysql_fetch_array(mysql_query($query));
		if($resp[0]!=NULL){$Respuesta=$resp[0];}else{$Respuesta=0;}
		return($Respuesta);
	}
	
	
	function TotalRecetas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal){
		switch($IdFarmacia){
			case 0:
				$Complemento="";
			break;

			case 1:
				$Complemento="and IdFarmacia=1";
			break;
			case 2:
				$Complemento="and IdFarmacia=2";
			break;
			case 3:
				$Complemento="and IdFarmacia=3";
			break;
		}//switch
		
		$query="select count(farm_medicinarecetada.IdMedicina) as Total
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdEstado<>'D'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				".$Complemento;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
	}
	
	function TotalSatisfechas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal){
		switch($IdFarmacia){
			case 0:
				$Complemento="";
			break;

			case 1:
				$Complemento="and IdFarmacia=1";
			break;
			case 2:
				$Complemento="and IdFarmacia=2";
			break;
			case 3:
				$Complemento="and IdFarmacia=3";
			break;
		}//switch
		$query="select count(farm_medicinarecetada.IdMedicina) as Total
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdEstado<>'D'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				and (farm_medicinarecetada.IdEstado='S' or farm_medicinarecetada.IdEstado='')
				".$Complemento;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
	}
	
	function TotalInsatisfechas($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal){
		switch($IdFarmacia){
			case 0:
				$Complemento="";
			break;

			case 1:
				$Complemento="and IdFarmacia=1";
			break;
			case 2:
				$Complemento="and IdFarmacia=2";
			break;
			case 3:
				$Complemento="and IdFarmacia=3";
			break;
		}//switch
		$query="select count(farm_medicinarecetada.IdMedicina) as Total
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdEstado<>'D'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				and farm_medicinarecetada.IdEstado='I'
				".$Complemento;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
		
	}
	
	function IngresoPorGrupo($IdTerapeutico,$IdFarmacia,$FechaInicial,$FechaFinal){
			switch($IdFarmacia){
			case 0:
				$Complemento="";
			break;

			case 1:
				$Complemento="and IdFarmacia=1";
			break;
			case 2:
				$Complemento="and IdFarmacia=2";
			break;
			case 3:
				$Complemento="and IdFarmacia=3";
			break;
		}//switch
		$query="select distinct farm_medicinarecetada.IdMedicina
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				inner join farm_catalogoproductos
				on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
				inner join mnt_grupoterapeutico
				on mnt_grupoterapeutico.IdTerapeutico=farm_catalogoproductos.IdTerapeutico
				where farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				and farm_recetas.IdEstado<>'D'
				and mnt_grupoterapeutico.IdTerapeutico='$IdTerapeutico'
				".$Complemento;
		$resp=mysql_query($query);
		return($resp);
	}//Ingreso por Grupo
	
	
	
/*************************************/
}//Clase Reporte Farmacias
?>