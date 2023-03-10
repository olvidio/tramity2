<?php

use documentos\domain\entity\Documento;
use escritos\domain\entity\EscritoAdjunto;
use escritos\domain\repositories\EscritoAdjuntoRepository;

// INICIO Cabecera global de URL de controlador *********************************
require_once("src_org/core/global_header.inc");
// Archivos requeridos por esta url **********************************************

// Crea los objetos de uso global **********************************************
require_once("src_org/core/global_object.inc");
// Crea los objetos por esta url  **********************************************

// FIN de  Cabecera global de URL de controlador ********************************

// example of a PHP server code that is called in `uploadUrl` above
// file-upload-batch script
header('Content-Type: application/json'); // set json response headers
$outData = upload(); // a function to upload the bootstrap-fileinput files
echo json_encode($outData, JSON_THROW_ON_ERROR); // return json data
exit(); // terminate

// main upload function used above
// upload the bootstrap-fileinput files
// returns associative array
function upload()
{
    $Q_id_escrito = (integer)filter_input(INPUT_POST, 'id_escrito');
    $Q_id_item = (integer)filter_input(INPUT_POST, 'id_item');

    $preview = [];
    $config = [];
    $errors = [];
    $input = 'adjuntos'; // the input name for the fileinput plugin
    if (empty($_FILES[$input])) {
        return [];
    }

    $total = count($_FILES[$input]['name']); // multiple files
    $escritoAdjuntoRepository = new EscritoAdjuntoRepository();
    for ($i = 0; $i < $total; $i++) {
        $tmpFilePath = $_FILES[$input]['tmp_name'][$i]; // the temp file path
        $fileName = $_FILES[$input]['name'][$i]; // the file name
        $fileSize = $_FILES[$input]['size'][$i]; // the file size
        if ($fileSize > $_SESSION['oConfig']->getMax_filesize_en_bytes()) {
            exit (_("Fichero demasiado grande"));
        }

        //Make sure we have a file path
        if ($tmpFilePath !== '' && $Q_id_escrito) {
            $fp = fopen($tmpFilePath, 'rb');
            $contenido_doc = fread($fp, filesize($tmpFilePath));

            $oEscritoAdjunto = $escritoAdjuntoRepository->findById($Q_id_item);
            if ($oEscritoAdjunto === null) {
                $id_item = $escritoAdjuntoRepository->getNewId_item();
                $oEscritoAdjunto = new EscritoAdjunto();
                $oEscritoAdjunto->setId_item($id_item);
            }
            $oEscritoAdjunto->setId_escrito($Q_id_escrito);
            $oEscritoAdjunto->setNom($fileName);
            $oEscritoAdjunto->setTipo_doc(Documento::DOC_UPLOAD);
            $oEscritoAdjunto->setAdjunto($contenido_doc);

            if ($escritoAdjuntoRepository->Guardar($oEscritoAdjunto) !== FALSE) {
                $id_item = $oEscritoAdjunto->getId_item();
                $preview[] = "'$fileName'";
                $config[] = [
                    'key' => $id_item,
                    'caption' => $fileName,
                    'url' => 'src/escritos/controller/adjunto_delete.php', // server api to delete the file based on key
                ];
            } else {
                $errors[] = $fileName;
            }
        } else {
            $errors[] = $fileName;
        }
    }
    $out = ['initialPreview' => $preview, 'initialPreviewConfig' => $config];
    if (!empty($errors)) {
        $img = count($errors) === 1 ? 'file "' . $errors[0] . '" ' : 'files: "' . implode('", "', $errors) . '" ';
        $out['error'] = 'Oh snap! We could not upload the ' . $img . 'now. Please try again later.';
    }
    return $out;
}