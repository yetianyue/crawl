<div class="panel panel-default" style="width:750px;float:left;margin-left:10px;">
	
	<div class="panel-body">
		<form method="post" id="search_form" action="/segment/match">	
		<div class="input-group has-feedback" style="margin-bottom:10px;width:600px" >
	       	<span class="input-group-addon">快报URL</span>
	        <input type="text" class="form-control"  placeholder="请输入链接" onpaste="" id="input_url" value="<?php echo $url ?>">
	        <span class="form-control-feedback" style="margin-right:50px;width:100px;overflow:hidden"></span>
	        <span class="input-group-btn"><button class="btn btn-default" onclick="javascript:SearchSubmit();" type="button">搜索</button></span>	        
        </div>
        <div id="msg"></div>
        </form>
        
		<div style="margin-bottom:20px; " >
			<?php if(!empty($cmsid)):?>
				<div style="padding: 20px 30px;background-color: #F3F3F3;">
      				<div class="title" style="font-size: 18px;">
      					<a target="_blank" href="http://kuaibao.qq.com/s/<?php echo $cmsid?>">
      					
      					<?php 
      						if(!empty($title))
      						{
      							echo $title;
      						}
      						else
      						{
      							echo "文章已删";
      						}      					     					     					
      					?>      					      				
      					</a>
      					
      					<div style="font-size: small;">     						     				 	
	      				 	<?php 
	      				 		if(!empty($cmsid))
	      				 		{
	      				 			echo "<font color=\"#006621\">http://kuaibao.qq.com/s/{$cmsid}</font>";
	      				 		}
	      				 	?>
	      				 	<?php 
      				 		if(!empty($pubtime))
      				 		{
      				 			echo "&nbsp<font color=\"#8A8A8A\">{$src}&nbsp{$pubtime}</font>";
      				 		}
      				 		?>
      				 		&nbsp<a href="/segment/onlinesegment?url=http://kuaibao.qq.com/s/<?php echo $cmsid?>" target="_blank">在线分词</a>
      				 		
      					</div>
      				</div>
      				
      				<div class="content" style="font-size: small;">
      					<?php 
      						if(!empty($content))
      						{
      							echo $content;
      						}
      						else
      						{
      							echo "";
      						}      					     					     					
      					?>
      				</div>      				     					     				       							      				     						      					      				  		      					
      			</div>	  						
			<?php endif;?>
		
      			<?php foreach ($data as $key=>$val): ?>
      			<div style="padding: 20px 30px;">
      				<div class="title" style="font-size: 18px;">
      					<a target="_blank" href="http://kuaibao.qq.com/s/<?php echo $val["cmsid"]?>">
      					
      					<?php 
      						if(!empty($val["title"]))
      						{
      							echo $val["title"];
      						}
      						else
      						{
      							echo "文章已删";
      						}      					     					     					
      					?>      					      				
      					</a>
      					<div style="font-size: small;">     						     				 	
	      				 	<?php 
	      				 		if(!empty($val["cmsid"]))
	      				 		{
	      				 			echo "<font color=\"#006621\">http://kuaibao.qq.com/s/{$val["cmsid"]}</font>";
	      				 		}
	      				 	?>
	      				 	<?php 
      				 		if(!empty($val["pubtime"]))
      				 		{
      				 			echo "&nbsp<font color=\"#8A8A8A\">{$val["src"]}&nbsp{$val["pubtime"]}</font>";
      				 		}
      				 		?>
      					</div>
      				</div>
      				
      				<div class="content" style="font-size: small;">
      					<?php 
      						if(!empty($val["content"]))
      						{
      							echo $val["content"];
      						}
      						else
      						{
      							echo "";
      						}      					     					     					
      					?>
      				</div>      				     					
      				 
      				 <div style="font-size: small;">
      				 	<a target="blank" href="/segment/match?cmsid=<?php echo $val["cmsid"]?>">相似文章数：<?php echo $val["simNum"]?></a>
      				 	<?php 
      				 		if(!empty($val["similarity"]))
      				 		{
      				 			if($val["similarity"]>80)
      				 			{
      				 				echo "<b><font color=\"red\">&nbsp相似度：{$val["similarity"]}</font></b>";
      				 			}
      				 			else 
      				 			{
      				 				echo "<font >&nbsp相似度：{$val["similarity"]}</font>";
      				 			}
      				 			
      				 		}
      				 	?>
      				 	
      				 	<?php 
      				 		if(!empty($val["policy"]))
      				 		{
      				 			echo "&nbsp方法：{$val["policy"]}";
      				 		}
      				 	?>
      				 	&nbsp<a href="/segment/onlinesegment?url=http://kuaibao.qq.com/s/<?php echo $val["cmsid"]?>" target="_blank">在线分词</a>
      				 </div>     				      				     						      					      				  		      					
      			</div>	      			
      			<?php endforeach;?>
      		
      	
      		<?php $this->load->view("incs/page");?>
      	</div>        		        			
	</div>
</div>


<script type="text/javascript">

function SearchSubmit()
{
	var url = $.trim($("#input_url").val());
	if(url=='')
	{
		$('#input_url').attr('style','border:#ff0000 1px solid');			
		return;
	}

	if(url.substr(0,24) != 'http://kuaibao.qq.com/s/')
	{
		$("#msg").html("输入的链接只能为快报链接");
		return;
	}

	var cmsid = url.substr(24);
	$("#search_form").attr("action",'/segment/match?cmsid='+cmsid);
	$("#search_form").submit();	
}

</script>