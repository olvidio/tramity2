<?php

use core\ConfigGlobal;
use core\ViewTwig;
use entradas\domain\entity\EntradaCompartida;
use entradas\domain\entity\EntradaRepository;
use etiquetas\domain\repositories\EtiquetaRepository;
use expedientes\domain\repositories\ExpedienteRepository;
use usuarios\domain\entity\Cargo;
use usuarios\domain\repositories\CargoRepository;
use web\DateTimeLocal;
use web\Desplegable;
use web\DesplegableArray;
use web\Hash;

// INICIO Cabecera global de URL de controlador *********************************

require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos para esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************


$Q_id_expediente = (integer)filter_input(INPUT_POST, 'id_expediente');
$Q_filtro = (string)filter_input(INPUT_POST, 'filtro');
$Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');

$pagina_contestar = '';
// Añado la opción crear un expediente desde entradas
switch ($Q_filtro) {
    case 'entradas_semana':
    case 'escritos_cr':
    case 'permanentes_cr':
    case 'en_buscar':
        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        $asunto = $oEntrada->getAsunto();

        $a_condicion = [];
        $str_condicion = (string)filter_input(INPUT_POST, 'condicion');
        parse_str($str_condicion, $a_condicion);
        $a_condicion['filtro'] = $Q_filtro;
        switch ($Q_filtro) {
            case 'en_buscar':
                $pagina_cancel = web\Hash::link('src/busquedas/controller/buscar_escrito.php?' . http_build_query($a_condicion));
                break;
            case 'permanentes_cr':
                $pagina_cancel = web\Hash::link('src/busquedas/controller/lista_permanentes.php?' . http_build_query($a_condicion));
                // En los ctr, buscar en entradas compartidas:
                if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
                    $oEntrada = new EntradaCompartida($Q_id_entrada);
                    $asunto = $oEntrada->getAsunto_entrada();
                }
                break;
            case 'escritos_cr':
                $a_condicion['opcion'] = 51;
                $pagina_cancel = web\Hash::link('src/busquedas/controller/ver_tabla.php?' . http_build_query($a_condicion));
                break;
            case 'entradas_semana':
                $a_condicion['opcion'] = 52;
                $pagina_cancel = web\Hash::link('src/busquedas/controller/ver_tabla.php?' . http_build_query($a_condicion));
                break;
            default:
                $err_switch = sprintf(_("opción no definida en switch en %s, linea %s"), __FILE__, __LINE__);
                exit ($err_switch);
        }
        break;
    case 'en_aceptado':
        $Q_oficina = (string)filter_input(INPUT_POST, 'oficina');
        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        $asunto = $oEntrada->getAsunto();

        $url_cancel = 'src/entradas/controller/entrada_lista.php';
        $pagina_cancel = Hash::link($url_cancel . '?' . http_build_query(['filtro' => $Q_filtro, 'oficina' => $Q_oficina]));
        // En los ctr, ir directo a contestar:
        if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
            $url_contestar = 'src/escritos/controller/escrito_from_entrada.php';
        } else {
            $url_contestar = $url_cancel;
        }
        $pagina_contestar = Hash::link($url_contestar . '?' . http_build_query(['filtro' => $Q_filtro, 'id_entrada' => $Q_id_entrada]));
        break;
    case 'en_encargado':
        $Q_encargado = (integer)filter_input(INPUT_POST, 'encargado');
        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        $asunto = $oEntrada->getAsunto();

        $url_cancel = 'src/entradas/controller/entrada_lista.php';
        $pagina_cancel = Hash::link($url_cancel . '?' . http_build_query(['filtro' => $Q_filtro, 'encargado' => $Q_encargado]));
        // En los ctr, ir directo a contestar:
        if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
            $url_contestar = 'src/escritos/controller/escrito_from_entrada.php';
        } else {
            $url_contestar = $url_cancel;
        }
        $pagina_contestar = Hash::link($url_contestar . '?' . http_build_query(['filtro' => $Q_filtro, 'id_entrada' => $Q_id_entrada]));
        break;
    default:
        if (empty($Q_id_expediente)) {
            exit ("Error, no existe el expediente");
        }

        $ExpedienteRepository = new ExpedienteRepository();
        $oExpediente = $ExpedienteRepository->findById($Q_id_expediente);
        $asunto = $oExpediente->getAsunto();
        $id_ponente = $oExpediente->getPonente();

        $CargoRepository = new CargoRepository();
        $oCargo = $CargoRepository->findById($id_ponente);
        $oficina_ponente = $oCargo->getId_oficina();

        $url_cancel = 'src/expedientes/controller/expediente_lista.php';
        $pagina_cancel = Hash::link($url_cancel . '?' . http_build_query(['filtro' => $Q_filtro]));
}

/*
- a "para firmar" i "circulando" el botó "mov/cop" ha de fer:
    - "a borrador" (passa a "borrador") [els que són propis o de l'oficina]
    - "copia a borrador" (fa una copia a "borrador") [els que són propis o de l'oficina]
    - "còpia a 'Copias'" (fa una còpia a "Copias") [els que són d'altres oficines]
        
- a "para reunión" i "reunión día" el botó "mov/cop" ha de fer:
    - "copia a borrador" (fa una copia a "borrador") [els que són propis o de l'oficina]
    - "còpia a 'Copias'" (fa una còpia a "Copias") [els que són d'altres oficines]
        
- a "Acabados", "Copias" el botó "mov/cop" ha de fer:
    - "a borrador" (passa a "borrador")
    - "copia a borrador" (fa una copia a "borrador")
    - "re-circular" (només als centres)

- a "Archivados" el botó "mov/cop" ha de fer:
    - "a borrador" (passa a "borrador")
    - "copia a borrador" (fa una copia a "borrador")
    - "còpia a oficina" (fa una còpia a una altre oficina)
*/

$oDesplCargosOficinaPendiente = [];
$oDesplCargosOficinaEncargado = [];
$oDesplCargos = [];
$a_botones = [];
$txt_plazo = '';
$f_plazo = '';
$hoy_iso = '';
$titulo = _("Acciones para el expediente");
switch ($Q_filtro) {
    case 'en_aceptado':
    case 'en_encargado':
    case 'entradas_semana':
        $titulo = _("Acciones para la entrada");
        $a_botones[4] = ['accion' => 'en_add_encargado',
            'txt' => _("Encargar a"),
            'tipo' => 'modal',
        ];
    case 'permanentes_cr':
    case 'en_buscar':
        $titulo = _("Acciones para la entrada");
        $a_botones[3] = ['accion' => 'en_visto',
            'txt' => _("marcar como visto"),
            'tipo' => '',
        ];
    case 'escritos_cr':
        $titulo = _("Acciones para la entrada");

        $a_botones[0] = ['accion' => 'en_add_expediente',
            'txt' => _("añadir a un expediente"),
            'tipo' => 'modal',
        ];
        $a_botones[1] = ['accion' => 'en_expediente',
            'txt' => _("crear un nuevo expediente"),
            'tipo' => '',
        ];
        $a_botones[2] = ['accion' => 'en_pendiente',
            'txt' => _("crear un nuevo pendiente de la oficina"),
            'tipo' => 'modal',
        ];
        $a_botones[5] = ['accion' => 'en_add_etiqueta',
            'txt' => _("Etiquetas"),
            'tipo' => 'modal1',
        ];

        $txt_plazo = _("plazo para contestar");
        $oHoy = new DateTimeLocal();
        $hoy_iso = $oHoy->getIso();
        $f_plazo = $oHoy->getFromLocal();

        $CargoRepository = new CargoRepository();
        $a_posibles_cargos_oficina = $CargoRepository->getArrayUsuariosOficina(ConfigGlobal::role_id_oficina());
        $oDesplCargosOficinaPendiente = new Desplegable('id_cargo_pendiente', $a_posibles_cargos_oficina, '', '');
        $oDesplCargosOficinaEncargado = new Desplegable('id_cargo_encargado', $a_posibles_cargos_oficina, '', '');
        break;
    case 'borrador_oficina':
    case 'borrador_propio':
        // los de la oficina
        if ($oficina_ponente === ConfigGlobal::role_id_oficina()) {
            $a_botones[0] = ['accion' => 'exp_eliminar',
                'txt' => _("eliminar"),
            ];
        }
        break;
    case 'firmar':
    case 'circulando':
        // sólo si soy el ponente (creador)
        if ($id_ponente === ConfigGlobal::role_id_cargo()) {
            $a_botones[0] = ['accion' => 'exp_a_borrador',
                'txt' => _("mover a borrador"),
            ];
        }
        // Si soy director de la oficina, al mover debo cambiar el creador.
        if ($oficina_ponente === ConfigGlobal::role_id_oficina() && ConfigGlobal::soy_dtor()) {
            $a_botones[0] = ['accion' => 'exp_a_borrador_cmb_creador',
                'txt' => _("mover a borrador"),
            ];
        }
        // los de la oficina
        if ($oficina_ponente === ConfigGlobal::role_id_oficina()) {
            $a_botones[1] = ['accion' => 'exp_cp_borrador',
                'txt' => _("copiar a borrador"),
            ];
        }
        // para todos
        $a_botones[2] = ['accion' => 'exp_cp_copias',
            'txt' => _("copiar a copias"),
        ];
        break;
    case 'reunion':
    case 'seg_reunion':
        // los de la oficina
        if ($oficina_ponente === ConfigGlobal::role_id_oficina()) {
            $a_botones[1] = ['accion' => 'exp_cp_borrador',
                'txt' => _("copiar a borrador"),
            ];
        }
        // para todos
        $a_botones[2] = ['accion' => 'exp_cp_copias',
            'txt' => _("copiar a copias"),
        ];
        break;
    case 'acabados':
    case 'acabados_encargados':
    case 'copias':
        // sólo si soy el ponente (creador)
        if ($id_ponente === ConfigGlobal::role_id_cargo()) {
            $a_botones[0] = ['accion' => 'exp_a_borrador',
                'txt' => _("mover a borrador"),
            ];
        }
        $a_botones[1] = ['accion' => 'exp_cp_borrador',
            'txt' => _("copiar a borrador"),
        ];
        if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
            $a_botones[2] = ['accion' => 'recircular',
                'txt' => _("re-circular"),
            ];

        }
        break;
    case 'archivados':
        // sólo si soy el ponente (creador)
        if ($id_ponente === ConfigGlobal::role_id_cargo()) {
            $a_botones[0] = ['accion' => 'exp_a_borrador',
                'txt' => _("mover a borrador"),
            ];
        }
        $a_botones[1] = ['accion' => 'exp_cp_borrador',
            'txt' => _("copiar a borrador"),
        ];
        $a_botones[2] = ['accion' => 'exp_cp_oficina',
            'txt' => _("copiar a otro cargo"),
            'tipo' => 'modal',
        ];
        $CargoRepository = new CargoRepository();
        $a_posibles_cargos = $CargoRepository->getArrayCargos(ConfigGlobal::role_id_oficina());
        $oDesplCargos = new Desplegable('of_destino', $a_posibles_cargos, '', '');
        break;
    case 'fijar_reunion':
    case 'distribuir':
        // No hace nada.
        break;
    default:
        $err_switch = sprintf(_("opción no definida en switch en %s, linea %s"), __FILE__, __LINE__);
        exit ($err_switch);
}

if (empty($a_botones)) {
    $a_botones[] = ['accion' => '',
        'txt' => _("no tiene permiso"),
    ];
}

// Etiquetas
$etiquetas = []; // No hay ninguna porque en archivar es cuando se añaden.
$etiquetaRepository = new EtiquetaRepository();
$cEtiquetas = $etiquetaRepository->getMisEtiquetas();
$a_posibles_etiquetas = [];
foreach ($cEtiquetas as $oEtiqueta) {
    $id_etiqueta = $oEtiqueta->getId_etiqueta();
    $nom_etiqueta = $oEtiqueta->getNom_etiqueta();
    $a_posibles_etiquetas[$id_etiqueta] = $nom_etiqueta;
}

if (!empty($oEntrada)) {
    $etiquetas = $oEntrada->getEtiquetasVisiblesArray();
}
$oArrayDesplEtiquetas = new DesplegableArray($etiquetas, $a_posibles_etiquetas, 'etiquetas');
$oArrayDesplEtiquetas->setBlanco('t');
$oArrayDesplEtiquetas->setAccionConjunto('fnjs_mas_etiquetas()');

// datepicker
$oFecha = new DateTimeLocal();
$format = $oFecha::getFormat();
$yearStart = date('Y');
$yearEnd = (int)$yearStart + 2;

$vista = ConfigGlobal::getVista();

$a_campos = [
    'id_entrada' => $Q_id_entrada,
    'id_expediente' => $Q_id_expediente,
    'filtro' => $Q_filtro,
    //'oHash' => $oHash,
    'titulo' => $titulo,
    'asunto' => $asunto,
    'a_botones' => $a_botones,
    'pagina_cancel' => $pagina_cancel,
    'oDesplCargosOficinaPendiente' => $oDesplCargosOficinaPendiente,
    'oDesplCargosOficinaEncargado' => $oDesplCargosOficinaEncargado,
    'oDesplCargos' => $oDesplCargos,
    'oArrayDesplEtiquetas' => $oArrayDesplEtiquetas,
    // para crea pendiente:
    'txt_plazo' => $txt_plazo,
    'f_plazo' => $f_plazo,
    'hoy_iso' => $hoy_iso,
    // datepicker
    'format' => $format,
    'yearStart' => $yearStart,
    'yearEnd' => $yearEnd,
    //Solo para saltar directo al contestar una entrada
    'pagina_contestar' => $pagina_contestar,
    'vista' => $vista,
];

$oView = new ViewTwig('expedientes/controller');
$oView->renderizar('expediente_accion.html.twig', $a_campos);