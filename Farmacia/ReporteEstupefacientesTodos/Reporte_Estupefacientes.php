<?php

session_start();
if (!isset($_SESSION["nivel"])) {
    echo "ERROR_SESSION";
} else {
    if ($_SESSION["Reportes"] != 1) {
        ?>
        <script language="javascript">
            window.location='../Principal/index.php?Permiso=1';
        </script>
        <?php

    } else {
        $tipoUsuario = $_SESSION["tipo_usuario"];
        $nombre = $_SESSION["nombre"];
        $nivel = $_SESSION["nivel"];
        $nick = $_SESSION["nick"];
        require('../Clases/class.php');
        include('Funciones.php');
        $query = new queries;
        conexion::conectar();

//html { font: 100%/2.5 Arial, Helvetica, sans-serif; }
        $FechaInicio = explode('-', $_REQUEST["fechaInicio"]);
        $FechaFin = explode('-', $_REQUEST["fechaFin"]);
        $FechaInicio2 = $FechaInicio[2] . '-' . $FechaInicio[1] . '-' . $FechaInicio[0];
        $FechaFin2 = $FechaFin[2] . '-' . $FechaFin[1] . '-' . $FechaFin[0];
        $FechaInicio = $_REQUEST["fechaInicio"];
        $FechaFin = $_REQUEST["fechaFin"];
        $IdEmpleado = $_GET["IdEmpleado"];

        $IdFarmacia = $_GET["IdFarmacia"];

        if ($IdFarmacia != '0') {
            $t = mysql_fetch_array(mysql_query("select Farmacia from mnt_farmacia where IdFarmacia=" . $IdFarmacia));
            $Farmacia = "Farmacia: <strong>" . $t[0] . "</strong><br>";
        } else {
            $Farmacia = "Farmacia: <strong>GENERAL</strong> <br>";
        }


        if ($IdEmpleado != '0') {
            $t = mysql_fetch_array(mysql_query("select NombreEmpleado from mnt_empleados where IdEmpleado='$IdEmpleado'"));
            $NombreEmpleado = "Medico: <strong>" . $t[0] . "</strong><br>";
        } else {
            $NombreEmpleado = "Medico: <strong>GENERAL</strong> <br>";
        }

        /*         * ********************INFORMACION DE REPORTE********************************* */
        if (isset($_GET["IdSubServicio"])) {
            $IdSubEspecialidad = $_GET["IdSubServicio"];
        } else {
            $IdSubEspecialidad = 0;
        }
        if (isset($_GET["IdTerapeutico"])) {
            $IdGrupoTerapeutico = $_GET["IdTerapeutico"];
        } else {
            $IdGrupoTerapeutico = 0;
        }
        if (isset($_GET["IdMedicina"])) {
            $IdMedicina = $_GET["IdMedicina"];
        } else {
            $IdMedicina = 0;
        }
        /*         * *************************************************************************** */

        if ($IdSubEspecialidad != 0) {
            $t = mysql_fetch_array(mysql_query("select ss.IdSubServicio,NombreServicio,NombreSubServicio 
				from mnt_subservicio ss
				inner join mnt_subservicioxestablecimiento sse
				on sse.IdSubServicio=ss.IdSubServicio
				inner join mnt_servicio ms
				on ms.IdServicio=ss.IdServicio
				where sse.IdEstablecimiento = " . $_SESSION["IdEstablecimiento"] . "
				and ss.IdSubServicio='$IdSubEspecialidad'"));

            $Especialidad_Servicio = "Especialidad / Servicio: <strong>[ " . $t[1] . " ] " . $t[2] . "</strong><br>";
        } else {
            $Especialidad_Servicio = "Especialidad / Servicio: <strong>GENERAL</strong><br>";
        }

//     GENERACION DE EXCEL
        $NombreExcel = 'Estupefacientes_' . $nick . '_' . date('d_m_Y__h_i_s A');
        $nombrearchivo = "../ReportesExcel/" . $NombreExcel . ".xls";
        $punteroarchivo = fopen($nombrearchivo, "wb") or die("El archivo de reporte no pudo crearse");
//***********************
//LIBREOFFICE
        $nombrearchivo2 = "../ReportesExcel/" . $NombreExcel . ".ods";
        $punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************


        $reporte = '<table width="968" border="1">';
        $Total = 0;
        $TotalRecetasGlobal = 0;
        $TotalSatisGlobal = 0;
        $TotalInsatGlobal = 0;

//*************************************
//******************************* QUERIES Y RECORRIDOS
        $respServicios = Servicios($IdSubEspecialidad, $IdGrupoTerapeutico, $IdMedicina, $FechaInicio, $FechaFin, $IdEmpleado, $IdFarmacia);
        if ($rowServicios = mysql_fetch_array($respServicios)) {
            do {
                $IdSubEspecialidad = $rowServicios["IdSubServicio"];
                $NombreSubEspecialidad = $rowServicios["NombreSubServicio"];
                $Ubicacion = $rowServicios["Ubicacion"];


                $SubTotalServicio = 0;
                $TotalRecetas = 0;
                $TotalSatis = 0;
                $TotalInsat = 0;
                $TotalConsumo = 0;

                $reporte.='
	<tr class="MYTABLE">
		<td colspan="11" align="center">' . $_SESSION["NombreEstablecimiento"] . '<br>
		<strong>CONSUMO DE MEDICAMENTOS ESTUPEFACIENTES</strong> <br>
                                '.$Farmacia.'
				' . $NombreEmpleado . '
				' . $Especialidad_Servicio . '
			PERIODO DEL: ' . $FechaInicio2 . ' AL ' . $FechaFin2 . ' .- </td></tr>
	<tr class="MYTABLE"><td align="right" colspan="11">Fecha de Emisi&oacute;n: ' . $DateNow = date("d-m-Y") . '
	</td>
	</tr>
			
			
	<tr class="FONDO2">
	<td colspan="11" align="center" style="background:#666666;">
		<strong><h2>[' . $Ubicacion . '] ' . $NombreSubEspecialidad . '</h2></strong>
	</td>
	</tr>';


                $nombreTera = NombreTera($IdGrupoTerapeutico, $IdSubEspecialidad, $FechaInicio, $FechaFin, $IdEmpleado,$IdFarmacia);
                if ($grupos = mysql_fetch_array($nombreTera)) {
                    do {
                        $NombreTerapeutico = $grupos["GrupoTerapeutico"];
                        $IdTerapeutico = $grupos["IdTerapeutico"];
                        $SubTotal = 0;
                        $SubTotalRecetas = 0;
                        $SubTotalSatis = 0;
                        $SubTotalInsat = 0;
                        $SubTotalConsu = 0;

                        $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
			  <td colspan="11" align="center">
		&nbsp;<strong>' . $NombreTerapeutico . '</strong></td>
			</tr>
			<tr class="FONDO2">
			  <th width="37" scope="col">Codigo</th>
			  <th width="182" scope="col">Medicamento</th>
			  <th width="61" scope="col">Concen.</th>
			  <th width="54" scope="col">Prese.</th>
			  <th width="54" scope="col">Recetas</th>
			  <th width="50" scope="col">Satis.</th>
			  <th width="70" scope="col">No Satis.</th>
			  <th width="63">Unidad de Medida</th>
			  <th width="78" scope="col">Consumo</th>
			  <th width="135" scope="col">Precio</th>
			  <th width="136" scope="col">Monto[$]</th>
			</tr>';

                        $resp1 = QueryExterna($IdTerapeutico, $IdMedicina, $IdSubEspecialidad, $FechaInicio, $FechaFin, $IdEmpleado,$IdFarmacia);
                        while ($row = mysql_fetch_array($resp1)) {
                            $GrupoTerapeutico = $IdTerapeutico;
                            $Medicina = $row["IdMedicina"];
                            $codigoMedicina = $row["Codigo"];
                            $NombreMedicina = $row["Nombre"];
                            $concentracion = $row["Concentracion"];
                            $presentacion = $row["FormaFarmaceutica"] . ' - ' . $row["Presentacion"];

                            $Nrecetas = 0; //conteo de recetas
                            $consumo = 0;


                            $respuesta = ObtenerReporteGrupoTerapeutico($IdTerapeutico, $Medicina, $FechaInicio, $FechaFin, $IdSubEspecialidad);

                            if ($row2 = mysql_fetch_array($respuesta)) { /* verificacion de datos */
                                $precioActual = 0;

                                //$IdReceta=$row2["IdReceta"];
                                $IdReceta = 0;
                                $Divisor = $row2["Divisor"]; //Divisor de conversion
                                $UnidadMedida = $row2["Descripcion"]; //Tipo de unidad de Medida
                                $satisfechas = 0;
                                $insatisfechas = 0;



                                /* Obtencion de recetas satifechas e insatisfechas globales parametros ...,0,0) */
                                $sat = ObtenerRecetasSatisfechas($IdReceta, $Medicina, $FechaInicio, $FechaFin, $IdSubEspecialidad, 0, $IdEmpleado,$IdFarmacia);
                                $insat = ObtenerRecetasInsatisfechas($IdReceta, $Medicina, $FechaInicio, $FechaFin, $IdSubEspecialidad, 0, $IdEmpleado,$IdFarmacia);
                                $Nrecetas = $sat + $insat;
                                //***********
                                //***********
                                /*
                                  $Cantidad_Total=SumatoriaMedicamento($Medicina,$IdSubEspecialidad,$FechaInicio,$FechaFin);
                                  $CantidadReal=$Cantidad_Total/$Divisor;
                                  $Ano=date('Y');
                                  $Precio=ObtenerPrecioMedicina($Medicina,$Ano);
                                  $Monto=$CantidadReal*$Precio;
                                 */

                                $respSum = SumatoriaMedicamento($Medicina, $IdSubEspecialidad, $FechaInicio, $FechaFin, $IdEmpleado,$IdFarmacia);

                                if ($rowSum = mysql_fetch_array($respSum)) {
                                    $CantidadReal = 0;
                                    $Monto = 0;
                                    $Lotes = "";
                                    do {
                                        $CantidadReal+=$rowSum["TotalMedicamento"];

                                        if ($respDivisor = mysql_fetch_array(ValorDivisor($Medicina))) {
                                            $Divisor = $respDivisor[0];

                                            if ($CantidadReal < 1) {
                                                //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                                                $TransformaEntero = number_format($CantidadReal * $Divisor, 0, '.', ',');
                                                $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                                            } else {
                                                //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
                                                //$CantidadReal=number_format($CantidadReal,2,'.',',');	
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
                                            //$CantidadIntro=number_format($CantidadIntro,2,'.',',');
                                        }

                                        $Monto+=$rowSum["Costo"];
                                        $Lotes.=" Lote: " . $rowSum["Lote"] . "<br> $" . $rowSum["PrecioLote"] . "<br><br>";
                                    } while ($rowSum = mysql_fetch_array($respSum));
                                }


                                $PrecioNuevo = $Lotes;
                                $MontoNuevo = number_format($Monto, 3, '.', ',');

                                $SubTotal+=$Monto;

                                $SubTotalRecetas+=$Nrecetas;
                                $SubTotalSatis+=$sat;
                                $SubTotalInsat+=$insat;
                                $SubTotalConsu+=$CantidadReal;

                                if ($respDivisor = mysql_fetch_array(ValorDivisor($Medicina))) {
                                    $Divisor = $respDivisor[0];

                                    $CantidadReal=number_format($CantidadReal,3,'.','');
                                    
                                    if ($CantidadReal < 1) {
                                        //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                                        $TransformaEntero = number_format($CantidadReal * $Divisor, 0, '.', ',');
                                        $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                                    } else {
                                        //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
                                        //$CantidadReal=number_format($CantidadReal,2,'.',',');	
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
                                    //$CantidadIntro=number_format($CantidadIntro,2,'.',',');
                                }

                                $reporte.='<tr class="FONDO2">
			  <td style="vertical-align:middle">&nbsp;"' . $codigoMedicina . '"</td>
			  <td align="left" style="vertical-align:middle">&nbsp;' . $NombreMedicina . '</td>
			  <td align="center" style="vertical-align:middle">&nbsp;' . $concentracion . '</td>
			  <td style="vertical-align:middle">&nbsp;' . $presentacion . '</td>
			  <td align="center" style="vertical-align:middle">' . $Nrecetas . '</td>
			  <td align="center" style="vertical-align:middle">' . $sat . '</td>
			  <td align="center" style="vertical-align:middle">' . $insat . '</td>
			  <td align="center" style="vertical-align:middle">' . $UnidadMedida . '</td>
			  <td align="right" style="vertical-align:middle">' . $CantidadIntro . '</td>
			  <td align="right" style="vertical-align:middle">' . $PrecioNuevo . '</td>
			  <td align="right" style="vertical-align:middle">' . $MontoNuevo . '</td>
			</tr>';
                            }//if row2
                        }//while externo

                        $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><strong><em>SubTotal:</em></strong></td>
      <td align="right">' . $SubTotalRecetas . '</td>
      <td align="right">' . $SubTotalSatis . '</td>
      <td align="right">' . $SubTotalInsat . '</td>
      <td>&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right">&nbsp;</td>
      <td align="right"><strong>' . number_format($SubTotal, 3, '.', ',') . '</strong></td>
    </tr>';

                        $TotalRecetas+=$SubTotalRecetas;
                        $TotalSatis+=$SubTotalSatis;
                        $TotalInsat+=$SubTotalInsat;
                        $TotalConsumo+=$SubTotalConsu;
                        $SubTotalServicio+=$SubTotal;
                    } while ($grupos = mysql_fetch_array($nombreTera)); //while de nombreTera

                    $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><em><strong> Total de ' . $NombreSubEspecialidad . ':</strong></em></td>
	  <td align="right">' . $TotalRecetas . '</td>
	  <td align="right">' . $TotalSatis . '</td>
	  <td align="right">' . $TotalInsat . '</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
      <td align="right"><strong>' . number_format($SubTotalServicio, 3, '.', ',') . '</strong></td>
    </tr>';

                    $Total+=$SubTotalServicio;
                    $TotalRecetasGlobal+=$TotalRecetas;
                    $TotalSatisGlobal+=$TotalSatis;
                    $TotalInsatGlobal+=$TotalInsat;
                }/* else{
                  //SI NO HAY MEDICINA CONSUMIDA POR LA ESPECIALIDAD
                  $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
                  <td colspan="11" align="center">
                  &nbsp;<strong>No Hay consumo de Grupo Terapeutico</strong></td>
                  </tr>
                  <tr class="FONDO2">
                  <th width="37" scope="col">Codigo</th>
                  <th width="182" scope="col">Medicamento</th>
                  <th width="61" scope="col">Concen.</th>
                  <th width="54" scope="col">Prese.</th>
                  <th width="54" scope="col">Recetas</th>
                  <th width="50" scope="col">Satis.</th>
                  <th width="70" scope="col">No Satis.</th>
                  <th width="63">Unidad de Medida</th>
                  <th width="78" scope="col">Consumo</th>
                  <th width="135" scope="col">Precio[$]</th>
                  <th width="136" scope="col">Monto[$]</th>
                  </tr>
                  <tr class="FONDO2">
                  <td style="vertical-align:middle">&nbsp;-----</td>
                  <td align="left" style="vertical-align:middle">-----</td>
                  <td align="center" style="vertical-align:middle">-----</td>
                  <td align="center" style="vertical-align:middle">-----</td>
                  <td align="center" style="vertical-align:middle">-----</td>
                  <td align="center" style="vertical-align:middle">-----</td>
                  <td align="center" style="vertical-align:middle">-----</td>
                  <td align="center" style="vertical-align:middle">-----</td>
                  <td align="right" style="vertical-align:middle">0.00</td>
                  <td align="right" style="vertical-align:middle">0.00</td>
                  <td align="right" style="vertical-align:middle">0.00</td>
                  </tr>';
                  } */
            } while ($rowServicios = mysql_fetch_array($respServicios)); //While Servicios
        }//Comprueba Datos de Servicio

        $reporte.='<tr class="FONDO2" style="background:#CCCCCC;">
      <td colspan="4" align="right"><em><strong> Total Global:</strong></em></td>
	  <td align="right">' . $TotalRecetasGlobal . '</td>
	  <td align="right">' . $TotalSatisGlobal . '</td>
	  <td align="right">' . $TotalInsatGlobal . '</td>
	  <td>&nbsp;</td>
	  <td align="right">&nbsp;</td>
	  <td align="right">&nbsp;</td>
       <td align="right"><strong>' . number_format($Total, 3, '.', ',') . '</strong></td>
    </tr>
</table>';

//CIERRE DE ARCHIVO EXCEL
        fwrite($punteroarchivo, $reporte);
        fclose($punteroarchivo);
//CIERRE ODS
        fwrite($punteroarchivo2, $reporte);
        fclose($punteroarchivo2);

//***********************


        echo '<table>
	<tr>
		
		<td align="right" style="vertical-align:middle;">
		
		<a href="' . $nombrearchivo . '"> <H5>OFFICE <img src="../images/excel.gif"></H5></a>
		</td>
		
		<td align="center" style="vertical-align:middle;">
		
		<a href="' . $nombrearchivo2 . '"> <H5>LIBRE OFFICE <img src="../images/ods.png"></H5></a>
		</td>
	</tr>
	<tr>
		<td colspan=2>
		' . $reporte . '
		</td>
	</tr>
</table>';

        conexion::desconectar();
    }//Fin de IF nivel == 1
}//Fin de IF isset de Nivel
?>