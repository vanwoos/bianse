<?php
$filename=isset($_REQUEST['filename'])?$_REQUEST['filename']:'';
$filename=basename($filename);
$grayMax1=isset($_REQUEST['grayMax'])?$_REQUEST['grayMax']:'';
$grayMin1=isset($_REQUEST['grayMin'])?$_REQUEST['grayMin']:'';
if($filename=='' || !file_exists('./cache/'.$filename))
{
	echo '<p>It seems that the file is not exist</p>';
	exit(0);
}
$imgname='./cache/'.$filename;
//$imgname='./disp6.png';
$im=imagecreatefrompng($imgname);
//imagealphablending($im,false);
//imagesavealpha($im,true);
$imgX=imagesx($im);
$imgY=imagesy($im);
//imagefilter($im,IMG_FILTER_GRAYSCALE);
$grayMax=0;$grayMin=255;
/*for($i=0;$i<$imgX;++$i)
{
	for($j=0;$j<$imgY;++$j)
	{
		$color_index=imagecolorat($im,$i,$j);
		$color_tran=imagecolorsforindex($im,$color_index);
		$r=$color_tran['red'];
		$g=$color_tran['green'];
		$b=$color_tran['blue'];
		$gray=intval(($r+$g+$b)/3);
		if($gray>$grayMax) $grayMax=$gray;
		if($gray<$grayMin) $grayMin=$gray;*/
		/*$tmp=generateGradientColor($gray);
		imagecolorset($im,$color_index,$tmp[0],$tmp[1],$tmp[2],1);
		$color=imagecolorallocate($im,$tmp[0],$tmp[1],$tmp[2]);
		//$color=imagecolorallocate($im,$gray,$gray,$gray);
		imagesetpixel($im,$i,$j,$color);
		//echo "{$gray} {$r} {$g} {$b}</br >";*/
	/*}
}*/

//180,30较好
$grayMax=$grayMax1;
$grayMin=$grayMin1;
$graySpan=255/($grayMax-$grayMin);//灰度跨度

for($i=0;$i<$imgX;++$i)
{
	for($j=0;$j<$imgY;++$j)
	{
		$color_index=imagecolorat($im,$i,$j);
		$color_tran=imagecolorsforindex($im,$color_index);
		$r=$color_tran['red'];
		$g=$color_tran['green'];
		$b=$color_tran['blue'];
		$gray=intval(($r+$g+$b)/3);
		$gray=intval(($gray-$grayMin)*$graySpan);//跨度拉伸
		$gray=($gray<0)?0:$gray;
		$gray=($gray>255)?255:$gray;
		$tmp=generateGradientColor($gray);
		//imagecolorset($im,$color_index,$tmp[0],$tmp[1],$tmp[2]);
		//imagecolorset($im,$color_index,255,255,0);
		$color=imagecolorallocate($im,$tmp[0],$tmp[1],$tmp[2]);
		//$color=imagecolorallocatealpha($im,0,255,0,75);
		//$color=imagecolorallocate($im,$gray,$gray,$gray);
		imagesetpixel($im,$i,$j,$color);
		//echo "{$gray} {$r} {$g} {$b}</br >";
	}
}
header("Content-Type:image/png");
imagepng($im);
imagedestroy($im);

function generateGradientColor($x)
{
	if($x>=0 && $x<=64)
	{
		$r=0;$g=intval($x*255/64);$b=255;
	}
	else if($x>64 && $x<=128)
	{
		$r=0;$g=255;$b=255-intval(($x-64)*255/64);
	}
	else if($x>128 && $x<=192)
	{
		$r=intval(($x-128)*255/64);$g=255;$b=0;
	}
	else{
		$r=255;$g=255-intval(($x-192)*255/64);$b=0;
	}
    return array($r,$g,$b);
}
?>