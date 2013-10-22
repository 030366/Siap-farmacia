<?php

session_start();

if (!isset($_SESSION["nivel"])) {
    echo "ERROR_SESSION";
} else {
    $IdPersonal = $_SESSION["IdPersonal"];
    require('TransferenciasProcesoClase.php');
    conexion::conectar();
    $proceso = new TransferenciaProceso;
    $Bandera = $_GET["Bandera"];
    $IdModalidad = $_SESSION["IdModalidad"];
    switch ($Bandera) {

        case 1:
            /* OBTENCION DE DATOS PARA LA INTRODUCCION DE TRANSFERENCIAS DE MEDICAMENTOS */
            $Cantidad = $_GET["Cantidad"];
            $IdMedicina = $_GET["IdMedicina"];
            $IdAreaOrigen = $_GET["IdAreaOrigen"];
            $IdAreaDestino = $_GET["IdAreaDestino"];
            $Justificacion = $_GET["Justificacion"];
            $FechaTransferencia = $_GET["Fecha"];
            $Lote = $_GET["Lote"];

            $Divisor = $_GET["Divisor"];
            $UnidadesContenidas = $_GET["UnidadesContenidas"];


            /* INTRODUCCION DE DATOS DE LA TRANSFERENCIA */
            $falta = $proceso->IntroducirTransferencia($Cantidad, $IdMedicina, $IdAreaOrigen, $IdAreaDestino, $Justificacion, $FechaTransferencia, $IdPersonal, $Lote, $Divisor, $UnidadesContenidas, $_SESSION["IdEstablecimiento"], $IdModalidad);

            echo '<select id="IdLote" name="IdLote" disabled="disabled"><option value="0">[Seleccione Lote...]</option></select>~<strong>' . $falta . '</h2></strong>';
            break;

        case 2:
            /* MUESTRA LAS TRANSFERENCIAS INTRODUCIDAD */
            $Fecha = $_GET["Fecha"];
            $IdAreaOrigen = $_GET["IdAreaOrigen"];
            $resp = $proceso->ObtenerTransferencias($IdPersonal, $Fecha);
            /* TABLA DE TRANSFERENCIAS */
            if ($row = mysql_fetch_array($resp)) {
                $tabla = '<table width="1018" border="1">
		<tr><td colspan="7" align="center"><strong>TRANFERENCIA(S) REALIZADA(S)</strong></td></tr>
		<tr class="FONDO">
		<td width="116" align="center"><strong>Cantidad</strong></td>
		<td width="189" align="center"><strong>Medicamento</strong></td>
		<td width="189" align="center"><strong>Unidad de Medida</strong></td>
		<td width="131" align="center"><strong>Tranf./Lote</strong></td>
		<td width="114" align="center"><strong>Area Origen</strong></td>
		<td width="159" align="center"><strong>Area Destino</strong></td>
		<td width="200" align="center"><strong>Justificacion</strong></td>
		<td width="74" align="center"><strong>Cancelar</strong></td>
		</tr>';
                $resp2 = $proceso->ObtenerTransferencias($IdPersonal, $Fecha);
                while ($row = mysql_fetch_array($resp2)) {
                    /* OBTENCION DE DETALLE DE TRANSFERENCIA POR LOTE */
                    $resp = $proceso->ObtenerDetalleLote($row["IdTransferencia"]);
                    $Cantidad = $resp["Cantidad"];
                    $IdLote = $resp["IdLote"];
                    $Lote = $resp["Lote"];
                    $DetalleLotes = '';



                    /*                     * ************************************************* */
                    $Divisor = $proceso->UnidadesContenidas($row["IdMedicina"]);

                    $TotalExistencia = $row["Cantidad"];
                    if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"], $_SESSION["IdEstablecimiento"], $IdModalidad))) {
                        $Divisor = $respDivisor[0];

                        if ($TotalExistencia < 1) {
                            //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                            $TransformaEntero = number_format($TotalExistencia * $Divisor, 0, '.', ',');
                            $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                        } else {
                            //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
                            $TotalExistencia = number_format($TotalExistencia, 3, '.', ',');
                            $CantidadBase = explode('.', $TotalExistencia);

                            $Entero = $CantidadBase[0]; //Faccion ENTERA
                            if (!isset($CantidadBase[1])) {
                                $Decimal = 0;
                            } else {
                                $Decimal = $CantidadBase[1];
                            }

                            if ($Decimal == 0) {
                                $Decimal = "";
                                $Quebrado = "";
                            } else {

                                $Quebrado = number_format(($Decimal / 1000) * $Divisor, 0, '.', ',');
                                $Quebrado = '[' . $Quebrado . '/' . $Divisor . ']';
                            }


                            $CantidadTransformada = $Entero . ' ' . $Quebrado;
                        }
                        $CantidadIntro = $CantidadTransformada;
                    } else {
                        $CantidadIntro = $TotalExistencia;
                        $CantidadIntro = $TotalExistencia / $Divisor;
                    }


                    $DetalleLotes.="Cant.: " . $CantidadIntro . "<br>Lote= " . $Lote . "<br><br>";


                    $tabla = $tabla . '<tr class="FONDO"><td align="center">' . $CantidadIntro . '</td><td align="center">' . htmlentities($row["Nombre"]) . ', ' . htmlentities($row["Concentracion"]) . ' - ' . htmlentities($row["Presentacion"]) . '</td><td align="center">' . htmlentities($row["Descripcion"]) . '</td><td align="center">' . $DetalleLotes . '</td><td align="center">' . $row["Area"] . '</td><td align="center">' . $proceso->NombreArea($row["IdAreaDestino"]) . '</td><td>' . htmlentities($row["Justificacion"]) . '</td><td align="center"><input type="button" id="borrar" name="borrar" value="Eliminar" onclick="javascript:BorrarTransferencia(' . $row["IdTransferencia"] . ')"></td></tr>';
                }//while resp
                $tabla = $tabla . '</table>';
            } else {
                $tabla = "";
            }
            echo $tabla;
            break;

        case 3:
            /* ELIMINACION DE TRANSFERENCIA */
            $IdTransferencia = $_GET["IdTransferencia"];
            $resp = $proceso->EliminarTransferencia($IdTransferencia, $IdModalidad);
            echo $resp;
            break;

        case 4:
            /* GENERACION DEL LISTADO DE LOTES HABILITADOS PARA LA TRANSFERENCIA */
            $IdMedicina = $_GET["IdMedicina"];
            $Cantidad = $_GET["Cantidad"];
            $IdAreaOrigen = $_GET["IdAreaOrigen"];
            $resp = $proceso->ObtenerLotesMedicamento($IdMedicina, $Cantidad, $IdAreaOrigen, $_SESSION["IdEstablecimiento"], $IdModalidad);
            $combo = "<select id='IdLote' name='IdLote'>";
            $combo.="<option value='0'>[Seleccione Lote...]</option>";
            $ExistenciaTotal = 0;
            while ($row = mysql_fetch_array($resp)) {
                $fecha = explode('-', $row[3]);
                $fecha = $fecha[2] . "-" . $fecha[1] . "-" . $fecha[0];

                $CantidadReal = $row[0];
                $ExistenciaTotal+=$row[0];

                $UnidadesContenidas = $proceso->UnidadesContenidas($IdMedicina);
                $Divisor = 1;

                if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($IdMedicina, $_SESSION["IdEstablecimiento"], $IdModalidad))) {
                    $Divisor = $respDivisor[0];

                    if ($CantidadReal < 1) {
                        //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                        $TransformaEntero = number_format($CantidadReal * $Divisor, 0, '.', ',');
                        $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                    } else {
                        //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
                        $CantidadReal = number_format($CantidadReal, 3, '.', ',');
                        $CantidadBase = explode('.', $CantidadReal);

                        $Entero = $CantidadBase[0]; //Faccion ENTERA
                        if (!isset($CantidadBase[1])) {
                            $Decimal = 0;
                        } else {
                            $Decimal = $CantidadBase[1];
                        }

                        if ($Decimal == 0) {
                            $Decimal = "";
                            $Quebrado = "";
                        } else {

                            $Quebrado = number_format(($Decimal / 1000) * $Divisor, 0, '.', ',');
                            $Quebrado = '[' . $Quebrado . '/' . $Divisor . ']';
                        }


                        $CantidadTransformada = $Entero . ' ' . $Quebrado;
                    }
                    $CantidadIntro = $CantidadTransformada;
                } else {
                    $CantidadIntro = $CantidadReal;
                    $CantidadIntro = number_format($CantidadIntro, 3, '.', ',');
                }

                $ExistenciaTotal = $ExistenciaTotal * $Divisor;

                $combo.="<option value='" . $row[1] . "'>" . $CantidadIntro . " - " . $row[2] . " -> " . $fecha . "</option>";
            }//while
            $combo.="</select><input type='hidden' id='ExistenciaTotal' value='" . $ExistenciaTotal . "'>
			<input type='hidden' id='Divisor' value='" . $Divisor . "'>
			<input type='hidden' id='UnidadesContenidas' value='" . $UnidadesContenidas . "'>";
            echo $combo;

            break;

        case 5:
            /* LIBRE */

            break;

        case 6:
            /* CAMBIO DE ESTADO DE LAS TRANSFERENCIAS */
            $resp = $proceso->ObtenerCantidadMedicina($IdPersonal);
            while ($row = mysql_fetch_array($resp)) {
                $IdMedicina = $row["IdMedicina"];
                $IdArea = $row["IdArea"];
                /* PARES DE INFORMACION */
                $Cantidad = $row["Cantidad1"];
                $Lote = $row["IdLote"];
                $Cantidad2 = $row["Cantidad2"];
                $Lote2 = $row["IdLote2"];
                /*                 * ******************* */
                if ($Lote != 0) {
                    queries::MedicinaExistencias($IdMedicina, $Cantidad, "SI", $IdArea, 
                                                 $Lote, $IdEstablecimiento, $IdModalidad);
                }
                if ($Lote2 != 0) {
                    queries::MedicinaExistencias($IdMedicina, $Cantidad2, "SI", $IdArea, 
                                                 $Lote2, $IdEstablecimiento, $IdModalidad);
                }
            }//fin de while resp
            $proceso->FinalizaTransferencia($IdPersonal);
            break;

        default:
            /* LIBRE */

            break;
    }//Fin de switch
    conexion::desconectar();
}//fin de sesion
?>