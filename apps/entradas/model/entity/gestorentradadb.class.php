<?php
namespace entradas\model\entity;
use function core\any_2;
use core;
/**
 * GestorEntradaDB
 *
 * Classe per gestionar la llista d'objectes de la clase EntradaDB
 *
 * @package tramity
 * @subpackage model
 * @author Daniel Serrabou
 * @version 1.0
 * @created 20/10/2020
 */

class GestorEntradaDB Extends core\ClaseGestor {
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
		$this->setNomTabla('entradas');
	}


	/* METODES PUBLICS -----------------------------------------------------------*/
	
	/**
	 * Devuelve la colección de entradas, segun las condiciones del protcolo de entrada, más las normales
	 * 
	 * @param array $aProt_orgigen = ['id_lugar' => xx, 'num' => xx, 'any' => xx, 'mas' => xx]
	 * @param array $aWhere
	 * @param array $aOperators
	 * @return boolean|array
	 */
	function getEntradasByProtOrigenDB($aProt_orgigen=[], $aWhere=[], $aOperators=[]) {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        $oEntradaDBSet = new core\Set();
        
        /* {"any": 20, "mas": null, "num": 15, "lugar": 58}
        $sQuery = "SELECT t.*
                        FROM $nom_tabla t, jsonb_to_recordset(t.json_prot_origen) as items(any smallint, mas text, num smallint, lugar integer)
                        WHERE items.id=$id_lugar";
        */
        
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
        
        $sOrdre = '';
        $sLimit = '';
        if (isset($aWhere['_ordre']) && $aWhere['_ordre']!='') $sOrdre = ' ORDER BY '.$aWhere['_ordre'];
        if (isset($aWhere['_ordre'])) unset($aWhere['_ordre']);
        if (isset($aWhere['_limit']) && $aWhere['_limit']!='') $sLimit = ' LIMIT '.$aWhere['_limit'];
        if (isset($aWhere['_limit'])) unset($aWhere['_limit']);
        
        // Where del prot_origen
        $Where_json = '';
        if (!empty($aProt_orgigen['id_lugar'])) {
            $id_lugar = $aProt_orgigen['id_lugar'];
            $Where_json .= empty($Where_json)? '' : ' AND ';    
            $Where_json .= "items.lugar='$id_lugar'";
        }
        if (!empty($aProt_orgigen['num'])) {
            $num = $aProt_orgigen['num'];
            $Where_json .= empty($Where_json)? '' : ' AND ';    
            $Where_json .= "items.num='$num'";
        }
        if (!empty($aProt_orgigen['any'])) {
            $any = $aProt_orgigen['any'];
            $any_2 = any_2($any);
            $Where_json .= empty($Where_json)? '' : ' AND ';    
            $Where_json .= "items.any='$any_2'";
        }
        if (!empty($aProt_orgigen['mas'])) {
            $mas = $aProt_orgigen['mas'];
            $Where_json .= empty($Where_json)? '' : ' AND ';    
            $Where_json .= "items.mas='$mas'";
        }
        
        if (empty($sCondi)) {
            if (empty($Where_json)) {
                $where_condi = '';
            } else {
                $where_condi = $Where_json;
            }
        } else {
            if (!empty($Where_json)) {
                $where_condi = $Where_json. " AND ". $sCondi;
            } else {
                $where_condi = $sCondi;
            }
        }
        $where_condi = empty($where_condi)? '' : "WHERE ".$where_condi;
        
        // pongo tipo 'text' en todos los campos del json, porque si hay algun null devuelve error syntax
        $sQry = "SELECT t.*
                        FROM $nom_tabla t, jsonb_to_record(t.json_prot_origen) as items(\"any\" text, mas text, num text, lugar text)
                        $where_condi";
        
        if (($oDblSt = $oDbl->prepare($sQry)) === FALSE) {
            $sClauError = 'GestorEntradaDB.llistar.prepare';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
            return FALSE;
        }
        if (($oDblSt->execute($aWhere)) === FALSE) {
            $sClauError = 'GestorEntradaDB.llistar.execute';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClauError, __LINE__, __FILE__);
            return FALSE;
        }
        foreach ($oDblSt as $aDades) {
            $a_pkey = array('id_entrada' => $aDades['id_entrada']);
            $oEntradaDB = new EntradaDB($a_pkey);
            $oEntradaDB->setAllAtributes($aDades);
            $oEntradaDBSet->add($oEntradaDB);
        }
        return $oEntradaDBSet->getTot();
	}
	
	function getEntradasByLugarDB($id_lugar, $aWhere=array(),$aOperators=array()) {
        $oDbl = $this->getoDbl();
        $nom_tabla = $this->getNomTabla();
        $oEntradaDBSet = new core\Set();
        
        /* {"any": 20, "mas": null, "num": 15, "lugar": 58}
        $sQuery = "SELECT t.*
                        FROM $nom_tabla t, jsonb_to_recordset(t.json_prot_origen) as items(any smallint, mas text, num smallint, lugar integer)
                        WHERE items.id=$id_lugar";
        */
        
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
        if (empty($sCondi)) {
            $sCondi = " WHERE items.lugar='$id_lugar'";
        } else {
            $sCondi = " WHERE items.lugar='$id_lugar' AND ".$sCondi;
        }
        
        $sOrdre = '';
        $sLimit = '';
        if (isset($aWhere['_ordre']) && $aWhere['_ordre']!='') $sOrdre = ' ORDER BY '.$aWhere['_ordre'];
        if (isset($aWhere['_ordre'])) unset($aWhere['_ordre']);
        if (isset($aWhere['_limit']) && $aWhere['_limit']!='') $sLimit = ' LIMIT '.$aWhere['_limit'];
        if (isset($aWhere['_limit'])) unset($aWhere['_limit']);
        
        // pongo tipo 'text' en todos los campos del json, porque si hay algun null devuelve error syntax
        $sQry = "SELECT t.*
                        FROM $nom_tabla t, jsonb_to_record(t.json_prot_origen) as items(\"any\" text, mas text, num text, lugar text)
                        ".$sCondi.$sOrdre.$sLimit;
        
        if (($oDblSt = $oDbl->prepare($sQry)) === FALSE) {
            $sClauError = 'GestorEntradaDB.llistar.prepare';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
            return FALSE;
        }
        if (($oDblSt->execute($aWhere)) === FALSE) {
            $sClauError = 'GestorEntradaDB.llistar.execute';
            $_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClauError, __LINE__, __FILE__);
            return FALSE;
        }
        foreach ($oDblSt as $aDades) {
            $a_pkey = array('id_entrada' => $aDades['id_entrada']);
            $oEntradaDB = new EntradaDB($a_pkey);
            $oEntradaDB->setAllAtributes($aDades);
            $oEntradaDBSet->add($oEntradaDB);
        }
        return $oEntradaDBSet->getTot();
	}
	
	/**
	 * retorna l'array d'objectes de tipus EntradaDB
	 *
	 * @param string sQuery la query a executar.
	 * @return array Una col·lecció d'objectes de tipus EntradaDB
	 */
	function getEntradasDBQuery($sQuery='') {
		$oDbl = $this->getoDbl();
		$oEntradaDBSet = new core\Set();
		if (($oDbl->query($sQuery)) === FALSE) {
			$sClauError = 'GestorEntradaDB.query';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		foreach ($oDbl->query($sQuery) as $aDades) {
			$a_pkey = array('id_entrada' => $aDades['id_entrada']);
			$oEntradaDB= new EntradaDB($a_pkey);
			$oEntradaDB->setAllAtributes($aDades);
			$oEntradaDBSet->add($oEntradaDB);
		}
		return $oEntradaDBSet->getTot();
	}

	/**
	 * retorna l'array d'objectes de tipus EntradaDB
	 *
	 * @param array aWhere associatiu amb els valors de les variables amb les quals farem la query
	 * @param array aOperators associatiu amb els valors dels operadors que cal aplicar a cada variable
	 * @return array Una col·lecció d'objectes de tipus EntradaDB
	 */
	function getEntradasDB($aWhere=array(),$aOperators=array()) {
		$oDbl = $this->getoDbl();
		$nom_tabla = $this->getNomTabla();
		$oEntradaDBSet = new core\Set();
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
			$sClauError = 'GestorEntradaDB.llistar.prepare';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDbl, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		if (($oDblSt->execute($aWhere)) === FALSE) {
			$sClauError = 'GestorEntradaDB.llistar.execute';
			$_SESSION['oGestorErrores']->addErrorAppLastError($oDblSt, $sClauError, __LINE__, __FILE__);
			return FALSE;
		}
		foreach ($oDblSt as $aDades) {
			$a_pkey = array('id_entrada' => $aDades['id_entrada']);
			$oEntradaDB = new EntradaDB($a_pkey);
			$oEntradaDB->setAllAtributes($aDades);
			$oEntradaDBSet->add($oEntradaDB);
		}
		return $oEntradaDBSet->getTot();
	}

	/* METODES PROTECTED --------------------------------------------------------*/

	/* METODES GET i SET --------------------------------------------------------*/
}
