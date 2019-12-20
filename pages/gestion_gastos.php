<?php 
session_start();
if (isset($_REQUEST["salir"])){
    session_destroy();
    header("location:".$_SERVER['PHP_SELF']);
}
require 'acciones_base.php';
require 'constantes.php';
$db=conectaBD();
$mensaje='';
?>

<?php
echo "<form action='' method='post'>";
echo "<p><input type='date' name='fecha' placeholder='Fecha' required id='fecha'></p>";
echo "<p><input type='text' name='dni' placeholder='DNI' size='30' required id='dni'></p>";
echo "<select name='nombre'>";
listar_productos($db);
echo "</select>";
echo "<p><input type='number' name='cantidad' min='1' required id='cantidad'></p>";
echo "<p><input type='submit' name='factura' value='Hacer factura'></p>";
echo $mensaje;
echo "</form>";




if (isset($_REQUEST["factura"])){
    if (existe_dni($db, $_REQUEST["dni"])){
        meter_factura($db, "0", $_REQUEST["fecha"], $_REQUEST["dni"], $_REQUEST["nombre"], $_REQUEST["cantidad"]);
        $mensaje="<span>Gasto añadido de forma correcta</span>";
    }else{
        echo "El gasto lo debe introducir un miembro de esta asociacion"; 
    }
}
?>