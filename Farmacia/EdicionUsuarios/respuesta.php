<?php session_start();
include('../Clases/class.php');
conexion::conectar();
$Busqueda=$_GET['q'];
$querySelect="select * from farm_usuarios where Nombre like '%$Busqueda%' and IdModalidad=".$_SESSION["IdModalidad"]." and IdEstablecimiento=".$_SESSION["IdEstablecimiento"];
	$resp=mysql_query($querySelect);
while($row=mysql_fetch_array($resp)){
	$Nombre=htmlentities($row["Nombre"]);
	$Usuario=$row["nick"];
	$IdPersonal=$row["IdPersonal"];

?>
<li onselect="this.text.value = '<?php echo $Nombre;?>'; $('IdPersonal').value = '<?php echo $IdPersonal;?>'; MostrarDetalle(<?php echo $IdPersonal; ?>);"> 
	<span><?php echo "<i>".$Usuario."</i>";?></span>
	<strong><?php echo $Nombre;?></strong>
</li>
<?php
}
conexion::desconectar();
?>