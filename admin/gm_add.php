<?php
/**
 * Adds a Google Map location
 *
 * @category Plugins
 * @package GuildMap
 * @copyright (c) 2006, Garrett Hunter <loganfive@blacktower.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @author Garrett Hunter <loganfive@blacktower.com>
 * @version $Id: gm_add.php,v 1.5 2007/02/19 03:56:37 garrett Exp $
 */

if ( !defined('EQDKP_INC') )
{
    die('Hacking attempt');
}

/**
 * Adds a location
 * @subpackage GuildMapAdmin
 */
class GuildMap_Add extends EQdkp_Admin
{
    var $_config_name = 'GoogleMapsAPIKey';

    function GuildMap_Add()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        parent::eqdkp_admin();

        $this->member = array(
            'member_id' => post_or_db('member_id'),
            'member_latitude' => post_or_db('member_latitude'),
            'member_longitude' => post_or_db('member_longitude')
        );

        $this->set_vars(array(
            'uri_parameter' => 'member_id',
            'url_id'        => $_POST['member_id']			
        ));

        $this->assoc_buttons(array(
            'update' => array(
                'name'    => 'update',
                'process' => 'process_update'),
//                'check'   => 'a_members_man'),
            'form' => array(
                'name'    => '',
                'process' => 'display_form'))
//                'check'   => 'a_members_man'))
        );
	
    }

    /**
     * error check
     */
    function error_check()
    {
        global $user;

		if ($_POST['update']) {
			$this->fv->is_number(array(
				'member_id'         => $user->lang['fv_required_name'],
				'member_latitude'       => $user->lang['fv_number'],
				'member_longitude'      => $user->lang['fv_number'],
			));
		}

        return $this->fv->is_error();
    }

    /**
     * Process Update
     */
    function process_update()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        $_POST = htmlspecialchars_array($_POST);

        /**
         * Update the location
         */
        $query = $db->build_query('UPDATE', array(
            'member_latitude'   => $_POST['member_latitude'],
            'member_longitude'  => $_POST['member_longitude'],
        ));
        $db->query('UPDATE ' . MEMBERS_TABLE . ' SET ' . $query . " WHERE member_id=" . $_POST['member_id']);

        $this->display_form();
    }

    /**
     * Display form
     */
    function display_form()
    {
        global $db, $eqdkp, $user, $tpl, $pm;
        global $SID;

        /**
         * Generate the list main characters
         */
        $sql =   'SELECT member_id, member_name
                    FROM ' . MEMBERS_TABLE . '
                ORDER BY member_name';
        $result = $db->query($sql);

        $count_of_mains = $db->num_rows($result);
        while ( $row = $db->fetch_record($result) )
        {
            $tpl->assign_block_vars('main_member_row', array(
                'VALUE'    => $row['member_id'],
                'SELECTED' => ( $this->member['member_id'] == $row['member_id'] ) ? ' selected="selected"' : '',
                'OPTION'   => $row['member_name'])
            );
        }
        $db->free_result($result);

        /**
         * get the google maps api key
         */
        $sql = "SELECT config_value
                  FROM ".CONFIG_TABLE."
                 WHERE config_name = '".$this->_config_name."'";
        $api_key = $db->query_first($sql);

		/**
		 * Get member location
		 */
		if ( !empty($this->url_id) ) {
		
			$sql = "SELECT member_id, member_name, member_latitude, member_longitude
			          FROM ".MEMBERS_TABLE."
					 WHERE member_id = ".$this->url_id;
		
            $result = $db->query($sql);
            $row = $db->fetch_record($result);
            $db->free_result($result);

            $this->member = array(
                'member_id'         => $row['member_id'],
                'member_name'       => $row['member_name'],
                'member_latitude'   => $row['member_latitude'],
                'member_longitude'  => $row['member_longitude'],
            );
        }

        $tpl->assign_vars(array(
            // Form vars
            'F_CONFIG'      => 'manage_gm.php' . $SID . '&amp;mode=add',

            // Form values
            'ONLOAD'            => " onload=\"load()\" onunload=\"GUnload()\"",
            'API_KEY'           => $api_key,
            'LATITUDE'          => $this->member['member_latitude'],
            'LONGITUDE'         => $this->member['member_longitude'],

			/**
			 * toggle the map based on whether or not a member has been selected
			 */			
			'S_MEMBER'			=> $this->url_id,

            // Language
            'L_GM_ADD_TITLE'        => $user->lang['gm_admin_title_add'],
            'L_ADD_MEMBER_MESSAGE'  => $user->lang['gm_add_member_message'],
            'L_LATITUDE'            => $user->lang['gm_latitude'],
            'L_LONGITUDE'           => $user->lang['gm_longitude'],
            'L_MEMBER'              => $user->lang['name'],
			'L_MISSING_API_KEY'		=> $user->lang['gm_missing_api_key'],

            'L_RESET'           => $user->lang['reset'],
            'L_UPDATE'          => $user->lang['update'],

            // Form validation
            'FV_NAME'       => $this->fv->generate_error('member_id'),
            'FV_LATITUDE'   => $this->fv->generate_error('member_latitude'),
            'FV_LONGITUDE'  => $this->fv->generate_error('member_longitude'),

            // Menu Links
        ));

        $eqdkp->set_vars(array(
            'page_title'    => sprintf($user->lang['admin_title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['gm_adminmenu_title'].": ".$user->lang['gm_admin_title_add'],
            'template_path' => $pm->get_data('guildmap', 'template_path'),
            'template_file' => 'admin/add.html',
            'display'       => true)
        );
    }
}
?>
