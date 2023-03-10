<?php

use core\ViewTwig;
use usuarios\domain\repositories\CargoRepository;
use usuarios\domain\repositories\UsuarioRepository;

// INICIO Cabecera global de URL de controlador *********************************
require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// FIN de  Cabecera global de URL de controlador ********************************

$aWhere = ['id_oficina' => 0, '_ordre' => 'director DESC, cargo'];
$aOperador = ['id_oficina' => '>'];
$CargoRepository = new CargoRepository();
$cCargos = $CargoRepository->getCargos($aWhere, $aOperador);

$a_valores = [];
$UsuarioRepository = new UsuarioRepository();
$aUsuarios = $UsuarioRepository->getArrayUsuarios();
foreach ($cCargos as $oCargo) {
    $cargo = $oCargo->getCargo();
    $id_cargo = $oCargo->getId_cargo();
    $id_usuario = $oCargo->getId_usuario();
    $id_suplente = $oCargo->getId_suplente();
    $usuario = empty($aUsuarios[$id_usuario]) ? '' : $aUsuarios[$id_usuario];

    $oDesplSuplentes = $UsuarioRepository->getDesplUsuarios();
    $oDesplSuplentes->setNombre("id_suplente_$id_cargo");
    $oDesplSuplentes->setOpcion_sel($id_suplente);
    $oDesplSuplentes->setAction("fnjs_update_suplente($id_cargo)");

    $a_valor['cargo'] = $cargo;
    $a_valor['titular'] = $usuario;
    $a_valor['suplente'] = $oDesplSuplentes;

    $a_valores[] = $a_valor;
}


$a_campos = [
    'oPosicion' => $oPosicion,
    //'oHash' => $oHash,
    'a_valores' => $a_valores,
    /*
    'url_nuevo' => $url_nuevo,
    'url_form' => $url_form,
    'url_ajax' => $url_ajax,
    'url_actualizar' => $url_actualizar,
    */
];
$oView = new ViewTwig('usuarios/controller');
$oView->renderizar('suplente_lista.html.twig', $a_campos);

