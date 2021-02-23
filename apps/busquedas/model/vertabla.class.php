<?php
namespace busquedas\model;

use core\ConfigGlobal;
use core\ViewTwig;
use usuarios\model\entity\GestorCargo;
use usuarios\model\entity\GestorOficina;
use web\Lista;
use web\Protocolo;
use web\ProtocoloArray;

class VerTabla {
    
    /**
     * Id_sigla
     *
     * @var integer
     */
    private $id_sigla;

    /**
     * Id_lugar
     *
     * @var integer
     */
    private $id_lugar;

    /**
     * Prot_num
     *
     * @var integer
     */
    private $prot_num;

    /**
     * Prot_any
     *
     * @var integer
     */
    private $prot_any;

    /**
     * Collection
     *
     * @var array
     */
    private $aCollection;

    /**
     * Key (entradas | escritos)
     *
     * @var string
     */
    private $sKey;

    /**
     * condicion de la búsqueda
     *
     * @var string
     */
    private $sCondicion;


    
    /**
     * @return number
     */
    public function getId_sigla()
    {
        return $this->id_sigla;
    }

    /**
     * @return number
     */
    public function getId_lugar()
    {
        return $this->id_lugar;
    }

    /**
     * @return number
     */
    public function getProt_num()
    {
        return $this->prot_num;
    }

    /**
     * @return number
     */
    public function getProt_any()
    {
        return $this->prot_any;
    }

    /**
     * @param number $id_sigla
     */
    public function setId_sigla($id_sigla)
    {
        $this->id_sigla = $id_sigla;
    }

    /**
     * @param number $id_lugar
     */
    public function setId_lugar($id_lugar)
    {
        $this->id_lugar = $id_lugar;
    }

    /**
     * @param number $prot_num
     */
    public function setProt_num($prot_num)
    {
        $this->prot_num = $prot_num;
    }

    /**
     * @param number $prot_any
     */
    public function setProt_any($prot_any)
    {
        $this->prot_any = $prot_any;
    }

    /**
     * @param array $aCollection
     */
    public function setCollection($Collection)
    {
        $this->aCollection = $Collection;
    }

    /**
     * @param string $sKey
     */
    public function setKey($key)
    {
        $this->sKey = $key;
    }

    /**
     * @param string $sCondicion
     */
    public function setCondicion($condicion)
    {
        $this->sCondicion = $condicion;
    }

    
    public function mostrarTabla() {
        $aCollection = $this->aCollection;
        if ($this->sKey == 'entradas') {
            return $this->tabla_entradas($aCollection);
        }
        if ($this->sKey == 'escritos') {
            return $this->tabla_escritos($aCollection);
        }
    }
    // ---------------------------------- tablas ----------------------------

    public function tabla_entradas($aCollection) {
        $gesOficinas = new GestorOficina();
        $a_posibles_oficinas = $gesOficinas->getArrayOficinas();
        
        /*
        $go_to="registro_tabla.php?tabla=entradas&donde=".urlencode($donde)."&sql=".urlencode($sql);
        if ($orden && $sql) $sql .= "ORDER BY " .$orden;
        if ($orden && $donde) $donde .= "ORDER BY " .$orden;
        */
        
        if (ConfigGlobal::role_actual() === 'secretaria') { 
            $a_botones=array( array( 'txt' => _('modificar'), 'click' =>"fnjs_modificar_entrada(\"#$this->sKey\")" ) ,
                        array( 'txt' => _('eliminar'), 'click' =>"fnjs_borrar_entrada(\"#$this->sKey\")" ) 
                        );
        }

        $a_botones[]=array( 'txt' => _('detalle'), 'click' =>"fnjs_modificar_det_entrada(\"#$this->sKey\")" ) ;

        $a_cabeceras=array( array('name'=>ucfirst(_("protocolo origen")),'formatter'=>'clickFormatter'),
                            ucfirst(_("ref.")),
                            array('name'=>ucfirst(_("asunto")),'formatter'=>'clickFormatter2'),
                            ucfirst(_("ofic.")),
                            array('name'=>ucfirst(_("fecha doc.")),'class'=>'fecha'),
                            array('name'=>ucfirst(_("fecha entrada")),'class'=>'fecha')
                            );
        
        $oProtOrigen = new Protocolo();
        $a_valores = [];
        $i=0;
        foreach ($aCollection as $oEntrada) {
            $i++;
            
            $id_entrada=$oEntrada->getId_entrada();
            $f_entrada=$oEntrada->getF_entrada();
            
            /*
            $origen_sigla=$row["sigla"];
            $origen_prot_num=$row["o_prot_num"];
            $origen_prot_any=any_2($row["o_prot_any"]);
            $origen_mas=$row["mas"];
            
            $pagina_mod="scdl/registro/registro_modificar.php?id_reg=$id_reg&e_s=$e_s";
            $pagina="scdl/registro/asunto_of.php?nuevo=2&id_reg=$id_reg&e_s=$e_s&atras=$atras";
            */
            
            $oProtOrigen->setJson($oEntrada->getJson_prot_origen());
            $protocolo = $oProtOrigen->ver_txt();
            
            // referencias
            $json_ref = $oEntrada->getJson_prot_ref();
            $oArrayProtRef = new ProtocoloArray($json_ref,'','');
            $oArrayProtRef->setRef(TRUE);
            $referencias = $oArrayProtRef->ListaTxtBr();
            
            // oficinas
            $id_of_ponente =  $oEntrada->getPonente();
            $a_resto_oficinas = $oEntrada->getResto_oficinas();
            $oficinas_txt = '';
            $oficinas_txt .= '<span class="text-danger">'.$a_posibles_oficinas[$id_of_ponente].'</span>';
            foreach ($a_resto_oficinas as $id_oficina) {
                $oficinas_txt .= empty($oficinas_txt)? '' : ', ';
                $oficinas_txt .= $a_posibles_oficinas[$id_oficina];
            }
            $oficinas = $oficinas_txt;
            
            $asunto = $oEntrada->getAsuntoDetalle();
            /*
            if ($distribucion_cr=='t') {
                $sql_1= "SELECT m.descripcion
                    FROM destino_multiple m 
                    WHERE m.id_reg=$id_reg
                    ";
                //echo "query: $sql<br>";
                $oDblSt_query_1=$oDbl->query($sql_1);
                $descripcion=$oDblSt_query_1->fetchColumn();
                $asunto.=" <font style='color: Green;'>"._("dl y")." $descripcion</font>";
            }
            */
            
            $a_valores[$i]['sel']="$id_entrada";
            $a_valores[$i][1]=$protocolo;
            //if ( $GLOBALS['oPerm']->have_perm("scdl")) {
            //    $a_valores[$i][1]=array( 'ira'=>$pagina_mod, 'valor'=>$protocolo);
            //} else {
            //    $a_valores[$i][1]=$protocolo;
            //}
            $a_valores[$i][2]=$referencias;

            $a_valores[$i][3]= $asunto;
            $a_valores[$i][4]=$oficinas;
            $a_valores[$i][5]='?';
            $a_valores[$i][6]=$f_entrada->getFromLocal();
        }
        
        $oTabla = new Lista();
        $oTabla->setId_tabla('func_reg_entradas');
        $oTabla->setCabeceras($a_cabeceras);
        $oTabla->setBotones($a_botones);
        $oTabla->setDatos($a_valores);
        
        $titulo= _("escritos recibidos en la Delegación");
        
        $a_campos = [
            'titulo' => $titulo,
            'oTabla' => $oTabla,
            'key' => $this->sKey,
            'condicion' => $this->sCondicion,
            //'oHash' => $oHash,
            ];
        
        $oView = new ViewTwig('busquedas/controller');
        echo $oView->renderizar('ver_tabla.html.twig',$a_campos);
    }

    public function tabla_escritos($cCollection) {
        // salidas
        $gesCargos = new GestorCargo();
        $a_posibles_cargos = $gesCargos->getArrayCargos();
        
        if (ConfigGlobal::role_actual() === 'secretaria') { 
            $a_botones=array( array( 'txt' => _('modificar'), 'click' =>"fnjs_modificar_escrito(\"#$this->sKey\")" ) ,
                        array( 'txt' => _('eliminar'), 'click' =>"fnjs_borrar_escrito(\"#$this->sKey\")" ) 
                        );
        }

        $a_botones[]=array( 'txt' => _('detalle'), 'click' =>"fnjs_modificar_det_escrito(\"#$this->sKey\")" ) ;
                
        $a_cabeceras=array( array('name'=>ucfirst(_("protocolo")),'formatter'=>'clickFormatter'), ucfirst(_("destinos")),  ucfirst(_("ref.")), 
                array('name'=>ucfirst(_("asunto")),'formatter'=>'clickFormatter2'),
                   ucfirst(_("ofic.")),
                array('name'=>ucfirst(_("fecha doc.")),'class'=>'fecha'),
                array('name'=>ucfirst(_("aprobado")),'class'=>'fecha'),
                ucfirst(_("enviado")) // no puede ser class fecha, porque a veces se añade el modo de envio.
                   );
        
        $i=0;
        $oProtLocal = new Protocolo();
        $a_valores = [];
        foreach ($cCollection as $oEscrito) {
            $i++;
            $asunto = $oEscrito->getAsuntoDetalle();
            $anulado = $oEscrito->getAnulado();
            
            // protocolo local
            $json_prot_local = $oEscrito->getJson_prot_local();
            if (count(get_object_vars($json_prot_local)) == 0) {
                $protocolo_local = '';
            } else {
                $oProtLocal->setJson($json_prot_local);
                $protocolo_local = $oProtLocal->ver_txt();
            }
            
            // destinos
            $json_destino= $oEscrito->getJson_prot_destino();
            $oArrayProtDest = new ProtocoloArray($json_destino,'','');
            $protocolo_dst = $oArrayProtDest->ListaTxtBr();
            
            // referencias
            $json_ref = $oEscrito->getJson_prot_ref();
            $oArrayProtRef = new ProtocoloArray($json_ref,'','');
            $oArrayProtRef->setRef(TRUE);
            $referencias = $oArrayProtRef->ListaTxtBr();
            
            $id_escrito=$oEscrito->getId_escrito();
            $f_aprobacion=$oEscrito->getF_aprobacion();
            $f_escrito=$oEscrito->getF_escrito();
            $f_salida=$oEscrito->getF_salida();
            
            /*
            if ($distribucion_cr=='t') {
                $sql_1= "SELECT en.id_lugar as o_lugar,en.prot_num as o_prot_num,en.prot_any as o_prot_any,u.sigla
                FROM entradas en LEFT JOIN lugares u USING (id_lugar) 
                WHERE en.id_reg=$id_reg
                ";
                //echo "query: $sql<br>";
                $oEntrada=$oDbl->query($sql_1)->fetch(PDO::FETCH_OBJ);
                $origen_prot_num=$oEntrada->o_prot_num;
                $origen_prot_any=any_2($oEntrada->o_prot_any);
                $origen_sigla=$oEntrada->sigla;
                $protocolo=$origen_sigla." ".$origen_prot_num."/".$origen_prot_any;
            }
            */
            
            $entradilla = $oEscrito->getEntradilla();
            
            /*
            // destinos
            if (empty($descripcion)) {
                $destinos=buscar_destinos($id_reg);
            } else {
                $destinos=$descripcion;
            }
            */
            
            // referencias
            $json_ref = $oEscrito->getJson_prot_ref();
            $oArrayProtRef = new ProtocoloArray($json_ref,'','');
            $oArrayProtRef->setRef(TRUE);
            $referencias = $oArrayProtRef->ListaTxtBr();
            
            // oficinas
            $id_ponente =  $oEscrito->getCreador();
            $a_resto_oficinas = $oEscrito->getResto_oficinas();
            $oficina_txt = empty($a_posibles_cargos[$id_ponente])? '?' : $a_posibles_cargos[$id_ponente];
            $oficinas_txt = '';
            $oficinas_txt .= '<span class="text-danger">'.$oficina_txt.'</span>';
            foreach ($a_resto_oficinas as $id_oficina) {
                $oficinas_txt .= empty($oficinas_txt)? '' : ', ';
                $oficinas_txt .= $a_posibles_cargos[$id_oficina];
            }
            $oficinas = $oficinas_txt;
            
            if (!empty($anulado)) $asunto=_("ANULADO")." ($anulado) $asunto";

            $a_valores[$i]['sel']="$id_escrito";
            $a_valores[$i][1]=$protocolo_local;
            /*
            if ( $GLOBALS['oPerm']->have_perm("scdl")) {
                $a_valores[$i][1]=array( 'ira'=>$pagina_mod, 'valor'=>$protocolo);
            } else {
                $a_valores[$i][1]=$protocolo;
            }
            */
            $a_valores[$i][2]=$protocolo_dst;
            $a_valores[$i][3]=$referencias;
            $a_valores[$i][4]=$asunto;
            $a_valores[$i][5]=$oficinas;
            $a_valores[$i][6]=$f_escrito->getFromLocal();
            $a_valores[$i][7]=$f_aprobacion->getFromLocal();
            $a_valores[$i][8]=$f_salida->getFromLocal();
        }

        $oTabla = new Lista();
        $oTabla->setId_tabla('func_reg_salidas');
        $oTabla->setCabeceras($a_cabeceras);
        $oTabla->setBotones($a_botones);
        $oTabla->setDatos($a_valores);

        $titulo=_("escritos aprobados en la Delegación");
        $a_campos = [
            'titulo' => $titulo,
            'oTabla' => $oTabla,
            'key' => $this->sKey,
            'condicion' => $this->sCondicion,
            //'oHash' => $oHash,
            ];
        
        $oView = new ViewTwig('busquedas/controller');
        echo $oView->renderizar('ver_tabla.html.twig',$a_campos);
    }

}