<?php

namespace expedientes\model;

use core\ConfigGlobal;
use expedientes\domain\entity\Expediente;
use expedientes\domain\repositories\ExpedienteRepository;
use usuarios\domain\repositories\CargoRepository;
use function core\is_true;


class ExpedienteAcabadosEncargadosLista
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
        $this->setCondicion();
        $pagina_mod = ConfigGlobal::getWeb() . '/src/expedientes/controller/expediente_distribuir.php';
        $pagina_ver = ConfigGlobal::getWeb() . '/src/expedientes/controller/expediente_distribuir.php';

        $oFormatoLista = new FormatoLista();
        $oFormatoLista->setPresentacion(5);
        $oFormatoLista->setColumnaModVisible(TRUE);
        $oFormatoLista->setColumnaVerVisible(TRUE);
        $oFormatoLista->setColumnaFIniVisible(TRUE);
        $oFormatoLista->setPaginaMod($pagina_mod);
        $oFormatoLista->setPaginaVer($pagina_ver);
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
        $oExpedienteLista = new ExpedienteLista($cExpedientes, $oFormatoLista, new ExpedientesDeColor());
        $oExpedienteLista->setFiltro($this->filtro);

        $oExpedienteLista->mostrarTabla();
    }

    public function setCondicion(): void
    {
        $this->aWhere = [];
        $this->aOperador = [];
        $this->aWhere['estado'] = Expediente::ESTADO_ACABADO_ENCARGADO;
        // Si es el director los ve todos
        if (is_true(ConfigGlobal::soy_dtor())) {
            $a_cargos_oficina = [];
            // posibles oficiales de la oficina:
            $CargoRepository = new CargoRepository();
            $oCargo = $CargoRepository->findById(ConfigGlobal::role_id_cargo());
            if ($oCargo !== null) {
                $id_oficina = $oCargo->getId_oficina();
                $a_cargos_oficina = $CargoRepository->getArrayCargosOficina($id_oficina);
            }
            $a_cargos = [];
            foreach (array_keys($a_cargos_oficina) as $id_cargo) {
                $a_cargos[] = $id_cargo;
            }
            if (!empty($a_cargos)) {
                $this->aWhere['ponente'] = implode(',', $a_cargos);
                $this->aOperador['ponente'] = 'IN';
            } else {
                // para que no salga nada pongo
                $this->aWhere = [];
            }
        } else {
            // solo los propios:
            $this->aWhere['ponente'] = ConfigGlobal::role_id_cargo();
        }
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