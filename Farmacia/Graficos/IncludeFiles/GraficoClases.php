<?php
include('../Clases/class.php');
class Graficacion{

function QueryGrafica($IdGrupo,$IdMedicina,$fechaInicio,$fechaFin){//aun sin uso
$querySelect="select farm_catalogoproductos.Nombre,farm_medicinarecetada.IdMedicina, sum(farm_medicinarecetada.Cantidad)as MedicinaEntregada,farm_medicinaexistenciaxarea.Existencia
from farm_medicinarecetada
inner join farm_medicinaexistenciaxarea
on farm_medicinaexistenciaxarea.IdMedicina=farm_medicinarecetada.IdMedicina
inner join farm_catalogoproductos
on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
group by farm_medicinarecetada.IdMedicina 
order by farm_medicinarecetada.IdMedicina";

$resp=mysql_query($querySelect);

return($resp);
}//QueryGrafica


function QueryGraficaPorMedicamento($grupo,$IdMedicina,$fechaInicio,$fechaFin){
if($IdMedicina!=0){$comp="and farm_medicinarecetada.IdMedicina=".$IdMedicina;}else{$comp="";}


$querySelect="select farm_catalogoproductos.Nombre,farm_medicinarecetada.IdMedicina, 
farm_medicinarecetada.FechaEntrega,farm_medicinarecetada.Cantidad,monthname(farm_medicinarecetada.FechaEntrega)as MesNombre,
year(farm_medicinarecetada.FechaEntrega) as ano,farm_catalogoproductos.FormaFarmaceutica,
sum(farm_medicinarecetada.Cantidad)as Suma,farm_unidadmedidas.UnidadesContenidas as Divisor,farm_unidadmedidas.Descripcion, farm_catalogoproductos.Concentracion
from farm_medicinarecetada
inner join farm_catalogoproductos
on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
inner join farm_recetas
on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
inner join farm_unidadmedidas
on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
where  farm_medicinarecetada.FechaEntrega between '$fechaInicio' and '$fechaFin'
and IdTerapeutico=".$grupo."
".$comp."
and farm_medicinarecetada.IdEstado='S' 
and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
group by month(farm_medicinarecetada.FechaEntrega), farm_medicinarecetada.IdMedicina
order by farm_medicinarecetada.IdMedicina,month(farm_medicinarecetada.FechaEntrega)
";

$resp=mysql_query($querySelect);

return($resp);
}//GreficaPorMedicamento



function QueryGraficaPorMedicamento2($grupo,$IdMedicina,$fechaInicio,$fechaFin){
if($IdMedicina!=0){$comp="and farm_medicinarecetada.IdMedicina=".$IdMedicina;}else{$comp="";}


$querySelect="select distinct farm_medicinarecetada.IdMedicina
from farm_medicinarecetada
inner join farm_catalogoproductos
on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
inner join farm_recetas
on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
inner join farm_unidadmedidas
on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
where  farm_medicinarecetada.FechaEntrega between '$fechaInicio' and '$fechaFin'
and IdTerapeutico=".$grupo."
".$comp."
and farm_medicinarecetada.IdEstado='S' 
and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
group by month(farm_medicinarecetada.FechaEntrega), farm_medicinarecetada.IdMedicina
order by farm_medicinarecetada.IdMedicina
";

$resp=mysql_query($querySelect);

return($resp);
}//GreficaPorMedicamento2



/////////////////POR NUMERO DE RECETAS////////////////////////////


function QueryGraficaPorMedicamentoRecetas($grupo,$IdMedicina,$fechaInicio,$fechaFin){
if($IdMedicina!=0){$comp="and farm_medicinarecetada.IdMedicina=".$IdMedicina;}else{$comp="";}


$querySelect="select farm_catalogoproductos.Nombre,farm_medicinarecetada.IdMedicina, 
farm_medicinarecetada.FechaEntrega,farm_medicinarecetada.Cantidad,monthname(farm_medicinarecetada.FechaEntrega)as MesNombre,
year(farm_medicinarecetada.FechaEntrega) as ano,farm_catalogoproductos.FormaFarmaceutica,
sum(farm_medicinarecetada.Cantidad)as Suma,farm_unidadmedidas.UnidadesContenidas as Divisor,farm_unidadmedidas.Descripcion, farm_catalogoproductos.Concentracion, count(farm_medicinarecetada.IdMedicina) as TotalRecetas
from farm_medicinarecetada
inner join farm_catalogoproductos
on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
inner join farm_recetas
on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
inner join farm_unidadmedidas
on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
where  farm_medicinarecetada.FechaEntrega between '$fechaInicio' and '$fechaFin'
and IdTerapeutico=".$grupo."
".$comp."
and farm_medicinarecetada.IdEstado='S' 
and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
group by month(farm_medicinarecetada.FechaEntrega), farm_medicinarecetada.IdMedicina
order by farm_medicinarecetada.IdMedicina,month(farm_medicinarecetada.FechaEntrega)
";

$resp=mysql_query($querySelect);

return($resp);
}//GreficaPorMedicamento



function QueryGraficaPorMedicamentoRecetas2($grupo,$IdMedicina,$fechaInicio,$fechaFin){
if($IdMedicina!=0){$comp="and farm_medicinarecetada.IdMedicina=".$IdMedicina;}else{$comp="";}


$querySelect="select distinct farm_medicinarecetada.IdMedicina
from farm_medicinarecetada
inner join farm_catalogoproductos
on farm_catalogoproductos.IdMedicina=farm_medicinarecetada.IdMedicina
inner join farm_recetas
on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
inner join farm_unidadmedidas
on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
where  farm_medicinarecetada.FechaEntrega between '$fechaInicio' and '$fechaFin'
and IdTerapeutico=".$grupo."
".$comp."
and farm_medicinarecetada.IdEstado='S' 
and (farm_recetas.IdEstado='E' or farm_recetas.IdEstado='ER')
group by month(farm_medicinarecetada.FechaEntrega), farm_medicinarecetada.IdMedicina
order by farm_medicinarecetada.IdMedicina
";

$resp=mysql_query($querySelect);

return($resp);
}//GreficaPorMedicamento2




}//graficacion

?>