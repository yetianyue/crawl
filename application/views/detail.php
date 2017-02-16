
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
<p class="title" align="left">
<font size=4 style="color:red;">标题:</font>
<?php 
	if(!empty($title))
	{
		echo $title;
	}

?>
</p>

<div class="src"> <span style="margin-left:7px;">
<font size=4 style="color:red;">来源:</font>
<?php 
	if(!empty($source))
	{
		echo $source;
	}

?>
</span></div>
<hr>
<div>
<font size=4 style="color:red;">发表时间：</font>
	<?php 
		if(!empty($pubtime))
		{
			echo $pubtime;
		}
	?>
</div>
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
		if(!empty($videos))
		{
			$videos = implode(',',$videos);
			echo '<div>';
			echo '<font size=4 style="color:red;">视频地址：</font>';
			echo $videos;
			echo '</div><hr>';
		}
	?>

<div>
<font size=4 style="color:red;">内容：</font>
	<?php 
		if(!empty($content))
		{
			echo $content;
		}
	?>
</div>

<hr>
<div>
<font size=4 style="color:red;">抓取视频：</font>
	<?php 
		if(!empty($videos))
		{
			foreach($videos as $key=>$val)
			{
				echo "<video src='{$val}'>";
			}
		}
	?>
</div>

<hr>
<div>
<font size=4 style="color:red;">抓取图片：</font>
	<?php 
		if(!empty($imgs))
		{
			foreach($imgs as $key=>$val)
			{
				echo "<img src='{$val}'>";
			}
		}
	?>
</div>


</div>

<script language="javascript" src="http://mat1.gtimg.com/www/js/newsapp/cyshare/cyshare_20150619.js" type="text/javascript" charset="utf-8"></script>

</body>
</html>