<?php include('../Titulo/Titulo.php');
if(isset($_SESSION["nivel"])==3){
$IdFarmacia=$_SESSION["IdFarmacia2"];
$IdArea=$_SESSION["IdArea"];
$IdPersonal=$_SESSION["IdPersonal"]; 
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
<!-- <meta http-equiv="refresh" content="10" /> -->
<script type="text/javascript" src="ReLoad.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<title>...:::Recetas:::...</title>
<?php head(); ?>
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
<body onLoad="Carga();" >
<script type="text/javascript" src="../tooltip/wz_tooltip.js"></script>
<?php Menu(); ?>
<br>

<div id="TODO">

<!-- CARGA DE LAS RECETAS QUE HAN SIDO PREPARADAS  -->

</div>
</body>
</html>
<?php 

}else{?>
<script language="javascript">
window.location='../index.php?Permiso2=1';
</script>
<?php
}//fin de ELSE Nivel
?>