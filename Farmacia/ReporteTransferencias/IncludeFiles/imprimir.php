<html>
<head>
<script language="javascript" src="reporte.js"></script>
</head>
<body>
<?php
require('ReporteTransferenciasClase.php');
conexion::conectar();

$FechaPeriodo=$_GET["Periodo"];
$Usuarios=$_GET["Usuario"];
$proceso=new ReporteTransferencias;

/*DIFERENCIAS ENTRE TODOS LOS USUARIOS O UNO PUNTUAL*/
switch($Usuarios){
case 0:
/*PARA TODOS LOS USUARIOS QUE HAN INTRODUCIDO TRANSFERENIAS [SOLO SON DE NIVEL ADMINISTRATIVO, NO TECNICOS]*/

	$respUsuario=$proceso->ObtenerUsuarios($Usuarios);
	$tbl='<table width="915" border="1">
		<tr><td colspan="6" align="right"><input id="imprimir" type="button" value="Imprimir" onClick="javascript:Imprimir();" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"></td></tr>';

	while($row=mysql_fetch_array($respUsuario)){
		$IdPersonal=$row[0];
		$Nombre=$row[1];
		$tbl.='
	  <tr>
			<td colspan="6" align="center"><strong>'.strtoupper($Nombre).'</strong></td>
	  </tr>
		<tr>
			<th width="74">Cantidad</th>
			<th width="189">Medicamento</th>
			<th width="130">Origen de Transferencia</th>
			<th width="137">Destino de Transferencia</th>
			<th width="244">Justificacion</th>
			<th width="113">Fecha de Transferencia</th>
		</tr>';
		$respTrans=$proceso->ObtenerTransferencias($IdPersonal,$FechaPeriodo);		
		while($row2=mysql_fetch_array($respTrans)){
			$Cantidad=$row2["Cantidad"];
			$Medicamento=$row2["Nombre"];
			$Concentracion=$row2["Concentracion"];
			$AreaOrigen=$row2["Area"];
			$AreaDestino=$row2["IdAreaDestino"];
			$Justificacion=$row2["Justificacion"];
			$Fecha=$row2["FechaTransferencia"];
						
		$tbl.='<tr>
		<td align="center">'.$Cantidad.'</td>
		<td align="center">'.$Medicamento.', '.$Concentracion.'</td>
		<td align="center">'.$AreaOrigen.'</td>
		<td align="center">'.$proceso->ObtenerNombreArea($AreaDestino).'</td>
		<td>'.$Justificacion.'</td>
		<td align="center">'.$Fecha.'</td>		
		</tr>';
			
		}//while de Transferencias
	}//while de Usuarios
	
	$tbl.="</table>";
	echo $tbl;
break;

default:
	$tbl='<table width="915" border="1">
		<tr><td colspan="6" align="right"><input id="imprimir" type="button" value="Imprimir" onClick="javascript:Imprimir();" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099"></td></tr>';
	
	$Nombre=$proceso->ObtenerUsuarios($Usuarios);
		
		$tbl.='
	  <tr class="MYTABLE">
			<td colspan="6" align="center"><strong>'.strtoupper($Nombre).'</strong></td>
	  </tr>
		<tr class="FONDO">
			<th width="74">Cantidad</th>
			<th width="189">Medicamento</th>
			<th width="130">Origen de Transferencia</th>
			<th width="137">Destino de Transferencia</th>
			<th width="244">Justificacion</th>
			<th width="113">Fecha de Transferencia</th>
		</tr>';

		$respTrans=$proceso->ObtenerTransferencias($Usuarios,$FechaPeriodo);		
		while($row2=mysql_fetch_array($respTrans)){
			$Cantidad=$row2["Cantidad"];
			$Medicamento=$row2["Nombre"];
			$Concentracion=$row2["Concentracion"];
			$AreaOrigen=$row2["Area"];
			$AreaDestino=$row2["IdAreaDestino"];
			$Justificacion=$row2["Justificacion"];
			$Fecha=$row2["FechaTransferencia"];
						
		$tbl.='<tr>
		<td class="FONDO" align="center">'.$Cantidad.'</td>
		<td class="FONDO" align="center">'.$Medicamento.', '.$Concentracion.'</td>
		<td class="FONDO" align="center">'.$AreaOrigen.'</td>
		<td class="FONDO" align="center">'.$proceso->ObtenerNombreArea($AreaDestino).'</td>
		<td class="FONDO">'.$Justificacion.'</td>
		<td class="FONDO" align="center">'.$Fecha.'</td>		
		</tr>';
			
		}//while de Transferencias
	$tbl.="</table>";
	echo $tbl;
break;

}//switch
conexion::desconectar();
?>
</body></html>