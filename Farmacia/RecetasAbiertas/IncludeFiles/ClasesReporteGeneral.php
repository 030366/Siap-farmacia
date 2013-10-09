<?php
include($path.'../Clases/class.php');
class RecetasAbiertas{
    function CierreMes($Periodo,$IdEstablecimiento,$IdModalidad){
        $query="select MesCierre from farm_cierre 
                where MesCierre='".$Periodo."'
                and IdEstablecimiento=$IdEstablecimiento and IdModalidad=$IdModalidad";
        $resp=mysql_fetch_array(mysql_query($query));
        return($resp[0]);
    }
    
    function ListadoRecetasAbiertas($Periodo,$IdEstablecimiento,$IdModalidad){
        $query="select CorrelativoAnual,Nombre,Area,Fecha, IdReceta
                from farm_recetas fr
                inner join farm_usuarios fu
                on fu.IdPersonal=fr.IdPersonalIntro
                inner join mnt_areafarmacia maf
                on maf.IdArea=fr.IdArea
                where left(Fecha,7)='".$Periodo."' and IdEstado <> 'E'
                and fr.IdEstablecimiento=$IdEstablecimiento
                and fr.IdModalidad=$IdModalidad";
        $resp=mysql_query($query);
        return($resp);
    }
    
    function FinalizarReceta($IdReceta,$IdEstablecimiento,$IdModalidad){
        $query="update farm_recetas set IdEstado='E' 
                where IdReceta=".$IdReceta."
                and IdEstablecimiento=$IdEstablecimiento
                and IdModalidad=$IdModalidad";
        mysql_query($query);
    }
}//Clase Reporte Farmacias
?>