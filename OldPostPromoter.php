<?php
/*
Plugin Name: Powerfull Blog Post Promoter (by BM)
Plugin URI: http://www.blogform.co.cc/wordpress-plugins/powerfull-blog-post-promoter/
Description: Randomly choose an old post and reset the publication date to now.  The effect is to promote older posts by moving them back onto the front page and into the rss feed.  This plugin should only be used with data agnostic permalinks (permalink structures not containing dates). <a href="options-general.php?page=BM_PBPP_admin.php">Configuration options are here.</a>  "You down with PBPP?  Yeah you know me!" 
Version: 2.0.8
Author: BLOGFORM
Author URI: http://www.blogform.co.cc/
Donate: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=chetan1990%40hotmail%2ecom&lc=US&item_name=Blogform&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donateCC_LG%2egif%3aNonHosted
License: GNU GPL
*/
/*  Copyright 2008-2009  BLOGFORM (email : kevin@blogtrafficexchange.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/
require_once('BM_PBPP_admin.php');
require_once('BM_PBPP_core.php');
if (!class_exists('xmlrpcmsg')) {
	require_once('lib/xmlrpc.inc');
}		

define ('BM_RT_API_POST_STATUS', 'http://twitter.com/statuses/update.json');

define ('BM_PBPP_XMLRPC_URI', 'bteservice.com'); 
define ('BM_PBPP_XMLRPC', 'bte.php'); 

define ('BM_PBPP_1_HOUR', 60*60); 
define ('BM_PBPP_4_HOURS', 4*BM_PBPP_1_HOUR); 
define ('BM_PBPP_6_HOURS', 6*BM_PBPP_1_HOUR); 
define ('BM_PBPP_12_HOURS', 12*BM_PBPP_1_HOUR); 
define ('BM_PBPP_24_HOURS', 24*BM_PBPP_1_HOUR); 
define ('BM_PBPP_48_HOURS', 48*BM_PBPP_1_HOUR); 
define ('BM_PBPP_72_HOURS', 72*BM_PBPP_1_HOUR); 
define ('BM_PBPP_168_HOURS', 168*BM_PBPP_1_HOUR); 
define ('BM_PBPP_INTERVAL', BM_PBPP_12_HOURS); 
define ('BM_PBPP_INTERVAL_SLOP', BM_PBPP_4_HOURS); 
define ('BM_PBPP_AGE_LIMIT', 120); // 120 days
define ('BM_PBPP_OMIT_CATS', ""); 

register_activation_hook(__FILE__, 'bte_opp_activate');
register_deactivation_hook(__FILE__, 'bte_opp_deactivate');
add_action('init', 'bte_opp_old_post_promoter');
add_action('admin_menu', 'bte_opp_options_setup');
add_action('admin_head', 'bte_opp_head_admin');
add_filter('the_content', 'bte_opp_the_content');
add_filter('plugin_action_links', 'bte_opp_plugin_action_links', 10, 2);

function bte_opp_plugin_action_links($links, $file) {
	$plugin_file = basename(__FILE__);
	if (basename($file) == $plugin_file) {
		$settings_link = '<a href="options-general.php?page=BM_PBPP_admin.php">'.__('Settings', 'RelatedTweets').'</a>';
		array_unshift($links, $settings_link);
	}
	return $links;
}


function bte_opp_deactivate() {
	delete_option('bte_opp_give_credit');
}

function bte_opp_activate() {
	add_option('bte_opp_interval',BM_PBPP_INTERVAL);
	add_option('bte_opp_interval_slop',BM_PBPP_INTERVAL_SLOP);
	add_option('bte_opp_age_limit',BM_PBPP_AGE_LIMIT);
	add_option('bte_opp_omit_cats',BM_PBPP_OMIT_CATS);
	add_option('bte_opp_show_original_pubdate',1);	
	add_option('bte_opp_pos',0);	
	add_option('bte_opp_give_credit',1);	
	add_option('bte_opp_at_top',0);	
}
?>