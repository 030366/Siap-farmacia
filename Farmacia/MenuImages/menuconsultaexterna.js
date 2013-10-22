//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menuconsultaexterna.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".menuconsultaexterna_menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#1e56a7;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".menuconsultaexterna_plain, a.menuconsultaexterna_plain:link, a.menuconsultaexterna_plain:visited{text-align:left;background-color:#1e56a7;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menuconsultaexterna_plain:hover, a.menuconsultaexterna_plain:active{background-color:#92b9f1;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menuconsultaexterna_l:link, a.menuconsultaexterna_l:visited{text-align:left;background:#1e56a7 url("+loc+"menuconsultaexterna_l.gif) no-repeat right;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menuconsultaexterna_l:hover, a.menuconsultaexterna_l:active{background:#92b9f1 url("+loc+"menuconsultaexterna_l2.gif) no-repeat right;color: #000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:10pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0x92b9f1;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menuconsultaexterna_b1",".gif",20,161,loc+"../index2.php","","Inicio",2,2,"menuconsultaexterna_plain");
mainMenuItem("menuconsultaexterna_b2",".gif",20,161,"javascript:;","","Lectura y Preparacion de Recetas",2,2,"menuconsultaexterna_plain");
mainMenuItem("menuconsultaexterna_b3",".gif",20,161,"javascript:;","","Despacho de Recetas",2,2,"menuconsultaexterna_plain");
mainMenuItem("menuconsultaexterna_b4",".gif",20,161,"javascript:;","","Verificacion de Fechas",2,2,"menuconsultaexterna_plain");
mainMenuItem("menuconsultaexterna_b5",".gif",20,161,loc+"../ActualizarPassword/Actualizar.php","","Actualizar Password",2,2,"menuconsultaexterna_plain");
mainMenuItem("menuconsultaexterna_b6",".gif",20,161,loc+"../des.php","","Cerrar Sesion",2,2,"menuconsultaexterna_plain");
endMainMenu("",0,0);

startSubmenu("menuconsultaexterna_b4","menuconsultaexterna_menu",161);
submenuItem("Recetas Repetitivas",loc+"../ConsultaRecetas/ConsultaPrincipal.php","","menuconsultaexterna_plain");
endSubmenu("menuconsultaexterna_b4");

startSubmenu("menuconsultaexterna_b3_1","menuconsultaexterna_menu",129);
submenuItem("Recetas del Dia",loc+"../recetas/buscador_recetas.php","","menuconsultaexterna_plain");
submenuItem("Recetas Repetitivas",loc+"../recetas_repetitivas/buscador_recetas.php","","menuconsultaexterna_plain");
endSubmenu("menuconsultaexterna_b3_1");

startSubmenu("menuconsultaexterna_b3","menuconsultaexterna_menu",225);
mainMenuItem("menuconsultaexterna_b3_1","Recetas Listas",0,0,"javascript:;","","",1,1,"menuconsultaexterna_l");
//submenuItem("Recetas No Entregadas",loc+"../RegistroNoEntregadas/buscador_recetas.php","","menuconsultaexterna_plain");
submenuItem("Recetas Entregadas a Pacientes",loc+"../Lista_Recetas_Entregadas/buscador_recetas.php","","menuconsultaexterna_plain");
endSubmenu("menuconsultaexterna_b3");

startSubmenu("menuconsultaexterna_b2","menuconsultaexterna_menu",173);
submenuItem("Recetas del Dia",loc+"../Recetas_Dia_Lectura/recetas_all.php","","menuconsultaexterna_plain");
submenuItem("Recetas de Dias Anteriores",loc+"../Recetas_dia_Anterior/recetas_all.php","","menuconsultaexterna_plain");
submenuItem("Recetas Repetitivas",loc+"../Recetas_Lectura_Repetitiva/recetas_all.php","","menuconsultaexterna_plain");
endSubmenu("menuconsultaexterna_b2");

loc="";
