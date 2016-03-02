<?php
header("content-type:text/html;charset=UTF-8");
	$grayMax1=isset($_REQUEST['grayMax'])?$_REQUEST['grayMax']:'180';
	$grayMin1=isset($_REQUEST['grayMin'])?$_REQUEST['grayMin']:'30';
if(isset($_FILES['file']))
{
	$name=$_FILES['file']['name'];
	$name2=md5($name).time();
	$errors=array(
	1=>'文件大小超过了upload_max_filesize的限制',
	2=>'文件大小超过了HTML表单中MAX_FILE_SIZE 选项指定的值',
	3=>'文件只有部分被上传',
	4=>'没有文件被上传',
	6=>'找不到临时文件夹',
	7=>'文件写入失败'
	);
	if($_FILES['file']['error']!=0)
	{
		echo '<p>上传失败，'.$errors[$_FILES['file']['error']].'</p>';
	}
	else if($name=='' || mb_strlen($name,'utf-8')>50)
	{
		echo '<p>上传失败，文件名为空，或者文件名过长</p>';
	}
	else{
		move_uploaded_file($_FILES['file']['tmp_name'],'./cache/'.$name2);
		$fsize=changeFileSize($_FILES['file']['size']);
		//$name=mb_convert_encoding($name,'utf-8',CODING);
		echo "<p>上传成功</br>文件名称：{$name}<br />文件大小：{$fsize}，grayMax：{$grayMax1}，grayMin：{$grayMin1}</p>";
		echo "<p><img src=\"./bianse.php?filename={$name2}&grayMax={$grayMax1}&grayMin={$grayMin1}\" alt=\"图片正在处理中，请稍后 . . .\" /></p>";
		echo "<p><a href=\"./bianse.php?filename={$name2}&grayMax={$grayMax1}&grayMin={$grayMin1}\">若图片长时间未显示，可以点击此链接，或许会看到出错信息</a></p>";
	}
}

echo <<<HTML
<div style="background-color : #d2ff8d;padding:5px;">
<form action="./" method="post" enctype="multipart/form-data">
<div style="color:green;">
<p>上传的文件务必为png格式，且不能包含透明信息</p>
<p>将png图片去掉透明信息的方法：在windows中右键选择编辑，然后再保存为png格式的图片即可</p>
</div>
<p>选择图片：
<input type="file" name="file" /></p>
<p>grayMax:<input name="grayMax" size="5" value="{$grayMax1}" /></p>
<p>grayMin:<input name="grayMin" size="5" value="{$grayMin1}" /></p>
<p><input type="submit" value="上传" /></p>
</form>
</div>
HTML;

function changeFileSize($fileSize) {
	if ($fileSize >= 1073741824) {
		$fileSize = round($fileSize / 1073741824, 2) . 'GB';
	} elseif ($fileSize >= 1048576) {
		$fileSize = round($fileSize / 1048576, 2) . 'MB';
	} elseif ($fileSize >= 1024) {
		$fileSize = round($fileSize / 1024, 2) . 'KB';
	} else {
		$fileSize = $fileSize . 'Byte';
	}
	return $fileSize;
}
?>