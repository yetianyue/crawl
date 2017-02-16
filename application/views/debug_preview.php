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

<div>
<font size=4 style="color:red;">标题：</font>
    <?php echo $title?>
</div>
<hr>

<div>
<font size=4 style="color:red;">内容：</font>
    <?php echo $content?>
</div>
<hr>

<div>
<font size=4 style="color:red;">发表时间：</font>
    <?php echo $pubtime?>
</div>
<hr>

<div>
<font size=4 style="color:red;">来源：</font>
    <?php echo $source?>
</div>
<hr>
<?php 
if(!empty($videos))
{
    echo '<div>';
    echo '<font size=4 style="color:red;">视频地址：</font>';
    echo $videos;
    echo '</div><hr>';
}
?>
