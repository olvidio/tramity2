<?php
use core\ViewTwig;
use etherpad\model\Etherpad;
use expedientes\model\Escrito;
use web\Protocolo;
use web\ProtocoloArray;

// INICIO Cabecera global de URL de controlador *********************************

require_once ("apps/core/global_header.inc");
// Arxivos requeridos por esta url **********************************************

// Crea los objectos de uso global **********************************************
require_once ("apps/core/global_object.inc");
// Crea los objectos para esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Qid_escrito = (string) \filter_input(INPUT_POST, 'id_escrito');

// Para poder moverse de una escrito a otro:
$QSlide_mode =  (bool) \filter_input(INPUT_POST, 'slide_mode', FILTER_VALIDATE_BOOLEAN );

if ($QSlide_mode === TRUE) {
    $Qmov = (string) \filter_input(INPUT_POST, 'mov');

    if ($Qmov == 'prev') {
        $Qid_escrito = $Qid_escrito - 1;
    }
    if ($Qmov == 'next') {
        $Qid_escrito = $Qid_escrito + 1;
    }
} else {
    $Qid_escrito = (string) \filter_input(INPUT_GET, 'id_escrito');
}


$sigla = $_SESSION['oConfig']->getSigla();

$oProtRef = new Protocolo();
$oProtRef->setEtiqueta('Ref');
$oProtRef->setNombre('ref');
$oProtRef->setBlanco(TRUE);

$pagina = core\ConfigGlobal::getWeb().'/apps/expedientes/controller/escrito_ver.php';
$a_cosas = [ 'id_escrito' => $Qid_escrito, 'slide_mode' => 'TRUE', 'mov' => 'prev'];
$pagina_prev = web\Hash::link($pagina.'?'.http_build_query($a_cosas));
$a_cosas = [ 'id_escrito' => $Qid_escrito, 'slide_mode' => 'TRUE', 'mov' => 'next'];
$pagina_next = web\Hash::link($pagina.'?'.http_build_query($a_cosas));

if (!empty($Qid_escrito)) {
    $base_url = core\ConfigGlobal::getWeb();
    $url_download = $base_url.'/apps/expedientes/controller/adjunto_download.php?plugin=1';
    $url_download_pdf = $base_url.'/apps/expedientes/controller/escrito_download.php';
    // Pueden ser varios escritos separados por comas:
    $a_escritos = explode(',', $Qid_escrito);
    foreach ($a_escritos as $id_escrito) {
        $oEscrito = new Escrito($id_escrito);
        $json_prot_local = $oEscrito->getJson_prot_local();
        if (!empty((array)$json_prot_local)) { // Hay que pasarlo a array para ver si está vacío
            $oProtOrigen = new Protocolo();
            $oProtOrigen->setLugar($json_prot_local->lugar);
            $oProtOrigen->setProt_num($json_prot_local->num);
            $oProtOrigen->setProt_any($json_prot_local->any);
            $oProtOrigen->setMas($json_prot_local->mas);
            $origen_txt = $oProtOrigen->ver_txt();
        } else {
            $origen_txt = $sigla; 
        }

        $json_prot_destino = $oEscrito->getJson_prot_destino();
        $oArrayProtDestino = new ProtocoloArray($json_prot_destino,'','destinos');
        $oArrayProtDestino->setEtiqueta('De');

        $json_prot_ref = $oEscrito->getJson_prot_ref();

        $oArrayProtRef = new web\ProtocoloArray($json_prot_ref,'','referencias');
        $oArrayProtRef ->setBlanco('t');
        $oArrayProtRef ->setRef(TRUE);
        $oArrayProtRef ->setAccionConjunto('fnjs_mas_referencias(event)');
        
        $asunto_e = $oEscrito->getAsunto();
        
        $a_adjuntos = $oEscrito->getArrayIdAdjuntos();
        
        // mirar si tienen escrito
        $f_escrito = $oEscrito->getF_escrito()->getFromLocal();
        $tipo_doc = $oEscrito->getTipo_doc();
        
        $oEtherpad = new Etherpad();
        $oEtherpad->setId (Etherpad::ID_ESCRITO,$id_escrito);
        
        $escrito_html = $oEtherpad->generarHtml();

        $oView = new ViewTwig('expedientes/controller');
        if ($QSlide_mode === TRUE) {
            $a_campos = [
                'id_escrito' => $id_escrito,
                //'oHash' => $oHash,
                'oArrayProtDestino' => $oArrayProtDestino,
                'oArrayProtRef' => $oArrayProtRef,
                'asunto_e' => $asunto_e,
                'f_escrito' => $f_escrito,
                'tipo_doc' => $tipo_doc,
                'a_adjuntos' => $a_adjuntos,
                'pagina_prev' => $pagina_prev,
                'pagina_next' => $pagina_next,
                'base_url' => $base_url,
                'origen_txt' => $origen_txt,
                'escrito_html' => $escrito_html,
                'base_url' => $base_url,
                'url_download' => $url_download,
            ];
            echo $oView->renderizar('escrito_ver_slide.html.twig',$a_campos);
        } else {
            $a_campos = [
                'id_escrito' => $id_escrito,
                //'oHash' => $oHash,
                'oArrayProtDestino' => $oArrayProtDestino,
                'oArrayProtRef' => $oArrayProtRef,
                'asunto_e' => $asunto_e,
                'f_escrito' => $f_escrito,
                'tipo_doc' => $tipo_doc,
                'a_adjuntos' => $a_adjuntos,
                'origen_txt' => $origen_txt,
                'escrito_html' => $escrito_html,
                'base_url' => $base_url,
                'url_download' => $url_download,
                'url_download_pdf' => $url_download_pdf,
            ];
            echo $oView->renderizar('escrito_ver.html.twig',$a_campos);
        }
    }
    exit();
} else {
    $txt_alert = _("No hay escritos");
    $a_campos = [ 'txt_alert' => $txt_alert, 'btn_cerrar' => TRUE ];
    $oView = new ViewTwig('expedientes/controller');
    echo $oView->renderizar('alerta.html.twig',$a_campos);
}