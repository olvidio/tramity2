<?php

use tramites\domain\repositories\TramiteCargoRepository;
use tramites\domain\repositories\TramiteRepository;
use usuarios\domain\repositories\CargoRepository;

// INICIO Cabecera global de URL de controlador *********************************
require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");

// FIN de  Cabecera global de URL de controlador ********************************


$Q_mod = (string)filter_input(INPUT_POST, 'mod');
$Q_id_item = (integer)filter_input(INPUT_POST, 'id_item');
$Q_id_tramite = (integer)filter_input(INPUT_POST, 'id_tramite');

$TramiteRepository = new TramiteRepository();
$oTramite = $TramiteRepository->findById($Q_id_tramite);
$tramite = $oTramite->getTramite();

$CargoRepository = new CargoRepository();
$oDesplCargos = $CargoRepository->getDesplCargos();
$oDesplCargos->setNombre('id_cargo');
$oDesplCargos->setBlanco(true);
// para el form
if ($Q_mod === 'editar') {
    $TramiteCargoRepository = new TramiteCargoRepository();
    $oTramiteCargo = $TramiteCargoRepository->findById($Q_id_item);

    $orden_tramite = $oTramiteCargo->getOrden_tramite();
    $id_cargo = $oTramiteCargo->getId_cargo();
    $oDesplCargos->setOpcion_sel($id_cargo);
    $multiple = $oTramiteCargo->getMultiple();
}
if ($Q_mod === 'nuevo') {
    $orden_tramite = 0;
    $multiple = 1;
}

$url_ajax = "src/tramites/controller/tramitecargo_ajax.php";


$oHash = new web\Hash();
$oHash->setCamposForm('dep_num!id_fase!id_fase_previa!id_tarea!id_tarea_previa!mensaje_requisito!id_of_responsable!status');
$oHash->setCamposNo('que!id_fase_previa[]!id_tarea_previa[]!mensaje_requisito[]');
$oHash->setCamposChk('id_tarea_previa');
$a_camposHidden = [
    'que' => 'update',
    'id_item' => $Q_id_item,
    'id_tramite' => $Q_id_tramite,
];
$oHash->setArraycamposHidden($a_camposHidden);


$a_campos = ['oPosicion' => $oPosicion,
    'oHash' => $oHash,
    'url_ajax' => $url_ajax,
    'oDesplCargos' => $oDesplCargos,
    'tramite' => $tramite,
    'orden_tramite' => $orden_tramite,
    'multiple' => $multiple,
];

$oView = new core\ViewTwig('tramites/controller');
$oView->renderizar('tramitecargo_form.html.twig', $a_campos);