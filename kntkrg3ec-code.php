<?php
function kntkr_gallery3_embed_changer($the_content){
//Gallery3を設置したディレクトリのURL
$siteindex = get_option('kntkrg3ec_siteindex');
$resizes = 'http://'.$siteindex.'/var/resizes/';
$thumbnails = 'http://'.$siteindex.'/var/thumbs/';
//URLの正規表現文字列化
$sitepattern = preg_quote($siteindex,'/');
$resizespattern = preg_quote($resizes,'/');
$thumbnailspattern = preg_quote($thumbnails,'/');
$searchpattern = '(?<!\")http\:\/\/'.$sitepattern.'\/(?!var)([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)';
//文字列にURLが混じっている場合のみ下のスクリプト発動
if(preg_match('{'.$searchpattern.'}',$the_content)){
	$content1 = preg_replace('/<a(?:.*)?(?:href=\")(http\:\/\/'.$sitepattern.'\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)(?:\")(.*)?>(.*)?<\/a>/','$1',$the_content);
	$content2 = preg_replace('/(?:<img src=\")?(http\:\/\/'.$sitepattern.'\/)var\/(resizes|thumbnails)\/([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)\.(.*)(\")?(.*)(?:<\/a>)?/', '$1$3', $content1);
	preg_match_all('{'.$searchpattern.'}',$content2,$pattarn);
		foreach ($pattarn[1] as $key=>$val){
			if(@exif_imagetype($thumbnails.$val)==FALSE){
				$replace[] = '"Error, image not found."';
				$content3 = str_replace($pattarn[0],$replace,$content2);
			}else{
				switch (exif_imagetype($thumbnails.$val)){
					case 'IMAGETYPE_GIF':
						$imgext = '.gif';//gif拡張子を付加
						break;
					case 'IMAGETYPE_JPEG':
						if(@exif_imagetype($thumbnails.$val.'.jpeg')==IMAGETYPE_JPEG){
							$imgext = '.jpeg';//jpeg拡張子を付加
						}else{
							$imgext = '.jpg';//jpg拡張子に変更
						}
						break;
					case 'IMAGETYPE_PNG':
						$imgext = '.png';//png拡張子を付加
						break;
					case 'IMAGETYPE_BMP':
						$imgext = '.bmp';//bmp拡張子を付加
						break;
					default:
					break;
					}
				$replace[] = $thumbnails.$val.$imgext;
				$content3 = str_replace($pattarn[0],$replace,$content2);
			}
		}
	$content3 = str_replace($pattarn[0],$replace,$content2);
		if(wp_is_mobile()){
			$content4 = preg_replace('{'.$thumbnailspattern.'([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)'.'}','<div style="text-align:center;"><a href="'.$resizes.'$1'.'" rel="nofollow" target="_blank"><img src="'.'$0'.'"></a></div><br>',$content3);
		$the_content = $content4;
		}else{
			$content5 = preg_replace('{'.$thumbnailspattern.'([-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+)'.'}','<div style="text-align:center;"><a href="'.$resizes.'$1'.'" rel="nofollow"><img src="'.$resizes.'$1'.'"></a></div><br>',$content3);
		$the_content = $content5;
		}
	}
return $the_content;
}
add_filter('the_content','kntkr_gallery3_embed_changer', 1);
?>
