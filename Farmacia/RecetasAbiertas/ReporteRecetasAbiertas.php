<?php

session_start();

if (!isset($_SESSION["nivel"])) {
    echo "ERROR_SESSION";
} else {

    $path = "";
    include('IncludeFiles/ClasesReporteGeneral.php');
    conexion::conectar();
    
    $IdEstablecimiento=$_SESSION["IdEstablecimiento"];
    $IdModalidad=$_SESSION["IdModalidad"];
    
    $puntero = new RecetasAbiertas;

    switch ($_GET["Bandera"]) {
        case 1:
            /* 	PARAMETROS	 */

            $Mes = $_GET["Mes"];
            $Anio = $_GET["Anio"];


            $Periodo = $Anio . "-" . $Mes;

// Validacion de Cierres

            $resp = $puntero->CierreMes($Periodo,$IdEstablecimiento,$IdModalidad);

            if ($resp != NULL or $resp != "") {
                $reporte = "<strong><h2>NO SE PUEDE MOSTRAR ESTE MES, <br>
              DEBIDO A QUE SE ENCUENTRA CERRADO POR EL ADMINISTRADOR</h2></strong>";
            } else {



                $reporte = '<table width="65%">
	<tr class="MYTABLE">	
            <th align="center" style="vertical-align:middle;" colspan=5>
		<h4>Recetas Abiertas</h4>
            </th>
	</tr>';

                $reporte.='       
	<tr>
            <th class="FONDO">Correlativo</td>
            <th class="FONDO">Digitador</td>
            <th class="FONDO">Area</td>
            <th class="FONDO">Fecha</td>
            <th class="FONDO">Acciones</td>
	</tr>';



                $resp = $puntero->ListadoRecetasAbiertas($Periodo,$IdEstablecimiento,$IdModalidad);

                if ($row = mysql_fetch_array($resp)) {
                    do {
                        $reporte.="<tr>
                        <td align=center>" . $row["CorrelativoAnual"] . "</td>
                        <td>" . $row["Nombre"] . "</td>
                        <td align=center>" . $row["Area"] . "</td>
                        <td align=center>" . $row["Fecha"] . "</td>
                        <td align=center>
                            <span id='" . $row["IdReceta"] . "'></span>
                            <input type='button' id='Fin" . $row["IdReceta"] . "' name='Fin' value='Finalizar' onclick='Finalizar(" . $row["IdReceta"] . ",\"" . $row["CorrelativoAnual"] . "\");'/> 
                        </td>
                        </tr>";
                    } while ($row = mysql_fetch_array($resp));
                }else{
                    $reporte.="<tr><td colspan=5 align=center>NO HAY RECETAS ABIERTAS EN ESTE PERIODO</td></tr>";
                }

                $reporte.='<tr class="MYTABLE"><td colspan=5>&nbsp;</td></tr>
           </table>';
            }


            echo $reporte;

            break;
        case 2:
            $IdReceta = $_GET["IdReceta"];
            $puntero->FinalizarReceta($IdReceta,$IdEstablecimiento,$IdModalidad);
            break;
    }

// Finaliza conexion de Base
    conexion::desconectar();
}
?>