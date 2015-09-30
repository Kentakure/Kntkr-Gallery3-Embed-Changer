<?php
function kntkr_gallery3_embed_changer($the_content){
//Gallery3を設置したディレクトリのURL
$option_siteindex = get_option('kntkrg3ec_siteindex');
$resizes = 'http://'.$option_siteindex.'/var/resizes/';
$thumbs = 'http://'.$option_siteindex.'/var/thumbs/';
//URLの正規表現文字列化
$sitepattern = '(https?|ftp)\:\/\/'.preg_quote($option_siteindex,'/');
//本文中から指定したドメインが検出された場合のみ下記のスクリプトが発動。
if(preg_match('{'.$sitepattern.'}',$the_content)){
	//特定のAタグの解除
	$convert1 = preg_replace('{<a(.*)?href\=\"'.$sitepattern.'\/(?!photos|var)([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)\"([^<>]*)?>(.*)?<\/a>}','$5',$the_content);
	//IMGタグの整形。Class属性等が記述されている場合は継承。
	$convert2 = preg_replace('{(<a.*>)?<img(.*)?src\=\"'.$sitepattern.'\/var\/(albums|resizes|thumbs)\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)\.([^?"<>]*)(?:\?[^"<>]*)?\"([^<>]*)?>(<\/a>)?}', '<div class="kntkrg3ec" style="text-align:center;">$1<img$2src="$3://'.$option_siteindex.'/var/$4/$5.$6"$7>$8</div>', $convert1);
	//拡張子が付いていないURL（アドレスバーからのコピペ）の変換。
	$convert3 = preg_replace('{'.$sitepattern.'\/(?!photos|var\/albums|var\/resizes|var\/thumbs)([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)}','<div class="kntkrg3ec" style="text-align:center;"><a href="'.$resizes.'$2" rel="nofollow"><img src="'.$resizes.'$2"></a></div>',$convert2);
	//大きさをリサイズに統一。
	$convert4 = preg_replace('{<img(.*)?src\=\"'.$sitepattern.'\/var\/(?:albums|thumbs)\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)\"([^<>]*)?>}','<img$1src="'.$resizes.'$3"$4>',$convert3);
	$convert5 = preg_replace('{<a(.*)?href\=\"'.$sitepattern.'\/var\/(?:albums|thumbs)\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)\"([^<>]*)?><img(.*)?><\/a>}','<div class="kntkrg3ec" style="text-align:center;"><a$1href="'.$resizes.'$3"$4 rel="nofollow"><img$5></a></div><br>',$convert4);
	//モバイル判別
	if(wp_is_mobile()){
	//スマホでのアクセスの場合には、サムネイル画像を代用。
	$convert6 = preg_replace('{<img(.*)?src\=\"'.$sitepattern.'\/var\/resizes\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)(?:\?[^"<>]*)?\"([^<>]*)?>}','<img$1src="'.$thumbs.'$3"$4>',$convert5);
	//代用したサムネイル画像のリンク先を新規ウィンドウで開くに設定変更。
	$convert7 = preg_replace('{<a(.*)?href\=\"'.$sitepattern.'\/var\/resizes\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)\"([^<>]*)?><img(.*)?><\/a>}','<a$1href="'.$resizes.'$3"$4target="_blank"><img$5></a>',$convert6);
	$the_content = $convert7;
	}else{
	//PC表示の場合にはリサイズのままで申し送る。
	$the_content = $convert5;
	}
}
return $the_content;
}
add_filter('the_content','kntkr_gallery3_embed_changer', 1);
?>
