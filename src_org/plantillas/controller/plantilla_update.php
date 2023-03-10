<?php

use core\ConfigGlobal;
use escritos\domain\entity\Escrito;
use escritos\domain\repositories\EscritoRepository;
use etherpad\model\Etherpad;
use expedientes\domain\entity\Accion;
use expedientes\domain\repositories\AccionRepository;
use plantillas\domain\entity\Plantilla;
use plantillas\domain\repositories\PlantillaRepository;
use web\DateTimeLocal;

// INICIO Cabecera global de URL de controlador *********************************
require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Q_que = (string)filter_input(INPUT_POST, 'que');

switch ($Q_que) {
    case 'copiar':
        // crear escrito
        $Q_id_expediente = (integer)filter_input(INPUT_POST, 'id_expediente');
        $Q_id_plantilla = (integer)filter_input(INPUT_POST, 'id_plantilla');
        $Q_filtro = (integer)filter_input(INPUT_POST, 'filtro');
        // Para saber el nombre de la plantilla:
        $PlantillaRepository = new PlantillaRepository();
        $oPlantilla = $PlantillaRepository->findById($Q_id_plantilla);
        $asunto = $oPlantilla->getNombre();

        // crear un nuevo escrito:
        $escritoRepository = new EscritoRepository();
        $id_escrito = $escritoRepository->getNewId_escrito();
        $oEscrito = new Escrito();
        $oEscrito->setId_escrito($id_escrito);
        $oEscrito->setAccion(Escrito::ACCION_PLANTILLA);
        $oEscrito->setModo_envio(Escrito::MODO_MANUAL);
        $oEscrito->setTipo_doc(Escrito::TIPO_ETHERPAD);

        $oHoy = new DateTimeLocal();
        $f_escrito = $oHoy->getFromLocal();
        $id_ponente = ConfigGlobal::role_id_cargo();

        $oEscrito->setF_escrito($f_escrito);
        $oEscrito->setCreador($id_ponente);
        $oEscrito->setOK(Escrito::OK_NO);

        // El asunto no puede ser nulo (cojo el nombre de la plantilla)
        $oEscrito->setAsunto($asunto);

        if ($escritoRepository->Guardar($oEscrito) === FALSE) {
            echo _("hay un error, no se ha guardado");
            echo "\n" . $escritoRepository->getErrorTxt();
        }

        $id_escrito = $oEscrito->getId_escrito();

        $AccionRepository = new AccionRepository();
        $id_item = $AccionRepository->getNewId_item();
        $oAccion = new Accion();
        $oAccion->setId_item($id_item);
        $oAccion->setId_expediente($Q_id_expediente);
        $oAccion->setId_escrito($id_escrito);
        $oAccion->setTipo_accion(Escrito::ACCION_PLANTILLA);
        if ($AccionRepository->Guardar($oAccion) === FALSE) {
            echo _("hay un error, no se ha guardado");
            echo "\n" . $AccionRepository->getErrorTxt();
        }


        //clone:
        $oEtherpad = new Etherpad();
        $oEtherpad->setId(Etherpad::ID_PLANTILLA, $Q_id_plantilla);
        $sourceID = $oEtherpad->getPadId();

        $oNewEtherpad = new Etherpad();
        $oNewEtherpad->setId(Etherpad::ID_ESCRITO, $id_escrito);
        $destinationID = $oNewEtherpad->getPadID();

        $rta = $oEtherpad->copyPad($sourceID, $destinationID, 'true');

        /* con el Html, no hace bien los centrados (quiz?? m??s)
         * con el Text no coje los formatos.
        // copiar etherpad:
        $oEtherpad = new Etherpad();
        $oEtherpad->setId(Etherpad::ID_PLANTILLA, $Q_id_plantilla);
        //$padID = $oEtherpad->getPadId();
        //$txtPad = $oEtherpad->getTexto($padID);
        $htmlPad = $oEtherpad->getHHtml();
        
        // canviar el id, y clonar el etherpad con el nuevo id
        $oNewEtherpad = new Etherpad();
        $oNewEtherpad->setId(Etherpad::ID_ESCRITO, $id_escrito);
        $padId = $oNewEtherpad->getPadID();
        //$oNewEtherpad->setText($txtPad);
        $oNewEtherpad->setHTML($padId,$htmlPad);
        $oNewEtherpad->getPadId(); // Aqui crea el pad y utiliza el $txtPad
        */


        $jsondata['success'] = true;
        $jsondata['id_escrito'] = $id_escrito;
        $a_cosas = ['id_escrito' => $id_escrito, 'id_expediente' => $Q_id_expediente, 'filtro' => $Q_filtro];
        $pagina_mod = web\Hash::link('src/escritos/controller/escrito_form.php?' . http_build_query($a_cosas));
        $jsondata['pagina_mod'] = $pagina_mod;

        //Aunque el content-type no sea un problema en la mayor??a de casos, es recomendable especificarlo
        header('Content-type: application/json; charset=utf-8');
        echo json_encode($jsondata);
        exit();
    case "guardar_escrito":
        $Q_id_escrito = (integer)filter_input(INPUT_POST, 'id_escrito');
        $Q_id_plantilla = (integer)filter_input(INPUT_POST, 'id_plantilla');
        $Q_nombre = (string)filter_input(INPUT_POST, 'nombre');
        $Q_a_lugares = (array)filter_input(INPUT_POST, 'lugares', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (empty($Q_nombre)) {
            echo _("debe poner un nombre");
        }

        $escritoRepository = new EscritoRepository();
        $oEscrito = $escritoRepository->findById($Q_id_escrito);
        // borrar destinos existentes
        $oEscrito->setJson_prot_destino([]);
        $oEscrito->setId_grupos();
        // poner nueva selecci??n
        $oEscrito->setDestinos($Q_a_lugares);
        $oEscrito->setDescripcion($Q_nombre);

        if ($escritoRepository->Guardar($oEscrito) === FALSE) {
            echo _("hay un error, no se ha guardado");
            echo "\n" . $escritoRepository->getErrorTxt();
        }
        break;
    case "eliminar":
        $a_sel = (array)filter_input(INPUT_POST, 'sel', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if (!empty($a_sel)) { //vengo de un checkbox
            $Q_id_plantilla = (integer)strtok($a_sel[0], "#");
            $PlantillaRepository = new PlantillaRepository();
            $oPlantilla = $PlantillaRepository->findById($Q_id_plantilla);
            if ($PlantillaRepository->Eliminar($oPlantilla) === FALSE) {
                echo _("hay un error, no se ha eliminado");
                echo "\n" . $PlantillaRepository->getErrorTxt();
            }
        }
        break;
    case "nuevo":
    case "guardar":
        $Q_id_plantilla = (integer)filter_input(INPUT_POST, 'id_plantilla');
        $Q_nombre = (string)filter_input(INPUT_POST, 'nombre');

        if (empty($Q_nombre)) {
            echo _("debe poner un nombre");
        }

        $PlantillaRepository = new PlantillaRepository();
        $oPlantilla = $PlantillaRepository->findById($Q_id_plantilla);
        if ($oPlantilla === null) {
            $Q_id_plantilla = $PlantillaRepository->getNewId_plantilla();
            $oPlantilla = new Plantilla();
            $oPlantilla->setId_plantilla($Q_id_plantilla);
        }
        $oPlantilla->setNombre($Q_nombre);
        if ($PlantillaRepository->Guardar($oPlantilla) === FALSE) {
            echo _("hay un error, no se ha guardado");
            echo "\n" . $PlantillaRepository->getErrorTxt();
        }
        break;
    default:
        $err_switch = sprintf(_("opci??n no definida en switch en %s, linea %s"), __FILE__, __LINE__);
        exit ($err_switch);
}