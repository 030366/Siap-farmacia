<?php

session_start();

if (isset($_SESSION["nivel"])) {

    $IdArea = $_SESSION["IdArea"];
    require('IncludeFiles/RepetitivasClase.php');
    $query = new Repetitivas;
    conexion::conectar();
    /*     * VALORES POR POST* */

    $IdReceta = $_GET["IdReceta"];


//*******************************

    /* Si la receta estaba en proceso Bandera=P en la tabla se guarda la informacion
      y la bandera de la receta pasa a Lista (L) */

    mysql_query("update farm_recetas set IdEstado='RL' where IdReceta='$IdReceta'");

    conexion::desconectar();
} else {
    echo "ERROR_SESSION";
}
?>