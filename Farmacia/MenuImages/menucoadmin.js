//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menucoadmin.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");

document.write(".menucoadmin_menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#1e56a7;position:absolute;left:0px;top:0px;visibility:hidden;}");

document.write(".menucoadmin_plain, a.menucoadmin_plain:link, a.menucoadmin_plain:visited{text-align:left;background-color:#1e56a7;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");

document.write("a.menucoadmin_plain:hover, a.menucoadmin_plain:active{background-color:#92b9f1;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");

/*MENU PRINCIPAL CSS*/

document.write(".menu__Principal, a.menu__Principal:link, a.menu__Principal:visited{text-align:left;background-color:#3300FF;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;text-align:center;font-size:14px;}");

document.write("a.menu__Principal:hover, a.menu__plain:active{background-color:#000099;color:#FFFFF;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
/*******************************/

document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0x92b9f1;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menucoadmin_b1",".gif",22,175,loc+"../index.php","","Inicio",2,2,"menucoadmin_plain");
mainMenuItem("menucoadmin_b2",".gif",22,175,"javascript:;","","Reportes",2,2,"menucoadmin_plain");
mainMenuItem("menucoadmin_b3",".gif",22,175,"javascript:;","","Mantenimiento de Medicamento",2,2,"menucoadmin_plain");
mainMenuItem("menucoadmin_b4",".gif",22,175,loc+"../des.php","","Cerrar Sesion",2,2,"menucoadmin_plain");
endMainMenu("",0,0);

startSubmenu("menucoadmin_b3","menucoadmin_menu",184);
submenuItem("Introducción de Nuevas Existencias",loc+"../ManttoExistencias/IntroExistencias2/existencia.php","","menucoadmin_plain");
submenuItem("Asignacion de Existencias",loc+"../ManttoExistencias/IntroExistencias/area.php","","menucoadmin_plain");
submenuItem("Peticion de Medicamento a Almacen",loc+"../PeticionDeMedicamentos/Peticion.php","","menu__plain");
submenuItem("Introduccion de Medicamento",loc+"../IngresoMedicamento/IngresoMedicamentoPrincipal.php","","menucoadmin_plain");
endSubmenu("menucoadmin_b3");

startSubmenu("menucoadmin_b2","menucoadmin_menu",291);
submenuItem("Consumo de Medicamento por Grupo Terapeutico",loc+"../Reportes/ReporteGrupoTerapeutico/Rep_GrupoTerapeutico.php","","menucoadmin_plain");
submenuItem("Consumo de Medicamento por Grupo Terapeutico General",loc+"../Reportes/ReporteGrupoTerapeuticoGral/Rep_GrupoTerapeutico.php","","menucoadmin_plain");
submenuItem("Consumo de Medicamento por Medico",loc+"../Reportes/ReportePorMedico/Rep_Especialidad.php","","menucoadmin_plain");
submenuItem("Consumo de Medicamento por Especialidad",loc+"../Reportes/ReportePorEspecialidad/Rep_Servicios.php","","menucoadmin_plain");
submenuItem("Consumo de Medicamento por Especialidad General",loc+"../Reportes/ReportePorEspecialidadGral/Rep_Servicios.php","","menucoadmin_plain");
submenuItem("Reporte Por Servicios",loc+"../Reportes/ReportePorServicios/Rep_Servicios.php","","menucoadmin_plain");
submenuItem("Reporte Por Servicios General",loc+"../Reportes/ReportePorServiciosGral/Rep_Servicios.php","","menucoadmin_plain");
submenuItem("Reporte de Existencias",loc+"../Reportes/ReporteExistencias/Rep_Existencias.php","","menucoadmin_plain");
endSubmenu("menucoadmin_b2");

loc="";
