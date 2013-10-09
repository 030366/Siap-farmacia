<?php include('../Titulo/Titulo.php');

if(isset($_SESSION["nivel"])==4 and $_SESSION["Datos"]==1){
$IdFarmacia=$_SESSION["IdFarmacia2"];
$IdArea=$_SESSION["IdArea"];
$IdPersonal=$_SESSION["IdPersonal"];
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
include('../Clases/class.php');
?>
<html>
<script language="javascript">
function popUp(URL) {
day = new Date();
id = day.getTime();
eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=0,width=500,height=600,left = 450,top = 450');");
}//popUp
</script>
<head>
<?php head();?>
<script type="text/javascript" src="ReLoad.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Recetas:::...</title>

<script language="javascript">
function confirmacion(){
var valor=confirm('             Esta receta sera enviada \n�Son los datos de esta receta correctos?');
	if(valor==1){
		return true;
	}else{
		return false;
	}
}//confirmacion

</script>
</head>
<!-- Bloqueo de Click Derecho del Mouse -->
<body onLoad="document.getElementById('IdNumeroExp').focus();CargarCombo();">
<?php Menu();?>
<br>

	<table width="464">
		<tr><td colspan="2" align="center" class="MYTABLE"><h3>Busqueda de Receta Repetitiva</h3></td></tr>
		<tr class="FONDO">
		  <td ><strong>Numero de Expediente:</strong></td>
		  <td><input type="text" id="IdNumeroExp" name="IdNumeroExp" onKeyPress="return acceptNum(event)"></td>
	  </tr>
		<tr class="FONDO">
		  <td ><strong>Area:</strong></td>
		  <td><select id="IdArea" name="IdArea">
            <?php conexion::conectar();
								$resp=mysql_query("select IdArea,concat_ws(' ',Area,'  [',Farmacia,']') as Area
													from mnt_areafarmacia
													inner join mnt_farmacia
													on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia");
								conexion::desconectar();
								while($row=mysql_fetch_array($resp)){
									echo '<option value="'.$row["IdArea"].'">'.$row["Area"].'</option>';
								}
							?>
          </select></td>
	  </tr>
		<tr class="FONDO">
		  <td ><strong>Mes:</strong></td>
		  <td>
		  <select id="Mes" name="Mes">
		  	<option value="<?php echo '01-01';?>">ENERO</option>
			<option value="<?php echo '02-01';?>">FEBRERO</option>
			<option value="<?php echo '03-01';?>">MARZO</option>
			<option value="<?php echo '04-01';?>">ABRIL</option>
			<option value="<?php echo '05-01';?>">MAYO</option>
			<option value="<?php echo '06-01';?>">JUNIO</option>
			<option value="<?php echo '07-01';?>">JULIO</option>
			<option value="<?php echo '08-01';?>">AGOSTO</option>
			<option value="<?php echo '09-01';?>">SEPTIEMBRE</option>
			<option value="<?php echo '10-01';?>">OCTUBRE</option>
			<option value="<?php echo '11-01';?>">NOVIEMBRE</option>
			<option value="<?php echo '12-01';?>">DICIEMBRE</option>
		  </select></td>
	  </tr>
	  <tr class="FONDO">
		<TD><strong>A&ntilde;o:</strong></TD>
		<td>
			<div id="COMBOANO">
				<select id="Ano">
					<option value="2008">2008</option>
				</select>
			</div>
		</td>
	  </tr>
		<tr class="FONDO">
		  <td width="165" ><strong>Tipo de Receta:</strong> </td>
		<td width="287"><select id="IdEstado" name="IdEstado">
					<option value="RE">REPETITIVA</option>
					<option value="R">DEL DIA [FECHA PUNTUAL]</option>
        </select></td></tr>
		<tr><td colspan="2" align="right" class="MYTABLE"><input type="button" id="Buscar" name="Buscar" value="Buscar Receta !" onClick="Procesar(0,1);"></td></tr>
  </table>
<br>

<div id="Layer1"></div>

</body>
</html>
<?php 

}else{?>
<script language="javascript">
window.location='../Principal/index.php?Permiso2=1';
</script>
<?php
}//fin de ELSE Nivel
?>