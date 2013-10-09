<?php
class Obtencion{
function ObtenerGrupos(){

$selectGrupo="select * from mnt_grupoterapeutico";
$Grupo=mysql_query($selectGrupo);

return($Grupo);
}//Grupos

function ObtenerDetalleMedicamentoPorGrupo($area,$IdTerapeutico){
$querySelect="select farm_catalogoproductos.Codigo, farm_catalogoproductos.IdMedicina,farm_catalogoproductos.Nombre,farm_catalogoproductos.FormaFarmaceutica,
farm_catalogoproductos.Concentracion, Presentacion, farm_medicinaexistenciaxarea.IdArea
from farm_catalogoproductos
inner join farm_medicinaexistenciaxarea
on farm_medicinaexistenciaxarea.IdMedicina=farm_catalogoproductos.IdMedicina
where farm_medicinaexistenciaxarea.IdArea='$area' and farm_catalogoproductos.IdTerapeutico='$IdTerapeutico'";

 $resp=mysql_query($querySelect);

 return($resp);

}//DetalleMEd

function ObtenerExistencias($IdArea,$IdMedicina){

 $RespEx=mysql_query("select farm_medicinaexistenciaxarea.Existencia, farm_lotes.Lote, 
farm_lotes.FechaVencimiento
from farm_medicinaexistenciaxarea
inner join farm_lotes
on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
where farm_medicinaexistenciaxarea.IdArea='$IdArea' 
and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'
and farm_medicinaexistenciaxarea.Existencia<>0
order by FechaVencimiento asc");
 return($RespEx);
}//ObtenerExistencias




function ObtenerExistenciasBodega($IdMedicina){

 $RespEx=mysql_query("select farm_entregamedicamento.Existencia, farm_lotes.Lote, 
farm_lotes.FechaVencimiento
from farm_entregamedicamento
inner join farm_lotes
on farm_lotes.IdLote=farm_entregamedicamento.IdLote
where farm_entregamedicamento.IdMedicina='$IdMedicina'
and farm_entregamedicamento.Existencia<>0
order by FechaVencimiento asc");
 return($RespEx);
}//ObtenerExistencias


function ObtenerPorcentaje($IdMedicina,$IdArea){
				/* PORCENTAJE DEL MES PASADO */
	$querySelect="select sum(farm_medicinarecetada.Cantidad)
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdArea='$IdArea'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdEstado='S'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and left(farm_medicinarecetada.FechaEntrega,7)=left(adddate(curdate(),interval -1 month),7)
				group by farm_medicinarecetada.IdMedicina";
				
				/* PORCENTAJE DEL MES ACTUAL */	
	$querySelect2="select sum(farm_medicinarecetada.Cantidad)
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where farm_recetas.IdArea='$IdArea'
				and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdEstado='S'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and left(farm_medicinarecetada.FechaEntrega,7)=left(curdate(),7)
				group by farm_medicinarecetada.IdMedicina";
				
				/* EXISTENCIAS */
	$querySelect3="select sum(farm_medicinaexistenciaxarea.Existencia)
				from farm_medicinaexistenciaxarea
				where farm_medicinaexistenciaxarea.IdArea='$IdArea'
				and farm_medicinaexistenciaxarea.IdMedicina='$IdMedicina'";
				
				$resp1=mysql_fetch_array(mysql_query($querySelect));
				$resp2=mysql_fetch_array(mysql_query($querySelect2));
				$resp3=mysql_fetch_array(mysql_query($querySelect3));
				$datos[0]=$resp1[0];//mes anterior
				$datos[1]=$resp2[0];//mes actual
				$datos[2]=$resp3[0];//mes actual
	return($datos);
}//ObtenerPorcentaje


function ObtenerPorcentajeBodega($IdMedicina){
				/* PORCENTAJE DEL MES PASADO */
	$querySelect="select sum(farm_medicinarecetada.Cantidad)
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdEstado='S'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and left(farm_medicinarecetada.FechaEntrega,7)=left(adddate(curdate(),interval -1 month),7)
				group by farm_medicinarecetada.IdMedicina";
				
				/* PORCENTAJE DEL MES ACTUAL */	
	$querySelect2="select sum(farm_medicinarecetada.Cantidad)
				from farm_medicinarecetada
				inner join farm_recetas
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				where (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
				and farm_medicinarecetada.IdEstado='S'
				and farm_medicinarecetada.IdMedicina='$IdMedicina'
				and left(farm_medicinarecetada.FechaEntrega,7)=left(curdate(),7)
				group by farm_medicinarecetada.IdMedicina";
				
				/* EXISTENCIAS */
	$querySelect3="select sum(farm_entregamedicamento.Existencia)
				from farm_entregamedicamento
				where farm_entregamedicamento.IdMedicina='$IdMedicina'";
				
				$resp1=mysql_fetch_array(mysql_query($querySelect));
				$resp2=mysql_fetch_array(mysql_query($querySelect2));
				$resp3=mysql_fetch_array(mysql_query($querySelect3));
				$datos[0]=$resp1[0];//mes anterior
				$datos[1]=$resp2[0];//mes actual
				$datos[2]=$resp3[0];//mes actual
	return($datos);
}//ObtenerPorcentaje


	function ValorDivisor($IdMedicina){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina;
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

}//Clase Obtencion
class Combos{
   function Farmacias(){
	$SQL="select * from mnt_farmacia";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function Areas($IdFarmacia){
	$SQL="select * from mnt_areafarmacia where IdArea<> 7 and Habilitado='S' and IdFarmacia=".$IdFarmacia;
	$resp=mysql_query($SQL);
		return($resp);
   }

}
?>