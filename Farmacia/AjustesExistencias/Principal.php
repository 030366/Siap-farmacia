<?php include('../Titulo/Titulo.php');

if (!isset($_SESSION["nivel"])) { ?>
    <script language="javascript">
        window.location='../signIn.php';
    </script>
    <?php
} else {
    $nivel = $_SESSION["nivel"];
    if ($_SESSION["Datos"] != 1) {
        ?>
        <script language="javascript">
            window.location='../Principal/index.php?Permiso=1';
        </script>
        <?php
    } else {
        $tipoUsuario = $_SESSION["tipo_usuario"];
        $nombre = $_SESSION["nombre"];
        $nivel = $_SESSION["nivel"];
        $nick = $_SESSION["nick"];
        $IdFarmacia = $_SESSION["IdFarmacia2"];
        require('../Clases/class.php');
        $conexion = new conexion;

        $IdModalidad=$_SESSION["IdModalidad"];
        
//******Generacion del combo principal

        function generaSelect() { //creacioon de combo para las Regiones
            $conexion = new conexion;
            $conexion->conectar();
            $consulta = mysql_query("select * 
                                     from mnt_areafarmacia 
                                     inner join mnt_areafarmaciaxestablecimiento mafe
                                     on mafe.IdArea=mnt_areafarmacia.IdArea
                                     
                                     where mafe.IdArea <> 7 and mafe.IdArea <> 12 and mafe.Habilitado='S'
                                     and mafe.IdEstablecimiento=".$_SESSION["IdEstablecimiento"]." 
                                     and mafe.IdModalidad=".$_SESSION["IdModalidad"]);
            $conexion->desconectar();
            // Voy imprimiendo el primer select compuesto por los paises
            echo "<select name='IdArea' id='IdArea'>";
            echo "<option value='0'>[Seleccione Farmacia...]</option>";
            while ($registro = mysql_fetch_row($consulta)) {
                if ($registro[1] != "--") {
                    echo "<option value='" . $registro[0] . "'>" . $registro[1] . "</option>";
                }
            }
            echo "</select>";
        }

        function generaSelect2() { //creacioon de combo para las Regiones
            // Voy imprimiendo el primer select compuesto por los paises
            echo "<select name='IdAreaDestino' id='IdAreaDestino'>";
            echo "<option value='0'>[Seleccione Area Destino...]</option>";
            echo "</select>";
        }
        ?>
        <html>
            <head>
                <link rel="stylesheet" type="text/css" href="../default.css" media="screen" />
                <title>...:::Ajustes |--&gt;</title>
                <script language="javascript" src="IncludeFiles/IntroTransferencias.js"></script>
                <script language="javascript"  src="../ReportesArchives/calendar.js"> </script>
                <script type="text/javascript" src="IncludeFiles/FiltroEspecialidad.js"></script>
                <script language="JavaScript" src="../noCeros.js"></script>
                <script language="JavaScript" src="../trim.js"></script>

                <!-- AUTOCOMPLETAR -->
                <script type="text/javascript" src="scripts/prototype.js"></script>
                <script type="text/javascript" src="scripts/autocomplete.js"></script>
                <link rel="stylesheet" type="text/css" href="styles/autocomplete.css" />
                <!--  -->

        <?php head(); ?>

            </head>
            <body>
        <?php Menu(); ?>
                <br>

                <form action="" method="post" name="formulario">

                    <table width="90%" border="0">
                        <tr class="MYTABLE">
                            <td colspan="5" align="center"><strong>AJUSTES DE MEDICAMENTOS </strong></td>
                        </tr>
                        <tr><td colspan="5" class="FONDO"><br></td></tr>
                        <tr>
                            <td class="FONDO"><strong>Fecha del Ajuste: </strong></td>
                            <td colspan="4" class="FONDO"><input type="text" name="Fecha" id="Fecha" readonly="true" value="<?php echo date('Y-m-d'); ?>" onClick="scwShow (this, event);"/></td>
                        </tr>
                        <tr>
                            <td class="FONDO"><strong>Numero de Acta del Ajuste : </strong></td>
                            <td colspan="4" class="FONDO"><input type="text" name="Acta" id="Acta" size="10" onblur="trim(this.value,this.id);" onKeyUp="Mayuscula(this.value,this.id);"/></td>
                        </tr>
                        <tr>
                            <td class="FONDO"><strong>Farmacia: </strong></td>
                            <td colspan="4" class="FONDO"><?php generaSelect(); ?></td>
                        </tr>
                        <tr>
                            <td class="FONDO"><strong>Cantidad y Medicamento de Ajuste :</strong></td>
                            <td colspan="4" class="FONDO"><input type="hidden" id="IdMedicina" name="IdMedicina">
                                <input type="text" id="NombreMedicina" name="NombreMedicina" size="54" onFocus="VentanaBusqueda();"/><br>
                                <table>
                                    <tr><td>
                                            Cantidad: </td><td><input type="text" id="Cantidad" name="Cantidad" value="" size="5" onblur="NoCero(this.id);trim(this.value,this.id);"/>
                                            <span id='Descripcion'>[-]</span><input type="hidden" id="Divisor" name="Divisor"/><input type="hidden" id="UnidadesContenidas" name="UnidadesContenidas"/><br>
                                        </td>
                                    </tr><tr><td>
                                            <span id="ComboLotes" align="right"> Lote: </td><td><input id="IdLote" name="IdLote" onblur="trim(this.value,this.id);" onKeyUp="Mayuscula(this.value,this.id);"/></span><br/>
                                        </td>
                                    </tr><tr>
                                        <td>
                                            <span id="Precios" align="right"> Precio ($): </td><td><input id="Precio" name="Precio" size="5" onblur="NoCero(this.id);trim(this.value,this.id);"/></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Vencimiento :</td><td> <input type="hidden" id="FechaVencimiento" name="FechaVencimiento"/>

                                            <select id="mes" name="mes" onchange="GeneraFecha(this.id);">
                                                <option value="0">[Seleccione Mes]</option>
                                                <option value="1">ENERO</option>
                                                <option value="2">FEBRERO</option>
                                                <option value="3">MARZO</option>
                                                <option value="4">ABRIL</option>
                                                <option value="5">MAYO</option>
                                                <option value="6">JUNIO</option>
                                                <option value="7">JULIO</option>
                                                <option value="8">AGOSTO</option>
                                                <option value="9">SEPTIEMBRE</option>
                                                <option value="10">OCTUBRE</option>
                                                <option value="11">NOVIEMBRE</option>
                                                <option value="12">DICIEMBRE</option>
                                            </select>
                                            <select id="anio" name="anio" onchange="GeneraFecha(this.id);">
                                                <option value="0">[Seleccione A&ntilde;o]</option>
                                                <?php
                                                $date = date('Y');

                                                for ($i = 0; $i <= 12; $i++) {
                                                    $ano = $date + $i;
                                                    ?>
                                                    <option value="<?php echo $ano; ?>"><?php echo $ano; ?></option>
                                                <?php }//fin de for ?>
                                            </select>

                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td class="FONDO"><strong>Justificaci&oacute;n de Ajuste:</strong></td> 
                            <td class="FONDO">
                                <textarea id="Justificacion" name="Justificacion" cols="60" rows="5"></textarea>
                            </td>
                        </tr>
                        <tr>
                            <td class="FONDO">&nbsp;</td>
                            <td colspan="4" class="FONDO">
                                &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type="button" id="AddTrans" name="AddTrans" value="Guardar Ajuste" onClick="javascript:valida();">
                                <input type="button" id="Terminar" name="Terminar" value="Finalizar Ajuste(s)" onClick="javascript:FinalizarAjustes();">
                                <div id="IdReceta"></div></td>
                        </tr>
                        <tr><td colspan="5" class="FONDO"><div id='restante' align="center"></div></td></tr>
                        <tr>
                            <td colspan="5" class="FONDO"><div id="NuevaTransferencia" align="center"></div></td>
                        </tr>
                        <tr class="MYTABLE">
                            <td colspan="5" align="right">&nbsp;</td>
                        </tr>
                    </table>
                </form>

                <script>
                    new Autocomplete('NombreMedicina', function() { 
        		
                        return 'respuesta.php?q=' + this.value + '&IdArea='+$('IdArea').value; 
                    });
                </script>

            </body>
        </html>
        <?php
    }//Fin de IF nivel == 1
}//Fin de IF isset de Nivel
?>