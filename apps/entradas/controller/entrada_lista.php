<?php
use entradas\model\EntradaLista;

// INICIO Cabecera global de URL de controlador *********************************

require_once ("apps/core/global_header.inc");
// Arxivos requeridos por esta url **********************************************

// Crea los objectos de uso global **********************************************
require_once ("apps/core/global_object.inc");
// Crea los objectos para esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Qfiltro = (string) \filter_input(INPUT_POST, 'filtro');
$Qslide_mode = (string) \filter_input(INPUT_POST, 'slide_mode');

$oTabla = new EntradaLista();
$oTabla->setFiltro($Qfiltro);
$oTabla->setSlide_mode($Qslide_mode);

echo $oTabla->mostrarTabla();