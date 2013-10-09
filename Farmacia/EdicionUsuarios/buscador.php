<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){
?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{

require('../Clases/class.php');

if($_SESSION["Administracion"]==1 and $_SESSION["nivel"]==1){

?>
<html>
<head>
<?php head(); ?>
<title>...:::BUSQUEDA DE USUARIOS:::...</title>

<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script type="text/javascript" src="include/buscador.js"></script>
<!-- AUTOCOMPLETAR -->
	<script type="text/javascript" src="scripts/prototype.js"></script>
	<script type="text/javascript" src="scripts/autocomplete.js"></script>
	<link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
<!--  -->

</head>

<body>
<?php Menu(); ?>
       <br>
      
        <table width="50%" style="border:solid;">
          <tr>
            <td align="center" colspan="2"><strong>Modificaci&oacute;n de Usuarios </strong></td>
          </tr>
          <tr>
            <td width="25%"><strong>Nombre Usuario:</strong></td>
                <td><input type="text" id="q" name="q" value="" size="50"><input type="hidden" id="IdPersonal"></td>
		
          </tr>

	<tr><td colspan="2"><div id="loading">&nbsp;</div></td></tr>
	<tr><td colspan="2"><div id="resultados" align="center">&nbsp;</div></td></tr>
    

</table>

	<script>
		new Autocomplete('q', function() { 
			
			return 'respuesta.php?q=' + this.value; 
		});
	</script>
</body>
</html>
<?php
//IF Nivel == 1
}else{
?>
<script language="javascript">
window.location='../Principal/index.php';
</script>
<?php
}//nivel ==1

}//SESSION
?>
