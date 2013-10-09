<?php  
if(!isset($_SESSION["ADM"])){
	  if($nivel==1){?>
	  <script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menu_.js">'+'</scr'+'ipt>');/*img src="MenuImages/Menu.gif" moduleid="Default (Project)\Menu_off.xws"*/</script>
<?php }elseif($nivel==4){?>
	  <script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menudigitador.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuConsultaExterna.gif" moduleid="MenuConExt (Project)\MenuConsultaExterna_off.xws"*/</script>
<?php }else{?>
	  <script webstyle4>document.write('<scr'+'ipt src="../../xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../MenuImages/menucoadmin.js">'+'</scr'+'ipt>');/*img src="MenuImages/MenuCoAdmin.gif" moduleid="MenuCoAdmin (Project)\MenuCoAdmin_off.xws"*/</script>
<?php }
  
}else{?>
<script webstyle4>document.write('<scr'+'ipt src="../../../ReporteAdmin/Menu/xaramenu.js">'+'</scr'+'ipt>');document.write('<scr'+'ipt src="../../../ReporteAdmin/Menu/cerrar_sesion.js">'+'</scr'+'ipt>');/*img src="Menu/Cerrar_Sesion.gif" moduleid="Direccion (Project)\Cerrar_Sesion_off.xws"*/</script>  	
<?php 
} ?>