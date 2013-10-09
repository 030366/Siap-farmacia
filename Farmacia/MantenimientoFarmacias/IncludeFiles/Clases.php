<?php

include('../../Clases/class.php');

class Mantenimientos {

    function AreasFarmacia($IdFarmacia, $IdEstablecimiento,$IdModalidad) {
        $query = "select IdArea,Area, ( select Habilitado 
                                        from mnt_areafarmaciaxestablecimiento 
                                        where IdArea=maf.IdArea and IdEstablecimiento=" . $IdEstablecimiento . "
                                            and IdModalidad=$IdModalidad ) as Habilitado
                  from mnt_areafarmacia maf
                  where IdFarmacia='$IdFarmacia' and IdArea<>7";
        $resp = mysql_query($query);

        return($resp);
    }

    function IngresarArea($IdFarmacia, $NombreArea, $Estado, $IdEstablecimiento) {
        $SQL = "insert into mnt_areafarmacia (Area,IdFarmacia,Habilitado,IdEstablecimiento) 
                                       values('$NombreArea','$IdFarmacia','$Estado','$IdEstablecimiento')";
        mysql_query($SQL);
    }

    function CambioEstadoFarmacia($IdFarmacia, $Estado, $IdEstablecimiento, $IdModalidad) {

        $query = "select * from mnt_farmaciaxestablecimiento where IdFarmacia =$IdFarmacia and IdEstablecimiento=$IdEstablecimiento and IdModalidad=$IdModalidad";
        $r = mysql_query($query);

        if ($ro = mysql_fetch_array($r)) {
            $SQL = "update mnt_farmaciaxestablecimiento set HabilitadoFarmacia='" . $Estado . "' where IdFarmacia=" . $IdFarmacia . " and IdEstablecimiento=" . $IdEstablecimiento . " and IdModalidad=" . $IdModalidad;
        } else {

            $SQL = "insert into mnt_farmaciaxestablecimiento (IdFarmacia,HabilitadoFarmacia,IdEstablecimiento,IdModalidad) values ($IdFarmacia,'" . $Estado . "'," . $IdEstablecimiento . ",$IdModalidad)";
        }
        mysql_query($SQL);
    }

    function CambioEstado($IdArea, $Estado, $IdEstablecimiento, $IdModalidad) {

        $query = "select * from mnt_areafarmaciaxestablecimiento 
                  where IdArea =$IdArea 
                  and IdEstablecimiento=$IdEstablecimiento and IdModalidad=$IdModalidad";
        $r = mysql_query($query);

        if ($ro = mysql_fetch_array($r)) {
            $SQL = "update mnt_areafarmaciaxestablecimiento set Habilitado='" . $Estado . "' 
                    where IdEstablecimiento='" . $IdEstablecimiento . "' and IdModalidad=$IdModalidad and IdArea=" . $IdArea;
        } else {

            $SQL = "insert into mnt_areafarmaciaxestablecimiento (IdArea, Habilitado,IdEstablecimiento,IdModalidad) 
                                                          values ($IdArea,'" . $Estado . "', " . $IdEstablecimiento . ", $IdModalidad)";
        }
        mysql_query($SQL);
    }

    function verificar($NombreArea) {
        $SQL = "select * from mnt_areafarmacia where Area = '$NombreArea'";
        $resp = mysql_query($SQL);
        return($resp);
    }

    function Farmacia($IdFarmacia) {
        $SQL = "select IdFarmacia, Farmacia from mnt_farmacia where IdFarmacia=" . $IdFarmacia;
        $resp = mysql_query($SQL);
        return($resp);
    }

    function ActualizaNombreFarmacia($IdFarmacia, $NombreNuevo) {
        $SQL = "update mnt_farmacia set Farmacia='$NombreNuevo' where IdFarmacia=" . $IdFarmacia;
        mysql_query($SQL);
    }

    function FarmaciaArea($IdArea) {
        $SQL = "select IdArea, Area from mnt_areafarmacia where IdArea=" . $IdArea;
        $resp = mysql_query($SQL);
        return($resp);
    }

    function ActualizaNombreFarmaciaArea($IdArea, $NombreNuevo) {
        $SQL = "update mnt_areafarmacia set Area='$NombreNuevo' where IdArea=" . $IdArea;
        mysql_query($SQL);
    }

}

//Clase Ingreso Empleados
?>