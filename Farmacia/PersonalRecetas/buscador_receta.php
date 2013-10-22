<?php
require('include/ClasePersonalReceta.php');
conexion::conectar();
$IdPersonal = $_GET["IdPersonal"];
$NombrePersonal=$_GET["NombrePersonal"];
$query = Obtencion::ObtenerDatosRecetasLimit($IdPersonal," LIMIT 2");
if($test=mysql_fetch_array($query)){

require('include/conexion.php');
require('include/pagination.class2.php');


$items = 4;
$page2 = 1;

if(isset($_GET['page2']) and is_numeric($_GET['page2']) and $page2 = $_GET['page2']){
		$limit = " LIMIT ".(($page2-1)*$items).",$items";}
	else{
		$limit = " LIMIT $items";}

$query = Obtencion::ObtenerDatosRecetasLimit($IdPersonal,$limit);
$aux = Obtencion::ObtenerDatosRecetasTotal($IdPersonal);
?>
<style type="text/css">
<!--
#Layer111 {
	position:absolute;
	left:85px;
	top:8px;
	width:623px;
	height:21px;
	z-index:1;
}
-->
</style>

	<table width="990" height="96">
	<tr><td height="90"><div id="resultados2" align="center">
      <p>
        <?php
$aux=mysql_fetch_array($aux);
				echo $aux['total']." Recetas para el usuario seleccionado";
	?>
      </p>
	  <?php 
		if($aux['total']>0){
			$p = new pagination;
			$p->InicioPersonal($IdPersonal);
			$p->Items2($aux['total']);
			$p->limit2($items);
			if(isset($q))
					$p->target2("");
				else
					$p->target2("");
			$p->currentPage2($page2);
			$p->show2();
			echo "\t<table class=\"registros\">\n";
			echo "<tr class='titulos'><td colspan='8' style='background:#FFFF00'>Nombre de Empleado: <strong>$NombrePersonal</strong></td></tr>";
			echo "<tr class=\"titulos\"><td><strong>Estado</strong></td><td><strong>Receta #</strong></td><td align=\"center\"><strong>Nombre Paciente</strong></td><td><strong>Nombre de Medico</strong></td><td><strong>Repetitiva</strong></td><td><strong>Digitada</strong></td><td align='center'><strong>Fecha</strong></td><td align='center'><strong>Ver Detalle</strong></td></tr>\n";
			$r=0;
			while($row = mysql_fetch_assoc($query)){
$IdReceta=$row["IdReceta"];
$NumeroReceta=$row["NumeroReceta"];
$NombrePaciente=htmlentities($row["NombrePaciente"]);
$Medico=$row["NombreEmpleado"];
$IdEstado=$row["IdEstado"];
$Intro=$row["IdPersonalIntro"];
$Fecha=$row["Fecha"];
$resp=mysql_query("select concat_ws('-',day('$Fecha'),month('$Fecha'),year('$Fecha'))as fecha");
$Fechas=mysql_fetch_array($resp);
$Fecha2=$Fechas["fecha"];

//**************?>
<input type="hidden" value="<?php echo $NombrePaciente;?>" id="<?php echo "NombrePaciente".$IdReceta;?>" name="NombrePaciente">
<input type="hidden" value="<?php echo $NombrePersonal?>" id="Nombre" name="Nombre">
<?php
if($IdEstado=='ER' || $IdEstado=='RT'){$repeti="SI";}else{$repeti="NO";}
if($Intro!=NULL || $Intro!='' and $Intro==$IdPersonal){$digita="SI";}else{$digita="NO";}

 $NombreDiv="<div id=\"IMG".$IdReceta."\"><img src=\"Iconos/wainting.JPG\" /></div>";
	echo "\t\t<tr class=\"row$r\"><td>$NombreDiv</td><td align='center'><a href=\"#\">".$NumeroReceta."</a></td><td>".$NombrePaciente." </td><td> ".htmlentities($Medico)."</td><td align='center'>".$repeti."</td><td align='center'>".$digita."</td><td>".$Fecha2."</td><td align='center'><input type=\"button\" value=\"Ver Recetas\" onclick=\"DetalleReceta($IdReceta)\"> </td></tr>";
          if($r%2==0)++$r;else--$r;
        }
			echo "\t</table>\n";
			$p->show2();
		}//while
	?>
    </div>
</table>
	</div>
      <div id="Layer111"></div>
<?php 
conexion::desconectar();

}else{ echo "<h2>Este usuario no posee registros ....</h2>";}?>
