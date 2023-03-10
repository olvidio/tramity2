<?php

use core\ViewTwig;
use entradas\domain\entity\Entrada;
use entradas\domain\entity\EntradaRepository;
use entradas\domain\repositories\EntradaCompartidaRepository;
use etherpad\model\Etherpad;
use usuarios\domain\repositories\EntradaCompartidaAdjuntoRepository;
use function core\is_true;

// INICIO Cabecera global de URL de controlador *********************************

require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos para esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

// porque también se puede abrir en una ventana nueva, y entonces se llama por GET
$Qmethod = (string)filter_input(INPUT_SERVER, 'REQUEST_METHOD');
if ($Qmethod === 'POST') {
    $Qid_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
    $Qcompartida = (string)filter_input(INPUT_POST, 'compartida');
}
if ($Qmethod === 'GET') {
    $Qid_entrada = (integer)filter_input(INPUT_GET, 'id_entrada');
    $Qcompartida = (string)filter_input(INPUT_GET, 'compartida');
}

$sigla = $_SESSION['oConfig']->getSigla();

if (is_true($Qcompartida)) {
    $EntradaCompartidaRepository = new EntradaCompartidaRepository();
    $oEntrada = $EntradaCompartidaRepository->findById($Qid_entrada);
    $id_entrada_compartida = $Qid_entrada;
} else {
    $EntradaRepository = new EntradaRepository();
    $oEntrada = $EntradaRepository->findById($Qid_entrada);
    $id_entrada_compartida = $oEntrada->getId_entrada_compartida();
}

if (!empty($Qid_entrada)) {

    $asunto_e = $oEntrada->getAsunto_entrada();
    // mirar si tienen escrito
    $f_escrito = $oEntrada->getF_documento()->getFromLocal();
    $f_entrada = $oEntrada->getF_entrada()->getFromLocal();

    if (!empty($id_entrada_compartida)) {
        $bCompartida = TRUE;
        $cabeceraIzqd = $oEntrada->cabeceraIzquierda();
        $cabeceraDcha = $oEntrada->cabeceraDerecha();

        $entradaComparidaAdjuntoRepository = new EntradaCompartidaAdjuntoRepository();
        $a_adjuntos = $entradaComparidaAdjuntoRepository->getArrayIdAdjuntos($id_entrada_compartida);

        $oEtherpad = new Etherpad();
        $oEtherpad->setId(Etherpad::ID_COMPARTIDO, $id_entrada_compartida);
    } else {
        $bCompartida = FALSE;
        // En el caso de distribución cr, si ya está aceptado, el ver es ya para enviar
        // y por tanto las cabeceras van al revés, y el destino se coge del bypass.
        $estado = $oEntrada->getEstado();
        $bypass = $oEntrada->isBypass();
        if (is_true($bypass) && $estado === Entrada::ESTADO_ACEPTADO) {
            $cabeceraIzqd = $oEntrada->cabeceraDistribucion_cr();
            $cabeceraDcha = $oEntrada->cabeceraDerecha();
        } else {
            $cabeceraIzqd = $oEntrada->cabeceraIzquierda();
            $cabeceraDcha = $oEntrada->cabeceraDerecha();
        }

        $a_adjuntos = $oEntrada->getArrayIdAdjuntos();

        $oEtherpad = new Etherpad();
        $oEtherpad->setId(Etherpad::ID_ENTRADA, $Qid_entrada);
    }
    $escrito_html = $oEtherpad->generarHtml();
} else {
    $bCompartida = FALSE;
    $cabeceraIzqd = '';
    $cabeceraDcha = '';
    $a_adjuntos = [];
    $asunto_e = '';
    $f_escrito = '';
    $f_entrada = '';
    $escrito_html = '';
}

if (!empty($f_entrada)) {
    $chk_leido = 'checked';
} else {
    $chk_leido = '';
}

$base_url = core\ConfigGlobal::getWeb();
$url_download = $base_url . '/src/entradas/controller/download.php';
$url_download_pdf_adjunto = $base_url . '/src/entradas/controller/download_as_pdf.php';
$url_download_pdf = $base_url . '/src/entradas/controller/entrada_download.php';

$a_campos = [
    'id_entrada' => $Qid_entrada,
    //'oHash' => $oHash,
    'cabeceraIzqd' => $cabeceraIzqd,
    'cabeceraDcha' => $cabeceraDcha,
    'asunto_e' => $asunto_e,
    'f_escrito' => $f_escrito,
    'a_adjuntos' => $a_adjuntos,
    'url_download' => $url_download,
    'chk_leido' => $chk_leido,
    'f_entrada' => $f_entrada,
    'base_url' => $base_url,
    'sigla' => $sigla,
    'escrito_html' => $escrito_html,
    'url_download_pdf' => $url_download_pdf,
    'bCompartida' => $bCompartida,
    'url_download_pdf_adjunto' => $url_download_pdf_adjunto,
];

$oView = new ViewTwig('entradas/controller');
$oView->renderizar('entrada_ver.html.twig', $a_campos);