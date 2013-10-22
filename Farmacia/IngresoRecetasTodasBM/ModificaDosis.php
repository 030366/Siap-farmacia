<html>
    <head>
        <script language="javascript">
            function Aplicar(IdMedicinaRecetada){
                var NuevaDosis = document.getElementById("NuevaDosis").value;
                window.opener.ActualizaDosis(IdMedicinaRecetada,NuevaDosis);
                window.close();
            }
        </script>
    </head>
    <body onLoad="javascript:document.getElementById('NuevaDosis').focus();">
        <?php
        $IdMedicinaRecetada = $_GET["IdMedicinaRecetada"];
        require('../Clases/class.php');
        conexion::conectar();
        $querySelect = "select Dosis from farm_medicinarecetada where IdMedicinaRecetada='$IdMedicinaRecetada'";
        $DosisOld = mysql_fetch_array(mysql_query($querySelect));
        ?>
        <table align="center">
            <tr><th>Nueva Dosis</th></tr>
            <tr><td><input type="text" id="NuevaDosis" name="NuevaDosis" size="30" value="<?php echo $DosisOld[0]; ?>" ></td></tr>
            <tr><td align="center"><input type="button" id="Aplicar" name="Aplicar" value="Cambiar Dosis" onClick="javascript:Aplicar(<?php echo $IdMedicinaRecetada; ?>);"></td></tr>
        </table>
    </body>
</html>
<?php conexion::desconectar(); ?>