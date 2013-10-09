//©Xara Ltd
if(typeof(loc)=="undefined"||loc==""){var loc="";if(document.body&&document.body.innerHTML){var tt=document.body.innerHTML;var ml=tt.match(/["']([^'"]*)menu_.js["']/i);if(ml && ml.length > 1) loc=ml[1];}}

var bd=0
document.write("<style type=\"text/css\">");
document.write("\n<!--\n");
document.write(".menu__menu {z-index:999;border-color:#000000;border-style:solid;border-width:"+bd+"px 0px "+bd+"px 0px;background-color:#1e56a7;position:absolute;left:0px;top:0px;visibility:hidden;}");
document.write(".menu__plain, a.menu__plain:link, a.menu__plain:visited{text-align:left;background-color:#1e56a7;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menu__plain:hover, a.menu__plain:active{background-color:#92b9f1;color:#000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menu__l:link, a.menu__l:visited{text-align:left;background:#1e56a7 url("+loc+"menu__l.gif) no-repeat right;color:#ffffff;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("a.menu__l:hover, a.menu__l:active{background:#92b9f1 url("+loc+"menu__l2.gif) no-repeat right;color: #000000;text-decoration:none;border-color:#000000;border-style:solid;border-width:0px "+bd+"px 0px "+bd+"px;padding:2px 0px 2px 0px;cursor:hand;display:block;font-size:8pt;font-family:Arial, Helvetica, sans-serif;}");
document.write("\n-->\n");
document.write("</style>");

var fc=0x000000;
var bc=0x92b9f1;
if(typeof(frames)=="undefined"){var frames=0;}

startMainMenu("",0,0,2,0,0)
mainMenuItem("menu__b1",".gif",22,175,loc+"../index.php","","Inicio",2,2,"menu__plain");
mainMenuItem("menu__b2",".gif",22,175,"javascript:;","","Reportes",2,2,"menu__plain");
mainMenuItem("menu__b3",".gif",22,175,"javascript:;","","Mantenimiento de Medicamento",2,2,"menu__plain");
mainMenuItem("menu__b4",".gif",22,175,"javascript:;","","Mantenimiento de Sistema",2,2,"menu__plain");
mainMenuItem("menu__b5",".gif",22,175,"javascript:;","","Mantenimiento de Usuarios",2,2,"menu__plain");
mainMenuItem("menu__b6",".gif",22,175,loc+"../des.php","","Cerrar Sesion",2,2,"menu__plain");
endMainMenu("",0,0);

startSubmenu("menu__b5","menu__menu",175);
submenuItem("Agregar Nuevo Usuario",loc+"../NewUser.php","","menu__plain");
submenuItem("Actualizar Ubicación de Usuario",loc+"../EdicionUsuarios/buscador.php","","menu__plain");
submenuItem("Control de Recetas por Usuario",loc+"../PersonalRecetas/area.php","","menu__plain");
submenuItem("Actualizar Password",loc+"../ActualizarPassword/Actualizar.php","","menu__plain");
endSubmenu("menu__b5");

startSubmenu("menu__b4_7","menu__menu",84);
submenuItem("Cierre Mensual",loc+"../Cierre/CierreMes.php","","menu__plain");
submenuItem("Cierre Anual",loc+"../Cierre/Cierre.php","","menu__plain");
endSubmenu("menu__b4_7");

startSubmenu("menu__b4_6","menu__menu",149);
submenuItem("Por Numero de Receta",loc+"../IngresoRecetasTodas/BusquedaRecetas/BusquedaRecetas.php","","menu__plain");
submenuItem("Por Nombre de Medicamento",loc+"../IngresoRecetasTodas/BusquedaRecetasMedicina/BusquedaRecetas.php","","menu__plain");
endSubmenu("menu__b4_6");

startSubmenu("menu__b4","menu__menu",255);
submenuItem("Ingreso de Medicos/Enfermeras",loc+"../IngresoEmpleados/IngresoEmpleados.php","","menu__plain");
submenuItem("Ingreso de Servicios",loc+"../IngresoServicios/IngresoServicios.php","","menu__plain");
submenuItem("Actualizar Codigo de Medicos",loc+"../CodigoFarmaciaUpdate/ActualizacionPrincipal.php","","menu__plain");
submenuItem("Actualizar Codigo de Especialidades/Servicios",loc+"../CodigoFarmaciaEspecialidadesUpdate/ActualizacionPrincipal.php","","menu__plain");
submenuItem("Ingreso de Recetas",loc+"../IngresoRecetasTodas/IntroduccionRecetasPrincipal.php","","menu__plain");
mainMenuItem("menu__b4_6","Busqueda de Recetas",0,0,"javascript:;","","",1,1,"menu__l");
mainMenuItem("menu__b4_7","Cierre de Operaciones",0,0,"javascript:;","","",1,1,"menu__l");
endSubmenu("menu__b4");

startSubmenu("menu__b3","menu__menu",238);
submenuItem("Introduccion de Existencias",loc+"../ManttoExistencias/IntroExistencias2/existencia.php","","menu__plain");
submenuItem("Asignacion de Existencias a Areas",loc+"../ManttoExistencias/IntroExistencias/area.php","","menu__plain");
submenuItem("Introduccion de Medicamento",loc+"../IngresoMedicamento/IngresoMedicamentoPrincipal.php","","menu__plain");
submenuItem("Actualizacion de Precios",loc+"../ActualizacionPrecios/ActualizacionPrecios.php","","menu__plain");
submenuItem("Actualizacion de Informacion de Medicamentos",loc+"../ActualizacionMedicina/ActualizacionMedicina.php","","menu__plain");
endSubmenu("menu__b3");

startSubmenu("menu__b2","menu__menu",262);
submenuItem("Consumo de Medicamento por Farmacias",loc+"../Reportes/ReporteFarmacias/Rep_Farmacias.php","","menu__plain");
submenuItem("Reporte General",loc+"../Reportes/ReporteGeneral/Rep_General.php","","menu__plain");
submenuItem("Consumo de Medicamento por Areas",loc+"../Reportes/ReporteGrupoTerapeuticoGral/Rep_GrupoTerapeutico.php","","menu__plain");
submenuItem("Consumo de Medicamento por Medico",loc+"../Reportes/ReportePorMedico/Rep_Especialidad.php","","menu__plain");
submenuItem("Consumo de Medicamento por Especialidad/Servicio",loc+"../Reportes/ReportePorEspecialidadGral/Rep_Servicios.php","","menu__plain");
submenuItem("Reporte de Servicios General",loc+"../Reportes/ReportePorServiciosGral/Rep_Servicios.php","","menu__plain");
endSubmenu("menu__b2");

loc="";
