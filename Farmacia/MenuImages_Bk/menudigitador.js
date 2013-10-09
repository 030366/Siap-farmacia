//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menudigitador.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".menudigitador_menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#1e56a7;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".menudigitador_plain, a.menudigitador_plain:link, a.menudigitador_plain:visited{text-align:left;background-color:#1e56a7;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menudigitador_plain:hover, a.menudigitador_plain:active{background-color:#92b9f1;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menudigitador_l:link, a.menudigitador_l:visited{text-align:left;background:#1e56a7 url("+loc+"menudigitador_l.gif) no-repeat right;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menudigitador_l:hover, a.menudigitador_l:active{background:#92b9f1 url("+loc+"menudigitador_l2.gif) no-repeat right;color: #000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:9pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0x92b9f1;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menudigitador_b1",".gif",22,132,loc+"../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php","","Introduccion de Recetas",2,2,"menudigitador_plain");
mainMenuItem("menudigitador_b2",".gif",22,132,"javascript:;","","Reportes",2,2,"menudigitador_plain");
mainMenuItem("menudigitador_b3",".gif",22,132,"javascript:;","","Busqueda de Recetas",2,2,"menudigitador_plain");
mainMenuItem("menudigitador_b4",".gif",22,132,"javascript:;","","Mantenimientos",2,2,"menudigitador_plain");
mainMenuItem("menudigitador_b5",".gif",22,132,loc+"../ActualizarPassword/Actualizar.php","","Actualizar Password",2,2,"menudigitador_plain");
mainMenuItem("menudigitador_b6",".gif",22,132,loc+"../des.php","","Cerrar Sesion",2,2,"menudigitador_plain");
endMainMenu("",0,0);

startSubmenu("menudigitador_b4_9","menudigitador_menu",96);
submenuItem("Cierre Mensual",loc+"../Cierre/CierreMes.php","","menudigitador_plain");
submenuItem("Cierre Anual",loc+"../Cierre/Cierre.php","","menudigitador_plain");
endSubmenu("menudigitador_b4_9");

startSubmenu("menudigitador_b4","menudigitador_menu",312);
submenuItem("Actualizacion Codigos de Medicos",loc+"../CodigoFarmaciaUpdate/ActualizacionPrincipal.php","","menudigitador_plain");
submenuItem("Actualizacion Codigos de Especialidades/Servicios",loc+"../CodigoFarmaciaEspecialidadesUpdate/ActualizacionPrincipal.php","","menudigitador_plain");
submenuItem("Ingreso de Medicamentos",loc+"../IngresoMedicamento/IngresoMedicamentoPrincipal.php","","menudigitador_plain");
submenuItem("Ingreso de Medicos/Enfermeras",loc+"../IngresoEmpleados/IngresoEmpleados.php","","menudigitador_plain");
submenuItem("Ingreso de Servicios",loc+"../IngresoServicios/IngresoServicios.php","","menudigitador_plain");
submenuItem("Actualizacion de Precios",loc+"../ActualizacionPrecios/ActualizacionPrecios.php","","menudigitador_plain");
submenuItem("Actualizacion Estado de Medicamentos",loc+"../ActualizaEstadoMedicina/ActualizacionPrincipal.php","","menudigitador_plain");
submenuItem("Actualizacion de Informacion de Medicamentos",loc+"../ActualizacionMedicina/ActualizacionMedicina.php","","menudigitador_plain");
mainMenuItem("menudigitador_b4_9","Cierre de Operaciones",0,0,"javascript:;","","",1,1,"menudigitador_l");
endSubmenu("menudigitador_b4");

startSubmenu("menudigitador_b3","menudigitador_menu",173);
submenuItem("Por Numero de Receta",loc+"../IngresoRecetasTodas/BusquedaRecetas/BusquedaRecetas.php","","menudigitador_plain");
submenuItem("Por Nombre de Medicamento",loc+"../IngresoRecetasTodas/BusquedaRecetasMedicina/BusquedaRecetas.php","","menudigitador_plain");
endSubmenu("menudigitador_b3");

startSubmenu("menudigitador_b2","menudigitador_menu",257);
submenuItem("Consumo de Medicamento por Farmacias",loc+"../Reportes/ReporteFarmacias/Rep_Farmacias.php","","menudigitador_plain");
submenuItem("Reporte General",loc+"../Reportes/ReporteGeneral/Rep_General.php","","menudigitador_plain");
submenuItem("Consumo de Medicamento por Areas",loc+"../Reportes/ReporteGrupoTerapeuticoGral/Rep_GrupoTerapeutico.php","","menudigitador_plain");
submenuItem("Consumo de Medicamento por Medico",loc+"../Reportes/ReportePorMedico/Rep_Especialidad.php","","menudigitador_plain");
submenuItem("Consumo de Medicamento por Especialidad",loc+"../Reportes/ReportePorEspecialidadGral/Rep_Servicios.php","","menudigitador_plain");
submenuItem("Reporte por Servicios General",loc+"../Reportes/ReportePorServiciosGral/Rep_Servicios.php","","menudigitador_plain");
endSubmenu("menudigitador_b2");

loc="";
