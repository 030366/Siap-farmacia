<?php session_start();
if(!isset($_SESSION["nivel"])){?>
<script language="javascript">
window.location='../signIn.php';
</script>
<?php
}else{
if($_SESSION["nivel"]!=1 and $_SESSION["nivel"]!=2 and !isset($_SESSION["IdArea"])){?>
<script language="javascript">
window.location='../index.php?Permiso=1';
</script>
<?php
}else{
$IdFarmacia2=$_SESSION["IdFarmacia2"];
$IdFarmacia=$_SESSION["IdFarmacia"];
$IdArea=$_SESSION["IdAreaFarmacia"];
$tipoUsuario=$_SESSION["tipo_usuario"];
$nombre=$_SESSION["nombre"];
$nivel=$_SESSION["nivel"];
$nick=$_SESSION["nick"];

require('../Clases/class.php');
require('include/conexion.php');
require('include/funciones.php');
require('include/pagination.class.php');
$conexion=new conexion;
$items = 12;
$page = 1;

if(isset($_GET['page']) and is_numeric($_GET['page']) and $page = $_GET['page'])
		$limit = " LIMIT ".(($page-1)*$items).",$items";
	else
		$limit = " LIMIT $items";

if(isset($_GET['q']) and !eregi('^ *$',$_GET['q'])){
		$q = sql_quote($_GET['q']); //para ejecutar consulta
		$busqueda = htmlentities($q); //para mostrar en pantalla
		
       $sqlStr =  "select farm_catalogoproductos.*,mnt_farmacia.IdFarmacia
					from farm_catalogoproductos
					inner join mnt_areamedicina
					on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=mnt_areamedicina.IdArea
					inner join mnt_farmacia
					on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
					WHERE NOMBRE LIKE '%$q%' and mnt_farmacia.IdFarmacia='$IdFarmacia' and mnt_areafarmacia.IdArea='$IdArea'";

       $sqlStrAux = "select count(*) as total
					from farm_catalogoproductos
					inner join mnt_areamedicina
					on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=mnt_areamedicina.IdArea
					inner join mnt_farmacia
					on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
					WHERE NOMBRE LIKE '%$q%' and mnt_farmacia.IdFarmacia='$IdFarmacia' and mnt_areafarmacia.IdArea='$IdArea'";

	}else{
		$sqlStr = "select farm_catalogoproductos.*,mnt_farmacia.IdFarmacia
					from farm_catalogoproductos
					inner join mnt_areamedicina
					on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=mnt_areamedicina.IdArea
					inner join mnt_farmacia
					on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
					WHERE mnt_farmacia.IdFarmacia='$IdFarmacia' and mnt_areafarmacia.IdArea='$IdArea'";
		$sqlStrAux = "select count(*) as total
					from farm_catalogoproductos
					inner join mnt_areamedicina
					on mnt_areamedicina.IdMedicina=farm_catalogoproductos.IdMedicina
					inner join mnt_areafarmacia
					on mnt_areafarmacia.IdArea=mnt_areamedicina.IdArea
					inner join mnt_farmacia
					on mnt_farmacia.IdFarmacia=mnt_areafarmacia.IdFarmacia
					WHERE mnt_farmacia.IdFarmacia='$IdFarmacia' and mnt_areafarmacia.IdArea='$IdArea'";
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
	left:60px;
	top:255px;
	width:826px;
	height:59px;
	z-index:1;
}

#resultados{
	position:absolute;
	left:65px;
	top:332px;
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
	left:25px;
	top:105px;
	width:955px;
	height:30px;
	z-index:2;
}
#nombre {position:absolute;
	left:628px;
	top:1px;
	width:227px;
	height:34px;
	z-index:5;
}
.style1 {color:#0000CC}
.style2 {color:#0000CC; font-size:11px; font-family:Arial, Helvetica, sans-serif}
#Layer3 {position:absolute;
	left:2px;
	top:190px;
	width:1001px;
	height:30px;
	z-index:6;
}
.style4 {font-size: 24px}
#Layer41 {position:absolute;
	left:-199px;
	top:-39px;
	width:55px;
	height:31px;
	z-index:7;
}
#Layer71 {position:absolute;
	left:303px;
	top:39px;
	width:596px;
	height:23px;
	z-index:5;
}
-->
</style>
<script language="javascript">
function inicio(){
document.form.q.focus();
}//inicio

</script>
</head>

<body onLoad="inicio()">
	
	<form name="form" action="buscadorArea.php" onSubmit="return buscar()">
      <div id="Layer1">
	 <table width="863">
	 <tr>
	   <td width="830" align="left"><strong><span class="style1">Busqueda de Medicina y Control de Existencias de la Farmacia:</span>  <span style="color:#B13F42"><?php 
	   $conexion->conectar();
	   $resp=mysql_query("select Farmacia from mnt_farmacia where IdFarmacia='$IdFarmacia'");
	   $resp2=mysql_query("select Area from mnt_areafarmacia where IdArea='$IdArea'");
	   $data=mysql_fetch_array($resp);
	   $data2=mysql_fetch_array($resp2);
	   $conexion->desconectar();
	   $farmacia=$data["Farmacia"];
	   $area=$data2["Area"];
	   $_SESSION["nombreFarmacia"]=$farmacia;
	   echo$farmacia." ".'<span style="color:#0000CC">Area:</span>'.$area;?></span></strong></td>
	 </tr>
	 <tr>
	 <td><span class="style1"><strong>Nombre:</strong></span> 
	 <input type="text" id="q" name="q" value="<?php if(isset($q)) echo $busqueda;?>" onKeyUp="return buscar()" style="border-bottom-color:#000099; border-top-color:#000099; border-left-color:#000099; border-right-color:#000099" size="50">
		
      &nbsp;&nbsp;<input type="button" value="Buscar" id="boton" onClick="return buscar()" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
	  &nbsp;&nbsp;<input type="button" value="Regresar" id="boton" onClick="javascript:window.location='<?php if($IdFarmacia2!=0 and $IdFarmacia2!=1){ echo "../index.php";}else{ echo "area.php";} ?>'" style="border-bottom-color:#000099; border-left-color:#000099; border-top-color:#000099; border-right-color:#000099">
      <span id="loading"></span></td>
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
					$p->target("buscadorArea.php?q=".urlencode($q));
				else
					$p->target("buscadorArea.php");
			$p->currentPage($page);
			$p->show();
			echo "\t<table class=\"registros\">\n";
			echo "<tr class=\"titulos\"><td>Titulo</td><td>PRECIO</td></tr>\n";
			$r=0;
			while($row = mysql_fetch_assoc($query)){
		if(isset($page)){
	echo "\t\t<tr class=\"row$r\"><td><a href=\"detalle_medicina.php?p={$row['IdMedicina']}&page=$page\" target=\"_self\">".htmlentities($row['Codigo'])."  --  ".htmlentities($row['Nombre'])."  --  ".htmlentities($row['Concentracion'])."  --  ".htmlentities($row['FormaFarmaceutica'])."</a></td>
<td><a href=\"detalle_medicina.php?p={$row['IdMedicina']}&page=$page\" target=\"_self\">"."$ ".htmlentities($row['PrecioActual'])."</a></td>
	
	</tr>\n";
	}//if

          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}
	?>
</div>
    <div class="style2" id="Layer6" align="center">
  <?php encabezado::top($IdFarmacia2,$tipoUsuario,$nick,$nombre);?></div>
    <div id="Layer3" align="center">
      <?php if($nivel==1){?>
<script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
      <?php }else{?>
<script webstyle4>document.write('<scr'+'ipt src="../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
      <?php }?>
    </div>
    <div id="Layer71">
      <div id="Layer41"><img src="../images/paisanito.jpg" alt="" width="195" height="94" /></div>
      <span class="style4">Ministerio de Salud P&uacute;blica y Asistencia Social </span></div>
</body>
</html>
<?php 
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel ?>
