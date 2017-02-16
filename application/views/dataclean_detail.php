
<html>

<head>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta name="mqq-bottom-ad" content="no">
<link rel="dns-prefetch" href="//mat1.gtimg.com">
<link rel="dns-prefetch" href="//imgcache.gtimg.cn">
<meta name="format-detection" content="telephone=no, email=no">
<meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
<title>快豹</title>

<link href="http://mat1.gtimg.com/www/cssn/newsapp/cyshare/cyshare_20150619d.css" type="text/css" rel="stylesheet">

</head>
<body>

<div id="content" class="main fontSize2">

<hr>
<div>
<font size=4 style="color:red;">链接：</font>
	<?php 
		if(!empty($url))
		{
			echo "<a href='{$url}' target='_blank'>{$url}</a>";
		}
	?>
</div>
<hr>

<?php 
		if(!empty($content['title']))
		{
			echo '<p class="title" align="left">';
			echo '<font size=4 style="color:red;">标题:</font>';
			echo $content['title'];
			echo '</p><hr>';
		}
	?>

	<?php 
		if(!empty($content['source']))
		{
			echo '<div class="src"> ';
			echo '<font size=4 style="color:red;">来源:</font>';
			echo $content['source'];
			echo '</div><hr>';
		}
	?>
	
	
	<?php 
		if(!empty($content['pubtime']))
		{
			echo '<div>';
			echo '<font size=4 style="color:red;">发表时间：</font>';
			echo $content['pubtime'];
			echo '</div><hr>';
		}
	?>
	
	<?php 
		if(!empty($videos))
		{
			echo '<div>';
			echo '<font size=4 style="color:red;">视频地址：</font>';
			echo $videos;
			echo '</div><hr>';
		}
	?>

<div>
<font size=4 style="color:red;">内容：</font>
	<?php 
		if(!empty($content['content']))
		{
			echo $content['content'];
		}
	?>
</div>

</div>

<script language="javascript" src="http://mat1.gtimg.com/www/js/newsapp/cyshare/cyshare_20150619.js" type="text/javascript" charset="utf-8"></script>

</body>
</html>
