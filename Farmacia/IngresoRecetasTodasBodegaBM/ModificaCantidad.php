<html>
<head>
<script language="javascript">
function Aplicar(IdMedicinaRecetada){
var Cantidad = document.getElementById("NuevaCantidad").value;
window.opener.ActualizaCantidad(IdMedicinaRecetada,Cantidad);
window.close();
}
</script>
</head>
<body onLoad="javascript:document.getElementById('NuevaCantidad').focus();">
<?php
$IdMedicinaRecetada=$_GET["IdMedicinaRecetada"];
?>
<table align="center">
<tr><th>Nueva Dosis</th></tr>
<tr><td><input type="text" id="NuevaCantidad" name="NuevaCantidad" size="15"></td></tr>
<tr><td align="center"><input type="button" id="Aplicar" name="Aplicar" value="Cambiar Cantidad" onClick="javascript:Aplicar(<?php echo $IdMedicinaRecetada;?>);"></td></tr>
</table>
</body>
</html>