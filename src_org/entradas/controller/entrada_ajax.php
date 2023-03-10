<?php

use davical\model\Davical;
use entradas\domain\entity\EntradaDocDB;
use entradas\domain\entity\EntradaRepository;
use entradas\domain\repositories\EntradaBypassRepository;
use entradas\domain\repositories\EntradaDocDBRepository;
use escritos\model\Escrito;
use ethercalc\model\Ethercalc;
use etherpad\model\Etherpad;
use lugares\domain\repositories\LugarRepository;
use oasis_as4\model\As4;
use oasis_as4\model\As4CollaborationInfo;
use pendientes\model\GestorPendienteEntrada;
use pendientes\model\Pendiente;
use usuarios\domain\PermRegistro;
use usuarios\domain\repositories\OficinaRepository;
use web\DateTimeLocal;
use web\Lista;
use web\Protocolo;
use function core\is_true;

// INICIO Cabecera global de URL de controlador *********************************
require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Q_que = (string)filter_input(INPUT_POST, 'que');
switch ($Q_que) {
    case As4CollaborationInfo::ACCION_REEMPLAZAR:
        $plataforma = $_SESSION['oConfig']->getPlataformaMantenimiento();
        $error_txt = '';
        // id_entrada formato: tabla#id_reg
        $Q_id_entrada = (string)filter_input(INPUT_POST, 'id_entrada');
        $Qelim_pendientes = (integer)filter_input(INPUT_POST, 'elim_pendientes');
        // En el caso de reemplazar, no se pregunta el motivo. Siempre es:
        $Qtext = _("por n.v.");

        $tipo_escritos = strtok($Q_id_entrada, '#');
        // hay que quitar la 's' del final
        $tipo_escrito = rtrim($tipo_escritos, 's');
        $id_entrada = strtok('#');

        if ($tipo_escrito === 'escrito') {
            $oEscrito = new Escrito($id_entrada);
        }
        if ($tipo_escrito === 'entrada') {
            $EntradaBypassRepository = new EntradaBypassRepository();
            $oEscrito = $EntradaBypassRepository->findById($id_entrada);
            // comprobar que es bypass. Por el click podr??a ser una entrada normal
            $bypass = $oEscrito->isBypass();
            if (!is_true($bypass)) {
                $error_txt = _("S??lo se pueden reemplazar las entradas bypass");
            }
        }

        if (empty($error_txt)) {
            $oAS4 = new As4();
            $oAS4->setPlataforma_Destino($plataforma);
            $oAS4->setAccion(As4CollaborationInfo::ACCION_REEMPLAZAR);

            $filename = $oEscrito->getNombreEscrito(As4CollaborationInfo::ACCION_REEMPLAZAR);

            $oAS4->setEscrito($oEscrito);
            $oAS4->setTipo_escrito($tipo_escrito);
            $oAS4->setAnular_txt($Qtext);

            $error_txt = $oAS4->writeOnDock($filename);
        }


        if (empty($error_txt)) {
            $jsondata['success'] = true;
            $jsondata['mensaje'] = 'ok';
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $error_txt;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case As4CollaborationInfo::ACCION_ORDEN_ANULAR:
        $plataforma = $_SESSION['oConfig']->getPlataformaMantenimiento();
        $error_txt = '';
        // id_entrada formato: tabla#id_reg
        $Q_id_entrada = (string)filter_input(INPUT_POST, 'id_entrada');
        $Qtext = (string)filter_input(INPUT_POST, 'text');
        $Qelim_pendientes = (integer)filter_input(INPUT_POST, 'elim_pendientes');

        $tipo_escritos = strtok($Q_id_entrada, '#');
        // hay que quitar la 's' del final
        $tipo_escrito = rtrim($tipo_escritos, 's');
        $id_entrada = strtok('#');

        if ($tipo_escrito === 'escrito') {
            $oEscrito = new Escrito($id_entrada);
        }
        if ($tipo_escrito === 'entrada') {
            $EntradaBypassRepository = new EntradaBypassRepository();
            $oEscrito = $EntradaBypassRepository->findById($id_entrada);
            // comprobar que es bypass. Por el click podria ser una entrada normal
            $bypass = $oEscrito->isBypass();
            if (!is_true($bypass)) {
                $error_txt = _("S??lo se pueden anular las entradas bypass");
            }
        }

        if (empty($error_txt)) {
            $oAS4 = new As4();
            $oAS4->setPlataforma_Destino($plataforma);
            $oAS4->setAccion(As4CollaborationInfo::ACCION_ORDEN_ANULAR);

            $filename = $oEscrito->getNombreEscrito(As4CollaborationInfo::ACCION_ORDEN_ANULAR);

            $oAS4->setEscrito($oEscrito);
            $oAS4->setTipo_escrito($tipo_escrito);
            $oAS4->setAnular_txt($Qtext);

            $error_txt = $oAS4->writeOnDock($filename);
        }


        if (empty($error_txt)) {
            $jsondata['success'] = true;
            $jsondata['mensaje'] = 'ok';
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $error_txt;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'perm_ver':
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        $oPermiso = new PermRegistro();
        $perm = $oPermiso->permiso_detalle($oEntrada, 'escrito');
        if ($perm < PermRegistro::PERM_VER) {
            $mensaje = _("No tiene permiso para ver la entrada");
        } else {
            $mensaje = '';
        }

        if (empty($mensaje)) {
            $jsondata['success'] = true;
            $jsondata['mensaje'] = 'ok';
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $mensaje;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'modificar_anular':
        $error_txt = '';
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $Qtext = (string)filter_input(INPUT_POST, 'text');
        $Qelim_pendientes = (integer)filter_input(INPUT_POST, 'elim_pendientes');

        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        if ($oEntrada === null) {
            $err_cargar = sprintf(_("OJO! no existe la entrada en %s, linea %s"), __FILE__, __LINE__);
            exit ($err_cargar);
        }
        $oEntrada->setAnulado($Qtext);
        if ($EntradaRepository->Guardar($oEntrada) === FALSE) {
            $error_txt = $EntradaRepository->getErrorTxt();
        }
        // Mirar si hay pendientes
        if (!empty($Qelim_pendientes)) {
            $gesPendientes = new GestorPendienteEntrada();
            $cUids = $gesPendientes->getArrayUidById_entrada($Q_id_entrada);
            if (!empty($cUids)) {
                $calendario = 'registro';
                $oDavical = new Davical($_SESSION['oConfig']->getAmbito());
                $user_davical = $oDavical->getUsernameDavicalSecretaria();
                foreach ($cUids as $uid => $parent_container) {
                    $oPendiente = new Pendiente($parent_container, $calendario, $user_davical, $uid);
                    $oPendiente->eliminar();
                }
            }
        }
        if (empty($error_txt)) {
            $jsondata['success'] = true;
            $jsondata['mensaje'] = 'ok';
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $error_txt;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'modificar_detalle':
        $error_txt = '';
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $Qdetalle = (string)filter_input(INPUT_POST, 'text');

        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        if ($oEntrada === null) {
            $err_cargar = sprintf(_("OJO! no existe la entrada en %s, linea %s"), __FILE__, __LINE__);
            exit ($err_cargar);
        }
        $oEntrada->setDetalle($Qdetalle);
        if ($EntradaRepository->Guardar($oEntrada) === FALSE) {
            $error_txt = $EntradaRepository->getErrorTxt();
        }
        if (empty($error_txt)) {
            $jsondata['success'] = true;
            $jsondata['mensaje'] = 'ok';
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $error_txt;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'get_anular':
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        $anulado = $oEntrada->getAnulado();
        $mensaje = '';

        if (empty($mensaje)) {
            $jsondata['success'] = true;
            $jsondata['detalle'] = $anulado;
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $mensaje;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'get_detalle':
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $EntradaRepository = new EntradaRepository();
        $oEntrada = $EntradaRepository->findById($Q_id_entrada);
        $mensaje = '';
        $oPermiso = new PermRegistro();
        $perm = $oPermiso->permiso_detalle($oEntrada, 'detalle');
        if ($perm < PermRegistro::PERM_MODIFICAR) {
            $mensaje = _("No tiene permiso para modificar el detalle");
        }

        if (empty($mensaje)) {
            $jsondata['success'] = true;
            $jsondata['detalle'] = $oEntrada->getDetalle();
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $mensaje;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'get_destinos':
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $EntradaBypassRepository = new EntradaBypassRepository();
        $oEntradaBypass = $EntradaBypassRepository->findById($Q_id_entrada);
        $a_destinos = $oEntradaBypass->getDestinosByPass();
        $a_miembros = $a_destinos['miembros'];
        $LugarRepository = new LugarRepository();
        $aLugares = $LugarRepository->getArrayLugares();
        $destinos_txt = '';
        foreach ($a_miembros as $id_lugar) {
            $destinos_txt .= empty($destinos_txt) ? '' : "\n";
            $destinos_txt .= $aLugares[$id_lugar];
        }
        $mensaje = '';

        if (empty($mensaje)) {
            $jsondata['success'] = true;
            $jsondata['destinos'] = $destinos_txt;
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $mensaje;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'comprobar_pdte': //antes de eliminar
        $bypass_txt = '';
        $pendientes_txt = '';
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        // Comprobar si tiene pendientes
        $gesPendientes = new GestorPendienteEntrada();
        $cUids = $gesPendientes->getArrayUidById_entrada($Q_id_entrada);
        if (!empty($cUids)) {
            $c = count($cUids);
            $pendientes_txt = sprintf(_("Esta entrada tiene %s pendientes asociados."), $c);
        }

        $mensaje = '';
        if (!empty($bypass_txt)) {
            $mensaje .= $bypass_txt;
        }
        if (!empty($pendientes_txt)) {
            $mensaje .= empty($mensaje) ? '' : "<br>";
            $mensaje .= $pendientes_txt;
        }

        $jsondata['success'] = true;
        $jsondata['mensaje'] = $mensaje;

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'comprobar': //antes de eliminar
        $bypass_txt = '';
        $pendientes_txt = '';
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        // Comprobar si tiene pendientes
        $gesPendientes = new GestorPendienteEntrada();
        $cUids = $gesPendientes->getArrayUidById_entrada($Q_id_entrada);
        if (!empty($cUids)) {
            $c = count($cUids);
            $pendientes_txt = sprintf(_("Esta entrada tiene %s pendientes asociados."), $c);
        }
        // comprobar si tiene bypass
        $EntradaBypassRepository = new EntradaBypassRepository();
        $cByPass = $EntradaBypassRepository->getEntradasBypass(['id_entrada' => $Q_id_entrada]);
        if (is_array($cByPass) && !empty($cByPass)) {
            $c = count($cByPass);
            $bypass_txt = sprintf(_("Esta entrada tiene %s envios a ctr."), $c);
        }

        $mensaje = '';
        if (!empty($bypass_txt)) {
            $mensaje .= $bypass_txt;
        }
        if (!empty($pendientes_txt)) {
            $mensaje .= empty($mensaje) ? '' : "<br>";
            $mensaje .= $pendientes_txt;
        }

        $jsondata['success'] = true;
        $jsondata['mensaje'] = $mensaje;

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'eliminar':
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $error_txt = '';
        if (!empty($Q_id_entrada)) {
            $EntradaRepository = new EntradaRepository();
            $oEntrada = $EntradaRepository->findById($Q_id_entrada);
            // eliminar los pendientes
            $gesPendientes = new GestorPendienteEntrada();
            $cUids = $gesPendientes->getArrayUidById_entrada($Q_id_entrada);
            if (!empty($cUids)) {
                $calendario = 'registro';
                $oDavical = new Davical($_SESSION['oConfig']->getAmbito());
                $user_davical = $oDavical->getUsernameDavicalSecretaria();
                foreach ($cUids as $uid => $parent_container) {
                    $oPendiente = new Pendiente($parent_container, $calendario, $user_davical, $uid);
                    $oPendiente->eliminar();
                }
            }
            // eliminar la entrada y bypass
            if ($EntradaRepository->Eliminar($oEntrada) === FALSE) {
                $error_txt .= $EntradaRepository->getErrorTxt();
            }
        } else {
            $error_txt = _("No existe la entrada");
        }
        if (empty($error_txt)) {
            $jsondata['success'] = true;
            $jsondata['mensaje'] = 'ok';
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = $error_txt;
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case 'buscar':
        $Qid_expediente = (integer)filter_input(INPUT_POST, 'id_expediente');
        $Qid_oficina = (integer)filter_input(INPUT_POST, 'oficina_buscar');
        $Qasunto = (string)filter_input(INPUT_POST, 'asunto');
        $Qfiltro = (string)filter_input(INPUT_POST, 'filtro');
        $Qperiodo = (string)filter_input(INPUT_POST, 'periodo');

        $Qorigen_id_lugar = (integer)filter_input(INPUT_POST, 'origen_id_lugar');
        $Qorigen_prot_num = (integer)filter_input(INPUT_POST, 'prot_num');
        $Qorigen_prot_any = (string)filter_input(INPUT_POST, 'prot_any'); // string para distinguir el 00 (del 2000) de empty.

        $EntradaRepository = new EntradaRepository();
        $aWhere = [];
        $aOperador = [];

        if (!empty($Qid_oficina)) {
            // buscar los posibles ponentes de una oficina:
            $aWhere['ponente'] = $Qid_oficina;
        }

        if (!empty($Qasunto)) {
            $aWhere['asunto'] = $Qasunto;
            $aOperador['asunto'] = '~*';
        }

        switch ($Qperiodo) {
            case "mes":
                $periodo = 'P1M';
                break;
            case "mes_6":
                $periodo = 'P6M';
                break;
            case "any_1":
                $periodo = 'P1Y';
                break;
            case "any_2":
                $periodo = 'P2Y';
                break;
            case "siempre":
                $periodo = '';
                break;
            default:
                $periodo = 'P1M';
        }
        if (!empty($periodo)) {
            $oFecha = new DateTimeLocal();
            $oFecha->sub(new DateInterval($periodo));
            $aWhere['f_entrada'] = $oFecha->getIso();
            $aOperador['f_entrada'] = '>';
        }

        $aWhere['_ordre'] = 'f_entrada DESC';

        if (!empty($Qorigen_id_lugar)) {
            $EntradaRepository = new EntradaRepository();
            $id_lugar = $Qorigen_id_lugar;
            if (!empty($Qorigen_prot_num) && !empty($Qorigen_prot_any)) {
                // No tengo en quenta las otras condiciones de la b??squeda
                $aProt_origen = ['id_lugar' => $Qorigen_id_lugar,
                    'num' => $Qorigen_prot_num,
                    'any' => $Qorigen_prot_any,
                ];
                $cEntradas = $EntradaRepository->getEntradasByProtOrigenDB($aProt_origen);
            } else {
                $cEntradas = $EntradaRepository->getEntradasByLugarDB($id_lugar, $aWhere, $aOperador);
            }
        } else {
            $cEntradas = $EntradaRepository->getEntradas($aWhere, $aOperador);
        }

        $a_cabeceras = ['', _("protocolo"), _("fecha"), _("asunto"), _("oficina ponente"), ''];
        $a_valores = [];
        $a = 0;
        $OficinaRepository = new OficinaRepository();
        $a_posibles_oficinas = $OficinaRepository->getArrayOficinas();
        $oProtOrigen = new Protocolo();
        $oPermRegistro = new PermRegistro();
        foreach ($cEntradas as $oEntrada) {
            $perm_ver_escrito = $oPermRegistro->permiso_detalle($oEntrada, 'escrito');
            if ($perm_ver_escrito < PermRegistro::PERM_VER) {
                continue;
            }
            $a++;
            $id_entrada = $oEntrada->getId_entrada();
            $fecha_txt = $oEntrada->getF_entrada()->getFromLocal();
            $id_of_ponente = $oEntrada->getPonente();

            $of_ponente_txt = $a_posibles_oficinas[$id_of_ponente];

            $oProtOrigen->setJson($oEntrada->getJson_prot_origen());

            $ver = "<span class=\"btn btn-link\" onclick=\"fnjs_ver_entrada('$id_entrada');\" >" . _("ver") . "</span>";
            $add = "<span class=\"btn btn-link\" onclick=\"fnjs_adjuntar_entrada('$id_entrada','$Qid_expediente','$Qfiltro');\" >" . _("adjuntar") . "</span>";

            $a_valores[$a][1] = $ver;
            $a_valores[$a][2] = $oProtOrigen->ver_txt();
            $a_valores[$a][3] = $fecha_txt;
            $a_valores[$a][4] = $oEntrada->getAsuntoDetalle();
            $a_valores[$a][5] = $of_ponente_txt;
            $a_valores[$a][6] = $add;
        }


        $oLista = new Lista();
        $oLista->setCabeceras($a_cabeceras);
        $oLista->setDatos($a_valores);
        echo $oLista->mostrar_tabla();
        break;
    case 'guardar':
        $Q_id_entrada = (integer)filter_input(INPUT_POST, 'id_entrada');
        $Qtipo_doc = (integer)filter_input(INPUT_POST, 'tipo_doc');
        $Qf_escrito = (string)filter_input(INPUT_POST, 'f_escrito');
        $oF_escrito = DateTimeLocal::createFromLocal($Qf_escrito, 'date');

        if (!empty($Q_id_entrada)) {
            $EntradaDocDBRepository = new EntradaDocDBRepository();
            $oEntradaDocBD = $EntradaDocDBRepository->findById($Q_id_entrada);
            $oEntradaDocBD->setF_doc($oF_escrito);
            $oEntradaDocBD->setTipo_doc($Qtipo_doc);

            $error = FALSE;
            if ($EntradaDocDBRepository->Guardar($oEntradaDocBD) === FALSE) {
                $error_txt = $EntradaDocDBRepository->getErrorTxt();
                $error = TRUE;
            }
        } else {
            $error = TRUE;
        }

        $jsondata = [];
        if ($error === TRUE) {
            $jsondata['error'] = true;
        } else {
            switch ($Qtipo_doc) {
                case EntradaDocDB::TIPO_ETHERCALC :
                    $oEthercalc = new Ethercalc();
                    $oEthercalc->setId(Ethercalc::ID_ENTRADA, $Q_id_entrada);
                    $padID = $oEthercalc->getPadId();
                    $url = $oEthercalc->getUrl();

                    $fullUrl = "$url/$padID";

                    $jsondata['error'] = false;
                    $jsondata['url'] = $fullUrl;
                    break;
                case EntradaDocDB::TIPO_ETHERPAD :
                    $oEtherpad = new Etherpad();
                    $oEtherpad->setId(Etherpad::ID_ENTRADA, $Q_id_entrada);
                    $padID = $oEtherpad->getPadId();
                    // add user access to pad (Session)
                    //$oEtherpad->addUserPerm($id_entrada);
                    $url = $oEtherpad->getUrl();

                    $fullUrl = "$url/p/$padID?showChat=false&showLineNumbers=false";

                    $jsondata['error'] = false;
                    $jsondata['url'] = $fullUrl;
                    break;
                default:
                    $err_switch = sprintf(_("opci??n no definida en switch en %s, linea %s"), __FILE__, __LINE__);
                    exit ($err_switch);
            }
        }
        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        break;
    default:
        $err_switch = sprintf(_("opci??n no definida en switch en %s, linea %s"), __FILE__, __LINE__);
        exit ($err_switch);
}