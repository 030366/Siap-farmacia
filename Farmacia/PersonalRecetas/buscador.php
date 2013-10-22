<?php include('../Titulo/Titulo.php');
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1){?>
<script language="javascript">
window.location='../Principal/index.php?Permiso=1';
</script>
<?php
}else{
$IdFarmacia2=0;
if($IdFarmacia2!=0){?>
<script language="javascript">window.location='estableceArea.php';</script>
<?php }else{
require('include/conexion.php');
require('include/funciones.php');
require('include/pagination.class.php');
require('include/variables.php');
require('../Clases/class.php');
conexion::conectar();
$IdFarmacia2=$_SESSION["IdFarmacia2"];
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];

$IdFarmacia=$_REQUEST["farmacia"];
$IdArea=$_REQUEST["area"];?>
<br><br>
<input type="hidden" id="IDFarmacia" value="<?php echo $IdFarmacia;?>"><br>
<input type="hidden" id="IDArea" value="<?php echo $IdArea;?>">
<?php $items = 10;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
		$limit = " LIMIT ".(($page-1)*$items).",$items";
	else
		$limit = " LIMIT $items";

if(isset($_GET['q']) and !eregi('^ *$',$_GET['q'])){
		$q = sql_quote($_GET['q']); //para ejecutar consulta
		$busqueda = htmlentities($q); //para mostrar en pantalla
if($IdArea==0){
		$sqlStr =  "select IdPersonal,nick,Nombre,Nivel,IdFarmacia,IdArea from farm_usuarios where Nombre LIKE '%$q%' and IdFarmacia='$IdFarmacia' and Nivel='3'";
		$sqlStrAux = "SELECT count(*) as total from farm_usuarios WHERE Nombre LIKE '%$q%' and IdFarmacia='$IdFarmacia' and Nivel='3'";}
else{
		$sqlStr =  "select IdPersonal,nick,Nombre,Nivel,IdFarmacia,IdArea from farm_usuarios where Nombre LIKE '%$q%' and IdArea='$IdArea' and Nivel='3'";
		$sqlStrAux = "SELECT count(*) as total from farm_usuarios WHERE Nombre LIKE '%$q%' and IdArea='$IdArea' and Nivel='3'";}

	}else{
	
if($IdArea==0){	
		$sqlStr = "SELECT IdPersonal,nick,Nombre,Nivel,IdFarmacia,IdArea from farm_usuarios where IdFarmacia='$IdFarmacia' and Nivel='3'";
		$sqlStrAux = "SELECT count(*) as total from farm_usuarios where IdFarmacia='$IdFarmacia' and Nivel='3'";}
else{
		$sqlStr = "SELECT IdPersonal,nick,Nombre,Nivel,IdFarmacia,IdArea from farm_usuarios where IdArea='$IdArea' and Nivel='3'";
		$sqlStrAux = "SELECT count(*) as total from farm_usuarios where IdArea='$IdArea' and Nivel='3'";

}
	}

$aux = Mysql_Fetch_Assoc(mysql_query($sqlStrAux,$link));
$query = mysql_query($sqlStr.$limit, $link);
?>
<html>
<head>
<title>...:::BUSQUEDA DE MEDICINA:::...</title>
<?php head(); ?>
<link rel="stylesheet" href="pagination.css" media="screen">
<link rel="stylesheet" href="style.css" media="screen">
<link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
<script src="include/buscador.js" type="text/javascript" language="javascript"></script>
<script language="javascript" src="desplegar.js"></script>
<script language="javascript" src="procesos/Filtro.js"></script>
<script src="desplegar.js"></script>

<script language="javascript">
function inicio(){
document.form.q.focus();
}//inicio
</script>
<script language="javascript" src="desplegar.js"></script>
<script language="javascript" src="procesos/Filtro.js"></script>
<script src="desplegar.js"></script>
</head>

<body onLoad="inicio()">
<?php Menu(); ?>
<br>
	<table width="910" height="93">
	<tr><td><div id="resultados" align="center">
      <p>
        <?php
		if($aux['total'] and isset($busqueda)){
				echo "{$aux['total']} Resultado".($aux['total']>1?'s':'')." que coinciden con tu b&uacute;squeda \"<strong>$busqueda</strong>\".";
			}elseif($aux['total'] and !isset($q)){
				//echo "Total de registros: {$aux['total']}";
			}elseif(!$aux['total'] and isset($q)){
				echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>$busqueda</strong>\"";
			}
	?>
      </p>
	  <?php 
		if($aux['total']>0){
			$p = new pagination;
			$p->Items($aux['total']);
			$p->limit($items);
			if(isset($q))
					$p->target("buscador.php?q=".urlencode($q)."&farmacia=".urlencode($IdFarmacia)."&area=".urlencode($IdArea));
				else
					$p->target("buscador.php?farmacia=".urlencode($IdFarmacia)."&area=".urlencode($IdArea));
			$p->currentPage($page);
			$p->show();
			echo "\t<table class=\"registros\">\n";
			echo "<tr class=\"titulos\"><td>Nick</td><td align=\"center\">Nombre</td><td>Nivel</td><td>Farmacia</td><td>Area</td><td align='center'>Editar Usuario</td></tr>\n";
			$r=0;
			while($row = mysql_fetch_assoc($query)){
$Nick=$row["nick"];
$Nombre=$row["Nombre"];
$Nivel=$row["Nivel"];
$Farmacia=$row["IdFarmacia"];
$IdArea=$row["IdArea"];
$IdPersonal=$row["IdPersonal"];
//**************
$Nivel=variables::CambioNivel($Nivel);
$Farmacia=variables::CambioFarmacia($Farmacia);
$IdArea=variables::CambioArea($IdArea);
//**************



		if($row["Nivel"]!=1){
	echo "\t\t<tr class=\"row$r\"><td><a href=\"#\">".$row['nick']."</a></td><td>".$row['Nombre']." </td><td> ".$Nivel."</td><td>".$Farmacia."</td><td>".$IdArea."</td><td align='center'><input type=\"button\" value=\"Ver Recetas\" onclick=\"desplegar(".$row['IdPersonal'].")\"> </td></tr>";
?>
<input type="hidden" id="<?php echo "Nombre".$IdPersonal;?>" name="<?php echo "Nombre".$IdPersonal;?>" value="<?php echo $Nombre;?>">
<?php
	}//if
          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}
	?>
    </div></td></tr>
	<tr><td><div id="Layer2"></div></td></tr>
	<tr><td><div id="DetalleRecetas"></div></td></tr>
	
	</table>

    <form name="form" action="buscador.php" onSubmit="return buscar()">
      
	 <table width="732">
       <tr>
         <td width="265" align="center"><strong>Busqueda deUsuarios:  </strong></td>
       </tr>
       <tr>
         <td>Nombre de Usuario:
           <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $busqueda;?>" onKeyUp="return buscar()" style="border-bottom-color:#000099; border-top-color:#000099; border-left-color:#000099; border-right-color:#000099" size="50">
           <input name="button" type="button" id="boton" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" value="Buscar">&nbsp; <input name="regresar" type="button" id="regresar" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099" value="Regresar" onClick="javascript:window.location='area.php'">
         <span id="loading"></span> </td>
       </tr>
     </table>
	 <label></label>
      
    </form>
    
   
</body>
</html>
<?php
conexion::desconectar();
}//Else $IdFarmacia!=0
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel
?>
