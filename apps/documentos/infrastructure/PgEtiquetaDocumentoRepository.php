<?php

namespace documentos\infrastructure;

use core\ClaseRepository;
use core\Condicion;
use core\Set;
use documentos\domain\entity\EtiquetaDocumento;
use documentos\domain\repositories\EtiquetaDocumentoRepositoryInterface;
use etiquetas\domain\repositories\EtiquetaRepository;
use PDO;
use PDOException;


/**
 * Clase que adapta la tabla etiquetas_documento a la interfaz del repositorio
 *
 * @package tramity
 * @subpackage model
 * @author Daniel Serrabou
 * @version 2.0
 * @created 12/12/2022
 */
class PgEtiquetaDocumentoRepository extends ClaseRepository implements EtiquetaDocumentoRepositoryInterface
{
    public function __construct()
    {
        $oDbl = $GLOBALS['oDBT'];
        $this->setoDbl($oDbl);
        $this->setNomTabla('etiquetas_documento');
    }

    /* -------------------- GESTOR BASE ---------------------------------------- */

    /**
     * devuelve una colección (array) de objetos de tipo EtiquetaDocumento
     *
     * @param array $aWhere asociativo con los valores para cada campo de la BD.
     * @param array $aOperators asociativo con los operadores que hay que aplicar a cada campo
     * @return array|FALSE Una colección de objetos de tipo EtiquetaDocumento
     */
    public function getEtiquetasDocumento(array $aWhere = [], array $aOperators = []): array|false
    {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        $EtiquetaDocumentoSet = new Set();
        $oCondicion = new Condicion();
        $aCondicion = array();
        foreach ($aWhere as $camp => $val) {
            if ($camp === '_ordre') {
                continue;
            }
            if ($camp === '_limit') {
                continue;
            }
            $sOperador = $aOperators[$camp] ?? '';
            if ($a = $oCondicion->getCondicion($camp, $sOperador, $val)) {
                $aCondicion[] = $a;
            }
            // operadores que no requieren valores
            if ($sOperador === 'BETWEEN' || $sOperador === 'IS NULL' || $sOperador === 'IS NOT NULL' || $sOperador === 'OR') {
                unset($aWhere[$camp]);
            }
            if ($sOperador === 'IN' || $sOperador === 'NOT IN') {
                unset($aWhere[$camp]);
            }
            if ($sOperador === 'TXT') {
                unset($aWhere[$camp]);
            }
        }
        $sCondicion = implode(' AND ', $aCondicion);
        if ($sCondicion !== '') {
            $sCondicion = " WHERE " . $sCondicion;
        }
        $sOrdre = '';
        $sLimit = '';
        if (isset($aWhere['_ordre']) && $aWhere['_ordre'] !== '') {
            $sOrdre = ' ORDER BY ' . $aWhere['_ordre'];
        }
        if (isset($aWhere['_ordre'])) {
            unset($aWhere['_ordre']);
        }
        if (isset($aWhere['_limit']) && $aWhere['_limit'] !== '') {
            $sLimit = ' LIMIT ' . $aWhere['_limit'];
        }
        if (isset($aWhere['_limit'])) {
            unset($aWhere['_limit']);
        }
        $sQry = "SELECT * FROM $nom_tabla " . $sCondicion . $sOrdre . $sLimit;
        if (($oDblSt = $oDbl->prepare($sQry)) === FALSE) {
            $sClaveError = 'PgEtiquetaDocumentoRepository.listar.prepare';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClaveError, __LINE__, __FILE__);
            return FALSE;
        }
        if (($oDblSt->execute($aWhere)) === FALSE) {
            $sClaveError = 'PgEtiquetaDocumentoRepository.listar.execute';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClaveError, __LINE__, __FILE__);
            return FALSE;
        }

        $filas = $oDblSt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($filas as $aDatos) {
            $EtiquetaDocumento = new EtiquetaDocumento();
            $EtiquetaDocumento->setAllAttributes($aDatos);
            $EtiquetaDocumentoSet->add($EtiquetaDocumento);
        }
        return $EtiquetaDocumentoSet->getTot();
    }

    /* -------------------- ENTIDAD --------------------------------------------- */

    public function Eliminar(EtiquetaDocumento $EtiquetaDocumento): bool
    {
        $id_etiqueta = $EtiquetaDocumento->getId_etiqueta();
        $id_doc = $EtiquetaDocumento->getId_doc();

        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        if (($oDbl->exec("DELETE FROM $nom_tabla WHERE id_etiqueta = $id_etiqueta AND id_doc = $id_doc")) === FALSE) {
            $sClaveError = 'PgEtiquetaDocumentoRepository.eliminar';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClaveError, __LINE__, __FILE__);
            return FALSE;
        }
        return TRUE;
    }


    /**
     * Si no existe el registro, hace un insert, si existe, se hace el update.
     */
    public function Guardar(EtiquetaDocumento $EtiquetaDocumento): bool
    {

        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();

        $id_etiqueta = $EtiquetaDocumento->getId_etiqueta();
        $id_doc = $EtiquetaDocumento->getId_doc();

        $bInsert = $this->isNew($id_etiqueta, $id_doc);

        $aDatos = [];
        array_walk($aDatos, 'core\poner_null');

        if ($bInsert === FALSE) {
            //UPDATE
            $update = " ";
            if (($oDblSt = $oDbl->prepare("UPDATE $nom_tabla SET $update WHERE id_etiqueta = $id_etiqueta AND id_doc = $id_doc")) === FALSE) {
                $sClaveError = 'PgEtiquetaDocumentoRepository.update.prepare';
                $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClaveError, __LINE__, __FILE__);
                return FALSE;
            }

            try {
                $oDblSt->execute($aDatos);
            } catch (PDOException $e) {
                $err_txt = $e->errorInfo[2];
                $this->setErrorTxt($err_txt);
                $sClaveError = 'PgEtiquetaDocumentoRepository.update.execute';
                $_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClaveError, __LINE__, __FILE__);
                return FALSE;
            }
        } else {
            // INSERT
            array_unshift($aDatos, $id_etiqueta, $id_doc);
            $campos = "(id_etiqueta,id_doc)";
            $valores = "(:id_etiqueta,:id_doc)";
            if (($oDblSt = $oDbl->prepare("INSERT INTO $nom_tabla $campos VALUES $valores")) === FALSE) {
                $sClaveError = 'PgEtiquetaDocumentoRepository.insertar.prepare';
                $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClaveError, __LINE__, __FILE__);
                return FALSE;
            }
            try {
                $oDblSt->execute($aDatos);
            } catch (PDOException $e) {
                $err_txt = $e->errorInfo[2];
                $this->setErrorTxt($err_txt);
                $sClaveError = 'PgEtiquetaDocumentoRepository.insertar.execute';
                $_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClaveError, __LINE__, __FILE__);
                return FALSE;
            }
        }
        return TRUE;
    }

    private function isNew($id_etiqueta, $id_documento): bool
    {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        if (($oDblSt = $oDbl->query("SELECT * FROM $nom_tabla WHERE id_etiqueta = $id_etiqueta AND id_doc = $id_documento")) === FALSE) {
            $sClaveError = 'PgEtiquetaDocumentoRepository.isNew';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClaveError, __LINE__, __FILE__);
            return FALSE;
        }
        if (!$oDblSt->rowCount()) {
            return TRUE;
        }
        return FALSE;
    }

    /**
     * Devuelve los campos de la base de datos en un array asociativo.
     * Devuelve false si no existe la fila en la base de datos
     *
     * @param $id_etiqueta
     * @param $id_documento
     * @return array|bool
     */
    public function datosById($id_etiqueta, $id_documento): array|bool
    {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        if (($oDblSt = $oDbl->query("SELECT * FROM $nom_tabla WHERE id_etiqueta = $id_etiqueta AND id_doc = $id_documento")) === FALSE) {
            $sClaveError = 'PgEtiquetaDocumentoRepository.getDatosById';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClaveError, __LINE__, __FILE__);
            return FALSE;
        }
        $aDatos = $oDblSt->fetch(PDO::FETCH_ASSOC);
        return $aDatos;
    }


    /**
     * Busca la clase con  en la base de datos .
     */
    public function findById($id_etiqueta, $id_documento): ?EtiquetaDocumento
    {
        $aDatos = $this->datosById($id_etiqueta, $id_documento);
        if (empty($aDatos)) {
            return null;
        }
        return (new EtiquetaDocumento())->setAllAttributes($aDatos);
    }

/* -------------------- GESTOR EXTRA ---------------------------------------- */
    public function getArrayDocumentosTodos(): bool|array
    {
        // todas las etiquetas del usuario actual:
        $EtiquetaRepository = new EtiquetaRepository();
        $cEtiquetas = $EtiquetaRepository->getMisEtiquetas();
        $a_etiquetas = [];
        foreach ($cEtiquetas as $oEtiqueta) {
            $id_etiqueta = $oEtiqueta->getId_etiqueta();
            $a_etiquetas[] = $id_etiqueta;
        }

        return $this->getArrayDocumentos($a_etiquetas, 'OR');

    }

    public function getArrayDocumentos(array $a_etiquetas, string $andOr): bool|array
    {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        // Filtering the array
        $a_etiquetas_filtered = array_filter($a_etiquetas);

        if (!empty($a_etiquetas_filtered)) {
            if ($andOr === 'AND') {
                $sQuery = '';
                foreach ($a_etiquetas_filtered as $etiqueta) {
                    $sql = "SELECT DISTINCT id_doc
                        FROM $nom_tabla
                        WHERE id_etiqueta = $etiqueta";
                    $sQuery .= empty($sQuery) ? $sql : " INTERSECT $sql";
                }

            } else {
                $valor = implode(',', $a_etiquetas_filtered);
                $where = " id_etiqueta IN ($valor)";
                $sQuery = "SELECT DISTINCT id_doc
                        FROM $nom_tabla
                        WHERE $where ";
            }

            if (($oDbl->query($sQuery)) === FALSE) {
                $sClauError = 'GestorEtiequeta.queryPreparar';
                $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
                return FALSE;
            }
            $a_documentos = [];
            foreach ($oDbl->query($sQuery) as $aDades) {
                $a_documentos[] = $aDades['id_doc'];
            }
        } else {
            $a_documentos = [];
        }
        return $a_documentos;
    }

    public function deleteEtiquetasDocumento(int $id_doc): bool
    {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        $sQuery = "DELETE
                    FROM $nom_tabla
                    WHERE id_doc=$id_doc";

        if (($oDbl->query($sQuery)) === FALSE) {
            $sClauError = 'GestorEtiquetasDocumento.queryPreparar';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
            return FALSE;
        }

        return TRUE;
    }

}