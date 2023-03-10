<?php

namespace oasis_as4\model;


abstract class As4CollaborationInfo
{
    /* CONST -------------------------------------------------------------- */

    // tipo entrada
    const ACCION_NUEVO = 'nuevo';
    const ACCION_ELIMINAR = 'eliminar';
    const ACCION_ANULAR = 'anular';
    const ACCION_REEMPLAZAR = 'reemplazar';
    const ACCION_COMPARTIR = 'compartir';
    const ACCION_ORDEN_ANULAR = 'orden_anular';

    /* PROPIEDADES -------------------------------------------------------------- */

    protected $plataforma_destino;
    protected $accion;


    /* MÉTODOS PÚBLICOS ----------------------------------------------------------*/

    public function getArrayAccion()
    {
        return [
            self::ACCION_ANULAR => _("anular"),
            self::ACCION_ELIMINAR => _("eliminar"),
            self::ACCION_NUEVO => _("nuevo"),
            self::ACCION_COMPARTIR => _("compartir"),
            self::ACCION_REEMPLAZAR => _("reemplazar"),
            self::ACCION_ORDEN_ANULAR => _("orden anular"),
        ];
    }

    protected function getPm_id()
    {
        return 'pm-' . $this->getPlataforma_Origen() . '-' . $this->getPlataforma_Destino() . '-' . $this->getAccion();
    }

    /**
     * @return mixed
     */
    public function getPlataforma_Origen()
    {
        return $_SESSION['oConfig']->getNomDock();
    }

    /**
     * @return mixed
     */
    public function getPlataforma_Destino()
    {
        return $this->plataforma_destino;
    }

    /**
     * @param mixed $plataforma_destino
     */
    public function setPlataforma_Destino($plataforma_destino)
    {
        $this->plataforma_destino = $plataforma_destino;
    }

    /**
     * @return mixed
     */
    public function getAccion()
    {
        return $this->accion;
    }

    /**
     * @param mixed $accion
     */
    public function setAccion($accion)
    {
        $this->accion = strtolower($accion);
    }

}