<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="procesos/ActualizaLotesLibreria.js"></script>
<script language="javascript">
<!--
var nav4 = window.Event ? true : false;
function acceptNum(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57, 46 = '.'
var key = nav4 ? evt.which : evt.keyCode;

return (key!=32);
}

function acceptNum2(evt){	
// NOTE: Backspace = 8, Enter = 13, '0' = 48, '9' = 57	
var key = nav4 ? evt.which : evt.keyCode;
return ((key < 13) || (key >= 48 && key <= 57 ||(key==46)));
}
</script>

</head>
<body>
<?php
include('procesos/ClaseActualizaLotes.php');
conexion::conectar();
$IdMedicina=$_GET["IdMedicina"];
$Lote=$_GET["Lote"];

$resp=Actualiza::ObtenerMedicinaInformacion($IdMedicina,$Lote);
$NombreMedicamento=$resp["Nombre"];
$Concentracion=$resp["Concentracion"];
$PrecioLote=$resp["PrecioLote"];
$UnidadMedida=$resp["Descripcion"];
$Divisor=$resp["Divisor"];
$ano=$resp["ano"];
$IdLote=$resp["IdLote"];
$Existencia=$resp["Existencia"];
$mes=meses::NombreMes($resp["mes"]);//nombre de mes a español
?>
<table width="654" border="1">
<tr class="MYTABLE"><th colspan="5" align="center">Medicamento:<h3> <?php echo $NombreMedicamento.", ".$Concentracion;?></h3></th></tr>
<tr class="FONDO">
<th width="80">Existencia</th>
<th width="86">Unidad de Medida</th>
<th width="142">Lote</th>
<th width="147">Precio del Lote</th>
<th width="165">Fecha de Vencimiento</th>
</tr>
<tr class="FONDO">
<td align="center"><?php echo $Existencia/$Divisor;?></td>
<td align="center"><?php echo $UnidadMedida;?></td>
<td align="center"><?php echo $Lote;?><input type="hidden" id="LoteOld" name="LoteOld" value="<?php echo $IdLote;?>"></td>
<td align="center"><?php echo "$ ".$PrecioLote;?></td>
<td align="center"><?php echo $mes."/".$ano;?></td>
</tr>

<tr class="FONDO">
<td align="center"><input type="text" id="Existencia" name="Existencia" size="6" value="<?php echo $Existencia/$Divisor;?>" disabled="disabled"></td>
<td align="center">-----------</td>
<td align="center"><input type="text" id="Lote" name="Lote" value="" size="9" onKeyPress="return acceptNum(event)"></td>
<td align="center"><input type="text" id="Precio" name="Precio" value="0" size="8" onFocus="if(this.value=='0'){this.value='';}" onKeyPress="return acceptNum2(event)" onBlur="if(this.value==''){this.value='0';}"></td>
<td align="center">
<select id="mes" name="mes">
  <option value="0">[Seleccione Mes]</option>
  <option value="01">ENERO</option>
  <option value="02">FEBRERO</option>
  <option value="03">MARZO</option>
  <option value="04">ABRIL</option>
  <option value="05">MAYO</option>
  <option value="06">JUNIO</option>
  <option value="07">JULIO</option>
  <option value="08">AGOSTO</option>
  <option value="09">SEPTIEMBRE</option>
  <option value="10">OCTUBRE</option>
  <option value="11">NOVIEMBRE</option>
  <option value="12">DICIEMBRE</option>
</select>
<select id="ano" name="ano">
    <option value="0">[Seleccione A&ntilde;o]</option>
    <?php 
$date=date('Y');

for($i=0;$i<=12;$i++){
$ano=$date+$i;
?>
    <option value="<?php echo $ano;?>"><?php echo $ano;?></option>
    <?php }//fin de for
?>
  </select></td>
</tr>

<tr class="MYTABLE">
<td colspan="5" align="right">&nbsp;<input type="button" id="guardar" name="guardar" value="Actualizar" onClick="javascript:ActualizaDatos(<?php echo $IdMedicina;?>,<?php echo $IdLote;?>);">
<input type="button" id="Cerrar" name="Cerrar" value="Cerrar" onClick="javascript:self.close();"></td>
</tr>

</table>


<?php
conexion::desconectar();
?>
</body>
</html>