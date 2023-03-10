<?php

use escritos\domain\repositories\EscritoRepository;
use escritos\model\Escrito;
use lugares\domain\entity\Grupo;
use lugares\domain\repositories\GrupoRepository;

// INICIO Cabecera global de URL de controlador *********************************
require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

$Q_que = (string)filter_input(INPUT_POST, 'que');

switch ($Q_que) {
    case "guardar_escrito":
        $Q_id_escrito = (integer)filter_input(INPUT_POST, 'id_escrito');
        $Q_id_grupo = (integer)filter_input(INPUT_POST, 'id_grupo');
        $Q_descripcion = (string)filter_input(INPUT_POST, 'descripcion');
        $Q_a_lugares = (array)filter_input(INPUT_POST, 'lugares', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (empty($Q_descripcion)) {
            echo _("debe poner un nombre");
        }
        $escritoRepository = new EscritoRepository();
        $oEscrito = $escritoRepository->findById($Q_id_escrito);
        if ($oEscrito === null) {
            $err_cargar = sprintf(_("OJO! no existe el escrito en %s, linea %s"), __FILE__, __LINE__);
            exit ($err_cargar);
        }
        // borrar destinos existentes
        $oEscrito->setJson_prot_destino([]);
        $oEscrito->setId_grupos();
        // poner nueva selección
        $oEscrito->setDestinos($Q_a_lugares);
        $oEscrito->setDescripcion($Q_descripcion);

        if ($escritoRepository->Guardar($oEscrito) === FALSE) {
            echo _("hay un error, no se ha guardado");
            echo "\n" . $escritoRepository->getErrorTxt();
        }
        break;
    case "eliminar":
        $a_sel = (array)filter_input(INPUT_POST, 'sel', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
        if (!empty($a_sel)) { //vengo de un checkbox
            $Q_id_grupo = (integer)strtok($a_sel[0], "#");
            $GrupoRepository = new GrupoRepository();
            $oGrupo = $GrupoRepository->findById($Q_id_grupo);
            if ($GrupoRepository->Eliminar($oGrupo) === FALSE) {
                echo _("hay un error, no se ha eliminado");
                echo "\n" . $GrupoRepository->getErrorTxt();
            }
        }
        break;
    case "nuevo":
    case "guardar":
        $Q_id_grupo = (integer)filter_input(INPUT_POST, 'id_grupo');
        $Q_descripcion = (string)filter_input(INPUT_POST, 'descripcion');
        $Q_a_lugares = (array)filter_input(INPUT_POST, 'lugares', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);

        if (empty($Q_descripcion)) {
            echo _("debe poner un nombre");
        }

        $GrupoRepository = new GrupoRepository();
        $oGrupo = $GrupoRepository->findById($Q_id_grupo);
        if ($oGrupo === null) {
            $id_grupo = $GrupoRepository->getNewId_grupo();
            $oGrupo = new Grupo();
            $oGrupo->setId_grupo($id_grupo);

        }
        $oGrupo->setDescripcion($Q_descripcion);
        $oGrupo->setMiembros($Q_a_lugares);
        if ($GrupoRepository->Guardar($oGrupo) === FALSE) {
            echo _("hay un error, no se ha guardado");
            echo "\n" . $GrupoRepository->getErrorTxt();
        }
        break;
}