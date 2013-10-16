<?php include('../Titulo/Titulo.php');
if($_SESSION["nivel"]!=1){
?>
<script language="javascript">
window.location='../index.php';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$IdModalidad=$_SESSION["IdModalidad"];
$IdEstablecimiento=$_SESSION["IdEstablecimiento"];
require('../Clases/class.php');

 ?>
<html>
<head>
<? head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>..::: Agregando Usuarios :::..</title>
<script language="JavaScript" src="NewUser.js"></script>
</head>

<body>
<?php Menu(); ?>
<br>

  <table width="65%">
    <tr class="MYTABLE">
      <td colspan="5" align="center"><strong>Creaci&oacute;n de Nuevos Usuario de Farmacia </strong></td>
    </tr>
    <tr>
      <td width="254" class="FONDO">&nbsp;Usuario (Nick): </td>
      <td colspan="4" class="FONDO">&nbsp;<input type="text" id="usuario" name="usuario" size="25" maxlength="35" tabindex="1" onkeyup="VerificaUsuarios();" autocomplete="off"> 
        <span class="style1">* </span><span id="Progreso"></span></td>
    </tr>
    <tr>
      <td class="FONDO">&nbsp;Contrase&ntilde;a (Password Provicional): </td>
      <td colspan="4"  class="FONDO">&nbsp;<input type="password" id="pass" name="pass" size="25" maxlength="15" tabindex="2" value='123456' disabled="true"> 
        <span class="style1">* [De forma provisional se genera el password con 123456]</span> </td>
      </tr>
    <tr>
      <td class="FONDO">Nombre de Usuario: </td>
      <td colspan="4"  class="FONDO">&nbsp;<input type="text" id="NombreEmpleado" name="NombreEmpleado" size="50" tabindex="2">
        <span class="style1">*</span> </td>
    </tr>
	<tr>
	  <td class="FONDO">&nbsp;Nivel de Usuario: </td>
      <td colspan="4"  class="FONDO">&nbsp;<select id="nivel" name="nivel" tabindex="5" onchange="PegarPermisos(this.value);">
	  <option value="0">Seleccione el Nivel del Nuevo Usuario</option>
	  <option value="2">Co-Administrador</option>
	  <option value="3">Tecnico de Farmacia</option>
	  <option value="4">Digitador</option>
	  <option value="5">Bodega</option>
	        </select>      </td>
      </tr>
    <tr>
	      <td class="FONDO">&nbsp;Farmacia: </td>
          <td colspan="4"  class="FONDO">&nbsp;<select id="farmacia" name="farmacia" tabindex="3" onChange="CargarAreas(this.value)">
	  <option value="0">Seleccione Farmacia</option>
 	  <?php 
	  $conexion=new conexion;
	  $cnx=$conexion->conectarp();
	  $resp=pg_query("select mnt_farmacia.IdFarmacia,mnt_farmacia.Farmacia from mnt_farmacia 
                            inner join mnt_farmaciaxestablecimiento 
                            on mnt_farmacia.IdFarmacia=mnt_farmaciaxestablecimiento.IdFarmacia
                            where IdEstablecimiento=$IdEstablecimiento and IdModalidad=$IdModalidad");
	  $conexion->desconectarp();
	   while($row=pg_fetch_array($resp,null,PGSQL_ASSOC)){
	  $IdFarmacia=$row[0];
	  $Farmacia=$row[1];
	  ?>
	  <option value="<?php echo "$IdFarmacia";?>"><?php echo"$Farmacia";?></option>
	 <?php }//fin de while?>
	        </select>      </td>
	</tr>
	<tr>
	  <td class="FONDO">&nbsp;&Aacute;rea:</td>
	  <td colspan="4" class="FONDO"><div id="ComboAreas"><select id="area" name="area" disabled="disabled" tabindex="4">
	  <option value="0">Seleccione una Area</option>
      </select> </div></td>
	</tr>	

<tr><td class="FONDO">Permisos: </td><td class="FONDO" colspan="4">
<table>
<tr><td>Administracion:</td><td><input type="checkbox" id="administracion" name="administracion" value="1"></td></tr>
<tr><td>Generacion Reportes:</td><td><input type="checkbox" id="reportes" name="reportes" value="1"></td></tr>
<tr><td>Introduccion de Datos:</td><td><input type="checkbox" id="datos" name="datos" value="1" ></td></tr>
</table>
</td>

</tr>

    <tr class="MYTABLE">
      <td colspan="5" align="right"><input type="button" id="add" name="add" value="Crear Cuenta" tabindex="6" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" onclick="Validar();">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="5"><div id="Progreso2" align="center">&nbsp;</div></td>
    </tr>

  </table>


</body>
</html>

<?php

}//nivel
?>
