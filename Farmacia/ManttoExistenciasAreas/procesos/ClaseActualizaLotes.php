<?php
include('../../Clases/class.php');
class Actualiza{
function ObtenerMedicinaInformacion($IdMedicina,$Lote){
	$querySelect="select farm_lotes.IdLote,farm_catalogoproductos.Nombre,farm_catalogoproductos.Concentracion,
				farm_lotes.PrecioLote,monthname(farm_lotes.FechaVencimiento) as mes,
				year(farm_lotes.FechaVencimiento) as ano,farm_medicinaexistenciaxarea.Existencia,
				farm_unidadmedidas.Descripcion,farm_unidadmedidas.UnidadesContenidas as Divisor
				from farm_catalogoproductos
				inner join farm_medicinaexistenciaxarea
				on farm_medicinaexistenciaxarea.IdMedicina=farm_catalogoproductos.IdMedicina
				inner join farm_lotes
				on farm_lotes.IdLote=farm_medicinaexistenciaxarea.IdLote
				inner join farm_unidadmedidas
				on farm_unidadmedidas.IdUnidadMedida=farm_catalogoproductos.IdUnidadMedida
				where farm_catalogoproductos.IdMedicina='$IdMedicina'
				and farm_lotes.Lote='$Lote'
                                ";
	$resp=mysql_fetch_array(mysql_query($querySelect));
	return($resp);
}//ObtenerMedicinaInformacion


function EliminarExistenciaxArea($IdMedicina,$IdExistenciaArea,$IdLote,$IdArea,$IdEstablecimiento){
   $SQL="select IdExistencia,Existencia
	from farm_medicinaexistenciaxarea
	where IdExistencia=".$IdExistenciaArea."
        and IdEstablecimiento=".$IdEstablecimiento;
   $resp=mysql_fetch_array(mysql_query($SQL));
   $IdExistenciaArea=$resp["IdExistencia"];
   $ExistenciaArea=$resp["Existencia"];

     $SQL2="select *
	from farm_entregamedicamento
	where IdMedicina=".$IdMedicina."
	and IdLote=".$IdLote."
        and IdEstablecimiento=".$IdEstablecimiento;

    $resp2=mysql_fetch_array(mysql_query($SQL2));
	$ExistenciaBodega=$resp2["Existencia"];
	$IdEntrega=$resp2["IdEntrega"];

	$ExistenciaBodegaNueva=$ExistenciaBodega+$ExistenciaArea;
	
    $SQL3="update farm_entregamedicamento set Existencia='$ExistenciaBodegaNueva' where IdEntrega='$IdEntrega'";
	mysql_query($SQL3);

    $SQL4="delete from farm_medicinaexistenciaxarea where IdExistencia=".$IdExistenciaArea;
	mysql_query($SQL4);
    $SQL5="update farm_bitacoramedicinaexistenciaxarea set IdExistenciaOrigen=NULL where IdExistenciaOrigen=".$IdExistenciaArea;
	mysql_query($SQL5);
}


	function ValorDivisor($IdMedicina,$IdModalidad){
	   $SQL="select DivisorMedicina from farm_divisores where IdMedicina=".$IdMedicina." and IdModalidad=$IdModalidad";
	   $resp=mysql_query($SQL);
	   return($resp);
    	}

}//clase Actualiza


?>