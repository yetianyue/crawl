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
            <form action="/task/items" method="get">
            <table class="table" style="margin-bottom: 10px;">
                <tbody>
                <!--                  
                	<tr>
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">级别：</td>
                        <td>
                            <select class="form-control" name="priority">
                                <option value="2">普通</option>
                                <option value="1">重要</option>
                                <option value="0">紧急</option>
                            </select>
                        </td>
                    </tr>
                 -->
                 
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
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">状态：</td>
                        <td>
                            <select class="form-control" name="state">
                            	<option value="" <?php if(empty($state)):?> selected <?php endif;?>>全部</option>
                                <option value="0" <?php if($state==='0'):?> selected <?php endif;?>>调度中</option>
                                <option value="1" <?php if($state==='1'):?> selected <?php endif;?>>排队中</option>
                                <option value="2" <?php if($state==='2'):?> selected <?php endif;?>>执行中</option>
                                <option value="3" <?php if($state==='3'):?> selected <?php endif;?>>已完成</option>
                                <option value="5" <?php if($state==='5'):?> selected <?php endif;?>>已失败</option>
                            </select>
                        </td>
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
                        <td colspan="2" align="center"><button type="submit" class="btn btn-info">项目检索</button></td>
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
                        <td style="width:5%;">#</td>
                        <td style="width:5%;">项目id</td>
                        <td style="width:20%;">URL</td>
                        <td style="width:5%;">状态</td>
                        <td style="width:10%;">失败原因</td>
                        <!--<td style="width:5%;">级别</td>-->
                        <!--<td style="width:15%;">调度时间</td>-->
                        <td style="width:10%;">处理时间</td>
                        <td style="width:4%;">层级</td>
                        <td style="width:8%;">有效期</td>
                        <td style="width:8%;">回调</td>
                        <td style="width:10%;">操作</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($taskList)):?>
                <?php foreach($taskList as $key=>$val) :?>
                    <tr id="<?php echo $val["task_id"];?>">
                        <td><?php echo $val["task_id"];?></td>
                        <td>
                        	<a href="/project/items?project_id=<?php echo $val["project_id"];?>" ><?php echo $val["project_id"];?></a>                                      
                        </td>
                        
                        <td style="overflow:hidden;text-overflow:ellipsis;">
                            <div class="url"><a href="<?php echo $val["url"]?>" target="_blank"><?php echo $val["url"]?></a></div>
                        </td>
                        <?php if($val["state"]==0):?>
                        	<td><span class="label label-primary" id="<?php echo "state-".$val["task_id"];?>">调度中</span></td>                       	
                        <?php elseif($val["state"]==1):?>
                        	<td><span class="label label-warning" id="<?php echo "state-".$val["task_id"];?>">排队中</span></td>
                        <?php elseif($val["state"]==2):?>
                        	<td><span class="label label-info" id="<?php echo "state-".$val["task_id"];?>">执行中</span></td>
                         <?php elseif($val["state"]==3):?>
                         	<td><span class="label label-success" id="<?php echo "state-".$val["task_id"];?>">已完成</span></td>
                         <?php elseif($val["state"]==4):?>
                         	<td><span class="label label-default" id="<?php echo "state-".$val["task_id"];?>">已停止</span></td>
                         <?php else:?>
                         	<td><span class="label label-danger" id="<?php echo "state-".$val["task_id"];?>">已失败</span></td>
                         <?php endif;?>
                         
                         <?php if($val["errno"]==0):?>
                        	<td id="<?php echo "errno-".$val["task_id"];?>">--</td>                       	
                        <?php elseif($val["errno"]==1):?>
                        	<td id="<?php echo "errno-".$val["task_id"];?>">网络问题</td>
                        <?php elseif($val["errno"]==2):?>
                        	<td id="<?php echo "errno-".$val["task_id"];?>">进程挂了</td>
                         <?php elseif($val["errno"]==3):?>
                         	<td id="<?php echo "errno-".$val["task_id"];?>">任务错误</td>
                         <?php elseif($val["errno"]==4):?>
                         	<td id="<?php echo "errno-".$val["task_id"];?>">项目错误</td>
                         <?php elseif($val["errno"]==5):?>
                         	<td id="<?php echo "errno-".$val["task_id"];?>">内容错误</td>                          
                         <?php else:?>
                         	<td id="<?php echo "errno-".$val["task_id"];?>">其他错误</td>
                         <?php endif;?>
                         
                        <!-- 
                         <?php if($val["priority"]==0):?>
                         	<td><span class="label label-danger">紧急</span></td>
                         <?php elseif($val["priority"]==1):?>
                         	<td><span class="label label-warning">重要</span></td>
                         <?php else:?>
                         	<td><span class="label label-info">普通</span></td>
                         <?php endif?>
                        
                        <td><?php echo $val["scheduledtime"]?></td>
			-->
                        <td><?php echo $val["processtime"]?></td>
                        <td><?php echo $val["layer"]?></td>
                        <td><?php echo $val["age"]?></td>
                        <td><?php echo $val["callback"]?></td>
                        <td>
                            <a style="cursor:pointer;" onclick="redo(<?php echo $val["task_id"];?>)">重做</a>
                            <a target="_blank" style="cursor:pointer;" href="/task/debug?task_id=<?php echo $val["task_id"];?>">调试</a>
                            <a href="#" onclick="del(<?php echo $val["task_id"]?>)">删除</a>
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

function redo(task_id)
{
	$.ajax({
        type: 'POST',
        url: '/task/redo',
        data: {
            'task_id':task_id
        },
        success: function(data) {
            if(data==0)
            {
            	var id = "#state-"+task_id;
            	$(id).attr("class","label label-primary");
                $(id).text('调度中');
                var id = "#errno-"+task_id;
                $(id).text('--');
            }
            else
            {
            	window.alert("重置失败");
            }
        	
                        
        },
        error: function(e) {
            window.alert("重置失败");
        }
    });
}


function del(task_id)
{
	
	$.ajax({
        type: 'GET',
        url: '/task/del',
        data: {
            'task_id':task_id
        },
        success: function(data) {
            if(data==0)
            {
            	var id = "#"+task_id;
            	$(id).html("");
            }
            else
            {
            	window.alert("删除失败");
            }
        	
                        
        },
        error: function(e) {
            window.alert("删除失败");
        }
    });
}

</script>








