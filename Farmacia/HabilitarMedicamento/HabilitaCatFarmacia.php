<?php include('../Titulo/Titulo.php'); 

if($_SESSION["Administracion"]!=1 and $_SESSION["Datos"]!=1){ ?>
<script language="JavaScript">alert('No posee permisos para accesar!');window.location='../Principal/index.php';</script>
<?php } ?>

<html>
<head>
<TITLE>Mantenimiento de Catalogo Farmaceutico</TITLE>
<?php head();?>
<script language="JavaScript" src="IncludeFiles/Common.js"></script>
<script language="JavaScript" src="../noCeros.js"></script>
<script language="JavaScript" src="../trim.js"></script>
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
</head>
<body onload="CargarGrupoTerapeuticos();">
<?php Menu();?>
<br>
<center>
<div id="Cargar"></div>
<div id="GruposTerapeuticos"></div>

</center>
</body>
</html>