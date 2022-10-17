<?php

// INICIO Cabecera global de URL de controlador *********************************
use documentos\model\Documento;

require_once("apps/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("apps/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

// El delete es via POST!!!";

$Q_id_doc = (integer)filter_input(INPUT_POST, 'key');

if (!empty($Q_id_doc)) {
    $oDocumento = new Documento($Q_id_doc);
    $oDocumento->DBCargar('');

    /* The deleteUrl server action must send data via AJAX request as a JSON response {error: BOOLEAN_VALUE} */
    $error = FALSE;
    $oDocumento->setDocumento('');
    if ($oDocumento->DBGuardar() === FALSE) {
        $error = TRUE;
    }
} else {
    $error = TRUE;
}

$outData = "{'error': $error}";
echo json_encode($outData); // return json data