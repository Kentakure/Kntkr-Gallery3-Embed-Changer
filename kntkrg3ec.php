<?php
/*
Plugin Name: Kntkr Gallery3 Embed Changer
Plugin URI: https://github.com/Kentakure/Kntkr-Gallery3-Embed-Changer
Description: 指定したGallery3のURLを自動的に画像に差し替える。設定画面付き。
Version: 1.0
Author: KENTAKURE
Author URI: http://kentakure.net
License: KENTAKURE
*/
/*編集ここから*/
include("kntkrg3ec-option.php");
require_once("kntkrg3ec-code.php");
//--------------------------------------------------------------------------
//
//  プラグインページに設定画面のリンクを表示
//
//--------------------------------------------------------------------------
function kntkrg3ec_add_settings_link( $links, $file ) {
	if ( plugin_basename(__FILE__) == $file && function_exists( 'admin_url' ) ) {
		$settings_link = '<a href="' . admin_url( 'options-general.php?page=kntkr-gallery3-embed-changer' ) . '">' . __( 'Settings' ) . '</a>';
		array_unshift( $links, $settings_link ); // before other links
	}
	return $links;
}
add_filter( 'plugin_action_links', 'kntkrg3ec_add_settings_link', 10, 2 );
//--------------------------------------------------------------------------
//
//  プラグイン削除の際に行うオプションの削除
//
//--------------------------------------------------------------------------
if ( function_exists('register_uninstall_hook') ) {
    register_uninstall_hook(__FILE__, 'uninstall_hook_kntkrg3ec');
}
function uninstall_hook_kntkrg3ec () {
    delete_option('kntkrg3ec_siteindex');
}
/*編集ここまで*/
?>
