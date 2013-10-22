<?php
session_start();
if (isset($_SESSION["IdPersonal"])) {
    $IdPersonalD = $_GET["IdPersonalD"];
    ?>
    <html xmlns="http://www.w3.org/1999/xhtml" xml:lang="es">
        <head>
            <meta http-equiv="cache-control" content="no-cache" />
            <title>MiniChat :)</title>
            <script type="text/javascript" src="IncludeFiles/ajax.js"></script>
        </head>
        <body onload="fajax();Nuevos();">

            <input type="hidden" id="id_hash" value="" />
            <input type="hidden" id="IdPersonal" value="<?php echo $_SESSION["IdPersonal"]; ?>" />
            <input type="hidden" id="IdPersonalD" value="<?php echo $IdPersonalD; ?>" />
            <table>
                <tr><TD>
                        <div id="chat" style='font-family:arial;font-size:14px;overflow:auto; width:345px; height:200px; '>
                        </div></TD></tr>
                <tr><TD><input type="textarea" id="comentario" size="34" onkeypress="return acceptNum(event,this.id)"/></TD></tr>
                <tr><TD align="right"><input type="button" value="Enviar" onclick="fajax();" />
                        <input type="button" value="Borrar Chat" onclick="fajaxClear()" /></TD></tr>
            </table>
        </body>
    </html> 
    <?php
} else {
    ?>
    <script type="text/javascript">
        alert('La sesion ha caducado \n Inicie sesion nuevamente');
        window.location="../signIn.php";
    </script>
    <?php
}
?>