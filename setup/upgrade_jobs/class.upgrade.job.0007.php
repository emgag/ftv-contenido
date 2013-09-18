<?php
/**
 * This file contains the upgrade job 7.
 *
 * @package    Setup
 * @subpackage UpgradeJob
 * @version    SVN Revision $Rev:$
 *
 * @author     Simon Sprankel
 * @copyright  four for business AG <www.4fb.de>
 * @license    http://www.contenido.org/license/LIZENZ.txt
 * @link       http://www.4fb.de
 * @link       http://www.contenido.org
 */

defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * Upgrade job 7.
 * Runs the upgrade job to remove unused mail logging include files from the DB.
 * Additionally, the used include file is renamed.
 * Besides, the column idmail_resend is removed from the con_mail_log_success
 * table.
 *
 * @package Setup
 * @subpackage UpgradeJob
 */
class cUpgradeJob_0007 extends cUpgradeJobAbstract {

    public $maxVersion = "4.9.0-beta1";

    public function _execute() {
        global $cfg, $db;

        if ($this->_setupType == 'upgrade') {
            // get the IDs of the mail log areas
            $areaItem = new cApiArea();
            $areaItem->loadByMany(array(
                'parent_id' => '0',
                'name' => 'mail_log'
            ));
            $mainIdarea = $areaItem->get('idarea');
            $areaItem->loadByMany(array(
                'parent_id' => 'mail_log',
                'name' => 'mail_log_overview'
            ));
            $overviewIdarea = $areaItem->get('idarea');
            $areaItem->loadByMany(array(
                'parent_id' => 'mail_log',
                'name' => 'mail_log_detail'
            ));
            $detailIdarea = $areaItem->get('idarea');

            // delete the unused mail log include files and rename the used ones
            $fileCollection = new cApiFileCollection();
            $file = new cApiFile();

            // delete the include.mail_log.left_bottom.php entry
            $file->loadByMany(array(
                'idarea' => $mainIdarea,
                'filename' => 'include.mail_log.left_bottom.php'
            ));
            if ($file->isLoaded()) {
                $fileCollection->delete($file->get('idfile'));
            }

            // delete the include.mail_log.subnav.php entry
            $file->loadByMany(array(
                'idarea' => $mainIdarea,
                'filename' => 'include.mail_log_subnav.php'
            ));
            if ($file->isLoaded()) {
                $fileCollection->delete($file->get('idfile'));
            }

            // rename the include.mail_log.right_bottom.php entries to
            // include.mail_log.php
            $file->loadByMany(array(
                'idarea' => $mainIdarea,
                'filename' => 'include.mail_log.right_bottom.php'
            ));
            if ($file->isLoaded()) {
                $file->set('filename', 'include.mail_log.php');
                $file->store();
            }
            $file->loadByMany(array(
                'idarea' => $overviewIdarea,
                'filename' => 'include.mail_log.right_bottom.php'
            ));
            if ($file->isLoaded()) {
                $file->set('filename', 'include.mail_log.php');
                $file->store();
            }
            $file->loadByMany(array(
                'idarea' => $detailIdarea,
                'filename' => 'include.mail_log.right_bottom.php'
            ));
            if ($file->isLoaded()) {
                $file->set('filename', 'include.mail_log.php');
                $file->store();
            }

            // remove the column idmail_resend from the table
            // con_mail_log_success if it exists
            $columns = array();
            $sql = 'SHOW COLUMNS FROM ' . $cfg['tab']['mail_log_success'];
            $db->query($sql);
            while ($db->nextRecord()) {
                $columns[] = $db->f('Field');
            }
            if (in_array('idmail_resend', $columns)) {
                $sql = 'ALTER TABLE `' . $cfg['tab']['mail_log_success'] . '` DROP `idmail_resend`';
                $db->query($sql);
            }
        }
    }

}