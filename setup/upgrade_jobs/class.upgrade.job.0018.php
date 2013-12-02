<?php

/**
 * This file contains the upgrade job 17.
 *
 * @package Setup
 * @subpackage UpgradeJob
 * @version SVN Revision $Rev:$
 *
 * @author frederic.schneider
 * @copyright four for business AG <www.4fb.de>
 * @license http://www.contenido.org/license/LIZENZ.txt
 * @link http://www.4fb.de
 * @link http://www.contenido.org
 */

// assert CONTENIDO framework
defined('CON_FRAMEWORK') || die('Illegal call: Missing framework initialization - request aborted.');

/**
 * Upgrade job 18.
 *
 * Add column 'uri' in table con_pifa_form so that buttons of type image can
 * store an URI to their image.
 *
 * @package Setup
 * @subpackage UpgradeJob
 */
class cUpgradeJob_0018 extends cUpgradeJobAbstract {

    public $maxVersion = "4.9.3";

    public function _execute() {
        global $cfg;

        if ($_SESSION['setuptype'] == 'upgrade') {

            // Initializing cApiActionCollection
            $actionColl = new cApiActionCollection();

            // Include PIM collections
            plugin_include('pim', 'classes/class.pim.plugin.collection.php');
            plugin_include('pim', 'classes/class.pim.plugin.relations.collection.php');

            // Initializing PimPluginRelationsCollection
            $pluginRelColl = new PimPluginRelationsCollection();

            // Initializing PimPluginCollection
            $pluginColl = new PimPluginCollection();

            // Get all installed plugins
            $pluginColl->select();
            while ($plugin = $pluginColl->next()) {

                // Get path to plugin.xml
                $pluginXmlPath = $cfg['path']['contenido'] . $cfg['path']['plugins'] . cSecurity::escapeString($plugin->get('folder')) . DIRECTORY_SEPARATOR . "plugin.xml";

                // Load plugin.xml and get xml strings
                $xml = simplexml_load_string(file_get_contents($pluginXmlPath));

                // Count all actions at plugin.xml for this plugin
                $actionCount = count($xml->contenido->actions->action);

                for ($i = 0; $i < $actionCount; $i++) {

                    // Get id of selected action
                    $actionColl->select("name = '" . cSecurity::escapeString($xml->contenido->actions->action[$i]) . "'");
                    $actionId = $actionColl->next();

                    // Set a relation
                    $pluginRelColl->create($actionId->get("idaction"), $plugin->get('idplugin'), 'action');
                }
            }
        }
    }

}
