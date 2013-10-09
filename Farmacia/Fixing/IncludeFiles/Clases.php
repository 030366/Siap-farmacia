<?php

class fixing {

    function NombreArea($IdArea) {
        $query = "select Area from mnt_areafarmacia where IdArea=" . $IdArea;
        $resp = mysql_fetch_array(mysql_query($query));
        return $resp[0];
    }

    function NombreMedicina($IdMedicina) {
        $query = "select Nombre from farm_catalogoproductos where IdMedicina=" . $IdMedicina;
        $resp = mysql_fetch_array(mysql_query($query));
        return $resp[0];
    }

    function ErroresDespacho($IdFarmacia, $FechaInicial) {
        if ($IdFarmacia == 0) {
            $comp = "";
        } else {
            $comp = " and fr.IdFarmacia=" . $IdFarmacia;
        }
        $query = "
            SELECT fmr.IdMedicinaRecetada, sum( fmd.CantidadDespachada ) AS Despacho, fmr.Cantidad, fr.Fecha, fmr.IdMedicina, 
            if(sum( fmd.CantidadDespachada ) != fmr.Cantidad,'NO','OK') as Valido, fr.IdArea, fmd.IdLote

            FROM farm_recetas fr
            INNER JOIN farm_medicinarecetada fmr ON fmr.IdReceta = fr.IdReceta
            INNER JOIN farm_medicinadespachada fmd ON fmd.IdMedicinaRecetada = fmr.IdMedicinaRecetada
            WHERE Fecha
            BETWEEN '$FechaInicial' AND curdate()
            " . $comp . "
            and  fmr.IdMedicina <> 0
                        
            GROUP BY fmr.IdMedicinaRecetada, fr.Fecha
            order by Valido";

        $resp = mysql_query($query);
        return $resp;
    }

    
    
    function detalleMedicina($IdMedicina,$IdArea){
        $query="select IdMedicina,Existencia,IdLote 
                from farm_medicinaexistenciaxarea 
                where IdMedicina=".$IdMedicina."
                and IdArea=".$IdArea."
                order by IdLote desc
                limit 1";
        $resp=mysql_fetch_array(mysql_query($query));
        return $resp;
    }
    
    
    
    function detalleDespacho($IdMedicinaRecetada){
        $query="select *
                from farm_medicinadespachada 
                where IdMedicinaRecetada=".$IdMedicinaRecetada;
        $resp=mysql_fetch_array(mysql_query($query));
        return $resp;
    }
    
    function detalleMedicinaRecetada($IdMedicinaRecetada){
        $query="select Cantidad from farm_medicinarecetada where IdMedicinaRecetada=".$IdMedicinaRecetada;
        $resp=mysql_fetch_array(mysql_query($query));
        return $resp[0];
    }
    
    function ActualizarDespacho($IdMedicinaDespachada,$CantidadDespachada,$IdLote){
        $query="update farm_medicinadespachada set CantidadDespachada=$CantidadDespachada,IdLote=$IdLote where IdMedicinaDespachada=".$IdMedicinaDespachada;
        $resp=mysql_query($query);       
    }
    
}

?>
