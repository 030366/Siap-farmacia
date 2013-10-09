<?php
include($path.'../Clases/class.php');
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

		$query="select farm_catalogoproductos.IdMedicina, Nombre, Concentracion,FormaFarmaceutica,Presentacion,Codigo
				from farm_catalogoproductos
				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina
				where IdTerapeutico='$IdTerapeutico'
				order by Codigo";
		$resp=mysql_query($query);
		return($resp);
	}
	
/************************************/

/*		CUERPO DEL REPORTE		*/
	
	function DatosMedicamentosPorGrupo($IdTerapeutico,$IdFarmacia,$IdMedicina){
		$Complemento="";
		//pasar por farm_medicinarecetada para obtener exactamente el medicamento en la base de datos
		if($IdMedicina!=0){$Complemento="and farm_catalogoproductos.IdMedicina='$IdMedicina'";}
		$query="select farm_catalogoproductos.IdMedicina,Codigo, Nombre, Concentracion,FormaFarmaceutica,Presentacion,Descripcion,UnidadesContenidas
				from farm_catalogoproductos
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida

				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina
				
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
		   case 4:
			$Complemento="and IdFarmacia=4";
		   break;
		}//switch
		if($Bandera==1){$ConsumoReal="and farm_medicinarecetada.IdEstado<>'I'";}else{$ConsumoReal="";}

	   $SQL="select farm_medicinarecetada.IdMedicina,sum(CantidadDespachada)/UnidadesContenidas as Total,
		farm_lotes.IdLote,UnidadesContenidas,Lote,PrecioLote,
		(sum(CantidadDespachada)/UnidadesContenidas)*PrecioLote as Costo

		from farm_recetas
		inner join farm_medicinarecetada
		on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
		inner join farm_medicinadespachada
		on farm_medicinadespachada.IdMedicinaRecetada=farm_medicinarecetada.IdMedicinaRecetada
		inner join farm_lotes
		on farm_lotes.IdLote=farm_medicinadespachada.IdLote
		inner join farm_catalogoproductos
		on farm_medicinarecetada.IdMedicina=farm_catalogoproductos.IdMedicina
		inner join farm_unidadmedidas
		on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
		
		where Fecha between '".$FechaInicial."' and '".$FechaFinal."'
		and farm_medicinarecetada.IdMedicina=".$IdMedicina."
		".$Complemento."
		".$ConsumoReal."
		group by farm_medicinarecetada.IdMedicina,farm_lotes.IdLote";
		
		$resp=mysql_query($SQL);
		return($resp);
	}
	
	
//Funciones de consumo antiguas
	function ConsumoMedicamento_old($IdMedicina,$IdFarmacia,$FechaInicial,$FechaFinal,$Bandera){
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
		   case 4:
			$Complemento="and IdFarmacia=4";
		   break;
		}//switch
		if($Bandera==1){$ConsumoReal="and farm_medicinarecetada.IdEstado<>'I'";}else{$ConsumoReal="";}
		$query="select sum(farm_medicinarecetada.Cantidad) as Total
				from farm_recetas
				inner join farm_medicinarecetada
				on farm_medicinarecetada.IdReceta=farm_recetas.IdReceta
				where Fecha between '$FechaInicial' and '$FechaFinal'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				".$Complemento."
				".$ConsumoReal."				
				and IdMedicina='$IdMedicina'
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
	
//*********************************************************************************
	
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
		   case 4:
				$Complemento="and IdFarmacia=4";
		   break;
		}//switch
		
		$query="select count(farm_medicinarecetada.IdMedicinaRecetada) as Total
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
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
		   case 4:
				$Complemento="and IdFarmacia=4";
		   break;
		}//switch
		$query="select count(farm_medicinarecetada.IdMedicina) as Total
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				and farm_medicinarecetada.IdEstado<>'I'
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
		   case 4:
				$Complemento="and IdFarmacia=4";
		   break;
		}//switch
		$query="select count(farm_medicinarecetada.IdMedicina) as Total
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				and farm_medicinarecetada.IdEstado='I'
				".$Complemento;
		$resp=mysql_fetch_array(mysql_query($query));
		return($resp[0]);
		
	}
	
	function IngresoPorGrupo($IdTerapeutico,$IdFarmacia,$FechaInicial,$FechaFinal){
		//Se verifica que medicamento tiene registros en farm_medicinarecetada
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
			case 4:
				$Complemento="and IdFarmacia=4";
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

				inner join farm_catalogoproductosxestablecimiento fcpe
				on fcpe.IdMedicina=farm_catalogoproductos.IdMedicina

				where farm_recetas.Fecha between '$FechaInicial' and '$FechaFinal'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and mnt_grupoterapeutico.IdTerapeutico='$IdTerapeutico'
				".$Complemento;
		$resp=mysql_query($query);
		return($resp);
	}//Ingreso por Grupo
	
	function InsatisfechasEstimadas($IdMedicina,$FechaInicial,$FechaFinal){
	   $SQL="select *
		from farm_periododesabastecido
		where (FechaInicio between '$FechaInicial' and '$FechaFinal' or FechaFin between '$FechaInicial' and '$FechaFinal')
		and IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return ($resp);
	}
	
	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}
        
        
                
        function TOP($limite,$IdUnidadMedida,$FechaInicio,$FechaFin,$IdFarmacia){
            
            if($IdFarmacia!=0){$comp="and fr.IdFarmacia=".$IdFarmacia;}else{$comp="";}
            
            $query="SELECT distinct fcp.IdMedicina,Codigo, Nombre,Concentracion,FormaFarmaceutica, Presentacion, 
                    Descripcion, (sum(CantidadDespachada)/UnidadesContenidas) as TotalConsumo, 
                    count(fmr.IdMedicina) as Recetas, (sum(CantidadDespachada)/UnidadesContenidas)*PrecioLote as Monto
                    
                    from farm_catalogoproductos fcp
                    inner join farm_unidadmedidas fum
                    on fum.IdUnidadMedida=fcp.IdUnidadMedida
                    inner join farm_medicinarecetada fmr
                    on fmr.IdMedicina=fcp.IdMedicina
                    inner join farm_medicinadespachada fmd
                    on fmd.IdMedicinaRecetada=fmr.IdMedicinaRecetada
                    inner join farm_recetas fr
                    on fr.IdReceta=fmr.IdReceta
                    inner join farm_lotes fl
                    on fl.IdLote=fmd.IdLote

                    where fum.IdUnidadMedida = ".$IdUnidadMedida."
                    and FechaEntrega between '".$FechaInicio."' and '".$FechaFin."'
                    ".$comp."
                    and fr.IdFarmacia in (1,2,3)
                    group by fmr.IdMedicina
                    order by TotalConsumo desc
                    limit ".$limite;
            $resp=mysql_query($query);
            return $resp;            
            
        }
        
        
        function TipoMedida($i){
            $query="select Descripcion from farm_unidadmedidas where IdUnidadMedida=".$i;
            $resp=mysql_fetch_array(mysql_query($query));
            return $resp;            
        }
/*************************************/
}//Clase Reporte Farmacias
?>