<?php
/**
 * Project: 
 * Contenido Content Management System
 * 
 * Description: 
 * 
 * Requirements: 
 * @con_php_req 5.0
 * 
 *
 * @package    Contenido Backend classes
 * @version    1.0
 * @author     Unknown
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * 
 * {@internal 
 *   created 
 *
 *   $Id: 
 * }}
 * 
 */
 
if(!defined('CON_FRAMEWORK')) {
	die('Illegal call');
}

cInclude('classes', 'class.security.php');


function cecFrontendCategoryAccess_Backend($idlang, $idcat, $user)
{
	global $cfg;
	$sql = "SELECT idright 
					FROM ".$cfg["tab"]["rights"]." AS A,
						 ".$cfg["tab"]["actions"]." AS B,
						 ".$cfg["tab"]["area"]." AS C
					 WHERE B.name = 'front_allow' AND C.name = 'str' AND A.user_id = '".$user."' AND A.idcat = '$idcat'
							AND A.idarea = C.idarea AND B.idaction = A.idaction AND A.idlang = $idlang";
	$db2 = new DB_Contenido;
	$db2->query(Contenido_Security::escapeDB($sql, $db2));

	if (!$db2->next_record())
	{
		return false;
	}
	else
	{
		return true;
	}
}
?>