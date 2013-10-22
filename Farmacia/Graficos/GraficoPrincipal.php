<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
include('../Clases/class.php');
function generaSelect(){ //creacioon de combo para las Regiones
	$conexion=new conexion;
	$conexion->conectar();
	$consulta=mysql_query("select * from mnt_grupoterapeutico");
	$conexion->desconectar();
	// Voy imprimiendo el primer select compuesto por los paises
	echo "<select name='select1' id='select1' onChange='cargaContenido(this.id)' onmouseover=\"Tip('Selecci&oacute;n de Farmacia')\" onmouseout=\"UnTip()\">";
	echo "<option value='0'>[Seleccione ...]</option>";
	while($registro=mysql_fetch_row($consulta)){
		if($registro[1]!="--"){
		echo "<option value='".$registro[0]."'>".$registro[0]."-".$registro[1]."</option>";
		}
	}
	echo "</select>";
}

?>

<html>
<head>
<?php head();?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script language="javascript" src="IncludeFiles/FiltroGrafico.js"></script>
<script language="javascript" src="IncludeFiles/graficacion.js"></script>
<script language="javascript" src="calendar.js"></script>
<title>Graficos Estadistico...</title>

</head>
<body>
<?php Menu();?>
<br>
<script language="javascript" src="../tooltip/wz_tooltip.js"></script>


<table height="638">
<tr><td height="120" style="vertical-align:top"><table width="928">
    <tr class="MYTABLE">
      <td colspan="4" align="center">&nbsp;<strong>Generaci&oacute;n de Gr&aacute;ficas Estad&iacute;sticas</strong></td>
    </tr>
    <tr class="FONDO2">
      <td width="232">Seleccione Grupo Terapeutico: </td>
      <td colspan="3">&nbsp;<?php generaSelect();?></td>
    </tr>
    <tr class="FONDO2">
      <td>Seleccione un Medicamento: </td>
      <td colspan="3">&nbsp;<select id="select2" name="select2" disabled="disabled">
        <option value="0">[Seleccione ...]</option>
      </select></td>
    </tr>

<tr class="FONDO2"><td>Informacion a Graficar</td><td colspan="3">&nbsp;<select id="TipoInfo" name="TipoInfo">
					<option value="1">Consumo</option>
					<option value="2">Recetas</option>
					</select>
</td></tr>

	<tr class="FONDO2">
		<td>Fecha de Inicio: </td><td colspan="3">&nbsp;<input id="fechaInicio" name="fechaInicio" type="text" readonly="true" onClick="scwShow(this,event)"></td>
	</tr>
	<tr class="FONDO2">
		<td>Fecha de Fin: </td><td colspan="3">&nbsp;<input id="fechaFin" name="fechaFin" type="text" readonly="true" onClick="scwShow(this,event)"></td>
	</tr>

		<tr class="FONDO2">
		<td>Tipo de Gr&aacute;fico: </td>
		<td width="233"  onMouseOver="Tip('Generar Gr&aacute;fica<br><img src=\'images/Gpastel.PNG\'>',TEXTALIGN,'center')" onmouseout='UnTip()'><input id="pastel" name="pastel" type="checkbox">
		  Gr&aacute;fico de Pastel </td>
	    <td width="222" onMouseOver="Tip('Generar Gr&aacute;fica<br><img src=\'images/GBarras.PNG\'>',TEXTALIGN,'center')" onmouseout='UnTip()'><input id="barras" name="barras" type="checkbox">
	      Gr&aacute;fico de Barras </td>
		<td width="223" onMouseOver="Tip('Generar Gr&aacute;fica<br><img src=\'images/GLineas.PNG\'>',TEXTALIGN,'center')" onmouseout='UnTip()'><input id="lineas" name="lineas" type="checkbox">
Gr&aacute;fico de Linea </td>
		</tr>
    <tr class="MYTABLE">
      <td>&nbsp;</td>
      <td colspan="3" align="center">
	  <input value="GENERAR GRAFICA" name="graficar" id="graficar" type="button" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" onClick="valida()">&nbsp;
	  
	  <input type="button" id="imprimir" name="imprimir" value="Vista Previa" onClick="javascript:popUp();"  onMouseOver="this.style.color='#CC0000';Tip('Vista Previa e Impresi&oacute;n <img src=\'../images/preview.jpg\'><br> del Reporte',TEXTALIGN,'justify')" onMouseOut="this.style.color='#000000'; UnTip()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" disabled="disabled"></td>
</td>
    </tr>
  </table>
  </td></tr>
  <tr><td style="vertical-align:top">
<div id="respuesta"></div>
  </td></tr>
 </table>

</form>
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>