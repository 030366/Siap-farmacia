<html>
    <head><title>Prueba</title></head>
    <body>
<?php
$usuario=$_REQUEST["usuario"];
$contra=$_REQUEST["contra"];
$contra=md5($contra);
    $strCnx = "host=localhost port=5432 dbname=siap_reloaded user=postgres password=admin";
	$connect = pg_connect($strCnx) ;//or die ("Error de conexion bananero. ". pg_last_error());

    if(!$connect)
        echo "<p><i>No me conecte</i></p>";
    else
        echo "<p><i>Me conecte</i></p>";
        
    $sql="SELECT * FROM farm_usuarios where nick='".$usuario."' and password='".$contra."'";
$result3 = pg_query($connect,$sql);
echo $sql."<br>";
var_dump($result3);
echo "<br>";
echo "numero".pg_numrows($result3)."<br>";
if($row = pg_fetch_array($result3, null, PGSQL_ASSOC)){
	 			 		$id=$row["idpersonal"];         
						$nombre=$row["nombre"];
						$nick=$row["nick"];
						$farmacia=$row["idfarmacia"];
						$nivel=$row["nivel"]; 
						$datos=$row["datos"];
						$reporte=$row["reportes"];
						$Administracion=$row["administracion"];
						$primera=$row["primeraVez"];
						$IdArea=$row["idarea"];
						$IdEstadoCuenta=$row["idestadocuenta"];
						$IdEstablecimiento=$row["idestablecimiento"];
                        $IdModalidad=$row["idmodalidad"];
					    //$NombreFarmacia=$row["Farmacia"];
					    echo "entro";
					    
					}
else{
	echo "no entro";
	}
echo "id:".$id." nombre".$nombre."<br>";
echo "nick:".$nick." farmacia".$farmacia." nivel".$nivel."<br>";
echo "datos ".$datos." reporte ".$reporte." administracion ".$Administracion."<br>";
echo "primera ".$primera." area ".$IdArea." estable ".$IdEstablecimiento."<br>";

    pg_close($connect);


if($nivel==1 or $nivel==2){

?>
<script language="javascript">
   window.location='Principal/index.php';
</script>

}
<?php
 }
?>

    </body>
</html>
