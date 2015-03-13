<?php
/**
 * Add and update map locations
 *
 * @category Plugins
 * @package GuildMap
 * @copyright (c) 2006, Garrett Hunter <loganfive@blacktower.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @author Garrett Hunter <loganfige@blacktower.com>
 * @version $Id: manage_gm.php,v 1.3 2007/02/19 03:56:37 garrett Exp $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
//define('IN_ADMIN', true);
define('PLUGIN', 'guildmap');

$eqdkp_root_path = './../../';

include_once($eqdkp_root_path . 'common.php');
include_once('config_gm.php');

$guildmap = $pm->get_plugin('guildmap');

if ( !$pm->check(PLUGIN_INSTALLED, 'guildmap') )
{
    message_die('The GuildMap plugin is not installed.');
}

//$user->check_auth('a_members_man');

/**
 * Handle GuildMap admin events
 * @subpackage ManageGM
 */
class ManageGM_Admin extends EQdkp_Admin {

    function ManageGM_Admin()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        parent::eqdkp_admin();

        $this->assoc_params(array(
            'config' => array(
                'name'    => 'mode',
                'value'   => 'config',
                'process' => 'config'),
//                'check'   => 'a_members_man'),
            'add' => array(
                'name'    => 'mode',
                'value'   => 'add',
                'process' => 'add')
//                'check'   => 'a_members_man')
        ));
    }

    function config () {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        include('admin/gm_config.php');
        $extension = new GuildMap_Config;
        $extension->process();
    }

    function add () {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        include('admin/gm_add.php');
        $extension = new GuildMap_Add;
        $extension->process();
    }

}

$ManageGM_Admin = new ManageGM_Admin;
$ManageGM_Admin->process();

?>