<?php
/**
 * Displays the guild map
 *
 * @category Plugins
 * @package GuildMap
 * @copyright (c) 2006, Garrett Hunter <loganfive@blacktower.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @author Garrett Hunter <loganfive@blacktower.com>
 * @version $Id: index.php,v 1.4 2007/02/19 03:56:37 garrett Exp $
 */

// EQdkp required files/vars
define('EQDKP_INC', true);
define('PLUGIN', 'guildmap');

$eqdkp_root_path = './../../';

include_once($eqdkp_root_path . 'common.php');
include_once('config_gm.php');

$user->check_auth('u_member_list');

$guildmap = $pm->get_plugin('guildmap');

if ( !$pm->check(PLUGIN_INSTALLED, 'guildmap') )
{
    message_die('The Guild Map plugin is not installed.');
}

/**
 * Get only members with locations
 */
$sql = 'SELECT member_name, member_latitude, member_longitude
          FROM ' . MEMBERS_TABLE .'
         WHERE member_latitude IS NOT NULL
           AND member_longitude IS NOT NULL
      ORDER BY member_name';
$member_results = $db->query($sql);

$count = 1;
while ( $member_row = $db->fetch_record($member_results) ) {

    $tpl->assign_block_vars('member_row', array(
            'MEMBER'    => $member_row['member_name'],
            'LATITUDE'  => $member_row['member_latitude'],
            'LONGITUDE' => $member_row['member_longitude'],
            'COUNT'     => $count++
        ));

}
$db->free_result($member_results);

/**
 * Get the Google Maps API key
 */
$sql = "SELECT config_value
          FROM ".CONFIG_TABLE."
         WHERE config_name = '".$guildmap->_config_name."'";
$api_key = $db->query_first($sql);

/**
 * Regsitered users may add / update their own locations
 */
$is_logged_in = false;
if ( ($user->data['user_id'] != ANONYMOUS) && (!isset($_GET['key'])) ) {
	$is_logged_in = true;
}

$tpl->assign_vars(array(

    'ONLOAD'            => " onload=\"load()\" onunload=\"GUnload()\"",
    'API_KEY'           => $api_key,
	'L_MISSING_API_KEY'	=> $user->lang['gm_missing_api_key'],
	'S_IS_LOGGED_IN'	=> $is_logged_in,
	
	'L_ADD_UPDATE_LOCATION'	=> $user->lang['gm_usermenu_add_update'],
	'U_ADD_UPDATE_LOCATION' => 'manage_gm.php' . $SID . '&amp;mode=add',

));

// Extra CSS Styles
$eqdkp->extra_css = $guildmap_css;

$eqdkp->set_vars(array(
    'page_title'    => sprintf($user->lang['title_prefix'], $eqdkp->config['guildtag'], $eqdkp->config['dkp_name']).': '.$user->lang['gm_user_title_guildmap'],
    'template_path' => $pm->get_data('guildmap', 'template_path'),
    'template_file' => 'guildmap.html',
    'display'       => true)
);
?>