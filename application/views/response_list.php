<style>
.main {
    float: left;
    width: 260px;
    min-height: 500px;
    margin: 0 10px;
}
.project_list {
    width: 80%;
    float: left;
}
.project_list .panel {
    min-height: 650px;
}
thead tr td {
    font-weight: bold;
}

.project_list .url {
    width: 300px;
    overflow: hidden;
    text-overflow: ellipsis;
}

</style>
<div class="main">
    <div class="panel panel-default">
        <div class="panel-body">
            <div style="font-size:12pt;margin-bottom:5px;">检索选项</div>
            <form id="form_info" action="/response/items" method="get">
            <table class="table" style="margin-bottom: 10px;">
                <tbody>
                               
                 	<tr style="display: none">
                 			<td><input type="text" name="project_id"
                 			<?php         
	                        	if(isset($project_id))
	                        	{
	                        		echo "value=".$project_id;
	                        	}
	                        	else
	                        	{
	                        		echo "value=''";
	                        	}	
                        	?>                   			
                 			></td>
                 	</tr>                   
                    <tr>
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">网址：</td>
                        <td><input type="text" class="form-control" placeholder="请输入网址" name="url"
                        	<?php         
	                        	if(isset($url))
	                        	{
	                        		echo "value=".$url;
	                        	}
	                        	else
	                        	{
	                        		echo "value=''";
	                        	}	
                        	?>   
                        
                        ></td>
                    </tr>
                    
                    <tr>
                        <td style="width:100px;text-align:right;height34px;line-height:34px;">快豹网址：</td>
                        <td><input type="text" class="form-control" placeholder="请输入网址" name="iurl"
                        	<?php         
	                        	if(isset($iurl))
	                        	{
	                        		echo "value=".$iurl;
	                        	}
	                        	else
	                        	{
	                        		echo "value=''";
	                        	}	
                        	?>                           
                        ></td>
                    </tr>

                    <tr>
                        <td style="width:100px;text-align:right;height34px;line-height:34px;">责任人：</td>
                        <td><input type="text" class="form-control" placeholder="请输入责任人" name="user"
                        	<?php         
	                        	if(isset($user))
	                        	{
	                        		echo "value=".$user;
	                        	}
	                        	else
	                        	{
	                        		echo "value=''";
	                        	}	
                        	?>                           
                        ></td>
                    </tr>
                     
                    <tr>
                        <td style="width:100px;text-align:right;height34px;line-height:34px;">状态：</td>
                        <td>
                            <select type="text" class="form-control" name="state">
                                <option value="0" <?php if($state == 0) {echo "selected";} ?>>全部</option>
                                <option value="1" <?php if($state == 1) {echo "selected";} ?>>已发布</option>
                                <option value="2" <?php if($state == 2) {echo "selected";} ?>>已拦截</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td colspan="2" align="center"><button id="btn_submit" type="submit" class="btn btn-info" onclick="OnSubmit()">项目检索</button></td>
                    </tr>
                </tbody>
            </table>
            </form>
        </div>
    </div>
</div>
<div class="project_list">
    <div class="panel panel-default">
        <div class="panel-body">
            <ol class="breadcrumb">
                <li><a href="/project/items">项目列表</a></li>
                <?php if(isset($name)):?>
                <li><a href="#"><?php echo $name?></a></li>
                <?php endif;?>
            </ol>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td >#</td>
                        <td >项目id</td>
                        <td style="width:200px;" >URL</td>                       
                        <td >抓取时间</td>
                        <td >发表时间</td>
                        <td >标题</td>
                        <td >来源</td>
                        <td >图片数</td>
						<td >视频数</td>
						<td >责任人</td>
                        <td style="width:100px;">操作</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($responseList)):?>
                <?php foreach($responseList as $key=>$val) :?>
                    <tr>
                        <td style="width:100px";><?php echo $val["id"];?>&nbsp
                            <span id="reviewed_<?php echo $val["id"];?>" style="float:right;margin-right:5px; <?php if(empty($val["review_time"])) echo "visibility:hidden";?>" class="glyphicon glyphicon-ok" ></span>
                        </td>
                        <td>
                        	<a href="/project/items?project_id=<?php echo $val["project_id"];?>" ><?php echo $val["project_id"];?></a>                              
                        </td>
                        <td>
                            <a href="<?php echo $val["url"]?>" title="<?php echo $val["url"]?>" target="_blank">原始文章</a>
                            <a href="http://kuaibao.qq.com/s/<?php echo $val["iurl"]?>00" title="http://kuaibao.qq.com/s/<?php echo $val["iurl"]?>00"
                             target="_blank" <?php if(empty($val["iurl"])) echo "style='display:none'"?> onclick="Review(<?php echo $val["id"];?>)">快豹文章</a>
                            <a href="/segment/onlinesegment?url=<?php echo urlencode("http://kuaibao.qq.com/s/".$val["iurl"]."00"); ?>" 
                             target="_blank" <?php if(empty($val["iurl"])) echo "style='display:none'"?>>分词</a>
                        </td>                      
                        <td><?php echo $val["create_time"]?></td>
                        <td><?php echo $val["pubtime"]?></td>
                        <td><div class="url"><?php echo $val["title"]?></div></td>
                        <td><?php echo $val["source"]?></td>
                        <td><?php echo $val["count"]?></td>
                        <td><?php if($val["video_count"]) echo $val["video_count"];else echo 0; ?></td>
                        <td><?php echo $val["user"]?></td>
                        <td>
                            <a href="/response/detail?response_id=<?php echo $val["id"];?>" target="_blank">详情</a>
                            <a target="_blank" href="http://inews.webdev.com/cmsDelNewsGeneral?id=<?php echo $val["iurl"]?>00" 
                             <?php if(empty($val["iurl"])) echo "style='display:none'"?> onclick="Cancel()">撤回</a>
                        </td>
                    </tr>                   
                 <?php endforeach;?>
                 <?php else:?>                     
                    <tr style="">
                        <td colspan="10" style="background-color:white;line-height:40px;height:40px;text-align:center;">
                            结果未找到
                        </td>
                    </tr>
               <?php endif;?> 
                </tbody>
            </table>
            <?php $this->load->view("incs/page")?>
        </div>
    </div>
</div>
<div style="clear:both;height:20px;"></div>

<script type="text/javascript">

function Review(id)
{
    $.ajax({
        type: 'POST',
        url: '/response/ReviewItem',
        dataType: 'json',
        data: {
            'id': id,
        },
        success: function(data) {
            var ele = $('#reviewed_'+id);
            ele.css("visibility","visible");
        },
        error: function(e) {
        }
    });
    
}

function OnSubmit()
{
    $("#btn_submit").attr({ disabled: "disabled" });
    $("#form_info").submit();
}

function Cancel()
{
    alert("撤回成功！");
}
</script>
