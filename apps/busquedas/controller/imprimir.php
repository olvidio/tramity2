<?php

use busquedas\model\ImprimirTabla;
use core\Converter;
use entradas\model\GestorEntrada;
use escritos\model\GestorEscrito;

// INICIO Cabecera global de URL de controlador *********************************

require_once("apps/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("apps/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

$Qopcion = (integer)\filter_input(INPUT_POST, 'opcion');
$Qmas = (integer)\filter_input(INPUT_POST, 'mas');
$Qfiltro = (string)\filter_input(INPUT_POST, 'filtro');

$filtro = empty($Qfiltro) ? 'en_buscar' : $Qfiltro;
$Qmas = '';
// buscar por periodo

$Qf_min = (string)\filter_input(INPUT_POST, 'f_min');
$Qf_max = (string)\filter_input(INPUT_POST, 'f_max');


$oConverter = new Converter('date', $Qf_min);
$f_min = $oConverter->toPg();
if (empty($Qf_max)) {
    $oHoy = new web\DateTimeLocal();
    $f_max = $oHoy->format("Y-m-d");
} else {
    $oConverter = new Converter('date', $Qf_max);
    $f_max = $oConverter->toPg(); //iso
}

// Entradas
$aWhere = [];
$aOperador = [];
if (!empty($f_min) && !empty($f_max)) {
    $aWhere ['f_entrada'] = "'$f_min','$f_max'";
    $aOperador ['f_entrada'] = 'BETWEEN';
} else {
    $aWhere['f_entrada'] = 'x';
    $aOperador['f_entrada'] = 'IS NOT NULL';
}
$gesEntradas = new GestorEntrada();
$cEntradas = $gesEntradas->getEntradasNumeradas($aWhere, $aOperador);
$aCollection['entradas'] = $cEntradas;
// EntradasBypass

// Escritos
$aWhere = [];
$aOperador = [];
if (!empty($f_min) && !empty($f_max)) {
    $aWhere ['f_salida'] = "'$f_min','$f_max'";
    $aOperador ['f_salida'] = 'BETWEEN';
} else {
    $aWhere['f_salida'] = 'x';
    $aOperador['f_salida'] = 'IS NOT NULL';
}
$gesEscritos = new GestorEscrito();
$cEscritos = $gesEscritos->getEscritosNumerados($aWhere, $aOperador);
$aCollection['escritos'] = $cEscritos;


$oImprimirTabla = new ImprimirTabla();
$oImprimirTabla->setKey('imprimir');
$oImprimirTabla->mostrarTabla($aCollection);
