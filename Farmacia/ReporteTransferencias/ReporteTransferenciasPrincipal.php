<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["Reportes"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
$IdFarmacia=$_SESSION["IdFarmacia2"];
}

if(($_SESSION["Reportes"]!=1)){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
require('../Clases/class.php');
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
conexion::conectar();
		function generaSelect(){
			$querySelect="select distinct farm_usuarios.IdPersonal,farm_usuarios.Nombre
						from farm_usuarios
						inner join farm_transferencias
						on farm_transferencias.IdPersonal=farm_usuarios.IdPersonal";
			$resp=mysql_fetch_array(mysql_query($querySelect));
			if($resp[0]!=NULL){
                $resp=mysql_query($querySelect);
				$combo="<select id='Usuarios' name='Usuarios'>
						<option value='0'>[Todos los usuarios]</option>";
				while($row=mysql_fetch_array($resp)){
						$combo.="<option value='".$row["IdPersonal"]."'>".$row["Nombre"]."</option>";
				}//while
				$combo.="</select>";
                echo $combo;
			}else{
				echo "<select id='Usuario'><option value='N'>No hay Tranferencias aun</option></select>";
			}
			
		}//generaSelect
?>

<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Reporte Existencias:::...</title>
<script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
<script language="javascript"  src="../ReportesArchives/validaFechas.js"> </script>
<script language="javascript" src="IncludeFiles/reporte.js"></script>
<?php head();?>
</head>
<body>
<?php Menu();?>
<br>
<form id="Formulario">
  <table width="521" height="194" border="0">
    <tr class="MYTABLE">
      <td colspan="5" align="center">&nbsp;<strong>REPORTE DE TRANSFERENCIAS  </strong></td>
      </tr>
			<tr><td colspan="5" class="FONDO"><br></td></tr>
    <tr>
      <td width="172" class="FONDO"><strong>Usuario Emisor de Transferencias: </strong></td>
      <td width="339" colspan="4" class="FONDO">
	  <div id="ComboUsuarios">
		<?php generaSelect();?>
	  </div>
	  </td>
    </tr>
 <tr>
      <td class="FONDO"><strong>Fecha de Inicio: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="fechaInicio" id="fechaInicio" readonly="true" onClick="scwShow (this, event);" /></td>
      </tr>
	  <tr>
      <td class="FONDO"><strong>Fecha de Finalizaci&oacute;n: </strong></td>
      <td colspan="4" class="FONDO"><input type="text" name="fechaFin" id="fechaFin" readonly="true" onClick="scwShow (this, event);"/></td>
      </tr>
	  <tr>
	  <tr>
      <td colspan="5" class="FONDO">&nbsp;</td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="5" align="right"><input type="button" name="generar" value="Generar Reporte" onMouseOver="this.style.color='#009900';" onMouseOut="this.style.color='#000000';" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" onClick="javascript:Valida();"></td>
    </tr>
  </table>
</form>
<div id="Layer2" align="center"></div>
</body>
</html>
<?php
conexion::desconectar();
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>