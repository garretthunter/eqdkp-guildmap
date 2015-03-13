<?php
/**
 * Update Google Map configuration settings
 *
 * @category Plugins
 * @package GuildMap
 * @copyright (c) 2006, Garrett Hunter <loganfive@blacktower.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @author Garrett Hunter <loganfive@blacktower.com>
 * @version $Id: gm_config.php,v 1.2 2007/01/05 04:26:18 garrett Exp $
 */

if ( !defined('EQDKP_INC') )
{
    die('Hacking attempt');
}

/**
 * Manages configuration settings
 * @subpackage GuildMapAdmin
 */
class GuildMap_Config extends EQdkp_Admin
{
    var $_config_name = 'GoogleMapsAPIKey';

    function GuildMap_Config()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        parent::eqdkp_admin();

        $this->assoc_buttons(array(
            'submit' => array(
                'name'    => 'submit',
                'process' => 'process_submit',
                'check'   => 'a_members_man'),
            'form' => array(
                'name'    => '',
                'process' => 'display_form',
                'check'   => 'a_members_man'))
        );

    }

    function error_check()
    {
        global $user;

        if ( isset($_POST['submit']) )
        {
            $this->fv->is_filled('api_key', $user->lang['fv_required_gm_api_key']);
        }

        return $this->fv->is_error();
    }

    /**
     * Process Submit
     */
    function process_submit()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        $_POST = htmlspecialchars_array($_POST);

        // Update each config setting
        $this->config_set(array(
            $this->_config_name => $_POST['api_key']
        ));

        $success_message = sprintf($user->lang['gm_admin_add_api_key_success'], $_POST['api_key']);
        $link_list = array(
            $user->lang['gm_adminmenu_add'] => 'manage_gm.php' . $SID . '&amp;mode=add');
        $this->admin_die($success_message, $link_list);
    }

    /**
     * Display form
     */
    function display_form()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        /**
         * Get any sets associated with this category
         */
        $sql = "SELECT config_value
                  FROM ".CONFIG_TABLE."
                 WHERE config_name = '".$this->_config_name."'";
        $api_key = $db->query_first($sql);

        $tpl->assign_vars(array(
            // Form vars
            'F_CONFIG'      => 'manage_gm.php' . $SID . '&amp;mode=config',

            // Form values
            'API_KEY'           => $api_key,

            // Language
            'L_GM_CONFIG_TITLE' => $user->lang['gm_admin_title_configure'],
            'L_API_KEY'         => $user->lang['gm_api_key'],
            'L_API_KEY_HELP'    => $user->lang['gm_api_key_help'],

            'L_RESET'           => $user->lang['reset'],
            'L_UPDATE'          => $user->lang['update'],

            // Form validation
            'FV_API_KEY'   => $this->fv->generate_error('api_key'),

            // Menu Links
        ));

        $eqdkp->set_vars(array(
            'page_title'    => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['gm_adminmenu_title'].": ".$user->lang['gm_admin_title_configure'],
            'template_path' => $pm->get_data('guildmap', 'template_path'),
            'template_file' => 'admin/config.html',
            'display'       => true)
        );
    }

    /**
     * config_set
     * @param $config_name mixed
     * @param $config_value
     */
    function config_set($config_name, $config_value='')
    {
        global $db;

        if ( is_object($db) )
        {
            if ( is_array($config_name) )
            {
                foreach ( $config_name as $d_name => $d_value )
                {
                    $this->config_set($d_name, $d_value);
                }
            }
            else
            {
                $sql = 'UPDATE ' . CONFIG_TABLE . "
                        SET config_value='".strip_tags(htmlspecialchars($config_value))."'
                        WHERE config_name='".$config_name."'";
                $db->query($sql);

                return true;
            }
        }

        return false;
    }
}
?>
