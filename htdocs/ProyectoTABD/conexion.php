<?php
// Sustituir por los valores de su base de datos:
$usuario = 'extintor';
$clave = 'extintor';
$cadena = '//localhost:1521/XEPDB1';

$conn = oci_connect($usuario, $clave, $cadena, 'AL32UTF8');

if (!$conn)
{
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}
?>
