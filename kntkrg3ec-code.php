<?php
function kntkr_gallery3_embed_changer($the_content){
//Gallery3を設置したディレクトリのURL
$option_siteindex = get_option('kntkrg3ec_siteindex');
//画像が格納されているディレクトリ
$resizes = $option_siteindex.'/var/resizes/';
$thumbs = $option_siteindex.'/var/thumbs/';
//URLの正規表現文字列化
$sitepattern = '(https?):\/\/'.preg_quote($option_siteindex,'/');
$varpattern = '(https?):\/\/'.preg_quote($option_siteindex,'/').'\/var\/(albums|resizes|thumbs)\/([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)(\.[a-zA-Z0-9]*)(?:\?.*)?';
$searchpattern = '(https?):\/\/'.preg_quote($option_siteindex,'/').'\/(?!photos\/|var\/albums\/|var\/resizes\/|var\/thumbs\/)([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)';
//整形したDIVタグに付加したい属性
$adddiv = 'class="kntkrg3ec" style="text-align:center;"';
//整形したAタグに付加したい属性（PC表示時）
$addapc = '';
//整形したAタグに付加したい属性（スマホ表示時）
$addasp = 'target="_blank"';
//Aタグで開きたい画像のサイズ(resizes or albums)
$size = 'resizes';
//整形したIMGタグに付加したい属性
$addimg = 'alt="Kntkr-Gallery3-Embed-Changer resized image"';
//特定のAタグの解除
$convert1 = preg_replace('{<a(.*)?href="'.$searchpattern.'"([^<>]*)?>(.*)?<\/a>}','$5',$the_content);
//IMGタグの整形（Class属性等が記述されている場合は継承させる）
$convert2 = preg_replace('{<img(.*)?src="'.$varpattern.'"([^<>]*)?>}','<img$1src="$2://'.$resizes.'$4$5"$6>', $convert1);
//拡張子が付いていないURL（アドレスバーからのコピペ）の変換。
if(preg_match('{'.$searchpattern.'}',$convert2)){
preg_match_all('{'.$searchpattern.'}',$convert2,$pattarn);
	$protocol = $pattarn[1][0].'://';
	foreach ($pattarn[2] as $key=>$val){
		if(@exif_imagetype($protocol.$resizes.$val)==FALSE){
			$replace[] = '"Error, image not found."';
			$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}elseif(@exif_imagetype($protocol.$resizes.$val.'.gif')==IMAGETYPE_GIF){
					$imgext = '.gif';//gif拡張子を付加
					$replace[] = '<img src="'.$protocol.$resizes.$val.$imgext.'">';
					$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}elseif(@exif_imagetype($protocol.$resizes.$val.'.jpeg')==IMAGETYPE_JPEG){
					$imgext = '.jpeg';//jpeg拡張子を付加
					$replace[] = '<img src="'.$protocol.$resizes.$val.$imgext.'">';
					$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}elseif(@exif_imagetype($protocol.$resizes.$val.'.jpg')==IMAGETYPE_JPEG){
					$imgext = '.jpg';//jpg拡張子を付加
					$replace[] = '<img src="'.$protocol.$resizes.$val.$imgext.'">';
					$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}elseif(@exif_imagetype($protocol.$resizes.$val.'.png')==IMAGETYPE_PNG){
					$imgext = '.png';//png拡張子を付加
					$replace[] = '<img src="'.$protocol.$resizes.$val.$imgext.'">';
					$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}elseif(@exif_imagetype($protocol.$resizes.$val.'bmp')==IMAGETYPE_GIF){
					$imgext = '.bmp';//bmp拡張子を付加
					$replace[] = '<img src="'.$protocol.$resizes.$val.$imgext.'">';
					$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}else{
			$replace[] = '"Sorry, this image is not replaced..."';
			$convert3 = str_replace($pattarn[0],$replace,$convert2);
		}
	}
}else{
	//拡張子が付いていないURLが一つもない場合はそのまま申し送る。
	$convert3 = $convert2;
}
//モバイル判別
if(wp_is_mobile()){
	//スマホ表示時はサムネイル画像を代用してリンク先を新規ウィンドウで開くに設定変更。
	$convert4 = preg_replace('{<img(.*)?src="'.$varpattern.'"([^<>]*)?>}','<div '.$adddiv.'><a href="$2://'.$option_siteindex.'/var/'.$size.'/$4$5" '.$addasp.'><img src="$2://'.$thumbs.'$4$5" '.$addimg.'$6></a></div>', $convert3);
	$convert5 = preg_replace('{<a([^<>]*)?><div([^<>]*)?><a([^<>]*)?><img([^<>]*)?><\/a><\/div><\/a>}','<div$2><a$1 '.$addasp.'><img$4></a></div>',$convert4);
	$the_content = $convert5;
}else{
	//PC表示時
	$convert6 = preg_replace('{<img(.*)?src="'.$varpattern.'"([^<>]*)?>}','<div '.$adddiv.'><a href="$2://'.$option_siteindex.'/var/'.$size.'/$4$5" '.$addapc.'><img src="$2://'.$resizes.'$4$5" '.$addimg.'$6></a></div>', $convert3);
	$convert7 = preg_replace('{<a([^<>]*)?><div([^<>]*)?><a([^<>]*)?><img([^<>]*)?><\/a><\/div><\/a>}','<div$2><a$1 '.$addapc.'><img$4></a></div>',$convert6);
	$the_content = $convert7;
}
return $the_content;
}
add_filter('the_content','kntkr_gallery3_embed_changer', 1);
?>
