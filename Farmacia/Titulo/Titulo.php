<?php session_start();

function head(){
echo '<link href="../Menu/xm-style.css" type="text/css" rel="stylesheet"><script src="../Menu/xm-menu.js" type="text/javascript"></script>
<link href="../Themes/menu.css" type="text/css" rel="stylesheet">
<link rel="stylesheet" type="text/css" href="../Themes/Cobalt/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/Mailbox/Style.css">
<link rel="stylesheet" type="text/css" href="../Themes/estilo.css">';
}

function Java(){
echo '<script language="JavaScript" src="../funcionesJS/Common.js"></script>';
}

function Menu(){

if($_SESSION["nivel"]==1 or $_SESSION["nivel"]==2){

echo '

<center>
<table width="100%" border="0">
  <tr>
    <td width="15%" align="center" colspan="2"><img id="Image1" height="86" src="../imagenes/paisanitoPequeno.GIF" name="Image1"></td>
    <td width="70%"><div align="center" style="font-size:26px; font-family:Arial, Helvetica, sans-serif; color:#009;"><strong>MINISTERIO DE SALUD</strong></div>
<div align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;"><strong>Sistema Integral De Atencion al Paciente</strong></div>    
    </td>
    <td width="15%" align="center" colspan="2"><img id="Image2"  height="86" src="../imagenes/EscudoES.jpg" name="Image2"></td>
  </tr>
    <tr>
    <td width="15%" align="center" colspan="2"><input type="hidden" size="1" value="{usuario}" name="usuarioname" id="usuarioname"></td>
    <td width="70%"><div id="Estab" align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;">'.$_SESSION["NombreEstablecimiento"].'</div></td>
    <td width="15%" align="left"><div id="Usuario" style="font-family:Arial, Helvetica, sans-serif; color:#90C"><strong>'.$_SESSION["Login"].'</strong></div></td></tr>
  <tr>
   
    <td  colspan="4" align="center" style="background-color:#003"><div id="id_xm">
    </div><script type="text/javascript">new XmlMenu("id_xm", "../Menu/xm-data.xml")</script></td>
    
  </tr>
</table>
<center>';

}

//NIVEL DE TECNICO DE FARMACIA
if($_SESSION["nivel"]==3){

echo '
<center>
<table width="100%" border="0">
  <tr>
    <td width="15%" align="center" colspan="2"><img id="Image1"  height="86" src="../imagenes/paisanitoPequeno.GIF" name="Image1"></td>
    <td width="70%"><div align="center" style="font-size:26px; font-family:Arial, Helvetica, sans-serif; color:#009;"><strong>MINISTERIO DE SALUD</strong></div>
<div align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;"><strong>Sistema Integral De Atencion al Paciente</strong></div>    
    </td>
    <td width="15%" align="center" colspan="2"><img id="Image2"  height="86" src="../imagenes/EscudoES.jpg" name="Image2"></td>
  </tr>
    <tr>
    <td width="15%" align="center" colspan="2"><input type="hidden" size="1" value="{usuario}" name="usuarioname" id="usuarioname"></td>
    <td width="70%"><div id="Estab" align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;">'.$_SESSION["NombreEstablecimiento"].'</div></td>
    <td width="15%" align="left"><div id="Usuario" style="font-family:Arial, Helvetica, sans-serif; color:#90C"><strong>'.$_SESSION["Login"].'<br>'.$_SESSION["Area"].'</strong></div></td></tr>
  <tr>
    <td  colspan="4" align="center" style="background-color:#003"><div id="id_xm">
    </div><script type="text/javascript">new XmlMenu("id_xm", "../Menu/xm-data2.xml")</script></td>

  </tr>
</table>
<center>';

}

//NIVEL DE DIGITADOR DE FARMACIA
if($_SESSION["nivel"]==4){

echo '

<center>
<table width="100%" border="0">
  <tr>
    <td width="15%" align="center" colspan="2"><img id="Image1"  height="86" src="../imagenes/paisanitoPequeno.GIF" name="Image1"></td>
    <td width="70%"><div align="center" style="font-size:26px; font-family:Arial, Helvetica, sans-serif; color:#009;"><strong>MINISTERIO DE SALUD</strong></div>
<div align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;"><strong>Sistema Integral De Atencion al Paciente</strong></div>    
    </td>
    <td width="15%" align="center" colspan="2"><img id="Image2"  height="86" src="../imagenes/EscudoES.jpg" name="Image2"></td>
  </tr>
    <tr>
    <td width="15%" align="center" colspan="2"><input type="hidden" size="1" value="{usuario}" name="usuarioname" id="usuarioname"></td>
    <td width="70%"><div id="Estab" align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;">'.$_SESSION["NombreEstablecimiento"].'</div></td>
    <td width="15%" align="left"><div id="Usuario" style="font-family:Arial, Helvetica, sans-serif; color:#90C"><strong>'.$_SESSION["Login"].'</strong></div></td></tr>
  <tr>
    <td  colspan="4" align="center" style="background-color:#003"><div id="id_xm">
    </div><script type="text/javascript">new XmlMenu("id_xm", "../Menu/xm-data3.xml")</script></td>

  </tr>

</table>
<center>';

}


//NIVEL DE DIGITADOR DE FARMACIA
if($_SESSION["nivel"]==5){

echo '

<center>
<table width="100%" border="0">
  <tr>
    <td width="15%" align="center" colspan="2"><img id="Image1"  height="86" src="../imagenes/paisanitoPequeno.GIF" name="Image1"></td>
    <td width="70%"><div align="center" style="font-size:26px; font-family:Arial, Helvetica, sans-serif; color:#009;"><strong>MINISTERIO DE SALUD</strong></div>
<div align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;"><strong>Sistema Integral De Atencion al Paciente</strong></div>    
    </td>
    <td width="15%" align="center" colspan="2"><img id="Image2"  height="86" src="../imagenes/EscudoES.jpg" name="Image2"></td>
  </tr>
    <tr>
    <td width="15%" align="center" colspan="2"><input type="hidden" size="1" value="{usuario}" name="usuarioname" id="usuarioname"></td>
    <td width="70%"><div id="Estab" align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;">'.$_SESSION["NombreEstablecimiento"].'</div></td>
    <td width="15%" align="left"><div id="Usuario" style="font-family:Arial, Helvetica, sans-serif; color:#90C"><strong>'.$_SESSION["Login"].'</strong></div></td></tr>
  <tr>
    <td  colspan="4" align="center" style="background-color:#003"><div id="id_xm">
    </div><script type="text/javascript">new XmlMenu("id_xm", "../Menu/xm-data4.xml")</script></td>

  </tr>
</table>
<center>';

}



}
function Encabezado(){
echo '
<center>
<table width="100%" border="0">
  <tr>
    <td width="15%" align="center" colspan="2"><img id="Image1"  height="86" src="../imagenes/paisanitoPequeno.GIF" name="Image1"></td>
    <td width="70%"><div align="center" style="font-size:26px; font-family:Arial, Helvetica, sans-serif; color:#009;"><strong>MINISTERIO DE SALUD</strong></div>
<div align="center" style="font-size:22px; font-family:Arial, Helvetica, sans-serif; color: #069;"><strong>Sistema Integral De Atencion al Paciente</strong></div>    
    </td>
    <td width="15%" align="center" colspan="2"><img id="Image2"  height="86" src="../imagenes/EscudoES.jpg" name="Image2"></td>
  </tr>
  <tr>
    <td width="15%"colspan="2" style="background-color:#003">&nbsp;</td>
    <td width="70%" colspan="1" style="background-color:#003"><div id="id_xm">
    </div></td>
    <td width="15%"colspan="2" style="background-color:#003">&nbsp;</td>
  </tr>
</table>
<center>';

}

