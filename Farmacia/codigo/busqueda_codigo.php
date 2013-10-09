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
 
}//Fin de IF nivel == 1

}//Fin de IF isset de Nivel ?>