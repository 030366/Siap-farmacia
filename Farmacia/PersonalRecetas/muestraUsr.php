<html>
<head>
<title>Untitled Document</title>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:43px;
	top:18px;
	width:723px;
	height:33px;
	z-index:1;
}
#Layer2 {
	position:absolute;
	left:50px;
	top:312px;
	width:725px;
	height:71px;
	z-index:2;
}
#Layer3 {
	position:absolute;
	left:50px;
	top:312px;
	width:154px;
	height:41px;
	z-index:3;
}
-->
</style>
<script src="desplegar.js"></script>
<script language="javascript" src="procesos/Filtro.js"></script>
</head>

<body>
<?php
include('../Clases/class.php');
conexion::conectar();
$querySelect="select * from farm_usuarios";
$resp=mysql_query($querySelect);
conexion::desconectar();
?>
<div id="Layer1">
  <table width="691">
    <tr>
      <td colspan="5" align="center">Usuarios de Farmacia</td>
    </tr>
    <tr>
      <td align="center">Nick</td>
      <td align="center">Nombre</td>
      <td align="center">Nivel</td>
      <td align="center">Farmacia</td>
      <td align="center">AreFarmacia</td>
    </tr>
    <?php while($row=mysql_fetch_array($resp)){



$UserId=$row["IdPersonal"];
if($Nivel != 1){
?>
    <tr>
      <td><a href="#" onClick="desplegar(<?php echo $UserId;?>)"><?php echo $Nick;?></a></td>
      <td><?php echo $Nombre;?></td>
      <td align="center"><?php echo $Nivel;?></td>
      <td align="center"><?php echo $Farmacia;?></td>
      <td align="center"><?php echo $IdArea;?></td>
    </tr>
    <?php
}//if
 }?>
  </table>
</div>
<div id="Layer2"></div>
<div id="Layer3"></div>
</body>
</html>