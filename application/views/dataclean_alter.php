<style>
.toolbar {
    width: 500px;
    top: 100px;
    left: 910px;
    position: fixed;
    padding: 10px 50px;
}
.main {
    padding: 0 10px;
}
.ResultList {
    height: 400px;
    overflow-y: scroll;
    margin: 0 -9px;
    border-top: 1px dotted #000;
}
.ResultItem {
    height: 40px;
    border-bottom: 1px dotted #ddd;
    padding: 0 10px;
    clear:both;
}
.ResultTitle {
    height: 40px;
    line-height: 40px;
    float:left;
    width: 300px;
    text-overflow: ellipsis;
    overflow: hidden;
}
.ResultAction {
    height: 40px;
    line-height: 40px;
    float:right;
    cursor:pointer;
    text-align:right;
    width:25px;
}
.para_selector {
    width: 150px;
    text-align: center;
}
</style>
<?php

$detail_config = json_decode($detail_config,true);
foreach($detail_config as $key=>$value)
{
$detail_config_count = $key;
}
$detail_config_count++;
?>
<div class="main">
    <div class="configure">
        <div class="panel panel-default" style="width:400px;float:left;">
            <div class="panel-body" style="">
                <div style="font-size:12pt;margin-bottom:5px;">项目基本信息</div>
                <table class="table" style="margin-bottom: 10px;">
                    <tbody>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">项目名称：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_name" style="margin-bottom:0px;">
                                    <input id="project_name" value="<?php echo $name;?>" type="text" class="form-control" placeholder="请输入项目名称">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">openid：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_openid" style="margin-bottom:0px;">
                                    <input id="project_openid" value="<?php   echo $openid;?>" type="text" class="form-control" placeholder="openid">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
						 <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">项目模板：</td>
                            <td>
                                <select id="project_template" class="form-control" onchange="javascript:selectStyle();">
                                    <option value="0" <?php if($template==0) echo "selected = selected"; ?> >微信数据清理模板</option>
									<option value="1" <?php if($template==1) echo "selected = selected"; ?> >cms新闻模板</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">项目标签：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_tag" style="margin-bottom:0px;">
                                    <input id="project_tag" value="<?php echo $tag;?>" type="text" class="form-control" value="" placeholder="请输入项目标签">
                                    <span class="form-control-feedback"></span>
                                </div>
                                <div id="taglist" style="margin-top:5px;">
                                    <span class="label label-primary" style="cursor:pointer;">微信</span>
                                    <span class="label label-primary" style="cursor:pointer;">cms新闻</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">责任人：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_user" style="margin-bottom:0px;">
                                    <input id="project_user" value="<?php echo $user; ?>" type="text" class="form-control" placeholder="请输入责任人">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default" style="width:500px;float:left;margin-left:10px;">
            <div class="panel-body" style="">
                <div style="font-size:12pt;margin-bottom:5px;">配置种子地址</div>
                <table class="table" style="margin-bottom: 10px;">
                    <tbody>
                        <tr>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;" id="seed_input">
                                    <span class="input-group-addon">URL</span>
                                    <input type="text" class="form-control" id="seed_url" placeholder="请输入种子地址" onpaste="" onkeydown="javascript:if(event.keyCode == 13){AddSeeds();}">
                                    <span class="form-control-feedback" style="margin-right:50px;width:100px;overflow:hidden"></span>
                                    <span class="input-group-btn"><button class="btn btn-default" onclick="javascript:AddSeeds();"type="button">添加</button></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="SeedList" style="margin-bottom:0px;height:200px;overflow-y:scroll;border:1px solid #ccc;">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
				<?php 
					if($template ==0 )
					{
						echo ' <div id="content_id" class="panel panel-default" style="clear:left;width:910px;float:left;display:block;">';
					}
					else
					{
						echo '<div id="content_id" class="panel panel-default" style="clear:left;width:910px;float:left;display:none;">';
					}
				?>
            <div class="panel-body" style="">
                <div style="font-size:12pt;margin-bottom:5px;">内容提取配置</div>
                <table class="table" style="margin-bottom: 10px;">
                    <tbody>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">文章正文筛选</td>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;">
                                    <span class="input-group-addon">$</span>
                                    <input id="list_dom" value="<?php echo $list_dom;?>" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="panel panel-default" style="clear:left;width:910px;float:left;">
            <div class="panel-body" style="">
                <div style="float:left;font-size:12pt;margin-bottom:5px;">详情页配置</div>
                <span id='filter_id' style="float:right;cursor: pointer;margin-right: 5px;" class="glyphicon glyphicon-plus" onclick="javascript:AddDataCleanConfigItem('<?php echo $detail_config_count ?>')"> &nbsp;添加过滤关键字</span>
                <table class="table" style="margin-bottom: 10px;" id="detail_table">
                    <tbody>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">段落筛选：</td>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;width:330px;">
                                    <span class="input-group-addon glyphicon glyphicon-align-left" style="top:0px;width:33px;padding:0px 9px 0px 8px;"></span>
                                    <input id="content_start" type="text" class="form-control para_selector" value="<?php if(!empty($detail_contentStart))echo $detail_contentStart; else echo 0?>" placeholder="起始段落索引">
                                    <span class="input-group-addon" style="border-left:0;border-right:0;background-color:white;">至</span>
                                    <input id="content_end" type="text" class="form-control para_selector" value="<?php if(!empty($detail_contentEnd))echo $detail_contentEnd; else echo -1?>" placeholder="结尾段落索引">
                                </div>
                            </td>
                        </tr>



<?php
foreach($detail_config as $key=>$value)
{


    $filterEle = '<tr id=Config_'.$key .'>'.
        '<td style="text-align:right">'.
        '<span style="width:100px;text-align:right;height34px;line-height:34px;">过滤'.$key .'：</span>'.
        '<br>'.
        '<span style="margin-right:30px;margin-top:15px;cursor:pointer;" onclick="javascript:DeleteConfigItem(this);" class="glyphicon glyphicon-trash"></span>'.
        '</td>'.
        '<td>'.
        '<div class="ConfigItem">'.
        '<div class="input-group has-feedback" style="margin-top: 5px;">'.
        '<span class="input-group-addon">$</span>'.
        '<input id="ConfigDom" type="text" class="form-control" placeholder="请输入过滤关键字" value="'.htmlspecialchars($value).'">'.
        '<span class="form-control-feedback"></span>'.
        '</div>'.
        '</div>'.
        '</td>'.
        '</tr>';
    echo $filterEle;
}
?>

                    </tbody>
                </table>
				<?php 
					if($template ==1 )
					{
						echo '<div id="detail_id" style="display:block">';
					}
					else
					{
						echo '<div id="detail_id" style="display:none">';
					}
				?>
				  <table class="table" style="margin-bottom: 10px;" id="detail_table">
				   <tbody >
								 <tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">文章标题：</td>
									<td>
										<div class="input-group has-feedback" style="margin-bottom:0px;">
											<span class="input-group-addon">$</span>
											<input id="detail_title" value="<?php echo $detail_title;?>" type="text" class="form-control" placeholder="请输入筛选路径">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">文章正文：</td>
									<td>
										<div class="input-group has-feedback" style="margin-bottom:0px;">
											<span class="input-group-addon">$</span>
											<input id="detail_content" value="<?php echo $detail_content;?>" type="text" class="form-control" placeholder="请输入筛选路径">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">发表时间：</td>
									<td>
										<div class="input-group has-feedback"  style="margin-bottom:0px;">
											<span class="input-group-addon">$</span>
											<input id="detail_pubtime" value="<?php echo $detail_pubtime;?>" type="text" class="form-control" placeholder="请输入筛选路径">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">文章来源：</td>
									<td>
										<div class="input-group has-feedback">
											<span class="input-group-addon">$</span>
											<input id="detail_src" type="text"   value="<?php echo $detail_src;?>" class="form-control" placeholder="请输入筛选路径">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">视频筛选：</td>
									<td>
										<div class="input-group has-feedback">
											<span class="input-group-addon">$</span>
											<input id="video_find" type="text"   value='<?php echo $video_find;?>' class="form-control" placeholder="请输入筛选路径">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">视频地址值</td>
									<td>
										<div class="input-group has-feedback">
											<span class="input-group-addon">$</span>
											<input id="video_src" type="text"   value="<?php echo $video_src;?>" class="form-control" placeholder="比如 href src value等">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
                    </tbody>
                </table>
				</div>
				
				
				
				
				
            </div>
        </div>
        <div style="height:50px;clear:both;"></div>
    </div>
    <div class="code" style="display:none;">
        <textarea id="editor" class="form-control" style="height:700px;width:910px;"><?php echo $script;?></textarea>
    </div>
</div>
<div class="toolbar">
    <div class="panel panel-default" style="clear:both; border-color:#aaa; margin-bottom:0px;">
        <div class="panel-body" style="padding:5px 10px;">
            <button type="button" onclick="javascript:RunTask();" class="btn btn-success">调试程序</button>
            <button type="button" onclick="javascript:GenerateScript();" class="btn btn-primary">生成代码</button>
            <button type="button" onclick="javascript:SwitchView(this);" class="btn btn-info">查看代码</button>
            <button type="button" onclick="javascript:AlterProject();"style="float:right"class="btn btn-warning">修改项目</button>
        </div>
    </div>
    <div class="panel panel-default" style="clear:both; border-color:#aaa; margin:10px 0px;">
        <div class="panel-body" style="padding:5px 5px;">
            <div id="output" style="padding:5px;height:100px;overflow-y:scroll;border:1px solid #ccc;">
                程序就绪...<br/>
            </div>
        </div>
    </div>
    <div class="panel panel-default" style="clear:both; border-color:#aaa; margin:10px 0px;">
        <div class="panel-heading">运行结果  &nbsp;&nbsp;&nbsp; <a target="_blank" href="/dataclean/debugres">查看调式结果</a></div>
        <div class="panel-body" style="padding:0 10px;">
            <div id="result" class="ResultList">
                
            </div>
        </div>
    </div>
    <div class="panel panel-default">
        <div class="panel-heading">修改记录</div>
        <div class="panel-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <td style="width:25%;">责任人</td>
                        <td style="width:50%;">修改日期</td>
                    </tr>
                </thead>
                <tbody>
                <?php if(!empty($history)):?>
                <?php foreach($history as $key=>$val): ?>
                    <tr>
                        <td><?php echo $val["user"]?></td>
                        <td><?php echo date("Y-m-d H:i:s",$val["modify_time"])?></td>
                    </tr>
                <?php endforeach;?>
                <?php else:?>
                    <tr>
                        <td colspan="8" style="background-color:white;line-height:40px;height:40px;text-align:left;">
                            无修改记录
                        </td>
                    </tr>
                <?php endif;?>    
                </tbody>
            </table> 
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/project.js">
</script>
<script>
var TaskConsole = {
    Log: function(msg) {
        var output = $('#output');
        output.html(output.html()+msg+'<br/>');
        output.scrollTop(output[0].scrollHeight);
    }
};

function RecoverScript(script)
{
    $('#editor').val(script);
    TaskConsole.Log('<span style="font-weight:bold;">恢复脚本成功!</span>');
}

function selectStyle()
{
    var templateV = $('#project_template').val();
    if(templateV==0)
    {
        document.getElementById("content_id").style.display = "block";
        document.getElementById("detail_id").style.display = "none";
		 document.getElementById("filter_id").style.display = "block";
    }
    if(templateV==1)
    {
        document.getElementById("content_id").style.display = "none";
        document.getElementById("detail_id").style.display = "block";
		document.getElementById("filter_id").style.display = "none";
    
    }
}

function RunTask()
{
    var editor = $('#editor');
    $.ajax({
        type: 'POST',
        url: '/debugger/run',
        dataType: 'json',
        data: {
            'code': editor.val(),
            'fetch': '',
            'from' : "dataclean"
        },
        success: function(data) {
            $('#result').html('');
            Process(data);
        },
        error: function(e) {
            TaskConsole.Log('<span style="font-weight:bold;color:red">执行失败: status('+e.status+') </span>');
        }
    });
    TaskConsole.Log('运行中...');
}

function RunSubTask(item)
{
    var editor = $('#editor');
    $.ajax({
        type: 'POST',
        url: '/debugger/run',
        dataType: 'json',
        data: {
            'code': editor.val(),
            'fetch': JSON.stringify(item),
            'from' : "dataclean"
        },
        success: function(data) {
            $('#result').html('');
            Process(data);
        },
        error: function(e) {
            TaskConsole.Log('<span style="font-weight:bold;color:red">执行失败: status('+e.status+') </span>');
        }
    });
    TaskConsole.Log('执行子任务 <b>'+item.url+'</b> ==〉 <b>'+item.callback+'</b> ...');
    $('#result').html('请等待抓取中...');
}

function AddFetchItem(item)
{
    var e = $('<div class="ResultItem">' + 
                    '<div class="ResultTitle"></div>' + 
                    '<div class="ResultAction"><span class="glyphicon glyphicon-circle-arrow-right"></span></div>' + 
                '</div>');
    var len = item.url.toString().length;
    var maxLen = 50;
    if(len <= maxLen)
    {
        e.find(".ResultTitle").html('<a target="_blank" href="'+item.url+'">'+item.url+'</a>');
    }
    else
    {
        var shortUrl = item.url.toString().substr(0, maxLen);
        e.find(".ResultTitle").html('<a target="_blank" href="'+item.url+'">'+shortUrl+'</a>');
    }
    
    var b = e.find(".ResultAction");
    b.data("item", item);
    b.click(function(){
        var item = $(this).data("item");
        RunSubTask(item);
    });
    $('#result').append(e);
}

function AddContentItem(key, value)
{
    if(key == null || value == null)
    {
        return;
    }
    var e = $('<div style="height:40px;line-height:40px;padding:2px;border-bottom:1px #999 dotted;" class="bg-info">' + 
              '<div class="key" style="font-weight:bold;height:40px;line-height:40px;width:60px;text-align:center;overflow:hidden;float:left;"></div>' + 
              '<div class="value" style="height:40px;line-height:40px;border-left:1px #999 dotted;width:300px;float:left;overflow:hidden;font-weight:bold;padding-left:10px;"></div>' + 
              '</div>');
    e.find(".key").text(key);

    if(value.toString().substr(0, 7) == "http://")
        e.find(".value").html('<a target="_blank" href="'+value+'">'+value+'</a>');
    else
        e.find(".value").text(value);

    e.find(".value").attr("title", value);
    $('#result').append(e);
}

function AddResultItem(item)
{
    AddContentItem("url", item.url);
    var obj = $.parseJSON(item.content);
    for(var k in obj)
    {
        AddContentItem(k, obj[k]);
    }
}

function Process(data)
{
    if(data['error'] != '')
    {
        TaskConsole.Log('<span style="font-weight:bold;color:red">执行失败: '+data['error']+'</span>');
        return;
    }
    TaskConsole.Log('<span style="font-weight:bold;">执行成功!</span>');

    var FetchList = data["result"]["FetchList"];
    for(var i=0; i<FetchList.length; ++i)
    {
        AddFetchItem(FetchList[i]);
    }

    var ResultList = data["result"]["ResultList"];
    for(var i=0; i<ResultList.length; ++i)
    {
        AddResultItem(ResultList[i]);
    }

}


function IsEmpty(ele)
{
    if($(ele).val() == '')
    {
        $(ele).parent().addClass("form-group has-error");
        return 1;
    }
    else
    {
        $(ele).parent().removeClass("form-group has-error"); 
        return 0;
    }
}

function AlterProject()
{
    if( -1 == CheckScriptInput())
    {
        return;
    }
    var project_name = $('#project_name');
    var project_user = $('#project_user');
    var project_openid = $('#project_openid');
    var project_tag = $('#project_tag');
    var editor = $('#editor');
    
    if(IsEmpty(project_name)||IsEmpty(project_openid) || IsEmpty(project_tag) || IsEmpty(project_user)){
        return;
    }

    if(''==editor.val())
    {
        TaskConsole.Log('<span style="font-weight:bold;color:red">修改项目失败:未添加脚本</span>');
        return;
    }
    $.ajax({
        type: 'POST',
        url: '/dataclean/updateProject',
        dataType: 'json',
        data: {
            'project_id':<?php echo $project_id?>,
			'project_template': $('#project_template').val(),
            'name': project_name.val(),
            'tag': $('#project_tag').val(),
            'project_openid': project_openid.val(),
            'user': project_user.val(),
            'script': editor.val(),
            'list_url': EncodeUrl(),
            'list_dom': $('#list_dom').val(),
            'detail_contentStart': $('#content_start').val(),
            'detail_contentEnd': $('#content_end').val(),
			'detail_title':$('#detail_title').val(),
			'detail_content':$('#detail_content').val(),
			'detail_pubtime':$('#detail_pubtime').val(),
			'detail_src':$('#detail_src').val(),
			'video_src':$('#video_src').val(),
			'video_find':$('#video_find').val(),
            'detail_config': GetDatacleanConfigItem()
        },
        success: function(data) {
            if(data['error'] != '')
            {
                TaskConsole.Log('<span style="font-weight:bold;color:red">修改项目失败: '+data['error']+'</span>');
            }
            else
            {
                TaskConsole.Log('<span style="font-weight:bold;">修改项目成功!</span>');
            }
        },
        error: function(e) {
            TaskConsole.Log('<span style="font-weight:bold;color:red">修改项目失败: status('+e.status+') </span>');
        }
    });


}

function CheckScriptInput()
{
    var list_dom = $('#list_dom');
    var content_start = $('#content_start');
    var content_end = $('#content_end');
	
	var detail_title = $('#detail_title');
	var detail_content = $('#detail_content');
	var detail_pubtime = $('#detail_pubtime');
	var detail_src = $('#detail_src');

	if(project_template==1)
	{
		if(IsEmpty(detail_title) || IsEmpty(detail_content) || IsEmpty(detail_pubtime) || IsEmpty(detail_src))
		{
			return -1;
		}
	}
   
	if(project_template==0)
	{
		if(IsEmpty(list_dom)  || IsEmpty(content_start) || IsEmpty(content_end))
		{
			return -1;
		}
	}


    if(content_start.val() < 0)
    {
        content_start.parent().addClass("form-group has-error");
        return -1;
    }
    else
    {
        content_start.parent().removeClass("form-group has-error"); 
    }

    if(content_end.val() > 0)
    {
        content_end.parent().addClass("form-group has-error");
        return -1;
    }
    else
    {
        content_end.parent().removeClass("form-group has-error"); 
    }

    if(-1 ==EncodeUrl())
    {
        return -1;
    }
    
    return 0;
}    


function GenerateScript(option)
{
    if( -1 == CheckScriptInput())
    {
        return -1;
    }
    var editor = $('#editor');
    $.ajax({
        type: 'POST',
        url: '/dataclean/GenerateScript',
        dataType: 'json',
        data: {
            'project_template': $('#project_template').val(),
            'list_url': EncodeUrl(),
            'list_dom': $('#list_dom').val(),
            'detail_contentStart': $('#content_start').val(),
            'detail_contentEnd': $('#content_end').val(),
			'detail_title':$('#detail_title').val(),
			'detail_content':$('#detail_content').val(),
			'detail_pubtime':$('#detail_pubtime').val(),
			'detail_src':$('#detail_src').val(),
			'video_src':$('#video_src').val(),
			'video_find':$('#video_find').val(),
            'detail_config': GetDatacleanConfigItem()
        },
        success: function(data) {
            if(data['error'] != '')
            {
                TaskConsole.Log('<span style="font-weight:bold;color:red">脚本生成失败: '+data['error']+'</span>');
                editor.val('');
            }
            else
            {
                TaskConsole.Log('<span style="font-weight:bold;">脚本生成成功!</span>');
                editor.val(data['script']);
                if(option == 'runTask')
                {
                    RunTask();
                }
                else if(option == 'AlterProject')
                {
                    AlterProject();
                }
            }
        },
        error: function(e) {
            TaskConsole.Log('<span style="font-weight:bold;color:red">脚本生成失败: status('+e.status+') </span>');
        }
    });
}

function SwitchView(e)
{
    if($(e).html() == "查看代码")
    {
        $(e).html("隐藏代码");
        $(".code").show();
        $(".configure").hide();
    }
    else
    {
        $(e).html("查看代码");
        $(".code").hide();
        $(".configure").show();
    }
}

function AddSeeds()
{
    var url = $('#seed_url').val();
    var input = $('#seed_input');
    
    if(url == '')
    {
        input.addClass("form-group has-error");
        $('.form-control-feedback', input).html("请输入URL");
        return;
    }
    else
    {
        input.removeClass("form-group has-error");
    }
    
    var urlArray = url.toString().split(" ");
    for(var index in urlArray)
    {
        CheckAndAddUrl(urlArray[index], 3600);
    }

    $('.form-control-feedback', input).html('');
    $('#seed_url').val('');
}

function CheckAndAddUrl(url,age)
{
    if(''==url)
    {
        return;
    }
    if(url.toString().substr(0, 7) != "http://")
    {
        url = "http://"+url;
    }

    if($('.SeedItem a[href="'+url+'"]').length > 0)
    {
        $('#seed_url').val('');
        input.addClass("form-group has-error");
        $('.form-control-feedback', input).html("URL重复");
        return;
    }

    AddOneSeed(url,age);
}

function AddOneSeed(url, age)
{   

    var ele =$('<div class="SeedItem" style="border-bottom:1px dotted #ccc;height:46px;padding:5px 5px;">'+
                    '<div class="SeedUrl" style="float:left;width:250px;height:34px;line-height:34px;overflow:hidden;">'+
                        '<a href="'+ url +'" title="'+ url +'" target="_blank">'+ url+'</a>'+
                    '</div>'+
                    '<div style="float:right;width:50px;margin-right:5px;margin-top:1px;">'+
                        '<button class="btn btn-default btn-sm" onclick="javascript:DeleteSeed(this);" type="button">删除</button>'+
                    '</div>'+
                '</div>');
    $(ele).find('.SeedAge select').val(age);
    var seedlist = $('.SeedList');
    seedlist.append(ele);

}

function EncodeUrl()
{
    var urlArray = new Object(); 
    $('.SeedItem').each(function(index)
    {
        var url = $(this).find('.SeedUrl a').attr('href');
        var age = $(this).find('.SeedAge select').val();
        urlArray[url] = url;
    });
    var urlStr = JSON.stringify(urlArray);
    var input = $('#seed_input');
    if(urlStr == '{}')
    {
        input.addClass("form-group has-error");
        $('.form-control-feedback', input).html("请输入URL");
        return -1;
    }
    else
    {
        input.removeClass("form-group has-error");
    }
    return urlStr;
}

function DecodeUrl()
{
    var urlStr =<?php echo $url;?>; 
    if(-1==urlStr)
    {
        return;
    }
    var urlArray = eval(urlStr);
    for(var url in urlArray){
        CheckAndAddUrl(url, urlArray[url]);
    }
    
}

function DeleteSeed(ele)
{
    $(ele).parent().parent().remove();
}

$(document).ready(function(){
    DecodeUrl();
    $.each($('#taglist .label'), function(index, ele){
        $(ele).click(function(){
            var separator = ', ';
            var tagEle = $('input[id="project_tag"]');
            var tagArray = tagEle.val().toString().split(separator);
            for(var i in tagArray){
                if(tagArray[i] == ele.innerText){
                    return;
                }
            }
            tagEle.val(tagEle.val()+ele.innerText + separator);
        });
    });
});

</script>
