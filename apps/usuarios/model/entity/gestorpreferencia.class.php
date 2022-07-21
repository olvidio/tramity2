<?php
namespace usuarios\model\entity;
use core;
/**
 * GestorPreferencia
 *
 * Classe per gestionar la llista d'objectes de la clase Preferencia
 *
 * @package tramity
 * @subpackage model
 * @author Daniel Serrabou
 * @version 1.0
 * @created 8/6/2020
 */

class GestorPreferencia Extends core\ClaseGestor {
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
		$this->setNomTabla('usuario_preferencias');
	}


	/* METODES PUBLICS -----------------------------------------------------------*/

	/**
	 * retorna un objecte Preferencia
	 * 
	 * @param integer $id_usuario
	 * @param string $tipo
	 * @return \usuarios\model\entity\Preferencia
	 */
	function getPreferenciaUsuario($id_usuario,$tipo) {
	    $gesPreferencias = new GestorPreferencia();
	    $cPreferencias = $gesPreferencias->getPreferencias(['id_usuario'=>$id_usuario,'tipo'=>$tipo]);
	    if (count($cPreferencias) > 0) {
	        $oPref = $cPreferencias[0]; // solo deberia haber uno.
	        $oPref->DBCarregar();
	    } else {
	        $oPref = new Preferencia();
	        $oPref->setId_usuario($id_usuario);
	        $oPref->setTipo($tipo);
	    }
	    return $oPref;
	}
	
	/**
	 * retorna un objecte Preferencia
	 * 
	 * @param string $tipo
	 * @return \usuarios\model\entity\Preferencia
	 */
	function getMiPreferencia($tipo) {
	    $id_usuario= core\ConfigGlobal::mi_id_usuario();
	    return $this->getPreferenciaUsuario($id_usuario, $tipo);
	}
	
	/**
	 * retorna l'array d'objectes de tipus Preferencia
	 *
	 * @param string sQuery la query a executar.
	 * @return array Una col·lecció d'objectes de tipus Preferencia
	 */
	function getPreferenciasQuery($sQuery='') {
		$oDbl = $this->getoDbl();
		$oPreferenciaSet = new core\Set();
		if (($oDbl->query($sQuery)) === FALSE) {
			$sClauError = 'GestorPreferencia.query';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		foreach ($oDbl->query($sQuery) as $aDades) {
			$a_pkey = array('id_item' => $aDades['id_item']);
			$oPreferencia= new Preferencia($a_pkey);
			$oPreferenciaSet->add($oPreferencia);
		}
		return $oPreferenciaSet->getTot();
	}

	/**
	 * retorna l'array d'objectes de tipus Preferencia
	 *
	 * @param array aWhere associatiu amb els valors de les variables amb les quals farem la query
	 * @param array aOperators associatiu amb els valors dels operadors que cal aplicar a cada variable
	 * @return array Una col·lecció d'objectes de tipus Preferencia
	 */
	function getPreferencias($aWhere=array(),$aOperators=array()) {
		$oDbl = $this->getoDbl();
		$nom_tabla = $this->getNomTabla();
		$oPreferenciaSet = new core\Set();
		$oCondicion = new core\Condicion();
		$aCondi = array();
		foreach ($aWhere as $camp => $val) {
			if ($camp == '_ordre') { continue; }
			if ($camp == '_limit') { continue; }
			$sOperador = isset($aOperators[$camp])? $aOperators[$camp] : '';
			if ($a = $oCondicion->getCondicion($camp,$sOperador,$val)) { $aCondi[]=$a; }
			// operadores que no requieren valores
			if ($sOperador == 'BETWEEN' || $sOperador == 'IS NULL' || $sOperador == 'IS NOT NULL' || $sOperador == 'OR') { unset($aWhere[$camp]); }
            if ($sOperador == 'IN' || $sOperador == 'NOT IN') { unset($aWhere[$camp]); }
            if ($sOperador == 'TXT') { unset($aWhere[$camp]); }
		}
		$sCondi = implode(' AND ',$aCondi);
		if ($sCondi!='') { $sCondi = " WHERE ".$sCondi; }
		$sOrdre = '';
        $sLimit='';
		if (isset($aWhere['_ordre']) && $aWhere['_ordre']!='') { $sOrdre = ' ORDER BY '.$aWhere['_ordre']; }
		if (isset($aWhere['_ordre'])) { unset($aWhere['_ordre']); }
		if (isset($aWhere['_limit']) && $aWhere['_limit']!='') { $sLimit = ' LIMIT '.$aWhere['_limit']; }
		if (isset($aWhere['_limit'])) { unset($aWhere['_limit']); }
		$sQry = "SELECT * FROM $nom_tabla ".$sCondi.$sOrdre.$sLimit;
		if (($oDblSt = $oDbl->prepare($sQry)) === FALSE) {
			$sClauError = 'GestorPreferencia.llistar.prepare';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		if (($oDblSt->execute($aWhere)) === FALSE) {
			$sClauError = 'GestorPreferencia.llistar.execute';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		foreach ($oDblSt as $aDades) {
			$a_pkey = array('id_item' => $aDades['id_item']);
			$oPreferencia= new Preferencia($a_pkey);
			$oPreferenciaSet->add($oPreferencia);
		}
		return $oPreferenciaSet->getTot();
	}

	/* METODES PROTECTED --------------------------------------------------------*/

	/* METODES GET i SET --------------------------------------------------------*/
}
