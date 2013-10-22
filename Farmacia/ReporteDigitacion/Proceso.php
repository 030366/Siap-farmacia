<?php

session_start();

if (!isset($_SESSION["nivel"])) {
    echo "ERROR_SESSION";
} else {

    include('../Clases/class.php');
    include('IncludeFiles/Clase.php');

    conexion::conectar();
    $mon = new Digitacion;
    $Bandera = $_GET["Bandera"];
    switch ($Bandera) {
        case 1:
            $IdPersonal = $_GET["IdPersonal"];
            $FechaInicial = $_GET["FechaInicial"];
            $FechaFinal = $_GET["FechaFinal"];
            $Total=0;


//     GENERACION DE EXCEL
            $NombreExcel = "Reporte_Digitacion_" . $_SESSION["nick"] . '_' . date('d_m_Y__h_i_s A');
            $nombrearchivo = "../ReportesExcel/" . $NombreExcel . ".xls";
            $punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");

//LIBREOFFICE
            $nombrearchivo2 = "../ReportesExcel/" . $NombreExcel . ".ods";
            $punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************

$F1=explode('-',$FechaInicial);
$F2=explode('-',$FechaFinal);

$tabla='<table border="1" WIDTH="600" class="borders">
  <tr class="MYTABLE">
    <td colspan="2" align="center"><strong>'.$_SESSION["NombreEstablecimiento"].'</strong><br>
<strong>REPORTE DE DIGITACION</strong></td></tr>';
	

	$tabla.='<tr class="MYTABLE">
    <td colspan="2" align="center" style="vertical-align:middle;"><strong>Periodo: '.$F1[2]."-".$F1[1]."-".$F1[0].' al '.$F2[2]."-".$F2[1]."-".$F2[0].'</strong></td></tr>';



            $tabla.= '
		
	 	 <tr style="background:#0099FF; color:#FFFFFF;">
			<td width="75%" align="center"><strong>Digitador</strong></td>
			<td align="center"><strong>Recetas Digitadas</strong></td>
		</tr>';
            $respPersonal = $mon->ObtenerInformacion($IdPersonal, $FechaInicial, $FechaFinal);
            while ($rowPersonal = mysql_fetch_array($respPersonal)) {
                $IdPersonalDetalle=$rowPersonal["IdPersonal"];
                $Nombre = $rowPersonal[0];
                $NumeroRecetas = $rowPersonal[1];


                $tabla.='<tr class="FONDO"><td><span onclick="detalleDigitacion('.$IdPersonalDetalle.')">' . htmlentities($Nombre) . '</span></td>';
                $tabla.='<td align="right">' . $NumeroRecetas . '</td>';
                $tabla.=' </tr>';
                $Total+=$NumeroRecetas;
            }//while
            
            $tabla.='<tr class="FONDO"><td align="right"><strong>Total </strong></td><td align="right"><strong>' .$Total . '</strong></td></tr>';
            $tabla.='</table>';



//CIERRE DE ARCHIVO EXCEL
            fwrite($punteroarchivo, $tabla);
            fclose($punteroarchivo);
//CIERRE ODS
            fwrite($punteroarchivo2, $tabla);
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
		' . $tabla . '
		</td>
	</tr>
</table>';


            break;
            
            case 2:
                //detalle de digitacion
                
            $IdPersonal = $_GET["IdPersonal"];
            $FechaInicial = $_GET["FechaInicial"];
            $FechaFinal = $_GET["FechaFinal"];
            $Total=0;


//     GENERACION DE EXCEL
            $NombreExcel = "Reporte_Digitacion_Detalle_" . $_SESSION["nick"] . '_' . date('d_m_Y__h_i_s A');
            $nombrearchivo = "../ReportesExcel/" . $NombreExcel . ".xls";
            $punteroarchivo = fopen($nombrearchivo, "w+") or die("El archivo de reporte no pudo crearse");

//LIBREOFFICE
            $nombrearchivo2 = "../ReportesExcel/" . $NombreExcel . ".ods";
            $punteroarchivo2 = fopen($nombrearchivo2, "w+") or die("El archivo de reporte no pudo crearse");

//***********************

$F1=explode('-',$FechaInicial);
$F2=explode('-',$FechaFinal);

$tabla='<table border="1" WIDTH="600" class="borders">
  <tr class="MYTABLE">
    <td colspan="2" align="center"><strong>'.$_SESSION["NombreEstablecimiento"].'</strong><br>
<strong>REPORTE DE DIGITACION</strong></td></tr>';
	

	$tabla.='<tr class="MYTABLE">
    <td colspan="2" align="center" style="vertical-align:middle;"><strong>Periodo: '.$F1[2]."-".$F1[1]."-".$F1[0].' al '.$F2[2]."-".$F2[1]."-".$F2[0].'</strong></td></tr>';




            $respPersonal = $mon->detalleDigitacion($IdPersonal, $FechaInicial, $FechaFinal);
            if($rowPersonal = mysql_fetch_array($respPersonal)){
                
                
                            $tabla.= '
		           
		
	 	 <tr style="background:#0099FF; color:#FFFFFF;">
			<td colspan=2 align="center"><strong>'.$rowPersonal[0].'</strong></td>
			
		</tr>
	 	 <tr style="background:#0099FF; color:#FFFFFF;">
			<td width="75%" align="center"><strong>Fecha</strong></td>
			<td align="center"><strong>Recetas Digitadas</strong></td>
		</tr>';
                
                
            do{
                $Fecha = $rowPersonal["Fecha"];
                $NumeroRecetas = $rowPersonal["Total"];


                $tabla.='<tr class="FONDO"><td>' . $Fecha . '</td>';
                $tabla.='<td align="right">' . $NumeroRecetas . '</td>';
                $tabla.=' </tr>';
                $Total+=$NumeroRecetas;
            }while ($rowPersonal = mysql_fetch_array($respPersonal));//while
    }
            $tabla.='<tr class="FONDO"><td align="right"><strong>Total </strong></td><td align="right"><strong>' .$Total . '</strong></td></tr>';
            $tabla.='</table>';



//CIERRE DE ARCHIVO EXCEL
            fwrite($punteroarchivo, $tabla);
            fclose($punteroarchivo);
//CIERRE ODS
            fwrite($punteroarchivo2, $tabla);
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
		' . $tabla . '
		</td>
	</tr>
</table>';
                
                break;
    }//switch
    conexion::desconectar();
}//session
?>