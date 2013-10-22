<?php include('../../Clases/class.php');

class Desabastecimiento{
   function ValidacionPeriodo($IdMedicina,$FechaInicio,$FechaFin,$IdEstablecimiento,$IdModalidad){
	$SQL="select *
		from farm_periododesabastecido
		where ('$FechaInicio' between FechaInicio and FechaFin or '$FechaFin' between FechaInicio and FechaFin)
		and IdMedicina=".$IdMedicina." and
		IdEstablecimiento=".$IdEstablecimiento." 
                and IdModalidad=$IdModalidad";
	$resp=mysql_query($SQL);	
	return($resp);
   }

   function InsatisfechasPromedio($IdMedicina,$FechaInicio,$FechaFin,$IdEstablecimiento,$IdModalidad){
	$SQL="select count(IdMedicinaRecetada) as TotalRecetas,

ceil(datediff('$FechaInicio',adddate('$FechaInicio',interval -1 month))) as DiasMeses,

count(IdMedicinaRecetada)/ceil(datediff('$FechaInicio',adddate('$FechaInicio',interval -1 month))) as PromedioDiaRecetas, 

ceil((count(IdMedicinaRecetada)/ceil(datediff('$FechaInicio',adddate('$FechaInicio',interval -1 month)))) * datediff('$FechaFin','$FechaInicio')) as PromInsatisfechas 

	from farm_recetas fr 
	inner join farm_medicinarecetada fmr 
	on fr.IdReceta=fmr.IdReceta 
        inner join sec_historial_clinico shc
        on shc.IdHistorialClinico=fr.IdHistorialClinico
	where (fr.IdEstado='E' or fr.IdEstado='ER') 
	and (fmr.IdEstado='S' or fmr.IdEstado='') 
	and IdMedicina = $IdMedicina 
        and shc.IdEstablecimiento=$IdEstablecimiento
        and fr.IdModalidad=$IdModalidad
	and fr.Fecha between adddate('$FechaInicio',interval -1 month) and '$FechaFin'
	";
	$resp=mysql_query($SQL);
	return($resp);
   }

   function IngresarDatosInsatisfecha($IdMedicina,$FechaInicio,$FechaFin,$InsatisfechasTotal,$PromedioDiaRecetas,$IdEstablecimiento,$IdModalidad){
	$SQL="insert into farm_periododesabastecido (IdMedicina,FechaInicio,FechaFin,PromedioRecetas,PromedioDiarias,IdEstablecimiento, IdModalidad) 
                                              values('$IdMedicina','$FechaInicio','$FechaFin','$InsatisfechasTotal','$PromedioDiaRecetas','$IdEstablecimiento',$IdModalidad)";
	mysql_query($SQL);
   }

}

?>