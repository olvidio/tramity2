<?php

// INICIO Cabecera global de URL de controlador *********************************
use entradas\domain\entity\EntradaRepository;
use escritos\domain\repositories\EscritoRepository;
use escritos\model\GestorEscrito;
use lugares\domain\repositories\LugarRepository;
use usuarios\domain\repositories\CargoRepository;

require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Q_que = (string)filter_input(INPUT_POST, 'que');
$Q_id_lugar = (integer)filter_input(INPUT_POST, 'id_lugar');
$Q_prot_num = (integer)filter_input(INPUT_POST, 'prot_num');
$Q_prot_any = (string)filter_input(INPUT_POST, 'prot_any'); // string para distinguir el 00 (del 2000) de empty.

$jsondata = [];
switch ($Q_que) {
    case 'buscar_entrada_correspondiente':
        $Q_prot_any = core\any_2($Q_prot_any);

        $aProt_origen = ['id_lugar' => $Q_id_lugar,
            'num' => $Q_prot_num,
            'any' => $Q_prot_any,
        ];

        $id_entrada = '';
        $EntradaRepository = new EntradaRepository();
        $cEntradas = $EntradaRepository->getEntradasByProtOrigenDB($aProt_origen);
        foreach ($cEntradas as $oEntrada) {
            $bypass = $oEntrada->isBypass();
            $anulado = $oEntrada->getAnulado();
            if ($bypass) {
                continue;
            }
            if (!empty($anulado)) {
                continue;
            }
            $id_entrada = $oEntrada->getId_entrada();
        }

        if (!empty($id_entrada)) {
            $jsondata['success'] = true;
            $jsondata['id_entrada'] = $id_entrada;
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = _("No se...");
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();

        break;
    case 'buscar_referencia_correspondiente':
        $Q_para = (string)filter_input(INPUT_POST, 'para');
        $Q_prot_any = core\any_2($Q_prot_any);

        // Si es de la dl busco en escritos, sino en entradas:
        $LugarRepository = new LugarRepository();
        $id_sigla_local = $LugarRepository->getId_sigla_local();
        if ($Q_id_lugar === $id_sigla_local) {
            // Escritos
            $aProt_local = ['id_lugar' => $Q_id_lugar,
                'num' => $Q_prot_num,
                'any' => $Q_prot_any,
            ];
            $id_escrito = '';
            $EscritoRepository = new EscritoRepository();
            $CargoRepository = new CargoRepository();
            $cEscritos = $EscritoRepository->getEscritosByProtLocal($aProt_local);
            foreach ($cEscritos as $oEscrito) {
                $id_escrito = $oEscrito->getId_escrito();
                $jsondata['asunto'] = $oEscrito->getAsunto();
                $jsondata['detalle'] = $oEscrito->getDetalle();
                $jsondata['categoria'] = $oEscrito->getCategoria();
                $jsondata['visibilidad'] = $oEscrito->getVisibilidad();
                // los escritos van por cargos, las entradas por oficinas: pongo al director de la oficina:
                $id_ponente = $oEscrito->getPonente();
                $a_firmas = $oEscrito->getResto_oficinas();

                if ($Q_para === 'escrito') {
                    $jsondata['id_ponente'] = $id_ponente;
                    $jsondata['firmas'] = $a_firmas;
                }
                if ($Q_para === 'entrada') {
                    $oCargo = $CargoRepository->findById($id_ponente);
                    $id_of_ponente = $oCargo->getId_oficina();
                    $jsondata['id_ponente'] = $id_of_ponente;
                    $a_oficinas = [];
                    foreach ($a_firmas as $id_cargo) {
                        $oCargo = $CargoRepository->findById($id_cargo);
                        $id_oficina = $oCargo->getId_oficina();
                        $a_oficinas[] = $id_oficina;
                    }
                    $jsondata['oficinas'] = $a_oficinas;
                }
            }
        } else {
            // Entradas
            $aProt_origen = ['id_lugar' => $Q_id_lugar,
                'num' => $Q_prot_num,
                'any' => $Q_prot_any,
            ];

            $id_entrada = '';
            $EntradaRepository = new EntradaRepository();
            $cEntradas = $EntradaRepository->getEntradasByProtOrigenDB($aProt_origen);
            foreach ($cEntradas as $oEntrada) {
                $bypass = $oEntrada->isBypass();
                if ($bypass) {
                    continue;
                }
                $id_entrada = $oEntrada->getId_entrada();
                $jsondata['asunto'] = $oEntrada->getAsunto();
                $jsondata['detalle'] = $oEntrada->getDetalle();
                $jsondata['categoria'] = $oEntrada->getCategoria();
                $jsondata['visibilidad'] = $oEntrada->getVisibilidad();
                // los escritos van por cargos, las entradas por oficinas: pongo al director de la oficina:
                //Ponente;
                $id_of_ponente = $oEntrada->getPonente();
                // oficinas
                $a_oficinas = $oEntrada->getResto_oficinas();

                if ($Q_para === 'entrada') {
                    $jsondata['id_ponente'] = $id_of_ponente;
                    $jsondata['oficinas'] = $a_oficinas;
                }
                if ($Q_para === 'escrito') {
                    $CargoRepository = new CargoRepository();
                    // Ponente
                    $id_ponente = $CargoRepository->getDirectorOficina($id_of_ponente);
                    // oficinas
                    $a_oficinas = $oEntrada->getResto_oficinas();
                    $a_resto_cargos = [];
                    foreach ($a_oficinas as $id_oficina) {
                        $a_resto_cargos[] = $CargoRepository->getDirectorOficina($id_oficina);
                    }
                    $jsondata['id_ponente'] = $id_ponente;
                    $jsondata['firmas'] = $a_resto_cargos;
                }
            }
        }

        if (!empty($id_entrada) || !empty($id_escrito)) {
            $jsondata['success'] = true;
        } else {
            $jsondata['success'] = false;
            $jsondata['mensaje'] = _("No encuentro nada con esta ref.");
        }

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
        break;
    default:
        $err_switch = sprintf(_("opci??n no definida en switch en %s, linea %s"), __FILE__, __LINE__);
        exit ($err_switch);
}