<?php
namespace envios\model;

use PHPMailer\PHPMailer\Exception;
use entradas\model\Entrada;
use entradas\model\entity\EntradaAdjunto;
use entradas\model\entity\GestorEntradaBypass;
use etherpad\model\Etherpad;
use expedientes\model\Escrito;
use expedientes\model\entity\EscritoAdjunto;
use lugares\model\entity\Grupo;
use lugares\model\entity\Lugar;
use web\Protocolo;
use web\ProtocoloArray;


class Enviar {
    /**
     *
     * @var object
     */
    private $oEtherpad;
    /**
     *
     * @var boolean
     */
    private $bLoaded = FALSE;
    /**
     *
     * @var integer
     */
    private $iid;
    /**
     *
     * @var string
     */
    private $tipo;
    /**
     *
     * @var string
     */
    private $destinos_txt;
    /**
     *
     * @var \DateTime
     *
     */
    private $f_salida;
    
    private $filename;
    private $filename_ext;
    private $contentFile;
    private $a_adjuntos;
    
    private $cabecera_izq;
    private $cabecera_dcha;
    private $oArrayProtDestino;
    
    private $a_rta=[];
    
    public function __construct($id='',$tipo) {
        $this->setId($id);
        $this->setTipo($tipo);
    }
    
    public function setId($id) {
        $this->iid = $id;
        $this->bLoaded = FALSE;
    }
    
    public function setTipo($tipo) {
        $this->tipo = $tipo;
        $this->bLoaded = FALSE;
    }
    
    private function getDestinatarios(){
        if ($this->tipo == 'entrada') {
            return $this->getDestinosByPass();
        }
        if ($this->tipo == 'escrito') {
            return $this->getDestinosEscrito();
        }
    }
    
    private function getDocumento($id_lugar='') {
        if ($this->tipo == 'entrada') {
            return $this->getDatosEntrada($id_lugar);
        }
        if ($this->tipo == 'escrito') {
            return $this->getDatosEscrito($id_lugar);
        }
    }
    
    private function getDestinosByPass() {
        $id_entrada = $this->iid;
        // a ver si ya está
        $gesEntradasBypass = new GestorEntradaBypass();
        $cEntradasBypass = $gesEntradasBypass->getEntradasBypass(['id_entrada' => $id_entrada]);
        if (count($cEntradasBypass) > 0) {
            // solo debería haber una:
            $oEntradaBypass = $cEntradasBypass[0];
            
            $a_grupos = $oEntradaBypass->getId_grupos();
            $this->f_salida = $oEntradaBypass->getF_salida()->getFromLocal('.');
            
            $aMiembros = [];
            if (!empty($a_grupos)) {
                $destinos_txt = $oEntradaBypass->getDescripcion();
                //(segun los grupos seleccionados)
                foreach ($a_grupos as $id_grupo) {
                    $oGrupo = new Grupo($id_grupo);
                    $a_miembros_g = $oGrupo->getMiembros();
                    $aMiembros = array_merge($aMiembros, $a_miembros_g);
                }
                $aMiembros = array_unique($aMiembros);
                $oEntradaBypass->setDestinos($aMiembros);
                $oEntradaBypass->DBGuardar();
            } else {
                //(segun individuales)
                $destinos_txt = '';
                $a_json_prot_dst = $oEntradaBypass->getJson_prot_destino();
                foreach ($a_json_prot_dst as $json_prot_dst) {
                    $aMiembros[] = $json_prot_dst->lugar;
                    $oLugar = new Lugar($json_prot_dst->lugar);
                    $destinos_txt .= empty($destinos_txt)? '' : ', ';
                    $destinos_txt .= $oLugar->getNombre();
                }
            }
        }
        $this->destinos_txt = $destinos_txt;
        return $aMiembros;
    }
    
    private function getDestinosEscrito() {
        $id_escrito = $this->iid;
        $oEscrito = new Escrito($id_escrito);
        
        $a_grupos = $oEscrito->getId_grupos();
        
        $aMiembros = [];
        if (!empty($a_grupos)) {
            //(segun los grupos seleccionados)
            foreach ($a_grupos as $id_grupo) {
                $oGrupo = new Grupo($id_grupo);
                $a_miembros_g = $oGrupo->getMiembros();
                $aMiembros = array_merge($aMiembros, $a_miembros_g);
            }
            $aMiembros = array_unique($aMiembros);
            $oEscrito->setDestinos($aMiembros);
            $oEscrito->DBGuardar();
        } else {
            //(segun individuales)
            $a_json_prot_dst = $oEscrito->getJson_prot_destino();
            foreach ($a_json_prot_dst as $json_prot_dst) {
                $aMiembros[] = $json_prot_dst->lugar;
            }
        }
        // Si no hay ni grupos ni json, miro ids
        if (empty($aMiembros)) {
            $aMiembros = $oEscrito->getDestinos();
        }
        return $aMiembros;
    }
    
    private function getDatosEntrada($id_lugar) {
        $oEntrada = new Entrada($this->iid);
        $this->f_salida = $oEntrada->getF_documento()->getFromLocal('.');
        
        $a_header = [ 'left' => $oEntrada->cabeceraIzquierda(),
            'center' => '',
            'right' => $oEntrada->cabeceraDerecha(),
        ];

        $json_prot_origen = $oEntrada->getJson_prot_origen();
        if (count(get_object_vars($json_prot_origen)) == 0) {
            exit (_("No hay más"));
        }
        $oProtOrigen = new Protocolo();
        $oProtOrigen->setLugar($json_prot_origen->lugar);
        $oProtOrigen->setProt_num($json_prot_origen->num);
        $oProtOrigen->setProt_any($json_prot_origen->any);
        $oProtOrigen->setMas($json_prot_origen->mas);
        $this->filename = $this->renombrar($oProtOrigen->ver_txt());
        
        $oEtherpad = new Etherpad();
        $oEtherpad->setId (Etherpad::ID_ENTRADA,$this->iid);
        
        // formato pdf:
        $this->filename_ext = $this->filename.'.pdf';
        $omPdf = $oEtherpad->generarPDF($a_header,$this->f_salida);
        
        $this->contentFile = $omPdf->Output($this->filename_ext,'S');
        
        // Attachments
        $a_adjuntos = [];
        $a_id_adjuntos = $oEntrada->getArrayIdAdjuntos();
        foreach ($a_id_adjuntos as $item => $adjunto_filename) {
            $oEntradaAdjunto = new EntradaAdjunto($item);
            $escrito = $oEntradaAdjunto->getAdjunto();
            $escrito_txt = stream_get_contents($escrito);
            $a_adjuntos[$adjunto_filename] = $escrito_txt;
        }
        $this->a_adjuntos = $a_adjuntos;
    }
    
    private function getDatosEscrito($id_lugar) {
        // para no tener que repetir todo cuando hay multiples destinos
        if ($this->bLoaded === FALSE) {
            $oEscrito = new Escrito($this->iid);
            // f_salida
            $this->f_salida = $oEscrito->getF_escrito()->getFromLocal('.');
            // Attachments
            $a_adjuntos = [];
            $a_id_adjuntos = $oEscrito->getArrayIdAdjuntos();
            foreach ($a_id_adjuntos as $item => $adjunto_filename) {
                $oEscritoAdjunto = new EscritoAdjunto($item);
                $escrito = $oEscritoAdjunto->getAdjunto();
                $escrito_txt = stream_get_contents($escrito);
                $a_adjuntos[$adjunto_filename] = $escrito_txt;
            }
            $this->a_adjuntos = $a_adjuntos;
            // nombre del archivo
            $json_prot_local = $oEscrito->getJson_prot_local();
            if (empty((array)$json_prot_local)) {
                // Ahora si dejo (30-12-2020)
                /*
                $this->a_rta['success'] = FALSE;
                $this->a_rta['mensaje'] = _("No está definido el protocolo local");
                return FALSE;
                */
                $this->filename = _("sin_protocolo");
            } else {
                $oProtOrigen = new Protocolo();
                $oProtOrigen->setLugar($json_prot_local->lugar);
                $oProtOrigen->setProt_num($json_prot_local->num);
                $oProtOrigen->setProt_any($json_prot_local->any);
                $oProtOrigen->setMas($json_prot_local->mas);
                $this->filename = $this->renombrar($oProtOrigen->ver_txt());
            }
            // etherpad
            $oEtherpad = new Etherpad();
            $oEtherpad->setId (Etherpad::ID_ESCRITO,$this->iid);
            $this->oEtherpad = $oEtherpad;
            // cabeceras
            $this->cabecera_izq = $oEscrito->cabeceraIzquierda();
            $this->cabecera_dcha = $oEscrito->cabeceraDerecha();
            
            $this->bLoaded = TRUE;
        }
        
        if (empty($id_lugar)) {
            $a_header = [ 'left' => $this->cabecera_izq,
                'center' => '',
                'right' => $this->cabecera_dcha,
            ];
        } else {
            $oLugar = new Lugar($id_lugar);
            $cabecera_izq = $oLugar->getSigla();
            $a_header = [ 'left' => $cabecera_izq,
                'center' => '',
                'right' => $this->cabecera_dcha,
            ];
            
        }
        
        // formato pdf:
        $this->filename_ext = $this->filename.'.pdf';
        $omPdf = $this->oEtherpad->generarPDF($a_header,$this->f_salida);
        
        $this->contentFile = $omPdf->Output($this->filename_ext,'S');
        
    }
    
    /**
     * para descargar en local
     *
     * @return array|string
     */
    public function getPdf() {
        if ($this->getDocumento() === FALSE) {
            return FALSE;
        }
        $filename = $this->filename;
        $filename_ext = $this->filename_ext;
        $contentFile = $this->contentFile;
        
        $a_Txt = ['content' => $contentFile,
            'name' => $filename,
            'ext' => $filename_ext,
        ];
        //$a_adjuntos = $this->a_adjuntos;
        
        return $a_Txt;
    }
    
    public function enviar() {
        $aDestinos = $this->getDestinatarios();
        
        $message = $_SESSION['oConfig']->getBodyMail();
        $message = empty($message)? _("Ver archivos adjuntos") : $message; 
        
        $err_mail = '';
        $num_enviados = 0;
        foreach ($aDestinos as $id_lugar) {
            ini_set( 'display_errors', 1 );
            error_reporting( E_ALL );
            
            $oMail = new TramityMail(TRUE); //passing 'true' enables exceptions
            $oLugar = new Lugar();
            $oLugar->setId_lugar($id_lugar);
            $oLugar->DBCarregar(); // obligar a recargar después de cambiar el id.
            $email = $oLugar->getE_mail();
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $oMail->addBCC($email);
                // generar el mail, Uno para cada destino (para poder poner bien la cabecera) en cco (bcc):
                try {
                    // generar un nuevo content, con la cabecera al ctr concreto.
                    $this->getDocumento($id_lugar);
                    $filename = $this->filename;
                    $filename_ext = $this->filename_ext;
                    $a_adjuntos = $this->a_adjuntos;
                    $contentFile = $this->contentFile;
                    $subject = "$filename";
                    // Attachments
                    //$oMail->addAttachment($File, $filename);    // Optional name
                    $oMail->addStringAttachment($contentFile, $filename_ext);    // Optional name
                    //$oMail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
                    //$oMail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name
                    
                    // adjuntos:
                    foreach ($a_adjuntos as $adjunto_filename => $escrito_txt) {
                        $oMail->addStringAttachment($escrito_txt, $adjunto_filename);    // Optional name
                    }
                    
                    // Content
                    $oMail->isHTML(true);                                  // Set email format to HTML
                    $oMail->Subject = $subject;
                    $oMail->Body    = $message;
                    //$oMail->AltBody = 'This is the body in plain text for non-HTML mail clients';
                    
                    $oMail->send();
                    $this->a_rta['success'] = TRUE;
                    $this->a_rta['mensaje'] = 'Message has been sent<br>';
                    $this->a_rta['marcar'] = TRUE;
                    $num_enviados++;
                } catch (Exception $e) {
                    $err_mail .= empty($err_mail)? '' : '<br>';
                    $err_mail .= "Message could not be sent. Mailer Error: {$oMail->ErrorInfo}";
                }
            } else {
                $err_mail .= empty($err_mail)? '' : '<br>';
                $err_mail .= $oLugar->getNombre() ."($email)";
            }
        
        }
        if (empty($aDestinos)) {
            $err_mail = _("No hay destinos para este escrito").':<br>'.$this->filename;
            $this->a_rta['success'] = FALSE;
            $this->a_rta['mensaje'] = $err_mail;
            $this->a_rta['marcar'] = FALSE;
            return $this->a_rta;
        }
        if (!empty($err_mail)) {
            $err_mail = _("mail no válido para").':<br>'.$err_mail;
            $this->a_rta['success'] = FALSE;
            $this->a_rta['mensaje'] = $err_mail;
            $this->a_rta['marcar'] = FALSE;
            if ($num_enviados > 1) {
                $this->a_rta['marcar'] = TRUE;
            }
        }
        return $this->a_rta;
    }
    
    private function renombrar($string) {
        //cambiar '/' por '_':
        $new = str_replace('/', '_', $string);
        return $new;
    }
}