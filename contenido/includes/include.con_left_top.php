<?php

/**
 * This file contains the left top frame backend page for content area.
 *
 * @package          Core
 * @subpackage       Backend
 * @author           Jan Lengowski
 * @copyright        four for business AG <www.4fb.de>
 * @license          http://www.contenido.org/license/LIZENZ.txt
 * @link             http://www.4fb.de
 * @link             http://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

cInclude("includes", "functions.str.php");
cInclude("includes", "functions.tpl.php");
cInclude('includes', 'functions.lang.php');

$tpl->reset();
global $sess, $frame, $area;
$idcat = (isset($_GET['idcat']) && is_numeric($_GET['idcat'])) ? $_GET['idcat'] : -1;

// Get sync options
if (isset($syncoptions)) {
    $syncfrom = (int) $syncoptions;
    $remakeCatTable = true;
}

if (!isset($syncfrom)) {
    $syncfrom = -1;
}

$syncoptions = $syncfrom;

$tpl->set('s', 'SYNC_LANG', $syncfrom);

// Delete a saved search
$bShowArticleSearch = false;
if (isset($_GET['delsavedsearch'])) {
    if (isset($_GET['itemtype']) && count($_GET['itemtype']) > 0 && isset($_GET['itemid']) && count($_GET['itemid']) > 0) {
        $propertyCollection = new cApiPropertyCollection();
        $propertyCollection->deleteProperties($_GET['itemtype'], $_GET['itemid']);
        $bShowArticleSearch = true;
    }
}

if (isset($_GET['save_search']) && $_GET['save_search'] == 'true') {
    $bShowArticleSearch = true;
}

// ARTICLE SEARCH
$arrDays      = ['--'] + range(0, 31);
$arrMonths    = ['--'] + range(0, 12);
$sCurrentYear = (int)date('Y');
$arrYears     = range($sCurrentYear - 10, $sCurrentYear + 30);
$arrYears     = ['-----'] + array_combine($arrYears, $arrYears);

// get user input
$bsSearchText              = isset($_REQUEST['bs_search_text']) ? $_REQUEST['bs_search_text'] : '';
$bsSearchId                = isset($_REQUEST['bs_search_id']) ? $_REQUEST['bs_search_id'] : '';
$bsSearchDateType          = isset($_REQUEST['bs_search_date_type']) ? $_REQUEST['bs_search_date_type'] : 'n/a';
$bsSearchDateTypeFromDay   = isset($_REQUEST['bs_search_date_from_day']) ? $_REQUEST['bs_search_date_from_day'] : '';
$bsSearchDateTypeFromMonth = isset($_REQUEST['bs_search_date_from_month']) ? $_REQUEST['bs_search_date_from_month'] : '';
$bsSearchDateTypeFromYear  = isset($_REQUEST['bs_search_date_from_year']) ? $_REQUEST['bs_search_date_from_year'] : '';
$bsSearchDateToDay         = isset($_REQUEST['bs_search_date_to_day']) ? $_REQUEST['bs_search_date_to_day'] : '';
$bsSearchDateToMonth       = isset($_REQUEST['bs_search_date_to_month']) ? $_REQUEST['bs_search_date_to_month'] : '';
$bsSearchDateToYear        = isset($_REQUEST['bs_search_date_to_year']) ? $_REQUEST['bs_search_date_to_year'] : '';
$bsSearchAuthor            = isset($_REQUEST['bs_search_author']) ? $_REQUEST['bs_search_author'] : 'n/a';

// validate user input
$bsSearchDateTypeFromDay   = max(0, (int)$bsSearchDateTypeFromDay);
$bsSearchDateTypeFromMonth = max(0, (int)$bsSearchDateTypeFromMonth);
$bsSearchDateTypeFromYear  = max(0, (int)$bsSearchDateTypeFromYear);
$bsSearchDateToDay         = max(0, (int)$bsSearchDateToDay);
$bsSearchDateToMonth       = max(0, (int)$bsSearchDateToMonth);
$bsSearchDateToYear        = max(0, (int)$bsSearchDateToYear);

// get users
$sql = "SELECT username, realname
        FROM " . $cfg['tab']['user'] . "
        ORDER BY realname";
$db->query($sql);

$arrUsers = ['n/a' => '-'];
while ($db->nextRecord()) {
    $arrUsers[$db->f('username')] = empty($db->f('realname')) ? $db->f('username') : $db->f('realname');
}

$articleLink = "editarticle";
$oListOptionRow = new cGuiFoldingRow("3498dbba-ed4a-4618-8e49-3a3635396e22", i18n("Article search"), $articleLink, $bShowArticleSearch);
$tpl->set('s', 'ARTICLELINK', $articleLink);

// Textfeld
$oTextboxArtTitle = new cHTMLTextbox("bs_search_text", $bsSearchText, 10);
$oTextboxArtTitle->setStyle('width:135px;');

// Artikel_ID-Feld
$oTextboxArtID = new cHTMLTextbox("bs_search_id", $bsSearchId, 10);
$oTextboxArtID->setStyle('width:135px;');

// Date type
$oSelectArtDateType = new cHTMLSelectElement("bs_search_date_type", "bs_search_date_type");
$oSelectArtDateType->autoFill(
    [
        'n/a'          => i18n('Ignore'),
        'created'      => i18n('Date created'),
        'lastmodified' => i18n('Date modified'),
        'published'    => i18n('Date published'),
    ]
);
$oSelectArtDateType->setStyle('width:135px;');
$oSelectArtDateType->setEvent("Change", "toggle_tr_visibility('tr_date_from');toggle_tr_visibility('tr_date_to');");
$oSelectArtDateType->setDefault($bsSearchDateType);

// DateFrom
$oSelectArtDateFromDay = new cHTMLSelectElement('bs_search_date_from_day');
$oSelectArtDateFromDay->setStyle('width:40px;');
$oSelectArtDateFromDay->autoFill($arrDays);
$oSelectArtDateFromDay->setDefault($bsSearchDateTypeFromDay);

$oSelectArtDateFromMonth = new cHTMLSelectElement('bs_search_date_from_month');
$oSelectArtDateFromMonth->setStyle('width:40px;');
$oSelectArtDateFromMonth->autoFill($arrMonths);
$oSelectArtDateFromMonth->setDefault($bsSearchDateTypeFromMonth);

$oSelectArtDateFromYear = new cHTMLSelectElement('bs_search_date_from_year');
$oSelectArtDateFromYear->setStyle('width:55px;');
$oSelectArtDateFromYear->autoFill($arrYears);
$oSelectArtDateFromYear->setDefault($bsSearchDateTypeFromYear);

// DateTo
$oSelectArtDateToDay = new cHTMLSelectElement('bs_search_date_to_day');
$oSelectArtDateToDay->setStyle('width:40px;');
$oSelectArtDateToDay->autoFill($arrDays);
$oSelectArtDateToDay->setDefault($bsSearchDateToDay);

$oSelectArtDateToMonth = new cHTMLSelectElement('bs_search_date_to_month');
$oSelectArtDateToMonth->setStyle('width:40px;');
$oSelectArtDateToMonth->autoFill($arrMonths);
$oSelectArtDateToMonth->setDefault($bsSearchDateToMonth);

$oSelectArtDateToYear = new cHTMLSelectElement('bs_search_date_to_year');
$oSelectArtDateToYear->setStyle('width:55px;');
$oSelectArtDateToYear->autoFill($arrYears);
$oSelectArtDateToYear->setDefault($bsSearchDateToYear);

// Author
$oSelectArtAuthor = new cHTMLSelectElement('bs_search_author');
$oSelectArtAuthor->setStyle('width:135px;');
$oSelectArtAuthor->autoFill($arrUsers);
$oSelectArtAuthor->setDefault($bsSearchAuthor);

$oSubmit = new cHTMLButton("submit", i18n("Search"));

$tplSearch = new cTemplate();
$tplSearch->set("s", "AREA", $area);
$tplSearch->set("s", "FRAME", $frame);
$tplSearch->set("s", "LANG", $lang);
$tplSearch->set("s", "LANGTEXTDIRECTION", langGetTextDirection($lang));
$tplSearch->set("s", "TEXTBOX_ARTTITLE", $oTextboxArtTitle->render());
$tplSearch->set("s", "TEXTBOX_ARTID", $oTextboxArtID->render());
$tplSearch->set("s", "SELECT_ARTDATE", $oSelectArtDateType->render());
$tplSearch->set("s", "SELECT_ARTDATEFROM", $oSelectArtDateFromDay->render() . $oSelectArtDateFromMonth->render() . $oSelectArtDateFromYear->render());
$tplSearch->set("s", "SELECT_ARTDATETO", $oSelectArtDateToDay->render() . $oSelectArtDateToMonth->render() . $oSelectArtDateToYear->render());
$tplSearch->set("s", "SELECT_AUTHOR", $oSelectArtAuthor->render());
$tplSearch->set("s", "SUBMIT_BUTTON", $oSubmit->render());

// Saved searches

$proppy = new cApiPropertyCollection();
$savedSearchList = $proppy->getAllValues('type', 'savedsearch', $auth);

foreach ($savedSearchList as $value) {
    if ($value["name"] == "save_name") {
        $tplSearch->set("d", "SEARCH_NAME", ($value['value'] == "") ? i18n("A saved search") : $value['value']);
        $tplSearch->set("d", "ITEM_ID", $value['itemid']);
        $tplSearch->set("d", "ITEM_TYPE", $value['itemtype']);
        $tplSearch->next();
    }
}

$oListOptionRow->setContentData($tplSearch->generate($cfg['path']['templates'] . $cfg["templates"]["con_left_top_art_search"], true));

$sSelfLink = 'main.php?area=' . $area . '&frame=2&' . $sess->name . "=" . $sess->id;
$tpl->set('s', 'SELFLINK', $sSelfLink);

$tpl->set('s', 'SEARCH', $oListOptionRow->render());

// Category
$sql = "SELECT idtpl, name FROM " . $cfg['tab']['tpl'] . " WHERE idclient = '" . cSecurity::toInteger($client) . "' ORDER BY name";
$db->query($sql);

$tpl->set('s', 'ID', 'oTplSel');
$tpl->set('s', 'NAME', 'oTplSel');
$tpl->set('s', 'CLASS', 'text_medium');
$tpl->set('s', 'OPTIONS', 'style="width:85%;"');
$tpl->set('s', 'BELANG', $belang);

$tpl->set('d', 'VALUE', '0');
$tpl->set('d', 'CAPTION', i18n("Choose template"));
$tpl->set('d', 'SELECTED', '');
$tpl->next();

$tpl->set('d', 'VALUE', '0');
$tpl->set('d', 'CAPTION', '--- ' . i18n("none") . ' ---');
$tpl->set('d', 'SELECTED', '');
$tpl->next();

$categoryLink = "editcat";
$editCategory = new cGuiFoldingRow("3498dbbb-ed4a-4618-8e49-3a3635396e22", i18n("Edit category"), $categoryLink);

while ($db->nextRecord()) {
    $tplname = $db->f('name');

    $tpl->set('d', 'VALUE', $db->f('idtpl'));
    $tpl->set('d', 'CAPTION', $tplname);
    $tpl->set('d', 'SELECTED', '');
    $tpl->next();
}
// Template Dropdown
$tplCatConfig = new cTemplate();
$tplCatConfig->set("s", "TEMPLATE_SELECT", $tpl->generate($cfg['path']['templates'] . $cfg['templates']['generic_select'], true));

$editCategory->setContentData($tplCatConfig->generate($cfg["path"]["templates"] . $cfg['templates']['con_left_top_cat_edit'], true));

$tpl->set('s', 'CAT_HREF', $sess->url("main.php?area=con_tplcfg&action=tplcfg_edit&frame=4&mode=art") . '&idcat=');
$tpl->set('s', 'IDCAT', $idcat);

$tpl->set('s', 'EDIT', $editCategory->render());
$tpl->set('s', 'CATEGORYLINK', $categoryLink);

//  SYNCSTUFF
$languages = getLanguageNamesByClient($client);
if (count($languages) > 1 && $perm->have_perm_area_action($area, "con_synccat")) {
    $sListId = 'sync';
    $oListOptionRow = new cGuiFoldingRow("4808dbba-ed4a-4618-8e49-3a3635396e22", i18n("Synchronize from"), $sListId);

    if (($syncoptions > 0) && ($syncoptions != $lang)) {
        $oListOptionRow->setExpanded(true);
    }

    $selectbox = new cHTMLSelectElement("syncoptions");

    $option = new cHTMLOptionElement("--- " . i18n("None") . " ---", -1);
    $selectbox->addOptionElement(-1, $option);
    foreach ($languages as $languageid => $languagename) {
        if ($lang != $languageid && $perm->have_perm_client_lang($client, $languageid)) {
            $option = new cHTMLOptionElement($languagename . " (" . $languageid . ")", $languageid);
            $selectbox->addOptionElement($languageid, $option);
        }
    }
    $selectbox->setDefault($syncoptions);

    $tplSync = new cTemplate();
    $tplSync->set("s", "TEXT_DIRECTION", langGetTextDirection($lang));
    $tplSync->set("s", "AREA", $area);
    $tplSync->set("s", "FRAME", $frame);
    $tplSync->set("s", "SELECTBOX", $selectbox->render());

    $oListOptionRow->setContentData($tplSync->generate($cfg["path"]["templates"] . $cfg["templates"]["con_left_top_sync"], true));

    $link = $sess->url("main.php?area=" . $area . "&frame=2") . '&syncoptions=';
    $sJsLink = "Con.multiLink('left_bottom', '{$link}' + document.getElementsByName('syncoptions')[0].value + '&refresh_syncoptions=true');";
    $tpl->set('s', 'UPDATE_SYNC_REFRESH_FRAMES', $sJsLink);
    $tpl->set('s', 'SYNCRONIZATION', $oListOptionRow->render());
    $tpl->set('s', 'SYNCLINK', $sListId);
    $sSyncLink = $sess->url("main.php?area=$area&frame=2&action=con_synccat");
    $tpl->set('s', 'SYNC_HREF', $sSyncLink);
} else {
    $tpl->set('s', 'SYNCRONIZATION', '');
    $tpl->set('s', 'SYNCLINK', $sListId);
    $tpl->set('s', 'SYNC_HREF', '');
}

// Collapse / Expand / Config Category
$selflink = "main.php";
$expandlink = $sess->url($selflink . "?area=$area&frame=2&expand=all");
$collapselink = $sess->url($selflink . "?area=$area&frame=2&collapse=all");
$tpl->set('s', 'COLLAPSE_LINK', $collapselink);
$tpl->set('s', 'EXPAND_LINK', $expandlink);

// necessary for expanding/collapsing of navigation tree per javascript/AJAX (I. van Peeren)
$tpl->set('s', 'AREA', $area);
$tpl->set('s', 'AJAXURL', cRegistry::getBackendUrl() . 'ajaxmain.php');

// LEGEND
$legendlink = 'legend';
$editCategory = new cGuiFoldingRow("31f52be2-7499-4d21-8175-3917129e6014", i18n("Legend"), $legendlink);

$divLegend = new cHTMLDiv("", "articleLegend", "legend-content");

$aInformation = array('imgsrc', 'description');
if (empty($aData)) {
    $aData = [];
}
$aData = xmlFileToArray($cfg['path']['xml'] . "legend.xml", $aData, $aInformation);

foreach ($aData as $key => $item) {
    $divKey = new cHTMLDiv("", $key);
    foreach ($item as $data) {
        $image = new cHTMLImage((string) $data['imgsrc'], "vAlignMiddle");
        $description = new cHTMLSpan(i18n((string) $data['description']), "tableElement");
        $divItem = new cHTMLDiv($image->render() . $description->render());
        $divKey->appendContent($divItem->render());
    }
    $divLegend->appendContent($divKey->render());
}

$editCategory->setContentData($divLegend->render());
$tpl->set('s', 'LEGEND', $editCategory->render());
$tpl->set('s', 'LEGENDLINK', $legendlink);

// Help
$tpl->set('s', 'HELPSCRIPT', getJsHelpContext("con"));
// CON-1907 show workflow icon only when plugin is installed
$tpl->set('s', 'DISPLAY', class_exists('Workflows')?'' : 'display:none;');
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['con_left_top']);

/**
 *
 * @param string $filename
 * @param array  $aData
 * @param array  $aInformation
 *
 * @return array
 */
function xmlFileToArray($filename, $aData = [], $aInformation)
{
    $_dom = simplexml_load_file($filename);
    for ($i = 0, $size = count($_dom); $i < $size; $i++) {
        foreach ($aInformation as $sInfoName) {
            if (!empty($_dom->article[$i]->$sInfoName)) {
                $aData['article'][$i][$sInfoName] = $_dom->article[$i]->$sInfoName;
            }
            if (!empty($_dom->category[$i]->$sInfoName)) {
                $aData['category'][$i][$sInfoName] = $_dom->category[$i]->$sInfoName;
            }
        }
    }

    return $aData;
}

?>