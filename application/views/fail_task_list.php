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
            <form action="/task/failitems" method="get">
            <table class="table" style="margin-bottom: 10px;">
                <tbody>  
                
                	<tr>
                        <td style="width:90px;text-align:right;height34px;line-height:34px;">项目名称：</td>
                        <td><input type="text" class="form-control" placeholder="请输入名称" name="name"
	                        <?php         
	                        	if(isset($name))
	                        	{
	                        		echo "value=".$name;
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
                                <option value="0" <?php if($state==='0'):?> selected <?php endif;?>>完成</option>
                                <option value="1" <?php if($state==='1'):?> selected <?php endif;?>>失败</option>
                                <option value="2" <?php if($state==='2'):?> selected <?php endif;?>>彻底失败</option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">RTX：</td>
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
                        <td style="width:20%;">URL</td>
                        <td style="width:8%;">状态</td>
                        <td style="width:5%;">失败总数</td>
                        <td style="width:5%;">失败原因</td>
                        <td style="width:10%;">处理时间</td>
                        <td style="width:5%;">回调函数</td>
                        <td style="width:10%;">项目名称</td>
                        <td style="width:5%;">责任人</td>
                        <td style="width:5%;">操作</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($failTaskList)):?>
                <?php foreach($failTaskList as $key=>$val) :?>
                    <tr>
                        <td><?php echo $val["task_id"];?></td>
                        <td style="overflow:hidden;text-overflow:ellipsis;">
                            <div class="url"><a href="<?php echo $val["url"]?>" target="_blank"><?php echo $val["url"]?></a></div>
                        </td>
                        <?php if($val["state"]==0):?>
                        	<td><span class="label label-primary" id="<?php echo "state-".$val["task_id"];?>">完成</span></td>                       	
                        <?php elseif($val["state"]==1):?>
                        	<td><span class="label label-warning" id="<?php echo "state-".$val["task_id"];?>">失败</span></td>
                        <?php else:?>
                        	<td><span class="label label-danger" id="<?php echo "state-".$val["task_id"];?>">彻底失败</span></td>
                         <?php endif;?>
                      
                        <td><?php echo $val["total_fail"]?></td>
                        
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
                        
                        <td><?php echo $val["processtime"]?></td>
                        <td><?php echo $val["callback"]?></td>
                        <td><?php echo $val["name"]?></td>
                        <td><?php echo $val["user"]?></td>
                        <td>
                            <a style="cursor:pointer;" onclick="redo(<?php echo $val["task_id"];?>)">重做</a>
                            <a target="_blank" style="cursor:pointer;" href="/task/debug?task_id=<?php echo $val["task_id"];?>">调试</a>
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
                $(id).text('失败');
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
</script>







