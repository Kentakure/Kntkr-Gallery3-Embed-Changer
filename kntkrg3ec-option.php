<?php
//--------------------------------------------------------------------------
//
//  プラグインにオプションを追加する
//
//--------------------------------------------------------------------------
// 管理メニューのアクションフック
add_action('admin_menu', 'kntkrg3ec_option');
  
// アクションフックのコールバッック関数
function kntkrg3ec_option () {
    // 設定メニュー下にサブメニューを追加:
    add_options_page('Kntkr Gallery3 Embed Changer Setting', 'Kntkr Gallery3 Embed Changer', 'manage_options', 'kntkr-gallery3-embed-changer', 'kntkrg3ec_setting');
}
//--------------------------------------------------------------------------
//
//  プラグイン設定変更画面を構築する
//
//--------------------------------------------------------------------------
function kntkrg3ec_setting () {
// 設定変更画面の編集ここから
?>
    <div class="wrap">
        <h2>Kntkr Gallery3 Embed Changer Setting</h2>
        <form method="post" action="options.php">
            <?php wp_nonce_field('update-options'); ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">Gallery3設置先<br></th>
                    <td><input type="text" required placeholder="example.com/gallery3" name="kntkrg3ec_siteindex" value="<?php echo get_option('kntkrg3ec_siteindex'); ?>" /></td>
                    <td>Gallery3設置先のURLを指定する。http://は不要。ただし末尾にはスラッシュ（/）を入れないこと！</td>
                </tr>
            </table>
            <input type="hidden" name="action" value="update" />
            <input type="hidden" name="page_options" value="kntkrg3ec_siteindex" />
            <p class="submit">
                <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </form>
    </div>
<?php } //設定変更画面の編集ここまで ?>
