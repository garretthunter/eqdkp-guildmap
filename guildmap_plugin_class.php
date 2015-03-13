<?php
/**
 * Guild Map plugin class
 *
 * @category Plugins
 * @package GuildMap
 * @copyright (c) 2006, Garrett Hunter <loganfive@blacktower.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @author Garrett Hunter <loganfive@blacktower.com>
 * @version $Id: guildmap_plugin_class.php,v 1.1 2007/01/04 10:34:21 garrett Exp $
 */

if ( !defined('EQDKP_INC') )
{
    die('You cannot access this file directly.');
}

class GuildMap_Plugin_Class extends EQdkp_Plugin
{

    var $_config_name = 'GoogleMapsAPIKey';

    function GuildMap_Plugin_Class($pm)
    {
        global $eqdkp_root_path, $user, $SID;

        $this->eqdkp_plugin($pm);
        $this->pm->get_language_pack('guildmap');

        $this->add_data(array(
            'name'          => "Guild Map",
            'code'          => 'guildmap',
            'path'          => 'guildmap',
            'contact'       => 'loganfive@blacktower.com',
            'template_path' => 'plugins/guildmap/templates/',
            'version'       => '1.1.2')
        );

        /**
         * Register our permissions
         */

        /**
         * Register our menu
         */
        $this->add_menu('main_menu1', $this->gen_main_menu1());
        $this->add_menu('main_menu2', array());
        $this->add_menu('admin_menu', $this->gen_admin_menu());

        /**
         * Register our log events for:
         */

        /**
         * SQL instructions to execute upon installation
         */
        $this->add_sql(SQL_INSTALL, "INSERT INTO `". CONFIG_TABLE ."` ( `config_name` , `config_value` )  VALUES ('".$this->_config_name."', '' );");
        $this->add_sql(SQL_INSTALL, "ALTER TABLE `". MEMBERS_TABLE ."` ADD `member_latitude` FLOAT NULL, ADD `member_longitude` FLOAT NULL;");

        /**
         * SQL instructions to execute upon uninstallation
         */
        $this->add_sql(SQL_UNINSTALL, "DELETE FROM `". CONFIG_TABLE ."` WHERE `config_name` = '".$this->_config_name."';");
        $this->add_sql(SQL_UNINSTALL, "ALTER TABLE `". MEMBERS_TABLE ."` DROP `member_latitude`, DROP `member_longitude`;");

    }

    function gen_main_menu1()
    {
        if ( $this->pm->check(PLUGIN_INSTALLED, 'guildmap') )
        {
            global $db, $user;

            $main_menu1 = array(
                array('link' => "plugins/" . $this->get_data('path') . '/' . $SID,
                      'text' => $user->lang['gm_usermenu_map_home'],
                      'check' => 'u_member_list')
            );

            return $main_menu1;
        }
        return;
    }

    function gen_admin_menu()
    {
        global $db, $user, $SID, $eqdkp;

        if ( $this->pm->check(PLUGIN_INSTALLED, 'guildmap') )
        {

            $url_prefix = ( EQDKP_VERSION < '1.3.2' ) ? $eqdkp_root_path : '';

            $admin_menu = array(
                    'guildmap' => array(
                    0 => $user->lang['gm_adminmenu_title'],
                    1 => array('link'  => $url_prefix . 'plugins/' . $this->get_data('path') . '/manage_gm.php' . $SID . '&amp;mode=config',
                               'text'  => $user->lang['gm_adminmenu_configure'],
                               'check' => 'a_members_man'),

                    2 => array('link'  => $url_prefix . 'plugins/' . $this->get_data('path') . '/manage_gm.php' . $SID . '&amp;mode=add',
                               'text'  => $user->lang['gm_adminmenu_add'],
                               'check' => 'a_members_man'),
                )
             );

            return $admin_menu;
        }
        return;
    }

    /**
     * @var $page URI which caused the hook to be called
     */
    function do_hook($page) {
    }
}
?>