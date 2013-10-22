<?php
session_start();

if (isset($_SESSION["nivel"])) {

    $path = "";
    include('IncludeFiles/ClasesReporteFarmacias.php');
    conexion::conectar();


    /* 	PARAMETROS	 */
    if (isset($_GET["Bandera"])) {
        $Bandera = $_GET["Bandera"];
    } else {
        $Bandera = 0;
    }

    $IdFarmacia = $_GET["IdFarmacia"];

    $TOP = $_GET["TOP"];

    $FechaInicial = $_GET["FechaInicial"];
    $FechaFinal = $_GET["FechaFinal"];

    $F1 = explode('-', $FechaInicial);
    $F2 = explode('-', $FechaFinal);

    /*     * ******** */
    if ($IdFarmacia == 0) {
        $NombreFarmacia = "CONSUMO GENERAL";
    } else {
        $SQL = "select Farmacia from mnt_farmacia where IdFarmacia=" . $IdFarmacia;
        $resp = mysql_fetch_array(mysql_query($SQL));
        $NombreFarmacia = strtoupper($resp[0]);
    }


    $MontoTotal = 0;
    $MontoSubTotal = 0;

    $TotalRecetas3 = 0;
    $TotalSatis3 = 0;
    $TotalInsat3 = 0;
    ?>
    <html>
        <head>
            <title>Reporte Por Farmacias</title>
            <?php
            if ($Bandera != 0) {
                echo '<script language="javascript" src="IncludeFiles/ReporteFarmacias.js"></script>';
            }
            ?>
        </head>

        <body>
            <?php
//     GENERACION DE EXCEL
            $NombreExcel = "Farmacias_" . $NombreFarmacia . "_" . $_SESSION["nick"] . '_' . date('d_m_Y__h_i_s A');
            $nombrearchivo = "../ReportesExcel/" . $NombreExcel . ".xls";
            $punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");

//LIBREOFFICE
            $nombrearchivo2 = "../ReportesExcel/" . $NombreExcel . ".ods";
            $punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************
//***********************



            $nota = "";
            $reporte = '<table width="990" border="1">
  <tr class="MYTABLE"><td colspan="8" align="center"><strong>' . $_SESSION["NombreEstablecimiento"] . '</strong></td></tr>
  <tr class="MYTABLE">
    <td colspan="8" align="center"><strong>MEDICAMENTOS CON MAYOR CONSUMO</strong></td></tr>
	
  <tr class="MYTABLE">
    <td colspan="8" align="center" style="vertical-align:middle;"><strong><h2>' . $NombreFarmacia . '</h2></strong></td>
  </tr>
    <tr class="MYTABLE">
    <td colspan="8" align="center" style="vertical-align:middle;"><strong>Periodo: ' . $F1[2] . "-" . $F1[1] . "-" . $F1[0] . ' al ' . $F2[2] . "-" . $F2[1] . "-" . $F2[0] . '</strong></td></tr>';
            //<!--  INICIO DE REPORTE  -->
//OBTENCION DE TOP DE MEDICAMENTOS

            
            for ($i = 1; $i <= 2; $i++) {

                $Tipos= ReporteFarmacias::TipoMedida($i);
                    $reporte.='
                        <tr  class="MYTABLE"><td colspan="8" align="center"><h2>'.$Tipos[0].'</h2></td></tr>
  	 <tr class="MYTABLE">
		<td width="66" align="center"><strong>Codigo</strong></td>
		<td width="141" align="center"><strong>Medicamento</strong></td>
		<td width="78" align="center"><strong>Concen.</strong></td>
		<td width="134" align="center"><strong>Presen.</strong></td>
		<td width="67" align="center"><strong>Recetas</strong></td>
		<td width="76" align="center"><strong>Unidad de Medida </strong></td>
		<td width="75" align="center"><strong>Consumo</strong></td>
		<td width="74" align="center"><strong>Monto ($) </strong></td>
	  </tr>';
                
                $respTOP = ReporteFarmacias::TOP($TOP, $i, $FechaInicial, $FechaFinal,$IdFarmacia);
                while ($rowTop = mysql_fetch_array($respTOP)) {


                    //**************VERIFICACION DE MEDICAMENTO DEL GRUPO CONTRA INGRESO


                   
                    $TotalConsumo2 = 0;


                    $IdMedicina = $rowTop["IdMedicina"];

                    $Codigo = $rowTop["Codigo"];
                    $Nombre = htmlentities($rowTop["Nombre"]);
                    $Concentracion = htmlentities($rowTop["Concentracion"]);
                    $Presentacion = htmlentities($rowTop["FormaFarmaceutica"]) . ' - ' . htmlentities($rowTop["Presentacion"]);
                    $DescripcionUnidadMedida = $rowTop["Descripcion"];

                    $TotalRecetas=$rowTop["Recetas"];
                    //***********************GENERACION DE CONSUMOS Y COSTOS***************************
                    $TotalConsumo = $rowTop["TotalConsumo"];
                    $MontoNuevo = number_format($rowTop["Monto"],3,'.','');
                    $PrecioLote = "";


                    if ($respDivisor = mysql_fetch_array(ReporteFarmacias::ValorDivisor($IdMedicina))) {
                        $Divisor = $respDivisor[0];

                        $TotalConsumo=number_format($TotalConsumo,3,'.','');
                        
                        if ($TotalConsumo < 1) {
                            //Si la cantidad a mostrar es menor que 1 es decir menor a un frasco
                            $TransformaEntero = number_format($TotalConsumo * $Divisor, 0, '.', ',');
                            $CantidadTransformada = $TransformaEntero . '/' . $Divisor;
                        } else {
                            //Si la cantidad es mayor a un frasco se realiza este cambio a quebrados
                            $TotalConsumo = $TotalConsumo;
                            $CantidadBase = explode('.', $TotalConsumo);

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
                        $CantidadIntro = $TotalConsumo;
                        $CantidadIntro = $CantidadIntro;
                    }


                    //*****************************************************	
                    $reporte.='<tr class="FONDO2">
			<td style="vertical-align:middle;">&nbsp;"' . $Codigo . '"</td>
			<td style="vertical-align:middle">' . $Nombre . '</td>
			<td align="center" style="vertical-align:middle;">' . $Concentracion . '</td>
			<td align="center" style="vertical-align:middle;">' . htmlentities($Presentacion) . '</td>
			<td align="center" style="vertical-align:middle;">' . $TotalRecetas . '</td>
			<td align="center" style="vertical-align:middle;">' . $DescripcionUnidadMedida . '</td>
			<td style="vertical-align:middle;" align="right">' . $CantidadIntro . '</td>
			<td align="right" style="vertical-align:middle;">' . $MontoNuevo . '</td>
		  </tr>';
                }//Datos TOP
                
                $reporte.=' <tr class="MYTABLE"><td colspan="8">&nbsp;</td>';
                
            }//Unidad de Medida

            $reporte.='
  
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
            ?>
        </body>
    </html>
    <?php
    conexion::desconectar();
} else {
    echo "ERROR_SESSION";
}
?>
