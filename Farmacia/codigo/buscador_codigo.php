<?php session_start();
if(!isset($_SESSION["IdFarmacia2"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2){?>
<script language="javascript">
window.location='../index.php?Permiso=1';
</script>
<?php
}else{
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];
require('include/conexion.php');
require('include/funciones.php');
require('include/pagination.class.php');

$items = 12;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
		$limit = " LIMIT ".(($page-1)*$items).",$items";
	else
		$limit = " LIMIT $items";

if(isset($_GET['q']) and !eregi('^ *$',$_GET['q'])){
		$q = sql_quote($_GET['q']); //para ejecutar consulta
		$busqueda = htmlentities($q); //para mostrar en pantalla

       $sqlStr =  "select * from farm_catalogoproductos 
	               inner join mnt_hospitales
                   on farm_catalogoproductos.IdHospital=mnt_hospitales.IdHospital
	               WHERE Codigo LIKE '%$q%'";
       $sqlStrAux = "SELECT count(*) as total FROM farm_catalogoproductos 
             	     inner join mnt_hospitales
                     on farm_catalogoproductos.IdHospital=mnt_hospitales.IdHospital
	                 WHERE Codigo LIKE '%$q%'";

	}else{
		$sqlStr = "SELECT * FROM farm_catalogoproductos 
		           inner join mnt_hospitales
                   on farm_catalogoproductos.IdHospital=mnt_hospitales.IdHospital ";
		$sqlStrAux = "SELECT count(*) as total FROM farm_catalogoproductos 
		              inner join mnt_hospitales
                      on farm_catalogoproductos.IdHospital=mnt_hospitales.IdHospital";
	}

$aux = Mysql_Fetch_Assoc(mysql_query($sqlStrAux,$link));
$query = mysql_query($sqlStr.$limit, $link);
?>
<html>
<head>
<title>...:::BUSQUEDA DE MEDICINA:::...</title>
<link rel="stylesheet" href="pagination.css" media="screen">
<link rel="stylesheet" href="style.css" media="screen">
<script src="include/buscador.js" type="text/javascript" language="javascript"></script>
<style type="text/css">
<!--
#Layer1 {
	position:absolute;
	left:224px;
	top:65px;
	width:453px;
	height:40px;
	z-index:1;
}

#resultados{
	position:absolute;
	left:62px;
	top:130px;
	width:854px;
	height:119px;
	z-index:2;
}
#Layer2 {
	position:absolute;
	left:400px;
	top:564px;
	width:58px;
	height:31px;
	z-index:3;
}
#Layer6 {position:absolute;
	left:21px;
	top:12px;
	width:417px;
	height:34px;
	z-index:5;
}
-->
</style>
<script language="javascript">
function inicio(){
document.form.q.focus();
}//inicio

function codigo(){
window.location='../ManttoGrupoTerapeutico/buscador_terapeutico.php';
}
</script>
</head>

<body onLoad="inicio()">
	
	<form name="form" action="buscador_terapeutico.php" onSubmit="return buscar()">
      <div id="Layer1">
	 <table width="474">
	 <tr><td width="265" align="center"><strong>Mantenimiento Grupo Terapeutico</strong></td>
	 <td width="16" rowspan="2" align="justify"><a href="../index.php">Index</a></td>
	 </tr>
	 <tr>
	 <td><strong>C&oacute;digo:</strong> 
	   <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $busqueda;?>" onKeyUp="return buscar()">
		
      &nbsp;&nbsp;<input type="button" value="Busqueda por Nombre" id="boton" onClick="javascript:codigo()">
      <span id="loading"></span>	</td>
	 </tr>
	</table>
	  </div>
    </form>
    
    <div id="resultados" align="center">
	<p><?php
		if($aux['total'] and isset($busqueda)){
				echo "{$aux['total']} Resultado".($aux['total']>1?'s':'')." que coinciden con tu b&uacute;squeda \"<strong>$busqueda</strong>\".";
			}elseif($aux['total'] and !isset($q)){
				//echo "Total de registros: {$aux['total']}";
			}elseif(!$aux['total'] and isset($q)){
				echo"No hay registros que coincidan con tu b&uacute;squeda \"<strong>$busqueda</strong>\"";
			}
	?></p>

	<?php 
		if($aux['total']>0){
			$p = new pagination;
			$p->Items($aux['total']);
			$p->limit($items);
			if(isset($q))
					$p->target("buscador_codigo.php?q=".urlencode($q));
				else
					$p->target("buscador_codigo.php");
			$p->currentPage($page);
			$p->show();
			echo "\t<table class=\"registros\">\n";
			echo "<tr class=\"titulos\"><td>Titulo</td></tr>\n";
			$r=0;
			while($row = mysql_fetch_assoc($query)){
		if(isset($page)){
	echo "\t\t<tr class=\"row$r\"><td><a href=\"Muestra_medicina.php?p={$row['IdMedicina']}&page=$page\" target=\"_self\">".htmlentities($row['Codigo'])."  --  ".htmlentities($row['Nombre'])."  --  ".htmlentities($row['Concentracion'])."  --  ".htmlentities($row['FormaFarmaceutica'])."</a></td></tr>\n";
	}//if

          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}
	?>
</div>
<div id="Layer2">
<form action="../Mantto GrupoTerapeutico/NuevaMedicina.php" method="post" name="nuevo">
<input name="" type="submit" value="Introducir Nueva Medicina">
</form>
</div>
<div id="Layer6">
  <?php
echo"<strong>Nombre de Usuario:</strong>&nbsp;&nbsp; $nombre </br>
<strong>Tipo de Usuario:</strong>&nbsp;&nbsp;$tipoUsuario<br>
<strong>Nick:</strong>&nbsp;&nbsp;$nick<br>";
?>
  &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div>
</body>
</html>
<?php 
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel ?>
