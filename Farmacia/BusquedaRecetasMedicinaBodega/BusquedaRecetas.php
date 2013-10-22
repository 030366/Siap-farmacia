<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?><script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if(isset($_SESSION["IdFarmacia2"])){
$IdFarmacia=$_SESSION["IdFarmacia2"];
}

if($_SESSION["Datos"]!=1){ ?>
<script language="JavaScript">window.location='../Principal/index.php?Permiso=1';</script>
<?php }else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
$path='';
require('IncludeFiles/RecetasProcesoClase.php');

	if($_SESSION["Datos"]!=1){?><script language="javascript">
		alert('No posee sufientes privilegios para acceder!');
		window.location='../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
	</script>	
	<?php }
?>
<html>
<head>
<?php head(); ?>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Introduccion de Recetas:::...</title><script language="javascript" src="IncludeFiles/IntroRecetas.js"></script><script language="javascript"  src="IncludeFiles/calendar.js"> </script><script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>

<!-- AUTOCOMPLETAR --><script type="text/javascript" src="scripts/prototype.js"></script><script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!-- -->
</head>
<body onLoad="javascript:document.getElementById('fechaInicial').focus();">
<?php Menu(); ?>
<br>
<form action="" method="post" name="formulario">

  <table width="825" border="0">
    <tr class="MYTABLE">
      <td colspan="6" align="center">
              <strong>B&Uacute;SQUEDA DE RECETAS </strong></td>
      </tr>
			<tr>
			  <td colspan="6" class="FONDO" align="center">Hoy es:&nbsp;&nbsp;&nbsp;<strong><?php echo date('d-m-Y');?></strong></td>
	  </tr>
			<tr>
			  <td colspan="4" class="FONDO">Farmacia:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	          <td class="FONDO"><select id="IdFarmacia" name="IdFarmacia" style="font-style:italic;" onChange="ComboArea(this.value);">
                <option value="0">...::: Farmacia :::...</option>
                <?php conexion::conectar();
		echo RecetasProceso::Combo(1,$_SESSION["TipoFarmacia"]); 
				conexion::desconectar();
		?>
              </select></td>
	          <td class="FONDO">&nbsp;<input type="hidden" id="nick" name="nick" value="<?php echo $nick;?>"></td>
	  </tr>
			<tr>
			  <td colspan="4" class="FONDO">Area:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;			    </td>
	          <td class="FONDO"><div id="ComboArea"><select id="IdArea" name="IdArea" style="font-style:italic;">
                <option value="0">...::: Area :::...</option>
                <?php conexion::conectar();
		echo RecetasProceso::Combo(2,$_SESSION["TipoFarmacia"]); 
				conexion::desconectar();
		?>
              </select></div></td>
	          <td class="FONDO">&nbsp;</td>
	  </tr>
			<tr>
			  <td colspan="4" class="FONDO">Especialidad/Servicio:&nbsp;&nbsp;</td>
	          <td width="300" class="FONDO"><input id="CodigoSubEspecialidad" name="CodigoSubEspecialidad" type="text" maxlength="4" onBlur="javascript:CargarSubEspecialidad(this.value);" style="width:50px;" onKeyPress="return Saltos(event,this.id);">
                <input type="button" id="Buscador2" name="Buscador2" onClick="javascript:VentanaBusqueda();" value="...">
                <input type="hidden" id="IdSubEspecialidad" name="IdSubEspecialidad" ></td>
	          <td width="579" class="FONDO"><div id="Especialidad">&nbsp;</div></td>
	  </tr>
			<tr>
			  <td colspan="4" class="FONDO">Medico:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
	          <td class="FONDO"><input id="CodigoFarmacia" name="CodigoFarmacia" type="text" maxlength="4" onBlur="javascript:ObtenerDatosMedico();" style="width:50px;" onKeyPress="return Saltos(event,this.id);">
                <input type="button" id="Buscador" name="Buscador" onClick="javascript:VentanaBusqueda2();" value="...">
                <!-- <input type="text" id="IdEspecialidad" name="IdEspecialidad"> -->
                <input type="hidden" id="IdMedico" name="IdMedico"></td>
	          <td class="FONDO"><div id="NombreMedico">&nbsp;</div></td>
	  </tr>
			<tr>
			  <td colspan="4" class="FONDO">Periodo del:</td>
	          <td class="FONDO">&nbsp;<input type="text" id="fechaInicial" name="fechaInicial" onClick="scwShow (this, event);" readonly="true">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;al </td>
	          <td class="FONDO">&nbsp;<input type="text" id="fechaFinal" name="fechaFinal" onClick="scwShow (this, event);" readonly="true"></td>
	  </tr>
			<tr>
			  <td colspan="6" class="FONDO">Nombre de Medicamento:&nbsp;
                <input type="hidden" id="IdMedicina" name="IdMedicina">
                <input type="text" id="NombreMedicina" name="NombreMedicina" onKeyPress="return Saltos(event,this.id); Limpieza(event,this.value);" size="90"></td>
	  </tr>
			<tr>
			  <td colspan="4" class="FONDO">&nbsp;</td>
			  <td class="FONDO">&nbsp;</td>
			  <td class="FONDO">&nbsp;</td>
			</tr>


      

	      <tr>
      <td colspan="2" class="FONDO"><div id="Progreso">&nbsp;</div></td>
      <td colspan="4" class="FONDO" align="right"><input type="button" id="Buscar" name="Buscar" value="Realizar Busqueda" onClick="javascript:valida();" >
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
<!-- <input type="button" id="Limpiar" name="Limpiar" value="Limpiar Pantalla"> -->
<div id="IdReceta">	
		<!-- VALORES DE HISTORIAL CLINICO Y DE ID DE RECETA -->
</div>	</td>
      </tr>
	      <tr>
      <td class="FONDO" align="center" colspan="6">
	  	<table width="100%">
	  		<tr><td><div id="RespuestaExcel" style="border:#009966 solid;" align="center">&nbsp;</div></td></tr>
	  		<tr><td><div id="Respuesta" align="center" style='border:solid;  overflow:scroll;  height:315; width:890;'>&nbsp;</div></td></tr>
	  	</table>
	  </td></tr>


	  <tr>
      <td colspan="6" class="FONDO"><div id="MedicinaNueva" align="center"></div></td>
      </tr>
	  <tr>
      <td colspan="6" class="FONDO"><div id="MedicinaNuevaRepetitiva" align="center"></div></td>
      </tr>
    <tr class="MYTABLE">
      <td colspan="6" align="right">&nbsp;</td>
    </tr>
  </table>
  </form>

	<script>
			new Autocomplete('NombreMedicina', function() { 
				return 'respuesta.php?q=' + this.value; 
			});

	</script>
	
</body>
</html>
<?php
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
