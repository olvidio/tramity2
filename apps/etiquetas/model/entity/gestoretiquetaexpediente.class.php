<?php
namespace etiquetas\model\entity;
use core;
/**
 * GestorEtiquetaExpediente
 *
 * Classe per gestionar la llista d'objectes de la clase EtiquetaExpediente
 *
 * @package tramity
 * @subpackage model
 * @author Daniel Serrabou
 * @version 1.0
 * @created 11/11/2020
 */

class GestorEtiquetaExpediente Extends core\ClaseGestor {
	/* ATRIBUTS ----------------------------------------------------------------- */

	/* CONSTRUCTOR -------------------------------------------------------------- */


	/**
	 * Constructor de la classe.
	 *
	 * @return $gestor
	 *
	 */
	function __construct() {
		$oDbl = $GLOBALS['oDBT'];
		$this->setoDbl($oDbl);
		$this->setNomTabla('etiquetas_expediente');
	}


	/* METODES PUBLICS -----------------------------------------------------------*/

	public function deleteEtiquetasExpediente($id_expediente) {
		$oDbl = $this->getoDbl();
		$nom_tabla = $this->getNomTabla();
	    $sQuery = "DELETE
                    FROM $nom_tabla
                    WHERE id_expediente=$id_expediente";
	    
	    if (($oDbl->query($sQuery)) === FALSE) {
	        $sClauError = 'GestorExpedienteDB.queryPreparar';
	        $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
	        return FALSE;
	    }

	    return TRUE;
	}
	/**
	 * retorna l'array d'objectes de tipus EtiquetaExpediente
	 *
	 * @param string sQuery la query a executar.
	 * @return array Una col·lecció d'objectes de tipus EtiquetaExpediente
	 */
	function getEtiquetasExpedienteQuery($sQuery='') {
		$oDbl = $this->getoDbl();
		$oEtiquetaExpedienteSet = new core\Set();
		if (($oDbl->query($sQuery)) === FALSE) {
			$sClauError = 'GestorEtiquetaExpediente.query';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		foreach ($oDbl->query($sQuery) as $aDades) {
			$a_pkey = array('id_etiqueta' => $aDades['id_etiqueta'],
							'id_expediente' => $aDades['id_expediente']);
			$oEtiquetaExpediente= new EtiquetaExpediente($a_pkey);
			$oEtiquetaExpediente->setAllAtributes($aDades);
			$oEtiquetaExpedienteSet->add($oEtiquetaExpediente);
		}
		return $oEtiquetaExpedienteSet->getTot();
	}

	/**
	 * retorna l'array d'objectes de tipus EtiquetaExpediente
	 *
	 * @param array aWhere associatiu amb els valors de les variables amb les quals farem la query
	 * @param array aOperators associatiu amb els valors dels operadors que cal aplicar a cada variable
	 * @return array Una col·lecció d'objectes de tipus EtiquetaExpediente
	 */
	function getEtiquetasExpediente($aWhere=array(),$aOperators=array()) {
		$oDbl = $this->getoDbl();
		$nom_tabla = $this->getNomTabla();
		$oEtiquetaExpedienteSet = new core\Set();
		$oCondicion = new core\Condicion();
		$aCondi = array();
		foreach ($aWhere as $camp => $val) {
			if ($camp == '_ordre') continue;
			if ($camp == '_limit') continue;
			$sOperador = isset($aOperators[$camp])? $aOperators[$camp] : '';
			if ($a = $oCondicion->getCondicion($camp,$sOperador,$val)) $aCondi[]=$a;
			// operadores que no requieren valores
			if ($sOperador == 'BETWEEN' || $sOperador == 'IS NULL' || $sOperador == 'IS NOT NULL' || $sOperador == 'OR') unset($aWhere[$camp]);
            if ($sOperador == 'IN' || $sOperador == 'NOT IN') unset($aWhere[$camp]);
            if ($sOperador == 'TXT') unset($aWhere[$camp]);
		}
		$sCondi = implode(' AND ',$aCondi);
		if ($sCondi!='') $sCondi = " WHERE ".$sCondi;
		$sOrdre = '';
        $sLimit = '';
		if (isset($aWhere['_ordre']) && $aWhere['_ordre']!='') $sOrdre = ' ORDER BY '.$aWhere['_ordre'];
		if (isset($aWhere['_ordre'])) unset($aWhere['_ordre']);
		if (isset($aWhere['_limit']) && $aWhere['_limit']!='') $sLimit = ' LIMIT '.$aWhere['_limit'];
		if (isset($aWhere['_limit'])) unset($aWhere['_limit']);
		$sQry = "SELECT * FROM $nom_tabla ".$sCondi.$sOrdre.$sLimit;
		if (($oDblSt = $oDbl->prepare($sQry)) === FALSE) {
			$sClauError = 'GestorEtiquetaExpediente.llistar.prepare';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		if (($oDblSt->execute($aWhere)) === FALSE) {
			$sClauError = 'GestorEtiquetaExpediente.llistar.execute';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		foreach ($oDblSt as $aDades) {
			$a_pkey = array('id_etiqueta' => $aDades['id_etiqueta'],
							'id_expediente' => $aDades['id_expediente']);
			$oEtiquetaExpediente = new EtiquetaExpediente($a_pkey);
			$oEtiquetaExpediente->setAllAtributes($aDades);
			$oEtiquetaExpedienteSet->add($oEtiquetaExpediente);
		}
		return $oEtiquetaExpedienteSet->getTot();
	}

	/* METODES PROTECTED --------------------------------------------------------*/

	/* METODES GET i SET --------------------------------------------------------*/
}