<?php

session_start();
if (isset($_SESSION["IdPersonal"])) {


    $IdPersonal = $_SESSION["IdPersonal"];
    if (isset($_GET["IdArea"])) {
        $IdArea = $_GET["IdArea"];
    }
    require('RecetasProcesoClase.php');
    conexion::conectar();
    $proceso = new RecetasProceso;
    $Bandera = $_GET["Bandera"];

    $IdModalidad = $_SESSION["IdModalidad"];
    $IdEstablecimiento = $_SESSION["IdEstablecimiento"];

    switch ($Bandera) {

        case 1:
            $IdReceta = $_GET["IdReceta"];
            /* DESPLEGAR DATOS DE RECETA */
            $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta, $_SESSION["IdEstablecimiento"], $IdModalidad);

            if (!$test = mysql_fetch_array($resp)) {
                echo "NO~";
            } else {

                if ($test["IdFarmacia"] == 4) {
                    echo "NO~";
                } else {

                    $IdReceta = $test["IdReceta"];
                    $Cierre = $proceso->Cierre($test["FechaConsulta"], $_SESSION["IdEstablecimiento"], $IdModalidad);
                    $CierreMes = $proceso->CierreMes($test["FechaConsulta"], $_SESSION["IdEstablecimiento"], $IdModalidad);
                    $respCierre = mysql_fetch_array($Cierre);
                    $respCierreMes = mysql_fetch_array($CierreMes);
                    if (($respCierre[0] != NULL and $respCierre[0] != '') || ($respCierreMes[0] != NULL and $respCierreMes[0] != '')) {

                        if ($respCierre[0] != NULL and $respCierre[0] != '') {
                            $c = $respCierre[0];
                        } else {
                            $c = $respCierreMes[0];
                        }
                        echo "NO2~" . $c;
                    } else {


                        $Fecha = $test["FechaEntrega"];
                        $IdArea = $test["IdArea"];
                        $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta, $_SESSION["IdEstablecimiento"], $IdModalidad);
                        $proceso->CambiarEstado($IdReceta, $_SESSION["IdEstablecimiento"], $IdModalidad);
                        $tabla = '<table width="744">
			<tr><td colspan="5" align="center"><strong>DETALLE DE RECETA</strong></td></tr>
			<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
			<td width="303" align="center"><strong>Medicina</strong></td>
                        <td width="303" align="center"><strong>LOTE(S)</strong></td>
			<td width="275" align="center"><strong>Dosis</strong></td>
			<td width="275" align="center"><strong>Insatisfecha</strong></td>
			<td width="275" align="center"><strong>Eliminar</strong></td>
			</tr>';
                        while ($row = mysql_fetch_array($resp)) {
                            $IdHistorialClinico = $row["IdHistorialClinico"];
                            if ($row["IdEstado"] == 'I') {
                                $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')" checked="checked">';
                            } else {
                                $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')">';
                            }

                            if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"], $_SESSION["IdEstablecimiento"], $IdModalidad))) {
                                $Divisor = $respDivisor[0];

                                if ($row["Cantidad"] < 1) {
                                    //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                                    $TransformaEntero = number_format($row["Cantidad"] * $Divisor, 0, '.', ',');
                                    $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                                } else {
                                    //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados

                                    $CantidadBase = explode('.', $row["Cantidad"]);

                                    $Entero = $CantidadBase[0]; //Faccion ENTERA

                                    $Decimal = $CantidadBase[1];
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
                                $CantidadIntro = number_format($row["Cantidad"],0,'.','');
                            }

                            $tabla.='<tr class="FONDO"><td align="center"><p style="color:red;">' . $CantidadIntro . '</p></td><td align="center">' . $row["Nombre"] . "<br>" . $row["Concentracion"] . ' - ' . htmlentities($row["FormaFarmaceutica"] . ' - ' . $row["Presentacion"]) . '</td><td align="center">'.$row["Lotes"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $check . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td>
			</tr>';
                        }//while resp
                        $tabla.="</table>";

                        /* 		Datos Grales		 */

                        $Datos = $proceso->ObtenerDatosGenerales($IdReceta, $_SESSION["IdEstablecimiento"], $IdModalidad);
                        $NombreMedico = "<a href='#' onClick='javascript:Correcciones(\"NombreMedico\");'>" . strtoupper($Datos["NombreEmpleado"]) . "</a>";

                        if ($Datos["Origen"] != NULL or $Datos["Origen"] != "") {
                            $Origen = $Datos["Origen"] . " -> ";
                        } else {
                            $Origen = "";
                        }
                        $Especialidad = "<a href='#' onClick='javascript:Correcciones(\"Especialidad\");'>" . $Origen . "" . strtoupper($Datos["NombreSubServicio"]) . "</a>";

                        $NombreArea = "<a href='#' onClick='javascript:Correcciones(\"NombreArea\");'>" . strtoupper($Datos["Area"]) . "</a><input type='hidden' id='IdAreaActual' value='" . $Datos["IdArea"] . "'>";

                        $NombreAreaOrigen = "<a href='#' onClick='javascript:Correcciones(\"NombreAreaOrigen\");'>" . strtoupper($Datos["AreaOrigen"]) . "</a><input type='hidden' id='IdAreaOrigenActual' value='" . $Datos["IdAreaOrigen"] . "'>";

                        $NombreFarmacia = "<strong>" . strtoupper($Datos["NombreFarmacia"]) . "</strong>";


                        /*                         * ********************************** */



                        echo $Fecha . "~" . $tabla . "~" . $NombreArea . "~" . $NombreMedico . "~" . $Especialidad . "~" . $IdHistorialClinico . "~" . $NombreFarmacia . "~" . $NombreAreaOrigen . "~" . $IdReceta;
                        /* FIN DESPLIEGUE DATOS */
                    }//validacion de cierre
                }
            }
            break;

        case 2:
            /* Introduccion de Medicina de una Receta */
            $IdMedicina = $_GET["IdMedicina"];
            $IdReceta = $_GET["IdReceta"];
            $Cantidad = $_GET["Cantidad"];
            $BanderaRepetitiva = $_GET["BanderaRepetitiva"];
            if ($BanderaRepetitiva == 1) {
                $NumeroMeses = $_GET["Repetitiva"];
            } else {
                $NumeroMeses = 0;
            }

            break;

        case 3:
            /* Recetas Repetitivas */
            $Cantidad = $_GET["Cantidad"];
            $IdReceta = $_GET["IdReceta"];
            $IdMedicina = $_GET["IdMedicina"];
            $Dosis = $_GET["Dosis"];
            $NumeroRepetitiva = $_GET["NumeroRepetitiva"];
            $IdHistorialClinico = $_GET["IdHistorialClinico"];
            $IdMedico = $_GET["IdMedico"];


            $proceso->IntroducirMedicinaPorReceta($IdReceta, $IdMedicina, $Cantidad, $Dosis);
            $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta, $_SESSION["IdEstablecimiento"]);
            $tabla = '<table width="744">
		<tr><td colspan="4" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
            while ($row = mysql_fetch_array($resp)) {
                $tabla = $tabla . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Cantidad"] . '</a></td><td align="center">' . $row["Nombre"] . ', ' . $row["Concentracion"] . '</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
            }//while resp
            $tabla = $tabla . '</table>';


//REPETITIVAS
            for ($i = 1; $i <= $NumeroRepetitiva; $i++) {
                $Fecha = $proceso->ObtenerFecha($i); //Aumento de la fecha

                $IdReceta = $proceso->ObtenerIdRecetaRepetitiva($IdHistorialClinico, $Fecha);
                if ($IdReceta == NULL) {
                    $proceso->IntroducirRecetaNuevaRepetitiva($IdHistorialClinico, $IdMedico, $IdPersonal, $Fecha, $IdArea);
                    $IdReceta = $proceso->ObtenerIdRecetaRepetitiva($IdHistorialClinico, $Fecha);
                    $proceso->IntroducirMedicinaPorReceta($IdReceta, $IdMedicina, $Cantidad, $Dosis);
                } else {//ELSE IdReceta NULL
                    $proceso->IntroducirMedicinaPorReceta($IdReceta, $IdMedicina, $Cantidad, $Dosis);
                }//fin de else IdReceta NULL
            }//fin del for

            $resp = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
            $resp2 = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
            $tabla2 = '<table width="744">
		<tr><td colspan="4" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
            $row2 = mysql_fetch_array($resp2);
            while ($row = mysql_fetch_array($resp)) {
                $tabla2 = $tabla2 . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Cantidad"] . '</a></td><td align="center">' . $row["Nombre"] . ', ' . $row["Concentracion"] . '</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $row["Fecha"] . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
                $row2 = mysql_fetch_array($resp2);
                if ($row2["Fecha"] != $row["Fecha"]) {
                    $tabla2 = $tabla2 . '<tr><td colspan="4"><hr></td>
			</tr>';
                }
            }//while resp
            $tabla2 = $tabla2 . '</table>';

            echo $tabla . "<br>" . $tabla2;

            break;

        case 4:
            /* ELIMINAR RECETA */
            $IdHistorialClinico = $_GET["IdHistorialClinico"];
            $IdReceta = $_GET["IdReceta"];
            $proceso->EliminarReceta($IdHistorialClinico, $IdPersonal, $IdReceta);
            break;

        case 5:
            /* Introduccion de medicina de la Receta */
            $Cantidad = $_GET["Cantidad"];
            $IdReceta = $_GET["IdReceta"];
            $IdMedicina = $_GET["IdMedicina"];
            $Dosis = $_GET["Dosis"];
            $Satisfecha = $_GET["Satisfecha"];
            $Fecha = $_GET["Fecha"];
            $IdArea = $_GET["IdArea"];

            if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($_GET["IdMedicina"],$IdEstablecimiento,$IdModalidad))) {
                $Cantidad = $Cantidad / $respDivisor[0];
            }


            $IdMedicinaRecetada = $proceso->IntroducirMedicinaPorReceta($IdReceta, $IdMedicina, $Cantidad, 
                                                                        $Dosis, $Satisfecha, $Fecha,$IdEstablecimiento,$IdModalidad);

            $proceso->ActualizarInventario($IdMedicina, $IdMedicinaRecetada, $Cantidad, $IdArea, $Fecha,
                                           $IdEstablecimiento,$IdModalidad);


            /* DESPLEGAR DATOS DE RECETA */
            $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta,$IdEstablecimiento,$IdModalidad);

            $tabla = '<table width="744">
		<tr><td colspan="5" align="center"><strong>DETALLE DE RECETA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
                <td width="303" align="center"><strong>LOTE(S)</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
            while ($row = mysql_fetch_array($resp)) {
                if ($row["IdEstado"] == 'I') {
                    $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')" checked="checked">';
                } else {
                    $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')">';
                }


                if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"],$IdEstablecimiento,$IdModalidad))) {
                    $Divisor = $respDivisor[0];

                    if ($row["Cantidad"] < 1) {
                        //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                        $TransformaEntero = number_format($row["Cantidad"] * $Divisor, 0, '.', ',');
                        $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                    } else {
                        //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados

                        $CantidadBase = explode('.', $row["Cantidad"]);

                        $Entero = $CantidadBase[0]; //Faccion ENTERA

                        $Decimal = $CantidadBase[1];
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
                    $CantidadIntro = number_format($row["Cantidad"],0,'.','');
                }


                $tabla = $tabla . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $CantidadIntro . '</a></td><td align="center">' . $row["Nombre"] . "<br>" . $row["Concentracion"] . ' - ' . htmlentities($row["FormaFarmaceutica"] . ' - ' . $row["Presentacion"]) . '</td><td align="center">'.$row["Lotes"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $check . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td>
		</tr>';
            }//while resp
            $tabla = $tabla . "</table>";

            echo $tabla;
            /* FIN DESPLIEGUE DATOS */
            break;

        case 6:

            /* CAMBIO DE ESTADO DE LA RECETA INTRODUCIDA */
            $IdReceta = $_GET["IdReceta"];
            $proceso->RecetaLista($IdReceta,$IdEstablecimiento,$IdModalidad);

            break;

        case 7:
            /* MOSTRAR RECETAS */
            $IdMedicinaRecetada = $_GET["IdMedicinaRecetada"];
            $IdReceta = $_GET["IdReceta"];
            $IdArea = $_GET["IdArea"];

            /*             * *	ELIMINACION DE MEDICAMENTO		** */
            $proceso->AumentarInventario($IdMedicinaRecetada, $IdArea,$IdEstablecimiento,$IdModalidad);

            $proceso->EliminarMedicinaRecetada($IdMedicinaRecetada,$IdEstablecimiento,$IdModalidad);

            /*             * *************************************** */
            $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta,$IdEstablecimiento,$IdModalidad);
            if ($tmp1 = mysql_fetch_array($resp)) {
                $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta,$IdEstablecimiento,$IdModalidad);
                $tabla = '<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
                <td width="303" align="center"><strong>LOTE(S)</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
                while ($row = mysql_fetch_array($resp)) {

                    if ($row["IdEstado"] == 'I') {
                        $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')" checked="checked">';
                    } else {
                        $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')">';
                    }

                    if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"],$IdEstablecimiento,$IdModalidad))) {
                        $Divisor = $respDivisor[0];

                        if ($row["Cantidad"] < 1) {
                            //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                            $TransformaEntero = number_format($row["Cantidad"] * $Divisor, 0, '.', ',');
                            $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                        } else {
                            //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados

                            $CantidadBase = explode('.', $row["Cantidad"]);

                            $Entero = $CantidadBase[0]; //Faccion ENTERA

                            $Decimal = $CantidadBase[1];
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
                        $CantidadIntro = $row["Cantidad"];
                    }


                    $tabla = $tabla . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $CantidadIntro . '</a></td><td align="center">' . $row["Nombre"] . ', ' . htmlentities($row["Concentracion"] . ' - ' . $row["FormaFarmaceutica"]) . ' - ' . $row["Presentacion"] . '</td><td align="center">'.$row["Lotes"].'</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $check . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
                }//while resp
                $tabla = $tabla . '</table>';
            } else {
                $tabla = ' ';
            }

            $tabla2 = ' ';

            echo $tabla . "<br>" . $tabla2;

            break;

        case 8:
            /* CAMBIO DE DOSIS */
            $IdMedicinaRecetada = $_GET["IdMedicinaRecetada"];
            $IdReceta = $_GET["IdReceta"];
            $IdHistorialClinico = $_GET["IdHistorialClinico"];
            $Dosis = $_GET["NuevaDosis"];

            $proceso->UpdateDosis($IdMedicinaRecetada, $Dosis);

            $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta);
            if ($tmp1 = mysql_fetch_array($resp)) {
                $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta);
                $tabla = '<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
                while ($row = mysql_fetch_array($resp)) {

                    if ($row["IdEstado"] == 'I') {
                        $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')" checked="checked">';
                    } else {
                        $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')">';
                    }

                    if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"]))) {
                        $Divisor = $respDivisor[0];

                        if ($row["Cantidad"] < 1) {
                            //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                            $TransformaEntero = number_format($row["Cantidad"] * $Divisor, 0, '.', ',');
                            $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                        } else {
                            //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados

                            $CantidadBase = explode('.', $row["Cantidad"]);

                            $Entero = $CantidadBase[0]; //Faccion ENTERA

                            $Decimal = $CantidadBase[1];
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
                        $CantidadIntro = $row["Cantidad"];
                    }


                    $tabla = $tabla . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $CantidadIntro . '</a></td><td align="center">' . $row["Nombre"] . ', ' . htmlentities($row["Concentracion"] . ' - ' . $row["Presentacion"]) . '</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $check . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
                }//while resp
                $tabla = $tabla . '</table>';
            } else {
                $tabla = ' ';
            }

//REPETITIVAS
            $resp = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
            if ($tmp2 = mysql_fetch_array($resp)) {
                $resp = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
                $resp2 = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
                $tabla2 = '<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
                $row2 = mysql_fetch_array($resp2);
                while ($row = mysql_fetch_array($resp)) {
                    $tabla2 = $tabla2 . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Cantidad"] . '</a></td><td align="center">' . $row["Nombre"] . ', ' . $row["Concentracion"] . '</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $row["Fecha"] . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
                    $row2 = mysql_fetch_array($resp2);
                    if ($row2["Fecha"] != $row["Fecha"]) {
                        $tabla2 = $tabla2 . '<tr><td colspan="4"><hr></td>
			</tr>';
                    }
                }//while resp
                $tabla2 = $tabla2 . '</table>';
            } else {
                $tabla2 = ' ';
            }
            echo $tabla . "<br>" . $tabla2;
            break;

        case 9:
            $IdMedicinaRecetada = $_GET["IdMedicinaRecetada"];
            $IdReceta = $_GET["IdReceta"];
            $IdHistorialClinico = $_GET["IdHistorialClinico"];
            $Cantidad = $_GET["Cantidad"];

            $proceso->UpdateCantidad($IdMedicinaRecetada, $Cantidad);

            $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta);
            if ($tmp1 = mysql_fetch_array($resp)) {
                $resp = $proceso->ObtenerMedicinaIntroducida($IdReceta);
                $tabla = '<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETA DEL DIA</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Insatisfecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
                while ($row = mysql_fetch_array($resp)) {
                    if ($row["IdEstado"] == 'I') {
                        $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')" checked="checked">';
                    } else {
                        $check = '<input id="Insa' . $row["IdMedicinaRecetada"] . '" name="Insa' . $row["IdMedicinaRecetada"] . '" type="checkbox" value="I" onclick="javascript:CambioEstado(' . $row["IdMedicinaRecetada"] . ',' . $row["IdMedicina"] . ')">';
                    }

                    if ($respDivisor = mysql_fetch_array($proceso->ValorDivisor($row["IdMedicina"]))) {
                        $Divisor = $respDivisor[0];

                        if ($row["Cantidad"] < 1) {
                            //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                            $TransformaEntero = number_format($row["Cantidad"] * $Divisor, 0, '.', ',');
                            $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                        } else {
                            //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados

                            $CantidadBase = explode('.', $row["Cantidad"]);

                            $Entero = $CantidadBase[0]; //Faccion ENTERA

                            $Decimal = $CantidadBase[1];
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
                        $CantidadIntro = $row["Cantidad"];
                    }

                    $tabla = $tabla . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $CantidadIntro . '</a></td><td align="center">' . $row["Nombre"] . ', ' . htmlentities($row["Concentracion"] . ' - ' . $row["Presentacion"]) . '</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $check . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
                }//while resp
                $tabla = $tabla . '</table>';
            } else {
                $tabla = ' ';
            }

//REPETITIVAS
            $resp = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
            if ($tmp2 = mysql_fetch_array($resp)) {
                $resp = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
                $resp2 = $proceso->ObtenerRecetaRepetitiva($IdHistorialClinico, $IdPersonal);
                $tabla2 = '<table width="744">
		<tr><td colspan="5" align="center"><strong>RECETAS REPETITIVAS</strong></td></tr>
		<tr class="FONDO"><td width="150" align="center"><strong>Cantidad</strong></td>
		<td width="303" align="center"><strong>Medicina</strong></td>
		<td width="275" align="center"><strong>Dosis</strong></td>
		<td width="275" align="center"><strong>Fecha</strong></td>
		<td width="275" align="center"><strong>Eliminar</strong></td>
		</tr>';
                $row2 = mysql_fetch_array($resp2);
                while ($row = mysql_fetch_array($resp)) {
                    $tabla2 = $tabla2 . '<tr class="FONDO"><td align="center"><a style="color:red;" onclick="javascript:VentanaBusqueda4(\'ModificaCantidad.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Cantidad"] . '</a></td><td align="center">' . $row["Nombre"] . ', ' . $row["Concentracion"] . '</td><td align="center"><a style="color:blue;" onclick="javascript:VentanaBusqueda4(\'ModificaDosis.php?IdMedicinaRecetada=' . $row["IdMedicinaRecetada"] . '\')">' . $row["Dosis"] . '</a></td><td align="center">' . $row["Fecha"] . '</td><td align="center"><input type="button" id="BorrarMedicamento" name="BorrarMedicamento" value="Eliminar Medicamento" onclick="javascript:EliminaMedicina(' . $row["IdMedicinaRecetada"] . ')"></td></tr>';
                    $row2 = mysql_fetch_array($resp2);
                    if ($row2["Fecha"] != $row["Fecha"]) {
                        $tabla2 = $tabla2 . '<tr><td colspan="4"><hr></td>
			</tr>';
                    }
                }//while resp
                $tabla2 = $tabla2 . '</table>';
            } else {
                $tabla2 = ' ';
            }
            echo $tabla . "<br>" . $tabla2;

            break;

        case 10:
            $Estado = $_GET["Estado"];
            $IdMedicinaRecetada = $_GET["IdMedicinaRecetada"];
            $IdMedicina = $_GET["IdMedicina"];
            $proceso->UpdateMedicinaRecetada($IdMedicinaRecetada, $Estado, $IdMedicina);

            break;

        case 11:
            $IdMedico = $_GET["IdMedico"];
            $Codigo = $proceso->ObtenerCodigoFarmacia($IdMedico);
            echo $Codigo;
            break;
        case 12:
            $CodigoFarmacia = $_GET["CodigoFarmacia"];
            $respuesta = "/Medico no seleccionado";
            if ($CodigoFarmacia != '') {
                $resp = $proceso->ObtenerDatosMedico($CodigoFarmacia);
                $respuesta = $resp[0] . '/' . $resp[1];
            }
            echo $respuesta;
            break;

        case 13:
            /* MOSTRAR SUBESPECIALIDADES O SERVICIO ORIGEN DE RECETA */
            $Codigo = strtoupper($_GET["Codigo"]);
            $query = "select msse.IdSubServicio,NombreSubServicio, NombreServicio as Ubicacion
			from mnt_subservicio
                        inner join mnt_subservicioxestablecimiento msse
                        on msse.IdSubServicio=mnt_subservicio.IdSubServicio
                        inner join mnt_servicioxestablecimiento mse
                        on mse.IdServicioxEstablecimiento=msse.IdServicioxEstablecimiento
                        inner join mnt_servicio ms
                        on ms.IdServicio=mse.IdServicio
			
			where msse.CodigoFarmacia='$Codigo'
                            and msse.IdEstablecimiento=$IdEstablecimiento
                            and msse.IdModalidad=$IdModalidad
			";

            $resp = mysql_fetch_array(mysql_query($query));
            if ($resp["Ubicacion"] != NULL and $resp["Ubicacion"] != "") {
                $Ubicacion = $resp["Ubicacion"] . " -> ";
            } else {
                $Ubicacion = "";
            }
            $NombreSubEspecialidad = $Ubicacion . "" . $resp["NombreSubServicio"];
            if ($Codigo != '') {
                echo $resp["IdSubServicio"] . "/" . $NombreSubEspecialidad;
            } else {
                echo "/No hay seleccion";
            }
            break;

        case 14:
            /* 	ACTUALIZACIONES */

            if (isset($_GET["IdArea"])) {
                $IdArea = $_GET["IdArea"];
                $IdAreaOriginal = $_GET["IdAreaOriginal"];
                $IdHistorialClinico = $_GET["IdHistorialClinico"];
                $IdReceta = $_GET["IdReceta"];
                $Fecha = $_GET["Fecha"];

                //if($IdArea!=$IdAreaOriginal){
                $IdFarmacia = $proceso->ObtenerIdFarmacia($IdArea);
                if ($IdReceta == '' or $IdReceta == NULL) {
                    $salida = 'N';
                } else {
                    $salida = $proceso->ActualizarArea($IdArea, $IdReceta, $IdFarmacia, $Fecha, $IdEstablecimiento, $IdModalidad);
                }
                //}
            }//Actualizacion de Area

            if (isset($_GET["IdAreaOrigen"])) {
                $IdArea = $_GET["IdAreaOrigen"];
                $IdAreaOriginal = $_GET["IdAreaOriginal"];
                $IdReceta = $_GET["IdReceta"];

                //if($IdArea!=$IdAreaOriginal){
                if ($IdReceta == '' or $IdReceta == NULL) {
                    $salida = 'N';
                } else {
                    $salida = $proceso->ActualizarAreaOrigen($IdArea, $IdReceta,$IdEstablecimiento,$IdModalidad);
                }
                //}
            }//Actualizacion de Area


            if (isset($_GET["IdMedico"])) {

                $IdHistorialClinico = $_GET["IdHistorialClinico"];
                $IdMedico = $_GET["IdMedico"];

                if ($IdHistorialClinico == '' or $IdHistorialClinico == NULL) {
                    $salida = 'N';
                } else {
                    $salida = $proceso->ActualizarMedico($IdHistorialClinico, $IdMedico,$IdEstablecimiento,$IdModalidad);
                }
            }//Actualizacion de Medico

            if (isset($_GET["IdSubEspecialidad"])) {
                $IdHistorialClinico = $_GET["IdHistorialClinico"];
                $IdSubEspecialidad = $_GET["IdSubEspecialidad"];

                if ($IdHistorialClinico == '' or $IdHistorialClinico == NULL) {
                    $salida = 'N';
                } else {
                    $salida = $proceso->ActualizarEspecialidad($IdHistorialClinico, $IdSubEspecialidad,$IdEstablecimiento,$IdModalidad);
                }
            }//Actualizacoin de Especialidad

            echo $salida;
            break;
        case 15:
            $IdOrigenCambio = $_GET["IdOrigenCambio"];
            $Tools = "";
            switch ($IdOrigenCambio) {
                case 'NombreArea':
                    $IdReceta = $_GET["IdReceta"];
                    $IdAreaOriginal = $proceso->ObtenerAreaReceta($IdReceta, $IdEstablecimiento, $IdModalidad);
                    $Tools = "<table>
			<tr class='FONDO2'>
			<td>
			<select id='IdArea2' name='IdArea2' onChange='PegarIdArea(this.value);'>
			<option value='0'>[Seleccione ...]</option>";
                    $resp = $proceso->ObtenerArea($IdEstablecimiento, $IdModalidad);
                    while ($row = mysql_fetch_array($resp)) {
                        $Tools.="<option value='" . $row[0] . "'>" . $row[1] . " <strong><em>" . $row[2] . "</em></strong></option>";
                    }
                    $Tools.="</select><input type='hidden' id='IdAreaNormal' name='IdAreaNormal' value='" . $IdAreaOriginal . "'>
			</td>
	  		 <td>
	  		 <input type='button' id='Cambiar3' name='Cambiar3' value='Corregir' onClick='CorregirArea();'>
	  		 </td>
	 		  </tr>
	   		</table>";

                    break;

                case 'NombreAreaOrigen':
                    $IdReceta = $_GET["IdReceta"];
                    $IdAreaOriginal = $proceso->ObtenerAreaOrigenReceta($IdReceta);
                    $Tools = "<table>
			<tr class='FONDO2'>
			<td>
			<select id='IdAreaOrigen2' name='IdAreaOrigen2' onChange='PegarIdAreaOrigen(this.value);'>
			<option value='0'>[Seleccione ...]</option>";
                    $resp = $proceso->ObtenerArea($IdEstablecimiento, $IdModalidad);
                    while ($row = mysql_fetch_array($resp)) {
                        $Tools.="<option value='" . $row[0] . "'>" . $row[1] . " <strong><em>" . $row[2] . "</em></strong></option>";
                    }
                    $Tools.="</select><input type='hidden' id='IdAreaOrigenNormal' name='IdAreaOrigenNormal' value='" . $IdAreaOriginal . "'>
			</td>
	   		<td>
	   		<input type='button' id='CambiarOrigen3' name='CambiarOrigen3' value='Corregir' onClick='CorregirAreaOrigen();'>
	  		 </td>
	  		 </tr>
	  		 </table>";

                    break;

                case 'NombreMedico':
                    $Tools = '<table>
			<tr class="FONDO2">
			<td>
			<input id="CodigoFarmacia" name="CodigoFarmacia" type="text" maxlength="4" onBlur="javascript:ObtenerDatosMedico();" style="width:50px;" onKeyPress="return Saltos(event,this.id);"><input type="button" id="Buscador" name="Buscador" onClick="javascript:VentanaBusqueda2();" value="...">
		<!-- <input type="text" id="IdEspecialidad" name="IdEspecialidad"> -->
		<input type="hidden" id="IdMedico" name="IdMedico"><strong><div id="NombreMedico2"></div></strong>
	   </td>
	   <td>
	   <input type="button" id="Cambiar1" name="Cambiar1" value="Corregir" onClick="CorregirMedico();">
	   </td>
	   </tr>
	   </table>';


                    break;
                case 'Especialidad':
                    $Tools = '<table>
			<tr class="FONDO2">
			<td>
			<input id="CodigoSubEspecialidad" name="CodigoSubEspecialidad" type="text" maxlength="4" onBlur="javascript:CargarSubEspecialidad(this.value);" style="width:50px;" onKeyPress="return Saltos(event,this.id);"><input type="button" id="Buscador2" name="Buscador2" onClick="javascript:VentanaBusqueda();" value="...">
	   <input type="hidden" id="IdSubEspecialidad" name="IdSubEspecialidad" ><strong><div id="NombreSubEspecialidad"></div></strong>
	   </td>
	   <td>
	   <input type="button" id="Cambiar2" name="Cambiar2" value="Corregir" onClick="CorregirEspecialidad();">
	   </td>
	   </tr>
	   </table>';


                    break;
            }//switch

            echo $Tools;

            break;
        case 16:
            $IdReceta = $_GET["IdReceta"];
            $FechaNueva = $_GET["Fecha"];
            $IdHistorialClinico = $_GET["IdHistorialClinico"];
            echo $proceso->CambiarFecha($IdReceta, $FechaNueva, $IdHistorialClinico,$IdEstablecimiento,$IdModalidad);


            break;
        case 17:
            $IdMedicina = $_GET["IdMedicina"];
            $IdArea = $_GET["IdArea"];
            echo $proceso->ObtenerExistencia($IdMedicina, $IdArea);

            break;
    }//Fin de switch
    conexion::desconectar();
} else {
    echo "ERROR_SESSION";
}
?>