<?php

use core\ConfigGlobal;
use core\ViewTwig;
use escritos\model\EscritoLista;
use etiquetas\domain\repositories\EtiquetaRepository;
use expedientes\domain\repositories\ExpedienteRepository;
use tramites\domain\repositories\FirmaRepository;
use tramites\domain\repositories\TramiteRepository;
use usuarios\domain\entity\Cargo;
use usuarios\domain\repositories\CargoRepository;
use usuarios\domain\Visibilidad;
use web\Desplegable;
use web\DesplegableArray;
use web\Hash;
use function core\is_true;

// INICIO Cabecera global de URL de controlador *********************************

require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos para esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Q_id_expediente = (integer)filter_input(INPUT_POST, 'id_expediente');
$Q_filtro = (string)filter_input(INPUT_POST, 'filtro');
$Q_modo = (string)filter_input(INPUT_POST, 'modo');

// En el caso de adjuntos, puedo abrir una nueva ventana para ver el expediente,
// y en ese caso el parámetro viene por GET:
$cargar_css = FALSE;
$show_tabs = TRUE;
if (empty($Q_id_expediente)) {
    $Q_id_expediente = (integer)filter_input(INPUT_GET, 'id_expediente');
    $cargar_css = TRUE;
    $show_tabs = FALSE;
    $Q_filtro = 'archivados';
}

$ExpedienteRepository = new ExpedienteRepository();
$oExpediente = $ExpedienteRepository->findById($Q_id_expediente);
if ($oExpediente === null) {
    $err_cargar = sprintf(_("OJO! no existe el expediente en %s, linea %s"), __FILE__, __LINE__);
    exit ($err_cargar);
}
$ponente_txt = '?';
$id_ponente = $oExpediente->getPonente();
$CargoRepository = new CargoRepository();
$aCargos = $CargoRepository->getArrayCargos();
$ponente_txt = $aCargos[$id_ponente];

$id_tramite = $oExpediente->getId_tramite();
$TramiteRepository = new TramiteRepository();
$oTramite = $TramiteRepository->findById($id_tramite);
$tramite_txt = $oTramite->getTramite();

$estado = $oExpediente->getEstado();
$a_estado = $oExpediente->getArrayEstado();
$estado_txt = $a_estado[$estado];

$prioridad = $oExpediente->getPrioridad();
$a_prioridad = $oExpediente->getArrayPrioridad();
$prioridad_txt = $a_prioridad[$prioridad];

$vida = $oExpediente->getVida();
if (empty($vida)) {
    $vida_txt = '?';
} else {
    $a_vida = $oExpediente->getArrayVida();
    $vida_txt = $a_vida[$vida];
}

$f_contestar = $oExpediente->getF_contestar()->getFromLocal();
$f_ini_circulacion = $oExpediente->getF_ini_circulacion()->getFromLocal();
$f_reunion = $oExpediente->getF_reunion()->getFromLocal();
$f_aprobacion = $oExpediente->getF_aprobacion()->getFromLocal();

$asunto = $oExpediente->getAsuntoEstado();
$entradilla = $oExpediente->getEntradilla();
$visibilidad = $oExpediente->getVisibilidad();

$oEscritoLista = new EscritoLista();
$oEscritoLista->setId_expediente($Q_id_expediente);
$oEscritoLista->setModo($Q_modo);
$oEscritoLista->setShow_tabs($show_tabs);

// Comentarios y Aclaraciones
$FirmaRepository = new FirmaRepository();
$aRecorrido = $FirmaRepository->getRecorrido($Q_id_expediente);
$a_recorrido = $aRecorrido['recorrido'];
$comentarios = $aRecorrido['comentarios'];

// visibilidad
$oVisibilidad = new Visibilidad();
$aOpciones = $oVisibilidad->getArrayVisibilidad();
$oDesplVisibilidad = new Desplegable();
$oDesplVisibilidad->setNombre('visibilidad');
$oDesplVisibilidad->setOpciones($aOpciones);
$oDesplVisibilidad->setOpcion_sel($visibilidad);

// Etiquetas
$ver_etiquetas = FALSE;
$etiquetas = $oExpediente->getEtiquetasVisiblesArray();
if (ConfigGlobal::role_actual() !== 'secretaria') {
    $etiquetaRepository = new EtiquetaRepository();
    $cEtiquetas = $etiquetaRepository->getMisEtiquetas();
    $a_posibles_etiquetas = [];
    foreach ($cEtiquetas as $oEtiqueta) {
        $id_etiqueta = $oEtiqueta->getId_etiqueta();
        $nom_etiqueta = $oEtiqueta->getNom_etiqueta();
        $a_posibles_etiquetas[$id_etiqueta] = $nom_etiqueta;
    }
    $oArrayDesplEtiquetas = new DesplegableArray($etiquetas, $a_posibles_etiquetas, 'etiquetas');
    $oArrayDesplEtiquetas->setBlanco('t');
    $oArrayDesplEtiquetas->setAccionConjunto('fnjs_mas_etiquetas()');
    $ver_etiquetas = TRUE;
} else {
    $oArrayDesplEtiquetas = new DesplegableArray('', [], 'etiquetas');
}
$txt_btn_etiquetas = _("Guardar etiquetas");

$lista_antecedentes = $oExpediente->getHtmlAntecedentes(FALSE);

$url_update = 'src/expedientes/controller/expediente_update.php';
$cosas = ['filtro' => $Q_filtro, 'modo' => $Q_modo];
if ($Q_filtro === 'archivados') {
    $Q_a_condiciones = (array)filter_input(INPUT_POST, 'condiciones', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
    $cosas = array_merge($cosas, $Q_a_condiciones);
}
$pagina_cancel = Hash::link('src/expedientes/controller/expediente_lista.php?' . http_build_query($cosas));
$pagina_actualizar = Hash::link('src/expedientes/controller/expediente_distribuir.php?' . http_build_query(['id_expediente' => $Q_id_expediente, 'filtro' => $Q_filtro, 'modo' => $Q_modo]));
$base_url = ConfigGlobal::getWeb(); //http://tramity.local

$disable_archivar = '';
$ver_encargar = FALSE;
if ($Q_filtro === 'distribuir') {
    $btn_action = 'distribuir';
    $txt_btn_success = _("Distribuir");
    $oEscritoLista->setFiltro('distribuir');
    $oDesplOficiales = new Desplegable('id_oficial', [], $id_ponente, TRUE);

    $perm_d = $_SESSION['oConfig']->getPerm_distribuir();
    if (ConfigGlobal::mi_usuario_cargo() === 'scdl' || is_true($perm_d)) {
        $perm_distribuir = TRUE;
    } else {
        $perm_distribuir = FALSE;
    }
} else {
    $perm_distribuir = TRUE;
    $btn_action = 'archivar';
    $txt_btn_success = _("Archivar");
    //$oEscritoLista->setFiltro('acabados');
    $oEscritoLista->setFiltro($Q_filtro);
    $disable_archivar = is_true($oEscritoLista->EstanTodosLosEscritosEnviados()) ? '' : 'disabled';
    // para encargar a los oficiales
    $id_oficina = ConfigGlobal::role_id_oficina();
    $a_usuarios_oficina = $CargoRepository->getArrayUsuariosOficina($id_oficina);
    $oDesplOficiales = new Desplegable('id_oficial', $a_usuarios_oficina, $id_ponente, FALSE);
    $ver_encargar = TRUE;
}
// para reducir la vista en el caso de los ctr
$vista_dl = TRUE;
if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
    $vista_dl = FALSE;
}

$a_campos = [
    'id_expediente' => $Q_id_expediente,
    //'oHash' => $oHash,
    'ponente_txt' => $ponente_txt,
    'id_ponente' => $id_ponente,
    'tramite_txt' => $tramite_txt,
    'estado_txt' => $estado_txt,
    'prioridad_txt' => $prioridad_txt,
    'vida_txt' => $vida_txt,

    'f_contestar' => $f_contestar,
    'f_ini_circulacion' => $f_ini_circulacion,
    'f_reunion' => $f_reunion,
    'f_aprobacion' => $f_aprobacion,

    'asunto' => $asunto,
    'entradilla' => $entradilla,
    'comentarios' => $comentarios,
    'a_recorrido' => $a_recorrido,

    'lista_antecedentes' => $lista_antecedentes,
    'oDesplVisibilidad' => $oDesplVisibilidad,
    'oArrayDesplEtiquetas' => $oArrayDesplEtiquetas,
    'oDesplOficiales' => $oDesplOficiales,
    'ver_encargar' => $ver_encargar,
    'vista_dl' => $vista_dl,

    'url_update' => $url_update,
    'pagina_cancel' => $pagina_cancel,
    'pagina_actualizar' => $pagina_actualizar,
    // para la pagina js
    'base_url' => $base_url,
    'cargar_css' => $cargar_css,
    'show_tabs' => $show_tabs,
    //acciones
    'oEscritoLista' => $oEscritoLista,
    'filtro' => $Q_filtro,
    'modo' => $Q_modo,
    'perm_distribuir' => $perm_distribuir,
    'btn_action' => $btn_action,
    'txt_btn_success' => $txt_btn_success,
    'txt_btn_etiquetas' => $txt_btn_etiquetas,
    'ver_etiquetas' => $ver_etiquetas,
    'disable_archivar' => $disable_archivar,
];

$oView = new ViewTwig('expedientes/controller');
$oView->renderizar('expediente_distribuir.html.twig', $a_campos);