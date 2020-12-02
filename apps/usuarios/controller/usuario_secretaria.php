<?php
// INICIO Cabecera global de URL de controlador *********************************
use core\ConfigGlobal;
use core\ViewTwig;
use entradas\model\EntradaLista;
use expedientes\model\ExpedienteLista;
use expedientes\model\EscritoLista;

require_once ("apps/core/global_header.inc");
// Arxivos requeridos por esta url **********************************************

// Crea los objectos de uso global **********************************************
require_once ("apps/core/global_object.inc");
// FIN de  Cabecera global de URL de controlador ********************************

/* Necesario para cargar solo una vez las paginas css y js. (_css_default.html.twig)
 * En concreto hay un problema con bootstrap.js y popper.js
 */
$peticion_ajax = 0;
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
{
    // handle request as AJAX
    $peticion_ajax = 1;
}

$username = $_SESSION['session_auth']['username'];
//oficinas adicionales (suplencias..)
/*
if ($username == 'scdl') {
    $a_roles_posibles = [ 'scdl', 'secretaria'];
}
*/

$Qtabs = (string) \filter_input(INPUT_POST, 'tabs');
$Qfiltro = (string) \filter_input(INPUT_POST, 'filtro');

$a_pills = [];
//Diferentes filtros:
// Expedientes:

$oExpedienteLista = new ExpedienteLista();
// fijar reunión = 1;
$filtro = 'fijar_reunion';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/expediente_lista.php?'.http_build_query($aQuery));
    $num_orden = 1;
    $text = _("fijar reunión");
    $oExpedienteLista->setFiltro($filtro);
    $num = $oExpedienteLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// seguimiento = 2;
$filtro = 'seg_reunion';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/expediente_lista.php?'.http_build_query($aQuery));
    $num_orden = 2;
    $text = _("seguimiento reunion");
    $oExpedienteLista->setFiltro($filtro);
    $num = $oExpedienteLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// firmar = 2;
$filtro = 'seguimiento';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/expediente_lista.php?'.http_build_query($aQuery));
    $num_orden = 7;
    $text = _("seguimiento");
    $oExpedienteLista->setFiltro($filtro);
    $num = $oExpedienteLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// reunion = 3;
$filtro = 'distribuir';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/expediente_lista.php?'.http_build_query($aQuery));
    $num_orden = 3;
    $text = _("distribuir");
    $oExpedienteLista->setFiltro($filtro);
    $num = $oExpedienteLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// circular = 4;
// se envian escritos, no expedientes
$filtro = 'enviar';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro, 'modo' => 'mod' ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/escrito_lista.php?'.http_build_query($aQuery));
    $num_orden = 4;
    $text = _("enviar");
    $oEscritoLista = new EscritoLista();
    $oEscritoLista->setFiltro($filtro);
    $num = $oEscritoLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// permanantes = 5;
$filtro = 'permanentes';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/expediente_lista.php?'.http_build_query($aQuery));
    $num_orden = 5;
    $text = _("permanentes");
    $oExpedienteLista->setFiltro($filtro);
    $num = $oExpedienteLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// pendientes = 6;
$filtro = 'pendientes';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/expedientes/controller/expediente_lista.php?'.http_build_query($aQuery));
    $num_orden = 6;
    $text = _("pendientes");
    $oExpedienteLista->setFiltro($filtro);
    $num = $oExpedienteLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// introducir entradas
$filtro = 'introducir';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/entradas/controller/entrada_lista.php?'.http_build_query($aQuery));
    $num_orden = 7;
    $text = _("introducir");
    $oEntradaLista = new EntradaLista();
    $oEntradaLista->setFiltro($filtro);
    $num = $oEntradaLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// entradas
$filtro = 'entrada_todos';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/entradas/controller/entrada_lista.php?'.http_build_query($aQuery));
    $num_orden = 8;
    $text = _("entradas");
    $oEntradaLista = new EntradaLista();
    $oEntradaLista->setFiltro($filtro);
    $num = $oEntradaLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// distribución cr
$filtro = 'bypass';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/entradas/controller/entrada_lista.php?'.http_build_query($aQuery));
    $num_orden = 9;
    $text = _("ditribución cr");
    $oEntradaLista = new EntradaLista();
    $oEntradaLista->setFiltro($filtro);
    $num = $oEntradaLista->getNumero();
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;

// Enviar
/*
$filtro = 'enviar';
    $active = ($filtro == $Qfiltro)? 'active' : '';
    $aQuery = [ 'filtro' => $filtro ];
    $pag_lst = web\Hash::link('apps/envios/controller/index_lista.php?'.http_build_query($aQuery));
    $num_orden = 11;
    $text = _("Enviar");
    $num = 'x5';
    $pill = [ 'orden'=> $num_orden, 'text' => $text, 'pag_lst' => $pag_lst, 'num' => $num, 'active' => $active];
$a_pills[$num_orden] = $pill;
*/

// ordenar:
ksort($a_pills);

$pagina_profile = web\Hash::link('apps/usuarios/controller/personal.php?'.http_build_query([]));
$pagina_etiquetas = web\Hash::link('apps/etiquetas/controller/etiqueta_lista.php?'.http_build_query([]));

$mi_idioma = ConfigGlobal::mi_Idioma_short();
$a_campos = [
    'oficina' => 'Secretaría',
    'username' => $username,
    'mi_idioma' => $mi_idioma,
    'error_fecha' => $_SESSION['oConfig']->getPlazoError(),
    'pagina_profile' => $pagina_profile,
    'pagina_etiquetas' => $pagina_etiquetas,
    // para tabs
    'a_pills' => $a_pills,
    'vista' => 'scdl',
    'filtro' => $filtro,
    'role_actual' => $_SESSION['session_auth']['role_actual'],
    'a_roles' => $_SESSION['session_auth']['a_roles'],
    'peticion_ajax' => $peticion_ajax,
];
$oView = new ViewTwig('usuarios/controller');

if (empty($Qtabs)) {
    echo $oView->renderizar('usuario_home.html.twig',$a_campos);
} else {
    echo $oView->renderizar('usuario_tabs.html.twig',$a_campos);
}
