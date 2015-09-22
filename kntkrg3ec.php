<?php
/*
Plugin Name: Kntkr Gallery3 Embed Changer
Plugin URI: http://kentakure.net
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
