<?php include 'conexion.php'; 
$IdMedicina=$_REQUEST["IdMedicina"];
$IdTerapeutico=$_REQUEST["terapeutico"];
$IdHospital=$_REQUEST["hospital"];
echo"$IdMedicina ,  $IdTerapeutico, $IdHospital";

$queryVerifica="SELECT * FROM farm_catalogoproductos WHERE IdMedicina='$IdMedicina' and IdTerapeutico='$IdTerapeutico'";
conectar();
$verifica=mysql_query($queryVerifica);
desconectar();

if($ver=mysql_fetch_array($verifica)){
echo"Ya existe esta relacion";
?>
<script language="javascript">
alert('Ya esta relacionada'); //aviso de duplicacion
window.location='buscador_terapeutico.php';
</script>
<?php
}//inf de IF

else{

$queryUpdate="update farm_catalogoproductos set IdTerapeutico='$IdTerapeutico', IdHospital='$IdHospital' where IDMEDICINA='$IdMedicina'";
conectar();
mysql_query($queryUpdate);
desconectar();

?>
<script language="javascript">
window.location='buscador_codigo.php'; //redireccionamiento
</script>
<?php
}//fin de Else


echo"<a href=\"buscador_terapeutico.php\">Regresar</a>";
?>
