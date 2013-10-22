<?php

session_start();

include("../../Clases/class.php");
include("Clases.php");
conexion::conectar();
$puntero = new fixing;

switch ($_GET["Bandera"]) {
    case 1:
        $IdFarmacia = $_GET["IdFarmacia"];
        $FechaInicial = $_GET["fechaInicial"];
        $datos = true;

        $result = $puntero->ErroresDespacho($IdFarmacia, $FechaInicial);

        if ($row = mysql_fetch_array($result)) {
            if ($row["Valido"] == 'NO') {

                $tabla = "<table>
                    <tr><th>IdMedicinaRecetada</th>
                        <th>IdMedicina</th>
                        <th>Fecha</th>
                        <th>Cantidad</th>
                        <th>Despachada</th>
                        <th>Lote Despacho</th>
                        <th>IdFarmacia</th>
                        <th>Valido</th>
                        <th>Accion</th>
                    </tr>";

                do {

                    if ($row["Valido"] == 'NO') {

                        $Medicina = htmlentities($puntero->NombreMedicina($row["IdMedicina"]));
                        $NombreArea = $puntero->NombreArea($row["IdArea"]);


                        $tabla.="
                    <tr><td align='center'>" . $row["IdMedicinaRecetada"] . "</td>
                        <td align='center'>" . $Medicina . "</td>
                        <td align='center'>" . $row["Fecha"] . "</td>
                        <td align='center'>" . $row["Cantidad"] . "</td>
                        <td align='center'>" . $row["Despacho"] . "</td>
                        <td align='center'>" . $row["IdLote"] . "</td>
                        <td align='center'>" . $NombreArea . "</td>
                        <td align='center'>" . $row["Valido"] . "</td>    
                        <td align='center'><span id='fix".$row["IdMedicinaRecetada"]."'><a onclick='MostrarDetalle(" . $row["IdMedicina"] . "," . $row["IdArea"] . "," . $row["IdMedicinaRecetada"] . ")'>FIX IT!</a></span></td> 
                    </tr>
                    <tr><td colspan='8' align='center'><span id='detalles" . $row["IdMedicinaRecetada"] . "'></span></td></tr>";
                    }
                } while ($row = mysql_fetch_array($result));

                $tabla.="</table>";
            } else {
                $datos = false;
            }
        } else {
            $datos = false;
        }

        if ($datos == FALSE) {
            $tabla = "<strong><h2>NO HAY DATOS</h2></strong>";
        }


        echo $tabla;
        break;


    case 2:
        $IdMedicina=$_GET["IdMedicina"];
        $IdArea=$_GET["IdArea"];
        $IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
        
        
        $resp=$puntero->detalleMedicina($IdMedicina,$IdArea);
        $resp2=$puntero->detalleDespacho($IdMedicinaRecetada);
        $CantidadReal=$puntero->detalleMedicinaRecetada($IdMedicinaRecetada);
        
        $tabla="<table align='center' border=1>
                <tr><td align='center'>Cambios Sugeridos</td><td><a onclick='Cerrar(".$resp2["IdMedicinaRecetada"].")'>Cerrar</a></td></tr>
                <tr><td colspan=2>
                        <table>
                            <tr><td>No. Registo</td><td>Cantidad</td><td>IdLote</td></tr>
                            <tr><td>".$resp2["IdMedicinaRecetada"]."</td>
                                <td align='center'><input readonly='true' type='text' id='nueva".$resp2["IdMedicinaRecetada"]."' value='".$CantidadReal."' size='5'/></td>
                                <td align='center'><input readonly='true' type='text' id='nuevo".$resp2["IdMedicinaRecetada"]."' value='".$resp["IdLote"]."' size='5'/></td>
                                <td align='right'><input type='button' id='aplicar".$resp2["IdMedicinaRecetada"]."' value='Aplicar!' onclick='AplicarCambios(".$resp2["IdMedicinaDespachada"].",".$resp2["IdMedicinaRecetada"].")'/>
                            </tr>
                        </table>
                    </td>
                </tr>

                ";
        

        $tabla.="</table>";
        
        echo $tabla;
        break;
    
    case 3:
        $IdMedicinaDespachada=$_GET["IdMedicinaDespachada"];
        $CantidadDespachada=$_GET["CantidadDespachada"];
        $IdLote=$_GET["LoteDespacho"];
        
        
        $puntero->ActualizarDespacho($IdMedicinaDespachada,$CantidadDespachada,$IdLote);
        
        
        break;
}



conexion::desconectar();
?>
