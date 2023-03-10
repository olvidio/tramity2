<?php

namespace entradas\domain\entity;

use core\ConfigGlobal;
use entradas\domain\repositories\EntradaBypassRepository;
use etiquetas\domain\entity\EtiquetaEntrada;
use etiquetas\domain\repositories\EtiquetaEntradaRepository;
use etiquetas\domain\repositories\EtiquetaRepository;
use JsonException;
use lugares\domain\repositories\LugarRepository;
use usuarios\domain\entity\Cargo;
use usuarios\domain\PermRegistro;
use usuarios\domain\Visibilidad;
use web\DateTimeLocal;
use web\NullDateTimeLocal;
use web\Protocolo;
use web\ProtocoloArray;
use function core\is_true;


class Entrada extends EntradaDB
{

    /* CONST -------------------------------------------------------------- */

    // modo entrada
    public const MODO_MANUAL = 1;
    public const MODO_XML = 2;

    // estado
    /*
     - Ingresa (secretaría introduce los datos de la entrada)
     - Admitir (vcd los mira y da el ok)
     - Asignar (secretaría añade datos tipo: ponente... Puede que no se haya hecho el paso de ingresar)
     - Aceptar (scdl ok)
     - Oficinas (Las oficinas puede ver lo suyo)
     - Archivado (Ya no sale en las listas de la oficina)
     - Enviado cr (Cuando se han enviado los bypass)
     */
    public const ESTADO_INGRESADO = 1;
    public const ESTADO_ADMITIDO = 2;
    public const ESTADO_ASIGNADO = 3;
    public const ESTADO_ACEPTADO = 4;
    //const ESTADO_OFICINAS           = 5;
    public const ESTADO_ARCHIVADO = 6;
    public const ESTADO_ENVIADO_CR = 10;

    /* PROPIEDADES -------------------------------------------------------------- */

    protected DateTimeLocal|NullDateTimeLocal|null $df_doc = null;
    protected ?int $itipo_doc = null;

    protected string $nombre_escrito;

    /* CONSTRUCTOR -------------------------------------------------------------- */

    /**
     * @throws JsonException
     */
    public function cabeceraIzquierda(): string
    {
        // sigla +(visibilidad) + ref
        $oVisibilidad = new Visibilidad();

        $sigla = $_SESSION['oConfig']->getSigla();
        $destinos_txt = $sigla;
        // excepción para bypass
        if (!is_true($this->isBypass())) {
            $visibilidad = $this->getVisibilidad();
            // si soy dl o ctr
            if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
                if ($visibilidad !== null && $visibilidad !== Visibilidad::V_CTR_TODOS) {
                    $a_visibilidad_dst = $oVisibilidad->getArrayVisibilidadCtr();
                    $visibilidad_txt = $a_visibilidad_dst[$visibilidad];
                    $destinos_txt .= " ($visibilidad_txt)";
                }
            } else {
                if ($visibilidad !== null && $visibilidad !== Visibilidad::V_CTR_TODOS) {
                    $a_visibilidad_dl = $oVisibilidad->getArrayVisibilidadDl();
                    $visibilidad_txt = $a_visibilidad_dl[$visibilidad];
                    $destinos_txt .= " ($visibilidad_txt)";
                }
            }
        }

        $LugarRepository = new LugarRepository();
        $cLugares = $LugarRepository->getLugares(['sigla' => $sigla]);
        if (!empty($cLugares)) {
            $id_sigla = $cLugares[0]->getId_lugar();

            // referencias
            $a_json_prot_ref = $this->getJson_prot_ref();
            $oArrayProtRef = new ProtocoloArray($a_json_prot_ref, '', 'referencias');
            $oArrayProtRef->setRef(TRUE);
            $aRef = $oArrayProtRef->ArrayListaTxtBr($id_sigla);
        } else {
            $aRef['dst_org'] = '??';
        }

        if (!empty($aRef['dst_org'])) {
            $destinos_txt .= '<br>';
            $destinos_txt .= $aRef['dst_org'];
        }
        return $destinos_txt;
    }

    /**
     * @throws JsonException
     */
    public function cabeceraDerecha(): string
    {
        // origen + ref
        $json_prot_origen = $this->getJson_prot_origen();
        if (!empty((array)$json_prot_origen)) {
            $id_org = $json_prot_origen->id_lugar;

            // referencias
            $a_json_prot_ref = $this->getJson_prot_ref();
            $oArrayProtRef = new ProtocoloArray($a_json_prot_ref, '', 'referencias');
            $oArrayProtRef->setRef(TRUE);
            $aRef = $oArrayProtRef->ArrayListaTxtBr($id_org);

            $oProtOrigen = new Protocolo();
            $oProtOrigen->setLugar($json_prot_origen->id_lugar);
            $oProtOrigen->setProt_num($json_prot_origen->num);
            $oProtOrigen->setProt_any($json_prot_origen->any);
            $oProtOrigen->setMas($json_prot_origen->mas);

            $origen_txt = $oProtOrigen->ver_txt();
        } else {
            $origen_txt = '??';
        }

        if (!empty($aRef['dst_org'])) {
            $origen_txt .= '<br>';
            $origen_txt .= $aRef['dst_org'];
        }

        return $origen_txt;
    }

    /**
     * añadir el detalle en el asunto.
     * también el grupo de destinos (si es distrbución cr)
     * tener en cuenta los permisos...
     *
     * return string
     * @throws JsonException
     */
    public function getAsuntoDetalle(): string
    {
        //
        $txt_grupos = '';
        if ($this->isBypass()) {
            $lista_grupos = $this->cabeceraDistribucion_cr();
            $lista_grupos = empty($lista_grupos) ? _("No hay destinos") : $lista_grupos;
            $txt_grupos = "<span class=\"text-success\"> ($lista_grupos)</span>";
        }
        $asunto = $this->getAsunto();
        $detalle = $this->getDetalle();
        $asunto_detelle = empty($detalle) ? $asunto : $asunto . " [$detalle]";

        $asunto_detelle .= $txt_grupos;

        return $asunto_detelle;
    }

    public function cabeceraDistribucion_cr(): string
    {
        // a ver si ya está
        $EntradaBypassRepository = new EntradaBypassRepository();
        $cEntradasBypass = $EntradaBypassRepository->getEntradasBypass(['id_entrada' => $this->iid_entrada]);
        if (!empty($cEntradasBypass)) {
            // solo debería haber una:
            $oEntradaBypass = $cEntradasBypass[0];

            // poner los destinos
            $a_grupos = $oEntradaBypass->getId_grupos();
            $descripcion = $oEntradaBypass->getDescripcion();

            if (!empty($a_grupos)) {
                //(según los grupos seleccionados)
                $destinos_txt = $descripcion;
            } else {
                //(según individuales)
                $destinos_txt = '';
                if (!empty($descripcion)) {
                    $destinos_txt = $descripcion;
                } else {
                    $a_json_prot_dst = $oEntradaBypass->getJson_prot_destino();
                    $LugarRepository = new LugarRepository();
                    foreach ($a_json_prot_dst as $json_prot_dst) {
                        $oLugar = $LugarRepository->findById($json_prot_dst->id_lugar);
                        if ($oLugar !== null) {
                            $destinos_txt .= empty($destinos_txt) ? '' : ', ';
                            $destinos_txt .= $oLugar->getNombre();
                        }
                    }
                }
            }
        } else {
            // No hay destinos definidos.
            $destinos_txt = _("No hay destinos");
        }

        return $destinos_txt;
    }

    /**
     * Recupera l'atribut sasunto de Entrada teniendo en cuenta los permisos
     *
     * @return string sasunto
     * @throws JsonException
     */
    public function getAsunto(): string
    {
        $oPermiso = new PermRegistro();
        $perm = $oPermiso->permiso_detalle($this, 'asunto');

        $asunto = _("reservado");
        if ($perm > 0) {
            $asunto = '';
            $anulado = $this->getAnulado();
            if (!empty($anulado)) {
                $asunto = _("ANULADO") . "($anulado) ";
            }
            $asunto .= $this->getAsuntoDB();
        }
        return $asunto;
    }

    /**
     * Recupera l'atribut sdetalle de Entrada teniendo en cuenta los permisos
     *
     * @return string|null sdetalle
     * @throws JsonException
     */
    public function getDetalle(): ?string
    {
        $oPermiso = new PermRegistro();
        $perm = $oPermiso->permiso_detalle($this, 'detalle');

        $detalle = _("reservado");
        if ($perm > 0) {
            $detalle = $this->getDetalleDB();
        }
        return $detalle;
    }

    /**
     * Hay que guardar dos objetos.
     * {@inheritDoc}
     * @see \entradas\model\entity\EntradaDB::DBGuardar()
     */
    public function DBCargar($que = NULL): bool
    {
        // El objeto padre:
        if (parent::DBCargar($que) === FALSE) {
            return FALSE;
        }
        // El tipo y fecha documento:
        if (!empty($this->iid_entrada)) {
            if ($this->getId_entrada_compartida() !== null) {
                $oEntradaCompartida = new EntradaCompartida($this->iid_entrada_compartida);
                $oFdoc = $oEntradaCompartida->getF_documento();
                $this->df_doc = $oFdoc;
            } else {
                $oEntradaDocDB = new EntradaDocDB($this->iid_entrada);
                $this->df_doc = $oEntradaDocDB->getF_doc();
                $this->itipo_doc = $oEntradaDocDB->getTipo_doc();
            }
        }
        return TRUE;
    }

    /**
     * Recupera l'atribut df_doc de Entrada
     * de EntradaDocDB, o si es una entrada compartida de 'EntradaCompartida'
     *
     * @return DateTimeLocal|NullDateTimeLocal df_doc
     * @throws JsonException
     */
    public function getF_documento(): DateTimeLocal|NullDateTimeLocal
    {
        if (!isset($this->df_doc) && !empty($this->iid_entrada)) {
            if ($this->getId_entrada_compartida() !== null) {
                $oEntradaCompartida = new EntradaCompartida($this->iid_entrada_compartida);
                $oFdoc = $oEntradaCompartida->getF_documento();
                $this->df_doc = $oFdoc;
            } else {
                $oEntradaDocDB = new EntradaDocDB($this->iid_entrada);
                $oFdoc = $oEntradaDocDB->getF_doc();
                $this->df_doc = $oFdoc;
            }
        }
        if (empty($this->df_doc)) {
            return new NullDateTimeLocal();
        }
        return $this->df_doc;
    }

    /**
     *
     * @param DateTimeLocal|null $df_doc
     */
    public function setF_documento(DateTimeLocal $df_doc = null): void
    {
        $this->df_doc = $df_doc;
    }

    public function getTipo_documento(): ?int
    {
        if (!isset($this->itipo_doc) && !empty($this->iid_entrada)) {
            $oEntradaDocDB = new EntradaDocDB($this->iid_entrada);
            $this->itipo_doc = $oEntradaDocDB->getTipo_doc();
        }
        return $this->itipo_doc;
    }

    public function setTipo_documento($itipo_doc): void
    {
        $this->itipo_doc = $itipo_doc;
    }

    public function getArrayIdAdjuntos(): bool|array
    {
        return (new GestorEntradaAdjunto())->getArrayIdAdjuntos($this->iid_entrada);
    }

    /**
     * Devuelve el nombre del escrito (sigla_num_año): cr_15_05
     *
     * @param string $parentesi si existe se añade al nombre, entre parentesis
     * @return string
     * @throws JsonException
     */
    public function getNombreEscrito(string $parentesi = ''): string
    {
        $json_prot_local = $this->getJson_prot_origen();
        // nombre del archivo
        if (empty((array)$json_prot_local)) {
            // genero un id: fecha
            $f_hoy = date('Y-m-d');
            $hora = date('His');
            $this->nombre_escrito = $f_hoy . '_' . _("E12") . "($hora)";
        } else {
            $oProtOrigen = new Protocolo();
            $oProtOrigen->setLugar($json_prot_local->id_lugar);
            $oProtOrigen->setProt_num($json_prot_local->num);
            $oProtOrigen->setProt_any($json_prot_local->any);
            $oProtOrigen->setMas($json_prot_local->mas);
            $this->nombre_escrito = $this->renombrar($oProtOrigen->ver_txt());
        }
        if (!empty($parentesi)) {
            $this->nombre_escrito .= "($parentesi)";
        }
        return $this->nombre_escrito;
    }

    private function renombrar($string): string
    {
        //cambiar ' ' por '_':
        //cambiar '/' por '_':
        return str_replace(array(' ', '/'), '_', $string);
    }

    public function getEtiquetasVisiblesArray(?int $id_cargo = null): array
    {
        $cEtiquetas = $this->getEtiquetasVisibles($id_cargo);
        $a_etiquetas = [];
        foreach ($cEtiquetas as $oEtiqueta) {
            $a_etiquetas[] = $oEtiqueta->getId_etiqueta();
        }
        return $a_etiquetas;
    }

    public function getEtiquetasVisibles(?int $id_cargo = null): array
    {
        if ($id_cargo === null) {
            $id_cargo = ConfigGlobal::role_id_cargo();
        }
        $etiquetaRepository = new EtiquetaRepository();
        $cMisEtiquetas = $etiquetaRepository->getMisEtiquetas($id_cargo);
        $a_mis_etiquetas = [];
        foreach ($cMisEtiquetas as $oEtiqueta) {
            $a_mis_etiquetas[] = $oEtiqueta->getId_etiqueta();
        }
        $etiquetaEntradaRepository = new EtiquetaEntradaRepository();
        $aWhere = ['id_entrada' => $this->iid_entrada];
        $cEtiquetasEnt = $etiquetaEntradaRepository->getEtiquetasEntrada($aWhere);
        $cEtiquetas = [];
        foreach ($cEtiquetasEnt as $oEtiquetaEnt) {
            $id_etiqueta = $oEtiquetaEnt->getId_etiqueta();
            if (in_array($id_etiqueta, $a_mis_etiquetas, true)) {
                $cEtiquetas[] = $etiquetaRepository->findById($id_etiqueta);
            }
        }

        return $cEtiquetas;
    }

    public function getEtiquetasVisiblesTxt($id_cargo = ''): string
    {
        $cEtiquetas = $this->getEtiquetasVisibles($id_cargo);
        $str_etiquetas = '';
        foreach ($cEtiquetas as $oEtiqueta) {
            $str_etiquetas .= empty($str_etiquetas) ? '' : ', ';
            $str_etiquetas .= $oEtiqueta->getNom_etiqueta();
        }
        return $str_etiquetas;
    }

    public function getEtiquetas(): array
    {
        $etiquetaRepository = new EtiquetaRepository();
        $etiquetaEntradaRepository = new EtiquetaEntradaRepository();
        $aWhere = ['id_entrada' => $this->iid_entrada];
        $cEtiquetasExp = $etiquetaEntradaRepository->getEtiquetasEntrada($aWhere);
        $cEtiquetas = [];
        foreach ($cEtiquetasExp as $oEtiquetaExp) {
            $id_etiqueta = $oEtiquetaExp->getId_etiqueta();
            $cEtiquetas[] = $etiquetaRepository->findById($id_etiqueta);
        }

        return $cEtiquetas;
    }

    public function setEtiquetas($aEtiquetas): void
    {
        $this->delEtiquetas();
        $a_filter_etiquetas = array_filter($aEtiquetas); // Quita los elementos vacíos y nulos.
        $etiquetaEntradaRepository = new EtiquetaEntradaRepository();
        foreach ($a_filter_etiquetas as $id_etiqueta) {
            $EtiquetaEntrada = new EtiquetaEntrada();
            $EtiquetaEntrada->setId_etiqueta($id_etiqueta);
            $EtiquetaEntrada->setId_entrada($this->iid_entrada);
            $etiquetaEntradaRepository->Guardar($EtiquetaEntrada);
        }
    }

    public function delEtiquetas(): bool
    {
        $etiquetaEntradaRepository = new EtiquetaEntradaRepository();
        return $etiquetaEntradaRepository->deleteEtiquetasEntrada($this->iid_entrada) !== FALSE;
    }

    /**
     * Hay que guardar dos objetos.
     * {@inheritDoc}
     * @see \entradas\domain\entity\EntradaDB::DBGuardar()
     *
     * TODO
     */
    public function DBGuardar(): bool
    {
        // El tipo y fecha documento: (excepto si es nuevo)
        if (!empty($this->iid_entrada)) {
            $oEntradaDocDB = new EntradaDocDB($this->iid_entrada);
            $oEntradaDocDB->setF_doc($this->df_doc);
            $oEntradaDocDB->setTipo_doc($this->itipo_doc);
            if ($oEntradaDocDB->DBGuardar() === FALSE) {
                return FALSE;
            }
        }
        // El objeto padre:
        return parent::DBGuardar();
    }


    /**
     * Comprueba si lo han visto todos y lo pone en estado Archivado
     *
     * @throws JsonException
     */
    public function comprobarVisto(): void
    {
        $ponente = $this->getPonente();
        $resto_oficinas = $this->getResto_oficinas();

        $a_json_visto = $this->getJson_visto();
        foreach ($a_json_visto as $json_visto) {
            $id_oficina = $json_visto['oficina'];
            $visto = $json_visto['visto'];
            if ($visto === 'true') {
                if ($id_oficina === $ponente) {
                    $ponente = '';
                } else {
                    $key_of = array_search($id_oficina, $resto_oficinas, true);
                    unset($resto_oficinas[$key_of]);
                }
            }
        }

        if (empty($ponente) && empty($resto_oficinas)) {
            $this->setEstado(self::ESTADO_ARCHIVADO);
            /* TODO
            $this->DBGuardar();
            */
        }

    }


}

