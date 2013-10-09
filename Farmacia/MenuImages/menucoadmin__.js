//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menu_.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".menu__menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#1e56a7;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".menu__plain, a.menu__plain:link, a.menu__plain:visited{text-align:left;background-color:#1e56a7;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menu__plain:hover, a.menu__plain:active{background-color:#92b9f1;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0x92b9f1;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menu__b1",".gif",22,175,loc+"../index.php","","Inicio",2,2,"menu__plain");
mainMenuItem("menu__b2",".gif",22,175,"javascript:;","","Reportes",2,2,"menu__plain");
mainMenuItem("menu__b3",".gif",22,175,"javascript:;","","Mantenimiento de Medicamento",2,2,"menu__plain");
//mainMenuItem("menu__b4",".gif",22,175,"javascript:;","","Mantenimiento de Usuarios",2,2,"menu__plain");
mainMenuItem("menu__b5",".gif",22,175,loc+"../des.php","","Cerrar Sesion",2,2,"menu__plain");
endMainMenu("",0,0);

startSubmenu("menu__b4","menu__menu",175);
submenuItem("Agregar Nuevo Usuario",loc+"../NewUser.php","","menu__plain");
submenuItem("Actualizar Ubicación de Usuario",loc+"../EdicionUsuarios/buscador.php","","menu__plain");
submenuItem("Control de Recetas por Usuario",loc+"../PersonalRecetas/area.php","","menu__plain");
endSubmenu("menu__b4");

startSubmenu("menu__b3","menu__menu",184);
submenuItem("Introducción de Nuevas Existencias",loc+"../ManttoExistencias/IntroExistencias/IntroExistencias2/existencia.php","","menu__plain");
submenuItem("Asignacion de Existencias",loc+"../ManttoExistencias/IntroExistencias/IntroExistencias/area.php","","menu__plain");
submenuItem("Introduccion de Medicamento",loc+"../IngresoMedicamento/IngresoMedicamentoPrincipal.php","","menu__plain");
endSubmenu("menu__b3");

startSubmenu("menu__b2","menu__menu",291);
submenuItem("Consumo de Medicamento por Grupo Terapeutico",loc+"../Reportes/ReporteGrupoTerapeutico/Rep_GrupoTerapeutico.php","","menu__plain");
submenuItem("Consumo de Medicamento por Grupo Terapeutico General",loc+"../Reportes/ReporteGrupoTerapeuticoGral/Rep_GrupoTerapeutico.php","","menu__plain");
submenuItem("Consumo de Medicamento por Servicio/Especialidad",loc+"../Reportes/ReportePorEspecialidad/Rep_Especialidad.php","","menu__plain");
submenuItem("Consumo de Medicamento por Especialidad General",loc+"../Reportes/ReportePorEspecialidadGral/Rep_Servicios.php","","menu__plain");
submenuItem("Reporte Por Servicios",loc+"../Reportes/ReportePorServicios/Rep_Servicios.php","","menu__plain");
submenuItem("Reporte Por Servicios General",loc+"../Reportes/ReportePorServiciosGral/Rep_Servicios.php","","menu__plain");
submenuItem("Reporte de Existencias",loc+"../Reportes/ReporteExistencias/Rep_Existencias.php","","menu__plain");
//submenuItem("Reporte de Transferencias",loc+"../ReporteTransferencias/ReporteTransferenciasPrincipal.php","","menu__plain");
endSubmenu("menu__b2");

loc="";
