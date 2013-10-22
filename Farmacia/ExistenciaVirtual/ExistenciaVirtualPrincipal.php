<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
$nivel=$_SESSION["nivel"];
$IdFarmacia=$_SESSION["IdFarmacia2"];
$IdArea=$_SESSION["IdArea"];
require('../Clases/class.php');
?>
<html>
<head>
<script language="javascript" src="IncludeFile/ExistenciaVirtual.js"></script>
<title>...</title>
</head>
<body onLoad="AumentaExistencia()">
<input type="hidden" id="IdArea" name="IdArea" value="<?php echo $IdArea;?>">
<div id="Datos"></div>
<div id="Datos2"></div>
</body>
</html>
<?php
}//Fin de IF nivel == 1
?>