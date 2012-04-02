<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * CONTENIDO main ajax file
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package    CONTENIDO Backend
 * @version    1.0.4
 * @author     Olaf Niemann, Jan Lengowski, Ingo van Peeren
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release <= 4.6
 *
 * {@internal
 *   created  2008-09-08, Ingo van Peeren
 *   modified 2010-05-20, Murat Purc, standardized CONTENIDO startup and security check invocations, see [#CON-307]
 *   modified 2011-02-08, Dominik Ziegler, removed old PHP compatibility stuff as CONTENIDO now requires at least PHP 5
 *
 *   $Id:$:
 * }}
 *
 */

if (!defined('CON_FRAMEWORK')) {
    define('CON_FRAMEWORK', true);
}

// CONTENIDO startup process
include_once('./includes/startup.php');

$cfg['debug']['backend_exectime']['fullstart'] = getmicrotime();

cInclude('includes', 'functions.api.php');
cInclude('includes', 'functions.forms.php');

page_open(array(
    'sess' => 'Contenido_Session',
    'auth' => 'Contenido_Challenge_Crypt_Auth',
    'perm' => 'Contenido_Perm'
));

i18nInit($cfg['path']['contenido'] . $cfg['path']['locale'], $belang);

require_once($cfg['path']['contenido'] . $cfg['path']['includes'] . 'functions.includePluginConf.php');

cInclude('includes', 'cfg_actions.inc.php');

// Create CONTENIDO classes
// FIXME: Correct variable names, instances of classes at objects, not classes!
$db = new DB_Contenido();
$notification = new Contenido_Notification();
$classarea = new cApiAreaCollection();
$classlayout = new cApiLayout();
$classclient = new cApiClientCollection();
/** @deprecated [2012-03-27] Uninitialized global cApiUser instance is no more needed */
$classuser = new cApiUser();

$currentuser = new cApiUser($auth->auth['uid']);

// Change client
if (isset($changeclient) && is_numeric($changeclient)) {
    $client = $changeclient;
    unset($lang);
}

// Change language
if (isset($changelang) && is_numeric($changelang)) {
    unset($area_rights);
    unset($item_rights);
    $lang = $changelang;
}

if (!is_numeric($client) ||
    (!$perm->have_perm_client('client['.$client.']') &&
    !$perm->have_perm_client('admin['.$client.']')))
{
    // use first client which is accessible
    $sess->register('client');
    $oClientColl = new cApiClientCollection();
    if ($oClient = $oClientColl->getFirstAccessibleClient()) {
        unset($lang);
        $client = $oClient->get('idclient');
    }
} else {
    $sess->register('client');
}

if (!is_numeric($lang) || $lang == '') {
    $sess->register('lang');
    // search for the first language of this client
    $sql = "SELECT * FROM ".$cfg['tab']['lang']." AS A, ".$cfg['tab']['clients_lang']." AS B WHERE A.idlang=B.idlang AND idclient=".Contenido_Security::toInteger($client)." ORDER BY A.idlang ASC";
    $db->query($sql);
    $db->next_record();
    $lang = $db->f('idlang');
} else {
    $sess->register('lang');
}

// send right encoding http header
sendEncodingHeader($db, $cfg, $lang);

$perm->load_permissions();

// Create CONTENIDO classes
$tpl = new Template();
$backend = new Contenido_Backend();

// Register session variables
$sess->register('sess_area');

if (isset($area)) {
    $sess_area = $area;
} else {
    $area = (isset($sess_area) && $sess_area != '') ? $sess_area : 'login';
}

$sess->register('cfgClient');
$sess->register('errsite_idcat');
$sess->register('errsite_idart');

if ($cfgClient['set'] != 'set') {
     rereadClients();
}

// Initialize CONTENIDO_Backend.
// Load all actions from the DB and check if permission is granted.
if ($cfg['debug']['rendering'] == true) {
    $oldmemusage = memory_get_usage();
}

// Select area
$backend->select($area);

$cfg['debug']['backend_exectime']['start'] = getmicrotime();

// If $action is set -> User klicked some button/link
// get the appopriate code for this action and evaluate it.
if (isset($action) && $action != '') {
    if (!isset($idart)) {
        $idart = 0;
    }
    $backend->log($idcat, $idart, $client, $lang, $action);
}


if (isset($action)) {
    if ($backend->getCode($action) != '') {
        if ($backend->debug == 1) {
            echo '<pre style="font-family: verdana; font-size: 10px"><b>Executing:</b>'."\n";
            echo $backend->getCode($action)."\n";
            echo '</pre>';
        }
        eval($backend->getCode($action));
    } else {
        if ($backend->debug == 1) {
            echo '<pre style="font-family: verdana; font-size: 10px"><b>Executing:</b>'."\n";
            echo "no code available in action\n";
            echo '</pre>';
        }
    }
}

if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] != '') {
    $oAjax = new Ajax();
    $sReturn = $oAjax->handle($_REQUEST['ajax']);
    echo $sReturn;
} else {
    include_once($cfg['path']['contenido'].$cfg['path']['includes'] . 'ajax/include.ajax.' . $area . '.php');
}

$cfg['debug']['backend_exectime']['end'] = getmicrotime();

cDebug("Building this page (excluding contenido includes) took: " . ($cfg['debug']['backend_exectime']['end'] - $cfg['debug']['backend_exectime']['start']) . " seconds");
cDebug("Building the complete page took: " . ($cfg['debug']['backend_exectime']['end'] - $cfg['debug']['backend_exectime']['fullstart']) . " seconds");
cDebug("Include memory usage: " . human_readable_size(memory_get_usage()-$oldmemusage));
cDebug("Complete memory usage: " . human_readable_size(memory_get_usage()));

// User Tracking (who is online)
$oActiveUser = new cApiOnlineUserCollection();
$oActiveUser->startUsersTracking();

page_close();

?>