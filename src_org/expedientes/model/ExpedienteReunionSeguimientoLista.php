<?php

namespace expedientes\model;

use core\ConfigGlobal;
use expedientes\domain\entity\Expediente;
use expedientes\domain\repositories\ExpedienteRepository;
use tramites\domain\repositories\FirmaRepository;


class ExpedienteReunionSeguimientoLista
{
    private string $filtro;
    private array $aWhere;
    private array $aOperador;

    public function __construct(string $filtro)
    {
        $this->filtro = $filtro;
    }

    public function mostrarTabla(): void
    {
        $oExpedientesDeColor = $this->setCondicion();
        $pagina_ver = ConfigGlobal::getWeb() . '/src/expedientes/controller/expediente_ver.php';

        $oFormatoLista = new FormatoLista();
        $oFormatoLista->setPresentacion(3);
        $oFormatoLista->setColumnaVerVisible(TRUE);
        $oFormatoLista->setColumnaFIniVisible(TRUE);
        $oFormatoLista->setPaginaVer($pagina_ver);
        $oFormatoLista->setTxtColumnaVer(_("revisar"));
        // Solo en el caso de secretaria:
        if (ConfigGlobal::role_actual() === 'secretaria') {
            $pagina_mod = ConfigGlobal::getWeb() . '/src/expedientes/controller/fecha_reunion.php';
            $oFormatoLista->setTxtColumnaMod(_("fecha"));
            $oFormatoLista->setColumnaModVisible(TRUE);
        } else {
            $pagina_mod = ConfigGlobal::getWeb() . '/src/expedientes/controller/expediente_ver.php';
            $oFormatoLista->setColumnaModVisible(FALSE);
        }
        $oFormatoLista->setPaginaMod($pagina_mod);
        /*
        if ($_SESSION['oConfig']->getAmbito() === Cargo::AMBITO_CTR) {
            $a_cosas = ['filtro' => $this->filtro];
            $pagina_nueva = Hash::link('src/expedientes/controller/expediente_form.php?' . http_build_query($a_cosas));
            $oFormatoLista->setPaginaNueva($pagina_nueva);
        }
        */

        if (!empty($this->aWhere)) {
            $ExpedienteRepository = new ExpedienteRepository();
            $this->aWhere['_ordre'] = 'id_expediente';
            $cExpedientes = $ExpedienteRepository->getExpedientes($this->aWhere, $this->aOperador);
        } else {
            $cExpedientes = [];
        }
        $oExpedienteLista = new ExpedienteLista($cExpedientes, $oFormatoLista, $oExpedientesDeColor);
        $oExpedienteLista->setFiltro($this->filtro);

        $oExpedienteLista->mostrarTabla();
    }

    public function setCondicion(): ExpedientesDeColor
    {
        $this->aWhere = [];
        $this->aOperador = [];
        $oExpedientesDeColor = new ExpedientesDeColor();

        $this->aWhere['estado'] = Expediente::ESTADO_FIJAR_REUNION;
        $this->aWhere['f_reunion'] = 'x';
        $this->aOperador['f_reunion'] = 'IS NOT NULL';

        //////// mirar los que falta alguna firma para marcarlos en color /////////
        $FirmaRepository = new FirmaRepository();
        $a_exp_reunion_falta_firma = $FirmaRepository->faltaFirmarReunion();

        //que tengan de mi firma, independiente de firmado o no
        $cFirmas = $FirmaRepository->getFirmasReunion(ConfigGlobal::role_id_cargo());
        $a_expedientes = [];
        foreach ($cFirmas as $oFirma) {
            $id_expediente = $oFirma->getId_expediente();
            $orden_tramite = $oFirma->getOrden_tramite();
            // S??lo a partir de que el orden_tramite anterior ya lo hayan firmado todos
            if (!$FirmaRepository->isAnteriorOK($id_expediente, $orden_tramite)) {
                continue;
            }
            $a_expedientes[] = $id_expediente;
        }
        if (!empty($a_expedientes)) {
            $this->aWhere['id_expediente'] = implode(',', $a_expedientes);
            $this->aOperador['id_expediente'] = 'IN';
        } else {
            // para que no salga nada pongo
            $this->aWhere = [];
        }

        $oExpedientesDeColor->setExpReunionFaltaFirma($a_exp_reunion_falta_firma);

        return $oExpedientesDeColor;
    }

    public function getNumero(): ?int
    {
        $this->setCondicion();
        if (!empty($this->aWhere)) {
            $ExpedienteRepository = new ExpedienteRepository();
            $this->aWhere['_ordre'] = 'id_expediente';
            $cExpedientes = $ExpedienteRepository->getExpedientes($this->aWhere, $this->aOperador);
            $num = count($cExpedientes);
        } else {
            $num = null;
        }
        return $num;
    }

}