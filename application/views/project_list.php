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
</style>
<div class="main">
    <div class="panel panel-default">
        <div class="panel-body">
            <div style="font-size:12pt;margin-bottom:5px;">检索选项</div>
            <form action="/project/items" method="get">
            <table class="table" style="margin-bottom: 10px;">
                <tbody>
                    <tr>
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">名称：</td>
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
                        <tr>
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">域名：</td>
                        <td><input type="text" class="form-control" placeholder="请输入域名" name="domain"
	                        <?php         
	                        	if(isset($domain))
	                        	{
	                        		echo "value=".$domain;
	                        	}
	                        	else
	                        	{
	                        		echo "value=''";
	                        	}	
                        	?>      
                        ></td>
                    </tr>
                    <tr >
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">等级：</td>
                        <td>
                            <select class="form-control" name="priority">
                            	<option value="" <?php if(empty($priority)) echo "selected";?>>全部</option>
                                <option value="2" <?php if($priority==='2') echo "selected";?>>普通</option>
                                <option value="1" <?php if($priority==='1') echo "selected";?>>重要</option>
                                <option value="0" <?php if($priority==='0') echo "selected";?>>紧急</option>
                            </select>
                        </td>
                    </tr>
               <!--  
                    <tr style="display: none">
                        <td style="width:60px;text-align:right;height34px;line-height:34px; ">标签：</td>
                        <td>
                            <input type="text" class="form-control" placeholder="请输入标签" style="margin-bottom:5px;" name="tag">
                            <span class="label label-primary" style="cursor:pointer;">新闻后台</span>
                            <span class="label label-primary" style="cursor:pointer;">数据挖掘</span>
                        </td>
                    </tr>
              -->
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
            </ol>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td style="width:8%;">#</td>
                        <td style="width:10%;">项目名称</td>
                        <td style="width:10%;">标签</td>
                        <td style="width:10%;">等待/成功/失败</td>
                        <td style="width:13%;">添加时间</td>
                        <td style="width:13%;">修改时间</td>
                        <td style="width:5%;">等级</td>
                        <td style="width:10%;">责任人</td>
                        <td style="width:10%;">操作</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($projectList)):?>
                <?php foreach($projectList as $key=>$val): ?>
                    <tr id="<?php echo $val["project_id"]?>">
                        <td><?php echo $val["project_id"]?>
                        <span id="span_<?php echo $val["project_id"]?>" style=" <?php if($val["review_state"] != 0) echo "display:none"?>" class="label label-success">已检查</span>
                        <button id="btn_<?php echo $val["project_id"]?>" style="width:52px;height:20px;font-size:6px;padding:0;<?php if($val["review_state"] != 1) echo"display:none"?>" type="button" onclick="Review(<?php echo $val["project_id"]?>);" class="btn btn-warning">已发文</button>
                        <span style=" <?php if($val["review_state"] != 2) echo "display:none"?>" class="label label-default">未发文</span>
                        </td>
                        <td>
                            <a href="/task/items?project_id=<?php echo $val["project_id"];?>"><?php echo $val["name"]?></a>
                        </td>
                        <td>
                        <?php foreach($val["tag"] as $k=>$v):?>
                            <span class="label label-primary"><?php echo $v?></span>
                        <?php endforeach;?>
                        </td>
                        <td>
                            <a href="/task/items?project_id=<?php echo $val["project_id"];?>&state=0"><span class="label label-default"><?php echo $val["state"][0]?></span></a>
                            <a href="/response/items?project_id=<?php echo $val["project_id"];?>&pub_time=1"><span class="label label-success"><?php echo $val["state"][1]?></span></a>
                            <a href="/response/items?project_id=<?php echo $val["project_id"];?>&pub_time=-1"><span class="label label-danger"><?php echo $val["state"][2]?></span></a>
                        </td>
                        
                        <td><?php echo $val["create_time"]?></td>
                         <td><?php echo $val["modify_time"]?></td>
                        <?php if($val["priority"]==0):?>
                         	<td><span class="label label-danger">紧急</span></td>
                         <?php elseif($val["priority"]==1):?>
                         	<td><span class="label label-warning">重要</span></td>
                         <?php else:?>
                         	<td><span class="label label-info">普通</span></td>
                         <?php endif?>
                        <td><?php echo $val["user"]?></td>
                        <td>
                        	 <a target="_blank" href="/response/items?state=1&project_id=<?php echo $val["project_id"];?>" onclick="Checking(<?php echo $val["project_id"]?>)">结果</a>
                            <a href="Alter?id=<?php echo $val["project_id"]?>" >修改</a>
                            <a href="#" onclick="del(<?php echo $val["project_id"]?>)">删除</a>
                         <?php if($val["state"][3]>0):?>  
                            <a id="<?php echo "ss_".$val["project_id"]?>" href="#" onclick="start(<?php echo $val["project_id"]?>)">开始</a>
                         <?php else:?>
                         	 <a id="<?php echo "ss_".$val["project_id"]?>" href="#" onclick="stop(<?php echo $val["project_id"]?>)">暂停</a>
                         <?php endif?>
                        </td>
                    </tr>
                <?php endforeach;?>
                <?php else:?>
                    <tr style="display:none;">
                        <td colspan="8" style="background-color:white;line-height:40px;height:40px;text-align:center;">
                            结果未找到
                        </td>
                    </tr>
             <?php endif;?>    
                </tbody>
            </table> 
            <?php $this->load->view("incs/page");?>  
        </div>
    </div>
    
</div>
<div style="clear:both;height:20px;"></div>

<script type="text/javascript">

function Review(id)
{
	$.ajax({
        type: 'POST',
        url: '/project/ReviewProject',
        data: {
            'project_id':id
        },
        success: function(data) {
            var btn = $('#btn_'+id);
            btn.css("display","none"); 
            var span = $('#span_'+id);
            span.css("display",""); 
        },
        error: function(e) {
        }
    });
}

function Checking(id)
{
    var btn = $('#btn_'+id);
    btn.addClass("btn btn-danger");
    btn.html("正在检查");

}

function del(project_id)
{
	var msg = confirm("您确定要删除吗，删除后将无法恢复？");
	if(msg==false)
	{
		return false;
	}
	$.ajax({
        type: 'GET',
        url: '/project/del',
        data: {
            'project_id':project_id
        },
        success: function(data) {
            if(data==0)
            {
            	var id = "#"+project_id;
            	$(id).html("");
            }
            else if(data==-2)
            {
            	window.alert("删除失败,没有权限");
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

function stop(project_id)
{
	$.ajax({
        type: 'GET',
        url: '/project/startStop',
        data: {
            'project_id':project_id,
            "state":1           
        },
        success: function(data) {
            if(data==0)
            {
            	var id = "#ss_"+project_id;
            	$(id).attr("onclick", "start("+project_id+")");
            	$(id).text("开启")
            }
            else
            {
            	window.alert("暂停失败");
            }
        	
                        
        },
        error: function(e) {
            window.alert("暂停失败");
        }
    });
}

function start(project_id)
{
	$.ajax({
        type: 'GET',
        url: '/project/startStop',
        data: {
            'project_id':project_id,
            "state":2           
        },
        success: function(data) {
            if(data==0)
            {
            	var id = "#ss_"+project_id;
            	$(id).attr("onclick", "stop("+project_id+")");
            	$(id).text("暂停")
            }
            else
            {
            	window.alert("开启失败");
            }
        	
                        
        },
        error: function(e) {
            window.alert("开启失败");
        }
    });
}



</script>
                        	
