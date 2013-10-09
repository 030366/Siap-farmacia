<?php 

function Conecta()
 {
  $nombreddbb="siap";
  if(!($link=mysql_connect("localhost","farma","farm4")))
   {
    echo "Error al conectar con la base de datos.";
    exit();
   }
  if(!mysql_select_db($nombreddbb,$link))
   {
    echo "Error al elegir la base de datos.";
    exit();
   }
   return $link;
 }
$link=Conecta();
?>