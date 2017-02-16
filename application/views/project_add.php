<style>
.toolbar {
    width: 500px;
    top: 50px;
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
<div class="main">
    <div class="configure">
        <div class="panel panel-default" style="width:400px;float:left;">
            <div class="panel-body" style="">
                <div style="font-size:12pt;margin-bottom:5px;">项目基本信息</div>
                <table class="table" style="margin-bottom: 10px;">
                    <tbody >
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">项目名称：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_name" style="margin-bottom:0px;">
                                    <input id="project_name" type="text" class="form-control" placeholder="请输入项目名称">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;" >项目模板：</td>
                            <td>
                                <select id="project_template" class="form-control" onchange="javascript:selectStyle();">
                                    <option value="0">新闻后台模板</option>
                                    <option value="1">数据挖掘模板</option>
									 <option value="2">新浪微博模板</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">抓取速率：</td>
                            <td>
                                <select id="project_rate" class="form-control">
                                    <option value="60">1分钟</option>
                                    <option value="120">2分钟</option>
                                    <option value="180">3分钟</option>
                                    <option value="300" selected>5分钟</option>
                                    <option value="600">10分钟</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">项目等级：</td>
                            <td>
                                <select id="project_priority" class="form-control">
                                    <option value="2">普通</option>
                                    <option value="1">重要</option>
                                    <option value="0">紧急</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">项目标签：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_tag" style="margin-bottom:0px;">
                                    <input id="project_tag" type="text" class="form-control" value="" placeholder="请输入项目标签">
                                    <span class="form-control-feedback"></span>
                                </div>
                                <div id="taglist" style="margin-top:5px;">
                                    <span class="label label-primary" style="cursor:pointer;">新闻后台</span>
                                    <span class="label label-primary" style="cursor:pointer;">数据挖掘</span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">字符编码：</td>
                            <td>
                                <div class="form-group has-feedback" style="margin-bottom:0px;">
                                    <input id="project_encoding" type="text" class="form-control" value="自动识别">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">登陆Cookie：</td>
                            <td>
                                <div class="form-group has-feedback" style="margin-bottom:0px;">
                                    <input id="project_cookie" type="text" class="form-control" placeholder="请输入Cookie">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">责任人：</td>
                            <td>
                                <div class="form-group has-feedback" id="div_user" style="margin-bottom:0px;">
                                    <input id="project_user" value="" type="text" class="form-control" placeholder="请输入责任人">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <!--
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">终端：</td>
                            <td>
                                <select id="project_agent" class="form-control">
                                    <option value="-1" selected>无</option>
                                    <option value="0">Apple iPad</option>
                                    <option value="1">Apple iPad Mini</option>
                                    <option value="2">Apple iPhone 4</option>
                                    <option value="3">Apple iPhone 5</option>
                                    <option value="4">Apple iPhone 6</option>
                                    <option value="5">Apple iPhone 6 Plus</option>
                                    <option value="6">Samsung Galaxy Note 3</option>
                                    <option value="7">Samsung Galaxy Note 2</option>
                                    <option value="8">Samsung Galaxy S3</option>
                                    <option value="9">Samsung Galaxy S4</option>
                                </select>
                            </td>
                        </tr>
                        -->
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
                                <div class="SeedList" style="margin-bottom:0px;height:365px;overflow-y:scroll;border:1px solid #ccc;">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div id="list_id" class="panel panel-default" style="clear:left;width:910px;float:left;">
            <div class="panel-body" style="">
                <div style="font-size:12pt;margin-bottom:5px;">列表页配置</div>
                <table class="table" style="margin-bottom: 10px;">
                    <tbody>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">链接筛选器：</td>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;">
                                    <span class="input-group-addon">$</span>
                                    <input id="list_dom" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">地址过滤器：</td>
                            <td>
                                <div class="input-group has-feedback"  style="margin-bottom:0px;">
                                    <span class="input-group-addon">#</span>
                                    <input id="list_regex" type="text" class="form-control" placeholder="请输入过滤正则表达式">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div  id="detail_id" class="panel panel-default" style="clear:left;width:910px;float:left;">
            <div class="panel-body" style="">
                <div style="float:left;font-size:12pt;margin-bottom:5px;">详情页配置</div>
                <span style="float:right;cursor: pointer;margin-right: 5px;" class="glyphicon glyphicon-plus" onclick="javascript:AddConfigItem()"></span>
                <table class="table" style="margin-bottom: 10px;" id="detail_table">
                    <tbody >
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">文章标题：</td>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;">
                                    <span class="input-group-addon">$</span>
                                    <input id="detail_title" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">文章正文：</td>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;">
                                    <span class="input-group-addon">$</span>
                                    <input id="detail_content" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">发表时间：</td>
                            <td>
                                <div class="input-group has-feedback"  style="margin-bottom:0px;">
                                    <span class="input-group-addon">$</span>
                                    <input id="detail_pubtime" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">文章来源：</td>
                            <td>
                                <div class="input-group has-feedback">
                                    <span class="input-group-addon">$</span>
                                    <input id="detail_src" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">发表作者：</td>
                            <td>
                                <div class="input-group has-feedback">
                                    <span class="input-group-addon">$</span>
                                    <input id="detail_author" type="text" class="form-control" placeholder="请输入筛选路径">
                                    <span class="form-control-feedback"></span>
                                </div>
                            </td>
                        </tr>
						<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">视频筛选：</td>
									<td>
										<div class="input-group has-feedback">
											<span class="input-group-addon">$</span>
											<input id="video_find" type="text"   value='' class="form-control" placeholder="请输入筛选路径,非视频类网站为空即可">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
								<tr>
									<td style="width:100px;text-align:right;height34px;line-height:34px;">视频地址值</td>
									<td>
										<div class="input-group has-feedback">
											<span class="input-group-addon">$</span>
											<input id="video_src" type="text"   value="" class="form-control" placeholder="比如 href src value等,非视频类网站为空即可">
											<span class="form-control-feedback"></span>
										</div>
									</td>
								</tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div  id="filter_id" class="panel panel-default" style="clear:left;width:910px;float:left;">
            <div class="panel-body" style="">
                <div style="float:left;font-size:12pt;margin-bottom:5px;">正文筛选项配置</div>
                <span style="float:right;cursor:pointer;margin-right:5px;" class="glyphicon glyphicon-plus" onclick="javascript:AddFilterItem()">&nbsp添加筛选项</span>
                <table class="table" style="margin-bottom: 10px;" id="filter_table">
                        <tr>
                            <td style="width:100px;text-align:right;height34px;line-height:34px;">段落筛选：</td>
                            <td>
                                <div class="input-group has-feedback" style="margin-bottom:0px;width:330px;">
                                    <span class="input-group-addon glyphicon glyphicon-align-left" style="top:0px;width:33px;padding:0px 9px 0px 8px;"></span>
                                    <input id="content_start" type="text" class="form-control para_selector" value="0" placeholder="起始段落索引">
                                    <span class="input-group-addon" style="border-left:0;border-right:0;background-color:white;">至</span>
                                    <input id="content_end" type="text" class="form-control para_selector" value="-1" placeholder="结尾段落索引">
                                </div>
                            </td>
                        </tr>
                </table>
            </div>
        </div>
        <div style="height:50px;clear:both;"></div>
    </div>
    <div class="code" style="display:none;">
        <textarea id="editor" class="form-control" style="height:700px;width:910px;"></textarea>
    </div>
</div>
<div class="toolbar">
    <div class="panel panel-default" style="clear:both; border-color:#aaa; margin-bottom:0px;">
        <div class="panel-body" style="padding:5px 10px;">
            <button type="button" onclick="javascript:RunTask();" class="btn btn-success">调试程序</button>
            <button type="button" onclick="javascript:GenerateScript();" class="btn btn-primary">生成代码</button>
            <button type="button" onclick="javascript:SwitchView(this);" class="btn btn-info">查看代码</button>
            <button id="btn_add" type="button" onclick="javascript:AddProject();"style="float:right"class="btn btn-warning">添加项目</button>
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
        <div class="panel-heading">运行结果  &nbsp;&nbsp;&nbsp; <a target="_blank" href="/debugger/Preview" onclick="AlterPreviewState()">预览抓取结果</a></div>
        <div class="panel-body" style="padding:0 10px;">
            <div id="result" class="ResultList">
                
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/project.js">
</script>
<script>
var IS_PREVIEWED = false;

var TaskConsole = {
    Log: function(msg) {
        var output = $('#output');
        output.html(output.html()+msg+'<br/>');
        output.scrollTop(output[0].scrollHeight);
    }
};

function AlterPreviewState()
{
    IS_PREVIEWED = true;
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
            'fetch': ''
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
            'fetch': JSON.stringify(item)
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

function AddProject()
{
    if(false == IS_PREVIEWED)
    {
        TaskConsole.Log('<span style="font-weight:bold;color:red">添加项目失败:未进行预览</span>');
        return;
    }
    if( -1 == CheckScriptInput())
    {
        return;
    }
    var project_name = $('#project_name');
    var project_user = $('#project_user');
    var project_tag = $('#project_tag');
    var editor = $('#editor');
    
    if(IsEmpty(project_name) || IsEmpty(project_tag) || IsEmpty(project_user)){
        return;
    }

    if(''==editor.val())
    {
        TaskConsole.Log('<span style="font-weight:bold;color:red">添加项目失败:未添加脚本</span>');
        return;
    }
    $.ajax({
        type: 'POST',
        url: '/project/saveProject',
        dataType: 'json',
        data: {
            'name': project_name.val(),
            'tag': $('#project_tag').val(),
            'rate': $('#project_rate').val(),
            'priority': $('#project_priority').val(),
            'cookie': $('#project_cookie').val(),
            'user': project_user.val(),
            'script': editor.val(),
            'list_url': EncodeUrl(),
            'list_dom': $('#list_dom').val(),
            'list_regex': $('#list_regex').val(),
            'detail_title': $('#detail_title').val(),
            'detail_content': $('#detail_content').val(),
            'detail_contentStart': $('#content_start').val(),
            'detail_contentEnd': $('#content_end').val(),
            'detail_pubtime': $('#detail_pubtime').val(),
            'detail_src': $('#detail_src').val(),
			'video_find':$('#video_find').val(),
			'video_src':$('#video_src').val(),
            'detail_author': $('#detail_author').val()
        },
        beforeSend: function () {
            $("#btn_add").attr({ disabled: "disabled" });
        },
        success: function(data) {
            if(data['error'] != '')
            {
                TaskConsole.Log('<span style="font-weight:bold;color:red">添加项目失败: '+data['error']+'</span>');
            }
            else
            {
                TaskConsole.Log('<span style="font-weight:bold;">添加项目成功!</span>');
            }
        },
        complete: function () {
            $("#btn_add").removeAttr("disabled");
        },
        error: function(e) {
            TaskConsole.Log('<span style="font-weight:bold;color:red">添加项目失败: status('+e.status+') </span>');
        }
    });


}

function CheckScriptInput()
{
    var list_dom = $('#list_dom');
    var list_regex = $('#list_regex');
    var detail_title = $('#detail_title');
    var detail_content = $('#detail_content');
    var detail_pubtime = $('#detail_pubtime');
    var content_start = $('#content_start');
    var content_end = $('#content_end');

	var templateV = $('#project_template').val();
	if(templateV==2)
	{
		return 0;
	}
	
    if(IsEmpty(list_dom) || IsEmpty(list_regex) || IsEmpty(detail_title) || IsEmpty(detail_content) || IsEmpty(content_start) || IsEmpty(content_end))
    {
        return -1;
    }

    if($('#project_template').val() == 0 && IsEmpty(detail_pubtime))
    {
        return -1;
    }
    else if($('#project_template').val() == 1)
    {
        $('#project_template').parent().removeClass("form-group has-error"); 
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
        url: '/debugger/GenerateScript',
        dataType: 'json',
        data: {
            'project_priority': $('#project_priority').val(),
            'project_encoding': $('#project_encoding').val(),
            'project_template': $('#project_template').val(),
            'project_cookie': $('#project_cookie').val(),
            'list_url': EncodeUrl(),
            'list_dom': $('#list_dom').val(),
            'list_regex': $('#list_regex').val(),
            'detail_title': $('#detail_title').val(),
            'detail_content': $('#detail_content').val(),
            'detail_contentStart': $('#content_start').val(),
            'detail_contentEnd': $('#content_end').val(),
            'detail_pubtime': $('#detail_pubtime').val(),
            'detail_src': $('#detail_src').val(),
            'detail_author': $('#detail_author').val(),
			'video_find':$('#video_find').val(),
			'video_src':$('#video_src').val(),
            'detail_config': GetConfigItem(),
            'detail_filter': GetFilterItem()
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
                else if(option == 'addProject')
                {
                    AddProject();
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
    
    $.ajax({
        type: 'POST',
        url: '/project/IsUrlExisted',
        dataType: 'json',
        data: {
            'url': url,
            'project_id': 0
        },
        success: function(data) {
            if( 1 == data['ret'])
            {       
                TaskConsole.Log('<span style="font-weight:bold;color:red">URL:'+url+'已在任务列表中 </span>');
                return;
            }
            else
            {
                AddOneSeed(url,age);
            }
        },
        error: function(e) {
            TaskConsole.Log('<span style="font-weight:bold;color:red">检查URL:'+url+'出现异常，请重试: status('+e.status+') </span>');
            return -1;
        }
    });
    
}

var templateOldV = 0;
function selectStyle()
{

    var templateV = $('#project_template').val();
    if(templateV==2)
    {
        document.getElementById("list_id").style.display = "none";
        document.getElementById("detail_id").style.display = "none";
    }
    if(templateV!=2 && templateOldV==2)
    {
        document.getElementById("list_id").style.display = "block";
        document.getElementById("detail_id").style.display = "block";
    
    }
    templateOldV= templateV;
}

function AddOneSeed(url,age)
{   

    var ele =$('<div class="SeedItem" style="border-bottom:1px dotted #ccc;height:46px;padding:5px 5px;">'+
                    '<div class="SeedUrl" style="float:left;width:250px;height:34px;line-height:34px;overflow:hidden;">'+
                        '<a href="'+ url +'" title="'+ url +'" target="_blank">'+ url+'</a>'+
                    '</div>'+
                    '<div style="float:right;width:50px;margin-right:5px;margin-top:1px;">'+
                        '<button class="btn btn-default btn-sm" onclick="javascript:DeleteSeed(this);" type="button">删除</button>'+
                    '</div>'+
                    '<div class="SeedAge" style="float:right;width:100px;margin-right:10px;">'+
                        '<select class="form-control">'+
                            '<option value="300">5分钟</option>'+
                            '<option value="900">15分钟</option>'+
                            '<option value="1800">30分钟</option>'+
                            '<option value="3600">60分钟</option>'+
                        '</select>'+
                    '</div>'+
                '</div>');
    var seedlist = $('.SeedList');
    $(ele).find('.SeedAge select').val(age);
    seedlist.append(ele);

}

function EncodeUrl()
{
    var urlArray = new Object(); 
    $('.SeedItem').each(function(index)
    {
        var url = $(this).find('.SeedUrl a').attr('href');
        var age = $(this).find('.SeedAge select').val();
        urlArray[url] = age;
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


function DeleteSeed(ele)
{
    $(ele).parent().parent().remove();
}

$(document).ready(function(){
    IS_PREVIEWED = false;
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
