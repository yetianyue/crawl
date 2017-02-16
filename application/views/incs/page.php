<?php   
 $pageInfo = config_item("pageInfo");
 if(!empty($pageInfo)):
?>
<div style="text-align: center;">
<nav>
<ul class="pagination" style="margin:0px;">
	<?php if($pageInfo["start"]>1):?>
		<li><a href="<?php echo $pageInfo["url"]."1"?>"><span>首页</span></a></li>
	<?php endif;?>
	<?php for($i=$pageInfo["start"];$i<=$pageInfo["end"];$i++):?>
		<?php if($i==$pageInfo["curPage"]):?>
			<li><a href="#" style="color:black"><strong><?php echo $i?></strong></a></li>
		<?php else:?>
			<li><a href="<?php echo $pageInfo["url"].$i?>"><?php echo $i?></a></li>
		<?php endif;?>
	<?php endfor;?>
	<?php if($pageInfo["end"]<$pageInfo["totalPage"]):?>
		<li><a href="<?php echo $pageInfo["url"].$pageInfo["next"]?>"><span>下8页</span></a></li>
	<?php endif;?>
	<li style="height:35px;line-height:35px;"><font size=2>&nbsp <?php echo "页数：{$pageInfo["curPage"]}/{$pageInfo["totalPage"]}&nbsp总数：{$pageInfo["num"]}"?></font></li>
</ul>
</nav>
</div>

<?php endif;?>