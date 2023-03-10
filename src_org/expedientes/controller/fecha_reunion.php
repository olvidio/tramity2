<?php

use core\ConfigGlobal;
use core\ViewTwig;
use expedientes\domain\entity\Expediente;
use expedientes\domain\repositories\ExpedienteRepository;
use lugares\domain\repositories\LugarRepository;
use usuarios\domain\repositories\OficinaRepository;
use web\Desplegable;
use web\Hash;

// INICIO Cabecera global de URL de controlador *********************************

require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos para esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Q_id_expediente = (string)filter_input(INPUT_POST, 'id_expediente');
$Q_filtro = (string)filter_input(INPUT_POST, 'filtro');

$id_oficina = ConfigGlobal::role_id_oficina();

$OficinaRepository = new OficinaRepository();
$oDesplOficinas = $OficinaRepository->getListaOficinas();
$oDesplOficinas->setNombre('id_oficina');
$oDesplOficinas->setOpcion_sel($id_oficina);

$LugarRepository = new LugarRepository();
$a_posibles_lugares = $LugarRepository->getArrayLugares();

$oDesplLugares = new Desplegable();
$oDesplLugares->setNombre('id_origen');
$oDesplLugares->setOpciones($a_posibles_lugares);
$oDesplLugares->setBlanco(TRUE);

$a_cosas = ['id_expediente' => $Q_id_expediente,
    'filtro' => $Q_filtro,
];
$pagina_cancel = Hash::link('src/expedientes/controller/expediente_lista.php?' . http_build_query($a_cosas));
$pagina_reunion = Hash::link('src/expedientes/controller/expediente_update.php?' . http_build_query([]));

$ExpedienteRepository = new ExpedienteRepository();
$oExpediente = $ExpedienteRepository->findById($Q_id_expediente);
$f_reunion = $oExpediente->getF_reunion()->getFromLocalHora();
$yearStart = date('Y');
$yearEnd = (int)$yearStart + 2;
$hoyIso = date('Y-m-d');

$titulo = _("Fijar fecha reunión:");

$a_campos = [
    'id_expediente' => $Q_id_expediente,
    'titulo' => $titulo,
    'pagina_cancel' => $pagina_cancel,
    'pagina_reunion' => $pagina_reunion,
    'yearStart' => $yearStart,
    'yearEnd' => $yearEnd,
    'hoyIso' => $hoyIso,
    'f_reunion' => $f_reunion,
];

$oView = new ViewTwig('expedientes/controller');
$oView->renderizar('fecha_reunion.html.twig', $a_campos);