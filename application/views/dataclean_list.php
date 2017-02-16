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
            <form action="/dataclean/items" method="get">
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
                    </tr>
                    <tr >
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">openid：</td>
                        <td><input type="text" class="form-control" placeholder="请输入名称" name="openid" 
                        <?php
	                        	if(isset($openid))
	                        	{
	                        		echo "value=".$openid;
	                        	}
	                        	else
	                        	{
	                        		echo "value=''";
	                        	}	
                        	?>
                        	        
                        ></td>
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
                <li><a href="/dataclean/items">项目列表</a></li>
            </ol>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td style="width:6%;">项目id</td>
                        <td style="width:17%;">项目名称</td>
                        <td style="width:15%;">标签</td>
                        <td style="width:18%;">openid</td>
                        <td style="width:12%;">创建时间</td>
                        <td style="width:12%;">更新时间</td>
                        <td style="width:10%;">责任人</td>
                        <td style="width:10%;">操作</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($projectList)):?>
                <?php foreach($projectList as $key=>$val): ?>
                    <tr id="<?php echo $val["project_id"]?>">
                        <td><?php echo $val["project_id"]?></td>
                        <td>
                            <a href="/task/items?project_id=<?php echo $val["project_id"];?>"><?php echo $val["name"]?></a>
                        </td>
                        <td>
                        <?php foreach($val["tag"] as $k=>$v):?>
                            <span class="label label-primary"><?php echo $v?></span>
                        <?php endforeach;?>
                        </td>
                        <td>

                        <?php echo $val["openid"]?>
                        </td>
                        
                         	<td>
                            <?php echo date("Y-m-d H:i:s",$val["create_time"])?>
                            </td>
                        <td><?php echo date("Y-m-d H:i:s",$val["update_time"])?></td>
                        <td><?php echo $val["user"]?></td>
                        <td>
                        	 <a href="/dataclean/result?project_id=<?php echo $val["project_id"];?>" >结果</a>
                            <a href="Alter?id=<?php echo $val["project_id"]?>" >修改</a>
                            <a href="#" onclick="del(<?php echo $val["project_id"]?>)">删除</a>
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

function del(project_id)
{
	var msg = confirm("您确定要删除吗，删除后将无法恢复？");
	if(msg==false)
	{
		return false;
	}
	$.ajax({
        type: 'GET',
        url: '/dataclean/del',
        data: {
            'project_id':project_id
        },
        success: function(data) {
            if(data==0)
            {
            	var id = "#"+project_id;
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
                        	
