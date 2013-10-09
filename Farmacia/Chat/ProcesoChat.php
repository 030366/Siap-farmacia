<?php session_start();
include("Conexion/Conexion.php");
//
if(isset($_GET["Enviar"]) and $_GET["Enviar"]=="si")
 {
  //
	$IdPersonal=$_GET['IdPersonal'];
	$IdPersonalD=$_GET['IdPersonalD'];

	$comentario=$_REQUEST["comentario"];

	$caracteres_prohibidos = array("'","/","<",">",";");    
        $comentario= str_replace($caracteres_prohibidos,"*",$comentario);

  $insert="insert into chat (comentario,fecha,IdPersonal,IdPersonalD,whosays) values('".htmlentities(utf8_decode($comentario))."',now(),'$IdPersonal','$IdPersonalD','$IdPersonal')";
	//$update="update chat set IdEstado='R' where whosays='$IdPersonalD' and IdEstado<>'X'";
	
  if(trim($_REQUEST["comentario"])!=NULL)
   {
    $insert=mysql_query($insert);
	//mysql_query($update);
   }
  exit();
 }

if(isset($_GET["Leer"]) and $_GET["Leer"]=="si")
 {
	$IdPersonal=$_GET['IdPersonal'];
	$IdPersonalD=$_GET['IdPersonalD'];

  header("Cache-Control: no-store, no-cache, must-revalidate");
  $select="select comentario,(select Nombre from farm_usuarios where IdPersonal=chat.whosays) as fromusuario, fecha, IdPersonal from chat where ((whosays='$IdPersonal' and IdPersonalD='$IdPersonalD') or (whosays='$IdPersonalD' and IdPersonalD='$IdPersonal')) and IdEstado <> 'X' order by id asc";

  $select=mysql_query($select);
	//echo "<table>";
  while($row = mysql_fetch_array($select))
   {
    if($row["comentario"]!=NULL)
     {
	
	if($row["IdPersonal"]!=$_SESSION["IdPersonal"]){
		$bgcolor="orange";
	}else{$bgcolor="#3EA99F";}
        echo "<span style='background-color:".$bgcolor.";'><strong><span style='font-size:x-small;background-color:".$bgcolor.";'>".htmlentities($row["fromusuario"])."</span> 
		</strong> - ".$row["comentario"]."</span><br>";
     }
   }
       
  exit();
 }

if(isset($_GET["Hash"]) and $_GET["Hash"]=="si")
 {

	$IdPersonal=$_GET['IdPersonal'];
	$IdPersonalD=$_GET['IdPersonalD'];

  header("Cache-Control: no-store, no-cache, must-revalidate");
  $max="select max(id) from chat";
  $max=mysql_query($max);
  $max=mysql_result($max,0,0);
  //
  $select="select * from chat where id=".$max." limit 1";
  $select=mysql_query($select);
  //
  $id=mysql_result($select,0,"id");
  $comentario=mysql_result($select,0,"comentario");
  $fecha=mysql_result($select,0,"fecha");
  //
  $hash=$id.$comentario.$fecha;
  if($hash==NULL)
   {
    echo "vacio";
   }
  else
   {
    $hash=md5($id.$comentario.$fecha);
    echo $hash;
   }
  exit();
 }


if(isset($_GET["Borrar"]) and $_GET["Borrar"]=="si")
 {
    header("Cache-Control: no-store, no-cache, must-revalidate");
  //
	$IdPersonal=$_GET['IdPersonal'];
	$IdPersonalD=$_GET['IdPersonalD'];
  $insert="update chat set IdEstado='X' where ((whosays='$IdPersonal' and IdPersonalD='$IdPersonalD') or (whosays='$IdPersonalD' and IdPersonalD='$IdPersonal'))";
	 mysql_query($insert);

  exit();
 }

if(isset($_GET["Leido"]) and $_GET["Leido"]=="si")
 {
       header("Cache-Control: no-store, no-cache, must-revalidate");
  //
	$IdPersonal=$_GET['IdPersonal'];
	$IdPersonalD=$_GET['IdPersonalD'];
  $update="update chat set IdEstado='R' where whosays='$IdPersonalD' and IdEstado<>'X'";
	 mysql_query($update);

  exit();
 }


if(isset($_GET["Nuevos"]) and $_GET["Nuevos"]=="si")
 {
        header("Cache-Control: no-store, no-cache, must-revalidate");
  //
	$IdPersonal=$_GET['IdPersonal'];
	$IdPersonalD=$_GET['IdPersonalD'];

	$select2="select comentario,(select Nombre from farm_usuarios where IdPersonal=chat.whosays) as fromusuario, fecha from chat where (whosays='$IdPersonalD' and IdPersonalD='$IdPersonal') and IdEstado = 'D'";

	$respNuevo=mysql_query($select2);
	if($rowNuevo=mysql_fetch_array($respNuevo)){
		$salida='S';
	}else{
		$salida='N';
	}
	echo $salida;
  exit();
 }

?> 