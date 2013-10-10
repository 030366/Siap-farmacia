<?php  session_start();?>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:330px;
	top:154px;
	width:422px;
	height:117px;
	z-index:1;
}
-->
</style>
<div id="Layer1"><?php 
echo'<link rel="stylesheet" type="text/css" href="default.css" media="screen" />';
echo '<h1><img src="images/carga.gif" />INICIANDO SESION</h1>';
?></div>
<?php
include 'conexion2.php';
$usuario=$_REQUEST["usuario"];
$contra=$_REQUEST["contra"];
$contra=md5($contra);
$cnx=conectar();
if (!$cnx) {
  echo "Ocurrio un error.\n";
  exit;
}
$result3 = pg_query($cnx,"SELECT * FROM farm_usuarios where nick='$usuario' and password='$contra'");

if($row = pg_fetch_array($result3, null, PGSQL_ASSOC)){
	 			 		$id=$row["idpersonal"];         
						$nombre=$row["nombre"];
						$nick=$row["nick"];
						$farmacia=$row["idfarmacia"];
						$nivel=$row["nivel"]; 
						$datos=$row["datos"];
						$reporte=$row["reportes"];
						$Administracion=$row["administracion"];
						$primera=$row["primeravez"];
						$IdArea=$row["idarea"];
						$IdEstadoCuenta=$row["idestadocuenta"];
						$IdEstablecimiento=$row["idestablecimiento"];
                        $IdModalidad=$row["idmodalidad"];
					    //$NombreFarmacia=$row["Farmacia"];


//$resp=pg_query("select Farmacia from mnt_farmacia where IdFarmacia='$farmacia'");
echo $nombre;
$query="select Nombre,IdTipoFarmacia,TipoExpediente from mnt_establecimiento 
        inner join mnt_modalidadxestablecimiento 
        on mnt_modalidadxestablecimiento.IdEstablecimiento=mnt_establecimiento.IdEstablecimiento 
        where mnt_establecimiento.IdEstablecimiento=".$IdEstablecimiento;
$query2="select Area from mnt_areafarmacia
        inner join mnt_areafarmaciaxestablecimiento 
        on mnt_areafarmaciaxestablecimiento.IdArea=mnt_areafarmacia.IdArea
        where mnt_areafarmaciaxestablecimiento.IdArea=$IdArea and IdEstablecimiento=$IdEstablecimiento and IdModalidad=$IdModalidad";

$NombreEstablecimiento1=pg_fetch_array(pg_query($query));
$Area=pg_fetch_array(pg_query($query2));

pg_query("update farm_usuarios set Conectado='S' where IdPersonal=".$id);
pg_query("update farm_usuarios set UltimaConexion=now() where IdPersonal=".$id);

	$NombreEstablecimiento=$NombreEstablecimiento1["nombre"];
	$IdTipoFarmacia=$NombreEstablecimiento1["idtipofarmacia"];
	$TipoExpediente=$NombreEstablecimiento1["tipoexpediente"];
	

		

	$Area=$Area[0];
if($IdEstadoCuenta=='D'){?>
	<script language="javascript">
	window.location='des.php?Cuenta=1';
	</script>
<?php }

//$row=pg_fetch_array($resp);
$_SESSION["farmacia"]='';

if($nivel==1){
//***** nivel de administrador ********
$_SESSION["IdFarmacia2"]=0;
}else{
$_SESSION["IdFarmacia2"]=$farmacia;
}

$_SESSION["IdArea"]=$IdArea;
//PERMISOS DE USUARIO*********************
//$_SESSION["count"]=1;
$_SESSION["Datos"]=$datos;
$_SESSION["Reportes"]=$reporte;
$_SESSION["Administracion"]=$Administracion;

//******ESTABLECIMIENTO EN EL QUE LABORA
$_SESSION["IdEstablecimiento"]=$IdEstablecimiento;
$_SESSION["NombreEstablecimiento"]=$NombreEstablecimiento;
$_SESSION["TipoFarmacia"]=$IdTipoFarmacia;
$_SESSION["TipoExpediente"]=$TipoExpediente;
$_SESSION["Area"]=$Area;
$_SESSION["IdModalidad"]=$IdModalidad;

//***************************************
$_SESSION["ID"]=$id;
$_SESSION["IdPersonal"]=$id;
$_SESSION["nick"]=$nick;
$_SESSION["Login"]=$nick;


//****************************************

//Cuando el personal es de despacho
$_SESSION["conteo"]=0;
$_SESSION["conteoAux"]=0;
//*****************************************

$_SESSION["nivel"]=$nivel;//obtencion de nivel de seguridad
$_SESSION["Nivel"]=$nivel;
//Obtencion de primera vez
$_SESSION["primera"]=$primera;
//tipos de usuarios


if($nivel=='1'){
$_SESSION["tipo_usuario"]="Administrador";
}
if($nivel=='2'){
$_SESSION["tipo_usuario"]="Co-Administrador";
}
if($nivel=='3'){
$_SESSION["tipo_usuario"]="Personal Farmacia";
}
if($nivel=='4'){
$_SESSION["tipo_usuario"]="Personal Farmacia";
}
if($nivel=='5'){
$_SESSION["tipo_usuario"]="Bodega";
}

//tipos de usuarios
$_SESSION["nombre"]=$nombre;


/*QUERY PARA DETERMINAR SI ESTE DIA YA SE HISO LA COMPROBACION DE EXISTENCIAS*/
//$FechaModificacion=pg_fetch_array(pg_query($cnx,"select FechaModifica from farm_modificavirtual where IdAreaModifica='$IdArea' and FechaModifica=curdate()"));

//if($FechaModificacion[0]==NULL and $nivel=='3'){
//$queryModifica="update farm_modificavirtual set FechaModifica=curdate() where IdAreaModifica='$IdArea'";
//pg_query($cnx,$queryModifica);
desconectar();
?>
<script language="javascript">
//window.location='ExistenciaVirtual/ExistenciaVirtualPrincipal.php'
</script>
<?php //}//fin de if IdAreas
/*FIN DE CUERPO DE MANEJO DE EXISTENCIA VIRTUAL*/

/*
	JUEGO DE NIVELES
   1 Y 2: ADMINISTRADO Y CO-ADMINISTRADOR
       3: TECNICO DE FARMACIA [VENTANILLAS]
       4: CENTRO DE COMPUTO [DIGITADORES DE FARMACIA]


*/

if($nivel==1 or $nivel==2){

?>
<script language="javascript">
   window.location='Principal/index.php';
</script>
<?php
}

if($nivel==3){ ?>
<script language="javascript">
    window.location='Principal/index2.php';
</script>
<?php 
}



if($nivel==4){ ?>
<script language="javascript">
    window.location='IngresoRecetasTodas/IntroduccionRecetasPrincipal.php';
</script>
<?php
}

if($nivel==5){ ?>
<script language="javascript">
     window.location='Principal/index.php';
</script>
<?php
}


} //if si Existen Datos
else{

?>
 <script LANGUAGE="JavaScript">
 window.location="signIn.php?bandera=1"
  </script>
<?php
}

?>
