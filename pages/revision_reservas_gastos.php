<?php
session_start();
if (isset($_REQUEST["salir"])){
    session_destroy();
    header("location:".$_SERVER['PHP_SELF']);
}
?>
<?php 
require 'acciones_base.php';
require 'constantes.php';
$db=conectaBD();
?>
<?php

//Saca por pantalla el numero de reservas hechas
echo "<table>";
echo "<tr><th>NUMERO DE RESERVA</th><th>FECHA</th><th>NUMERO DE COMENSALES</th><th>TURNO</th><th>DNI DEL SOCIO</th><th>BORRAR</th></tr>";
listar_reservas($db);
echo "</table>";

//Saco por pantalla el numero de reservas
if(numero_reservas($db)==0){
    echo "No hay reservas hechas";
}elseif(numero_reservas($db)==1){
    echo "Hay ".numero_reservas($db)." reserva hecha</caption>";
}else{
    echo "Hay ".numero_reservas($db)." reservas hechas</caption>";
}



//Borrar una reserva
if (isset($_REQUEST["borrar"])){
    borrar_reserva($db, $_REQUEST["reser"]);
    header("Location:revision_reservas_gastos.php");
}

?>

<?php



//Saca por pantalla el numero de facturas hechas
echo "<table>";
echo "<tr><th>NUMERO DE FACTURA</th><th>FECHA</th><th>DNI</th><th>ID PRODUCTOS</th><th>PRECIO</th><th>CANTIDAD</th><th>BORRAR</th></tr>";
listar_facturas($db);
echo "</table>";

//Saco por pantalla el numero de facturas
if(numero_facturas($db)==0){
    echo "No hay facturas hechas";
}elseif(numero_facturas($db)==1){
    echo "Hay ".numero_facturas($db)." facturas</caption>";
}else{
    echo "Hay ".numero_facturas($db)." facturas</caption>";
}

//Borrar una facura
if (isset($_REQUEST["borrar"])){
    borrar_factura($db, $_REQUEST["fact"]);
    header("Location:revision_reservas_gastos.php");
}

?>
