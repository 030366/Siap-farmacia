<?php session_start();
if(!isset($_SESSION["nivel"])){?>
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

require('include/conexion.php');
require('include/funciones.php');
require('include/pagination.class.php');
$IdFarmacia=$_SESSION["IdFarmacia"];
$IdArea=$_SESSION["IdAreaFarmacia"];
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
?>	<p><?php
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
	}//IF
		 
          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}

}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel ?>