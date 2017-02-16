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
            <form action="/dataclean/result" method="get">
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
                        <td style="width:60px;text-align:right;height34px;line-height:34px;">openid</td>
                        <td><input type="text" class="form-control" placeholder="请输入openid" name="openid"
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
                <?php if(isset($name)):?>
                <li><a href="#"><?php echo $name?></a></li>
                <?php endif;?>
            </ol>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td style="width:5%;">项目id</td>
                        <td style="width:8%;">创建时间</td>
                        <td style="width:20%;">openid</td>
                        <td style="width:20%;">状态</td>
                        <td style="width:10%;">请求的数据</td>
                        <td style="width:10%;">处理后的数据</td>
                        <td style="width:25%;white-space:nowrap;">URL</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($responseList)):?>
                <?php foreach($responseList as $key=>$val) :?>
                    <tr>
                        <td><?php echo $val["project_id"];?></td>
                        <td style="overflow:hidden;text-overflow:ellipsis;">
                            <div class="url"><a href="<?php echo $val["url"]?>" target="_blank"><?php echo $val["create_time"]?></a></div>
                        </td>
                       
                        <td><?php echo $val["openid"]?></td>
                        <td>
                        <?php
                        if($val["status"]==0)
                        {
                            echo "成功"; 
                        }
                        elseif($val["status"]==1)
                        {
                            echo "无模板"; 
                        }
                        elseif($val["status"]==2)
                        {
                            echo "模板异常";
                        }
                        elseif($val["status"]==3)
                        {
                            echo "参数错误";
                        }

                        
                        ?>
                        </td>
                        <td>
                            <a href="/dataclean/detail?type=before&response_id=<?php echo $val["id"];?>" target="_blank">请求的数据</a>
                        </td>
                        <td>
                            <a href="/dataclean/detail?type=after&response_id=<?php echo $val["id"];?>" target="_blank">处理后的数据</a>
                        </td>
                        <td>
                        <?php echo substr($val["url"],0,40)."..."?>
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









