<?php
/**
 * Project: 
 * Contenido Content Management System
 * 
 * Description: 
 * Display languages
 * 
 * Requirements: 
 * @con_php_req 5.0
 * 
 *
 * @package    Contenido Backend includes
 * @version    1.0.1
 * @author     Olaf Niemann
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 * @since      file available since contenido release <= 4.6
 * @deprecated Was replaced by include.default_subnav.php
 * 
 * {@internal 
 *   created 2003-04-23
 *   modified 2008-06-27, Frederic Schneider, add security fix
 *   modified 2010-09-07, Oliver Lohkemper, deprecated
 *
 *   $Id: include.log_menu.php 1205 2010-09-07 10:33:42Z OliverL $:
 * }}
 * 
 */

if(!defined('CON_FRAMEWORK')) {
	die('Illegal call');
}

$tpl->reset();

# Generate template
$tpl->generate($cfg['path']['templates'] . $cfg['templates']['log_menu']);
?>