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
    $TipoFarmacia = $_SESSION["TipoFarmacia"];
    
    $IdModalidad = $_SESSION["IdModalidad"];

    switch ($Bandera) {

        case 1:
            /* OBTENCION DE DATOS PARA LA INTRODUCCION DE TRANSFERENCIAS DE MEDICAMENTOS */
            $Cantidad = $_GET["Cantidad"];
            $IdMedicina = $_GET["IdMedicina"];
            $IdArea = $_GET["IdArea"];
            $Acta = $_GET["Acta"];
            $Justificacion = $_GET["Justificacion"];
            $FechaTransferencia = $_GET["Fecha"];
            $Lote = $_GET["Lote"];
            $Precio = $_GET["Precio"];
            $FechaVencimiento = $_GET["FechaVencimiento"];

            if ($_GET["Divisor"] == NULL or $_GET["Divisor"] == '') {
                $Divisor = 1;
            } else {
                $Divisor = $_GET["Divisor"];
            }
            $UnidadesContenidas = $_GET["UnidadesContenidas"];


            //CONVERSION DE EXISTENCIAS SEGUN UNIDAD MEDIDA
            //ESPECIAL CUIDO CON LOS RETROVIRALES
            $Cantidad = ($Cantidad * $UnidadesContenidas) / $Divisor;

            /* INTRODUCCION DE DATOS DE LA TRANSFERENCIA */
            //echo $Cantidad.','.$IdMedicina.','.$IdArea.','.$Acta.','.$Justificacion.','.$FechaTransferencia.','.$IdPersonal.','.$Lote.','.$Divisor.','.$UnidadesContenidas.','.$Precio.','.$TipoFarmacia.','.$FechaVencimiento;
            $proceso->IntroducirAjuste($Cantidad, $IdMedicina, $IdArea, $Acta, $Justificacion, $FechaTransferencia, $IdPersonal, $Lote, $Divisor, $UnidadesContenidas, $Precio, $TipoFarmacia, $FechaVencimiento, $_SESSION["IdEstablecimiento"], $IdModalidad);


            break;

        case 2:
            /* MUESTRA LAS TRANSFERENCIAS INTRODUCIDAD */
            $Fecha = $_GET["Fecha"];
            $IdArea = $_GET["IdArea"];
            $resp = $proceso->ObtenerAjustes($IdPersonal, $Fecha, $_SESSION["IdEstablecimiento"], $IdModalidad);
            /* TABLA DE TRANSFERENCIAS */
            if ($row = mysql_fetch_array($resp)) {
                $tabla = '<table width="1018" border="1">
		<tr><td colspan="8" align="center"><strong>AJUSTE(S) REALIZADO(S)</strong></td></tr>
		<tr class="FONDO">
                <td width="159" align="center"><strong>Acta Numero</strong></td>
		<td width="116" align="center"><strong>Cantidad</strong></td>
		<td width="189" align="center"><strong>Medicamento</strong></td>
		<td width="189" align="center"><strong>Unidad de Medida</strong></td>
		<td width="131" align="center"><strong>Lote</strong></td>
		<td width="114" align="center"><strong>Area</strong></td>
		<td width="200" align="center"><strong>Justificacion</strong></td>
		<td width="74" align="center"><strong>Cancelar</strong></td>
		</tr>';
                $resp2 = $proceso->ObtenerAjustes($IdPersonal, $Fecha, $_SESSION["IdEstablecimiento"], $IdModalidad);
                while ($row = mysql_fetch_array($resp2)) {
                    /* OBTENCION DE DETALLE DE TRANSFERENCIA POR LOTE */
                    $resp = $proceso->ObtenerDetalleLote($row["IdAjuste"]);
                    $Cantidad = $resp["Cantidad"];
                    $IdLote = $resp["IdLote"];
                    $Lote = $resp["Lote"];
                    $Acta = $row["ActaNumero"];
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


                    $tabla = $tabla . '<tr class="FONDO"><td align="center">' . $Acta . '</td><td align="center">' . $CantidadIntro . '</td><td align="center">' . htmlentities($row["Nombre"]) . ', ' . htmlentities($row["Concentracion"]) . ' - ' . htmlentities($row["Presentacion"]) . '</td><td align="center">' . htmlentities($row["Descripcion"]) . '</td><td align="center">' . $DetalleLotes . '</td><td align="center">' . $row["Area"] . '</td><td>' . htmlentities($row["Justificacion"]) . '</td><td align="center"><input type="button" id="borrar" name="borrar" value="Eliminar" onclick="javascript:BorrarAjustes(' . $row["IdAjuste"] . ')"></td></tr>';
                }//while resp
                $tabla = $tabla . '</table>';
            } else {
                $tabla = "";
            }
            echo $tabla;
            break;

        case 3:
            /* ELIMINACION DE AJUSTES */
            $IdAjuste = $_GET["IdAjuste"];
            $resp = $proceso->EliminarAjustes($IdAjuste, $TipoFarmacia, $_SESSION["IdEstablecimiento"], $IdModalidad);
            echo $resp;
            break;

        case 4:
            /* GENERACION DEL LISTADO DE LOTES HABILITADOS PARA LA TRANSFERENCIA */
            $IdMedicina = $_GET["IdMedicina"];
            $Cantidad = $_GET["Cantidad"];
            $IdArea = $_GET["IdArea"];
            $resp = $proceso->ObtenerLotesMedicamento($IdMedicina, $Cantidad, $IdArea, $_SESSION["IdEstablecimiento"], $IdModalidad);
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
                        $CantidadReal = number_format($CantidadReal, 2, '.', ',');
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

                            $Quebrado = number_format(($Decimal / 100) * $Divisor, 0, '.', ',');
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
            /* CAMBIO DE ESTADO DE LOS AJUSTES */
            $proceso->FinalizaAjustes($IdPersonal);
            break;

        default:
            /* LIBRE */

            break;
    }//Fin de switch
    conexion::desconectar();
}//fin de sesion
?>