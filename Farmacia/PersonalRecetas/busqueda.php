<?php
require('include/conexion.php');
require('include/funciones.php');
require('include/pagination.class.php');
require('include/variables.php');

$items = 10;
$page = 1;
$IdFarmacia=$_GET["farmacia"];
$IdArea=$_GET["area"];

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
					$p->target("buscador.php?q=".urlencode($q)."&farmacia=".urlencode($IdFarmacia)."&area=".urlencode($IdArea));
				else
					$p->target("buscador.php?farmacia=".urlencode($IdFarmacia)."&area=".urlencode($IdArea));
			$page=$page;
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
//*******************
$Nivel=variables::CambioNivel($Nivel);
$Farmacia=variables::CambioFarmacia($Farmacia);
$IdArea=variables::CambioArea($IdArea);

//*******************?>
<input type="hidden" id="<?php echo "Nombre".$IdPersonal;?>" name="<?php echo "Nombre".$IdPersonal;?>" value="<?php echo $Nombre;?>">
<?php
	if($row["Nivel"]!=1){
	echo "\t\t<tr class=\"row$r\"><td><a href=\"#\">".$row['nick']."</a></td><td>".$row['Nombre']." </td><td> ".$Nivel."</td><td>".$Farmacia."</td><td>".$IdArea."</td><td align='center'><input type=\"button\" value=\"Ver Recetas\" onclick=\"desplegar(".$row['IdPersonal'].")\"> </td></tr>";
}		 
          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show();
		}
	?>