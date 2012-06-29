<?php
/**
 * Project:
 * CONTENIDO Content Management System
 *
 * Description:
 * CONTENIDO Group Member Edit Page
 *
 * Requirements:
 * @con_php_req 5.0
 *
 *
 * @package    CONTENIDO Backend Includes
 * @version    1.1.5
 * @author     Timo A. Hummel
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since CONTENIDO release <= 4.6
 *
 * {@internal
 *   created 2003-06-03
 *   modified 2008-06-26, Dominik Ziegler, add security fix
 *   modified 2009-11-06, Murat Purc, replaced deprecated functions (PHP 5.3 ready)
 *
 *   $Id$:
 * }}
 *
 */

if (!defined('CON_FRAMEWORK')) {
    die('Illegal call');
}

$db2 = cRegistry::getDb();
$tpl3 = new Template();

if (!$perm->have_perm_area_action($area, $action)) {
    $notification->displayNotification("error", i18n("Permission denied"));
    return;
} elseif (!isset($groupid)) {
    return;
}


if (($action == "group_deletemember") && ($perm->have_perm_area_action($area, $action))) {
    $aDeleteMembers = array();
    if (!is_array($_POST['user_in_group'])) {
        if ($_POST['user_in_group'] > 0) {
            $aDeleteMembers[] = $_POST['user_in_group'];
        }
    } else {
        $aDeleteMembers = $_POST['user_in_group'];
    }

    $groupMemberColl = new cApiGroupMemberCollection();
    foreach ($aDeleteMembers as $idgroupuser) {
        $groupMemberColl->delete((int) $idgroupuser);
    }

    $notification->displayNotification(Contenido_Notification::LEVEL_INFO, i18n("Removed member from group successfully!"));
}

if (($action == "group_addmember") && ($perm->have_perm_area_action($area, $action))) {
    if (is_array($newmember)) {
        foreach ($newmember as $key => $value) {
            $myUser = new cApiUser();

            if (!$myUser->loadByPrimaryKey($value)) {
                $myUser->loadUserByUserName($value);
            }

            if ($myUser->getField("user_id") == "") {
                continue;
            }

            $groupMemberColl = new cApiGroupMemberCollection();
            $groupMember = $groupMemberColl->fetchByUserIdAndGroupId($myUser->getField('user_id'), $groupid);

            if (!$groupMember) {
                // group member entry does not exists, create it
                $newGroupMember = $groupMemberColl->create($myUser->getField('user_id'), $groupid);
                if ($notiAdded == '') {
                    $notiAdded .= $myUser->getField('realname');
                } else {
                    $notiAdded .= ', ' . $myUser->getField('realname');
                }
            } else {
                // group member entry already exists
                if ($notiAlreadyExisting == '') {
                    $notiAlreadyExisting .= $myUser->getField('realname');
                } else {
                    $notiAlreadyExisting .= ', ' . $myUser->getField('realname');
                }
            }
        }

        $notification->displayNotification(Contenido_Notification::LEVEL_INFO, i18n("Added user to group successfully!"));
    }
}


$tab1 = $cfg["tab"]["groupmembers"];
$tab2 = $cfg["tab"]["phplib_auth_user_md5"];

$sortby = getEffectiveSetting("backend", "sort_backend_users_by", "");

if ($sortby!='') {
    $sql = "SELECT ".$tab1.".idgroupuser, ".$tab1.".user_id FROM ".$tab1."
            INNER JOIN ".$tab2." ON ".$tab1.".user_id = ".$tab2.".user_id WHERE
            group_id = '".$db->escape($groupid)."' ORDER BY ".$tab2.".".$sortby;
} else {
    #Show previous behaviour by default
    $sql = "SELECT ".$tab1.".idgroupuser, ".$tab1.".user_id FROM ".$tab1."
            INNER JOIN ".$tab2." ON ".$tab1.".user_id = ".$tab2.".user_id WHERE
            group_id = '".$db->escape($groupid)."' ORDER BY ".$tab2.".realname, ".$tab2.".username";
}

$db->query($sql);

$sInGroupOptions = '';
$aAddedUsers = array();
$myUser = new cApiUser();

while ($db->next_record()) {

    $myUser->loadByPrimaryKey($db->f("user_id"));
    $aAddedUsers[] = $myUser->getField("username");

    $sOptionLabel = $myUser->getField("realname").' ('.$myUser->getField("username").')';
    $sOptionValue = $db->f("idgroupuser");
    if ($sOptionValue != '' && $sOptionLabel != '') {
        $sInGroupOptions .= '<option value="'.$sOptionValue.'">'.$sOptionLabel.'</option>'."\n";
    }
}

$tpl3->set('s', 'IN_GROUP_OPTIONS', $sInGroupOptions);

//Sort user list by given criteria
$orderBy = getEffectiveSetting('backend', 'sort_backend_users_by', '');

$userColl = new cApiUserCollection();
$users = $userColl->getAccessibleUsers(explode(',', $auth->auth['perm']), false, $orderBy);

$bAddedUser = false;
$sNonGroupOptions = '';
if (is_array($users)) {
    foreach ($users as $key => $value) {
        if (!in_array($value["username"], $aAddedUsers)) {
            $bAddedUser = true;
            $sOptionLabel = $value["realname"] . " (".$value["username"].")";
            $sOptionValue = $key;
            if ($sOptionValue != '' && $sOptionLabel != '') {
                $sNonGroupOptions .= '<option value="'.$sOptionValue.'">'.$sOptionLabel.'</option>'."\n";
            }
        }
    }
}

$tpl3->set('s', 'NON_GROUP_OPTIONS', $sNonGroupOptions);

$tpl3->set('s', 'CATNAME', i18n("Manage group members"));
$tpl3->set('s', 'CATFIELD', "&nbsp;");
$tpl3->set('s', 'FORM_ACTION', $sess->url('main.php'));
$tpl3->set('s', 'AREA', $area);
$tpl3->set('s', 'GROUPID', $groupid);
$tpl3->set('s', 'FRAME', $frame);
$tpl3->set('s', 'IDLANG', $lang);
$tpl3->set('s', 'RECORD_ID_NAME', 'groupid');
$tpl3->set('s', 'ADD_ACTION', 'group_addmember');
$tpl3->set('s', 'DELETE_ACTION', 'group_deletemember');
$tpl3->set('s', 'STANDARD_ACTION', 'group_addmember');
$tpl3->set('s', 'IN_GROUP_VALUE', $_POST['filter_in']);
$tpl3->set('s', 'NON_GROUP_VALUE', $_POST['filter_non']);
$tpl3->set('s', 'DISPLAY_OK', 'none');
$tpl3->set('s', 'RELOADSCRIPT', '');

# Generate template
$tpl3gen = $tpl3->generate($cfg['path']['templates'] . $cfg['templates']['grouprights_memberselect'],true);
echo $tpl3gen;

?>