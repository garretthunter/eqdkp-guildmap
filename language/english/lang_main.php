<?php
/**
 * Language localizations
 *
 * @category Plugins
 * @package GuildMap
 * @copyright (c) 2006, Garrett Hunter <loganfive@blacktower.com>
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License
 *
 * @author Garrett Hunter <loganfive@blacktower.com>
 * @version $Id: lang_main.php,v 1.4 2007/02/19 03:56:37 garrett Exp $
 */

if ( !defined('EQDKP_INC') )
{
     die('Do not access this file directly.');
}

// Plugin name
$lang['guildmap'] = "guildmap";

// permissions

// Page Titles & Headings
$lang['gm_user_title_guildmap']   = "Guild Map";
$lang['gm_admin_title_configure']   = "Configure Guild Map";
$lang['gm_admin_title_add']         = "Add Location";

// Messages
$lang['gm_missing_api_key']			= "First configure your Google Maps API Key in the <a href=\"manage_gm.php?s=&amp;mode=config\">Administrator panel</a>";
$lang['gm_admin_add_api_key_success']= "%s is now registered as your Google Maps API Key.";

// User Menus
$lang['gm_usermenu_map_home']       = "Guild Map";
$lang['gm_usermenu_add_update']     = "Add / Update Your Location";

// Admin Menus
$lang['gm_adminmenu_title']     = "Guild Map";
$lang['gm_adminmenu_add']       = "Add Location";
$lang['gm_adminmenu_configure'] = "Configure";

// Config form labels
$lang['gm_api_key']         = "Google Maps API Key";
$lang['gm_api_key_help']    = "Sign up for the <a href=\"http://www.google.com/apis/maps/signup.html\" target=\"_blank\">Google Maps API</a>";

// Add Location labels
$lang['gm_add_member_message']  = "Select a member...";
$lang['gm_latitude']            = "Latitude";
$lang['gm_longitude']           = "Longitude";

// Error messages
$lang['fv_required_gm_api_key'] = "A Goole Maps API Key is required.";

?>