<?php
function kntkr_gallery3_embed_changer($the_content){
//Gallery3を設置したディレクトリのURL
$option_siteindex = get_option('kntkrg3ec_siteindex');
$resizes = 'http://'.$option_siteindex.'/var/resizes/';
$thumbnails = 'http://'.$option_siteindex.'/var/thumbs/';
//URLの正規表現文字列化
$sitepattern = preg_quote($option_siteindex,'/');
$resizespattern = preg_quote($resizes,'/').'([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)';
$thumbnailspattern = preg_quote($thumbnails,'/').'([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)';
$searchpattern = '(https?|ftp)\:\/\/'.$sitepattern.'\/(?!photos|var\/albums|var\/resizes|var\/thumbs)([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)';
//本文中から指定したドメインが検出された場合のみ下記のスクリプトが発動。
if(preg_match('{'.$sitepattern.'}',$the_content)){
	//特定のAタグの解除
	$convert1 = preg_replace('{<a(.*)?href\=\"(https?|ftp)\:\/\/'.$sitepattern.'\/(?!photos|var)([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)\"([^<>]*)?>(.*)?<\/a>}','$5',$the_content);
	$convert2 = preg_replace('{<a(.*)?href\=\"(https?|ftp)\:\/\/'.$sitepattern.'\/var\/thumbs\/([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)\"([^<>]*)?>(.*)?<\/a>}','$5',$convert1);
	//IMGタグの整形。Class属性等が記述されている場合は継承。
	$convert3 = preg_replace('{<img(.*)?src\=\"(https?|ftp)\:\/\/'.$sitepattern.'\/var\/(albums|resizes|thumbs)\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)\.([^?"<>]*)(?:\?[^"<>]*)?\"([^<>]*)?>}', '<img$1src="$2://'.$option_siteindex.'/var/resizes/$4.$5"$6>', $convert2);
	$convert4 = preg_replace('{<a(.*)?href\=\"'.$sitepattern.'\/var\/([-_.!~*\'()a-zA-Z0-9;\/:@&=+$,%#]+)\"([^<>]*)?><img(.*)?><\/a>}','<div style="text-align:center;"><a$1href="'.$option_siteindex.'/var/$2"$3 rel="nofollow">$4</a></div><br>',$convert3);
	//拡張子が付いていないURLの変換。
	$convert5 = preg_replace('{'.$searchpattern.'}','<div style="text-align:center;"><a href="'.$resizes.'$2" rel="nofollow"><img src="'.$resizes.'$2"></a></div>',$convert4);
	//大きさをリサイズに統一。
	$convert6 = preg_replace('{<img(.*)?src\=\"'.$thumbnailspattern.'\"([^<>]*)?>}','<img$1src="'.$resizes.'$3"$4>',$convert5);
	$convert7 = preg_replace('{<a(.*)?href\=\"'.$thumbnailspattern.'\"([^<>]*)?><img(.*)?><\/a>}','<div style="text-align:center;"><a$1href="'.$resizes.'$2"$3 rel="nofollow">$4</a></div><br>',$convert6);
	//モバイル判別
	if(wp_is_mobile()){
	//スマホでのアクセスの場合には、サムネイル画像を代用。
	$convert8 = preg_replace('{<img(.*)?src\=\"'.$resizespattern.'(?:\?[^"<>]*)?\"([^<>]*)?>}','<img$1src="'.$thumbnails.'$2"$3>',$convert7);
	//代用したサムネイル画像のリンク先を新規ウィンドウで開くに設定変更。
	$convert9 = preg_replace('{<a(.*)?href\=\"'.$resizespattern.'\"([^<>]*)?><img(.*)?><\/a>}','<a$1href="'.$resizes.'$2"$3target="_blank"><img$4></a>',$convert8);
	$the_content = $convert9;
	}else{
	//PC表示の場合にはリサイズのままで申し送る。
	$the_content = $convert7;
	}
}
return $the_content;
}
add_filter('the_content','kntkr_gallery3_embed_changer', 1);
?>
