<?php

class Digitacion{
    
    	function ObtenerInformacion($IdPersonal,$FechaInicial,$FechaFinal){
            
            if($IdPersonal!=0){$comp=" and farm_usuarios.IdPersonal=".$IdPersonal;}else{$comp="";}
            
		$query="select farm_usuarios.Nombre,count(IdMedicinaRecetada),farm_usuarios.IdPersonal
				from farm_usuarios
				inner join farm_recetas
				on farm_recetas.IdPersonalIntro=farm_usuarios.IdPersonal
							
				inner join farm_medicinarecetada
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				
				where FechaHoraReg is not null
				and date(FechaHoraReg) between '$FechaInicial' and '$FechaFinal'
				
                                ".$comp."
                                group by farm_recetas.IdPersonalIntro
                                order by farm_recetas.IdPersonalIntro";
		$resp=mysql_query($query);
		return($resp);
	}//Informacion
        
        
        function detalleDigitacion($IdPersonal,$FechaInicial,$FechaFinal){
            $query="select farm_usuarios.Nombre,date(FechaHoraReg) as Fecha,count(IdMedicinaRecetada) as Total
				from farm_usuarios
				inner join farm_recetas
				on farm_recetas.IdPersonalIntro=farm_usuarios.IdPersonal
							
				inner join farm_medicinarecetada
				on farm_recetas.IdReceta=farm_medicinarecetada.IdReceta
				inner join sec_historial_clinico
				on sec_historial_clinico.IdHistorialClinico=farm_recetas.IdHistorialClinico
				
				where FechaHoraReg is not null
                                and date(FechaHoraReg) between '$FechaInicial' and '$FechaFinal'
				
                                and farm_recetas.IdPersonalIntro=$IdPersonal

                                group by date(FechaHoraReg)
                                order by farm_recetas.IdPersonalIntro";
            $resp=mysql_query($query);
            return $resp;
        }
    
}
?>
