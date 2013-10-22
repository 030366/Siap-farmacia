<?php session_start();?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
</head>
<body>
<?php
$IdFarmacia2=0;//de la hoja de firmando
if($IdFarmacia2!=0){$IdFarmacia=$IdFarmacia2;}else{
$IdFarmacia=$_REQUEST["farmacia"];
$IdArea=$_REQUEST["area"];}

$_SESSION["IdFarmacia"]=$IdFarmacia;
$_SESSION["IdAreaFarmacia"]=$IdArea;
?>
<script language="javascript">
window.location='buscadorArea.php';
</script>
</body>
</html>