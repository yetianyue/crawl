<style>
.panel table tbody td {
    height: 34px;
    line-height: 34px;
}
.panel table .title {
    width: 140px;
    text-align: right;
}
.scheduler_list {
    height:260px;
    overflow-y:scroll;
    border:1px dotted #ccc;
}
.scheduler_list .item {
    height: 35px;
    line-height: 35px;
    border-bottom:1px dotted #ccc;
    padding: 0 5px;
}
.scheduler_list .item div {
    float:left;
}
</style>
<div class="panel panel-default" style="width:300px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getSystemInfo();" class="btn btn-success">系统基本信息</button></div>
        <table class="table" style="margin-bottom: 10px;" id="systemInfo">            
        </table>
    </div>
</div>



<div class="panel panel-default" style="width:300px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getSchedulerInfo();" class="btn btn-success">调度基本信息</button></div>
        <table class="table" style="margin-bottom: 10px;" id="schedulerInfo">
            
        </table>
    </div>
</div>


<div class="panel panel-default" style="width:300px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getCrawlInfo();" class="btn btn-success">抓取文章信息</button></div>
        <table class="table" style="margin-bottom: 10px;" id="crawlInfo">
            
        </table>
    </div>
</div>


<div class="panel panel-default" style="width:610px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getWaitProjectList();" class="btn btn-success">等待调度项目列表</button></div>
        <div class="scheduler_list" id="wait_scheduler">
       
        </div>
    </div>
</div>


<div class="panel panel-default" style="clear:left;width:610px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getFailProjectList();" class="btn btn-success">任务失败项目列表</button></div>
        <div class="scheduler_list" id="fail_scheduler">
        
        </div>
    </div>
</div>


<div class="panel panel-default" style="width:610px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getSuccessProjectList();" class="btn btn-success">今日任务完成项目列表</button></div>
        <div class="scheduler_list" id="success_scheduler">
        
        </div>
    </div>
</div>

 

  
<div class="panel panel-default" style="width:610px;float:left;margin-left:10px;">
    <div class="panel-body">
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getUserInfo();" class="btn btn-success">项目完成进度</button></div>
        <div class="scheduler_list" id="userInfo">
                 
        </div>
    </div>
</div>


<div class="panel panel-default" style="width:610px;float:left;margin-left:10px;">
    <div class="panel-body" >
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:getResponseStatistics();" class="btn btn-success">七天内抓取的文章数</button></div>
        <div id="statics">
        </div>
        
    </div>
</div>

<div class="panel panel-default" style="width:610px;float:left;margin-left:10px;">
    <div class="panel-body" >
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:GetCrawlArticalInfo();" class="btn btn-success">三天内新闻抓取的数据统计</button></div>
        <div id="news_statics">
        </div>
        
    </div>
</div>

<div class="panel panel-default" style="width:610px;float:left;margin-left:10px;">
    <div class="panel-body" >
        <div style="font-size:12pt;margin-bottom:5px;font-weight:bold;">
        <button type="button" onclick="javascript:getNetFlowStatistics();" class="btn btn-success">流量统计(Mbps)</button></div>
        <div id="netflow">
        </div>
        
    </div>
</div>



<script type="text/javascript">

getSystemInfo();
getSchedulerInfo();
getWaitProjectList();
getFailProjectList();
getSuccessProjectList();
getUserInfo();
getResponseStatistics();
getNetFlowStatistics();
getCrawlInfo();
GetCrawlArticalInfo()

function getSystemInfo()
{
	var e = $('<tbody>'+
				'<tr>'+
					'<td class="title">项目总数：</td>'+
					'<td ><span class="label label-default" id="total_project"></span></td>'+
				'</tr>'+
				'<tr>'+
					'<td class="title">今日新增项目：</td>'+
					'<td><span class="label label-info" id="today_add"></span></td>'+
				'</tr>'+
				'<tr>'+
					' <td class="title">任务总数：</td>'+
					'<td><span class="label label-primary" id="total_task"></span></td>'+
				'</tr>'+
				'<tr>'+
					'<td class="title">已完成任务：</td>'+
					'<td><span class="label label-success" id="complete_task"></span></td>'+
				'</tr>'+
				'<tr>'+
					'<td class="title">今日抓取文章数：</td>'+
					'<td><span class="label label-success" id="today_finish"></span></td>'+
				'</tr>'+
			'</tbody>');

	 $.ajax({
	        type: 'get',
	        url: '/monitor/getSystemInfo',
	        dataType: 'json',
	        success: function(data) {
	            $('#systemInfo').html('');
	           	e.find('#total_project').html(data["Project"]["Total"]);
	           	e.find('#today_add').html(data["Project"]["Today"]);
	           	e.find('#total_task').html(data["Task"]["Total"]);
	           	e.find('#complete_task').html(data["Task"]["Complete"]);
	           	e.find('#today_finish').html(data["Response"]["Today"]);
	           	$('#systemInfo').append(e);	           	
	        },
	        error: function(e) {
	        	$('#systemInfo').html('');
	        	$('#systemInfo').html('数据加载失败');
	        	
	        }
	    });

	 $('#systemInfo').html('数据加载中...');	    					
}


function getSchedulerInfo()
{
	var e = $('<tbody>'+
   				'<tr>'+
    				'<td class="title">调度队列数：</td>'+
    				'<td><span class="label label-default" id="scheduler"></span></td>'+
				'</tr>'+
 			    '<tr>'+
    				'<td class="title">等待调度数：</td>'+
    				'<td><span class="label label-primary" id="wait"></span></td>'+
				'</tr>'+
				'<tr>'+
    				'<td class="title">处理队列数：</td>'+
    				'<td><span class="label label-warning" id="queue"></span></td>'+
				'</tr>'+
				'<tr>'+
    				'<td class="title">处理中：</td>'+
    				'<td><span class="label label-info" id="process"></span></td>'+
				'</tr>'+
				'<tr>'+
    				'<td class="title">失败任务数：</td>'+
    				'<td><span class="label label-danger" id="fail"></span></td>'+
				'</tr>'+
			'</tbody>');

	 $.ajax({
	        type: 'get',
	        url: '/monitor/getSchedulerInfo',
	        dataType: 'json',
	        success: function(data) {
	            $('#schedulerInfo').html('');
	           	e.find('#scheduler').html(data["Scheduler"]);
	           	e.find('#wait').html(data["Wait"]);
	           	e.find('#queue').html(data["Queue"]);
	           	e.find('#process').html(data["Process"]);
	           	e.find('#fail').html(data["Fail"]);
	           	$('#schedulerInfo').append(e);	           	
	        },
	        error: function(e) {
	        	$('#schedulerInfo').html('');
	        	$('#schedulerInfo').html('数据加载失败');
	        	
	        }
	    });

	 $('#schedulerInfo').html('数据加载中...');	    
	    					
}

function getCrawlInfo()
{
	var e = $('<tbody>'+   	
				'<tr>'+
    				'<td class="title">昨日新闻抓取数：</td>'+
    				'<td><span class="label label-info" id="artical_news_yestoday"></span></td>'+
				'</tr>'+
				'<tr>'+
    				'<td class="title">昨日新闻发表数：</td>'+
    				'<td><span class="label label-info" id="artical_news_yestoday_pub"></span></td>'+
				'</tr>'+
				'<tr>'+
    				'<td class="title">今日新闻抓取数：</td>'+
    				'<td><span class="label label-success" id="artical_news_today"></span></td>'+
				'</tr>'+
				'<tr>'+
    				'<td class="title">今日新闻发表数：</td>'+
    				'<td><span class="label label-success" id="artical_news_today_pub"></span></td>'+
				'</tr>'+
				'<tr>'+
					'<td class="title">近两天的发表率：</td>'+
					'<td><span class="label label-primary" id="artical_news_rate"></span></td>'+
				'</tr>'+
				
			'</tbody>');

	 $.ajax({
	        type: 'get',
	        url: '/monitor/getCrawlInfo',
	        dataType: 'json',
	        success: function(data) {
	            $('#crawlInfo').html('');	          
	           	e.find('#artical_news_yestoday').html(data["news_yestoday"]);
	           	e.find('#artical_news_yestoday_pub').html(data["news_yestoday_pub"]);
	           	e.find('#artical_news_today').html(data["news_today"]);
	           	e.find('#artical_news_today_pub').html(data["news_today_pub"]);	         
	           	e.find('#artical_news_rate').html(data["rate"]);
	           	$('#crawlInfo').append(e);	           	
	        },
	        error: function(e) {
	        	$('#crawlInfo').html('');
	        	$('#crawlInfo').html('数据加载失败');
	        	
	        }
	    });

	 $('#crawlInfo').html('数据加载中...');	    
	    					
}







function getWaitProjectList()
{
	 $.ajax({
	        type: 'get',
	        url: '/monitor/getWaitProjectList',
	        dataType: 'json',
	        success: function(data) {
	            $('#wait_scheduler').html('');
	            for(var i=0; i<data.length;++i)
	            {
                        var item = $('<div class="item">'+
                            '<div style="width:50px;" class="project_id"></div>'+
                            '<div style="width:160px;height:35px;overflow:hidden;">'+
                                '<a target="_blank" class="name"></a>'+
                            '</div>'+
                            '<div style="width:150px;height:35px;overflow:hidden;">'+
                                '<a  target="_blank" class="domain"></a>'+
                            '</div>'+
                            '<div style="width:70px;padding-left:30px;"><span class="rate label label-danger" >unknown</span></div>'+
                            '<div style="width:80px;float:right;"><span class="wait label label-primary" ></span></div>'+
	                    '</div>');
					
					item.find(".project_id").html(data[i]["project_id"]);
					item.find(".name").attr("href","/project/items?project_id="+data[i]["project_id"]);
					item.find(".name").html(data[i]["name"]);
					item.find(".domain").attr("href","http://crawl.webdev.com/task/items?state=0&url=http://"+data[i]["domain"]);
					item.find(".domain").html(data[i]["domain"]);
					var rateObj = item.find(".rate");
                    if(data[i]["rate"] != undefined)
                    {
                        rateObj.html((Math.round(data[i]["rate"]*100)/100)+'/s');
                        rateObj.removeClass('label-danger');
                        rateObj.addClass('label-warning');
                    }
					item.find(".wait").html(data[i]["count"]);
					$('#wait_scheduler').append(item);										
		        }
	           		           	
	        },
	        error: function(e) {
	        	 $('#wait_scheduler').html('');
	        	 $('#wait_scheduler').html('数据加载失败');
	        	
	        }
	    });

	 $('#wait_scheduler').html('数据加载中...');	    
	    					
}


function getFailProjectList()
{
	 $.ajax({
	        type: 'get',
	        url: '/monitor/getFailProjectList',
	        dataType: 'json',
	        success: function(data) {
	            $('#fail_scheduler').html('');
	            for(var i=0; i<data.length;++i)
	            {
	            	var item = $('<div class="item">'+
	                        '<div style="width:70px;" class="project_id"></div>'+
	                       '<div style="width:150px;height:35px;overflow:hidden;">'+
	                            '<a target="_blank" class="name"></a>'+
	                        '</div>'+
	                        '<div style="width:200px;height:35px;overflow:hidden;">'+
	                            '<a  target="_blank" class="domain"></a>'+
	                        '</div>'+
	                        '<div style="width:80px;float:right;"><span class="label label-danger" ></span></div>'+
	                    '</div>');
					
					item.find(".project_id").html(data[i]["project_id"]);
					item.find(".name").attr("href","/project/items?project_id="+data[i]["project_id"]);
					item.find(".name").attr("target","_blank");
					item.find(".name").html(data[i]["name"]);
					item.find(".domain").attr("href","http://"+data[i]["domain"]);
					item.find(".domain").html(data[i]["domain"]);
					item.find(".label").html(data[i]["Count"]);
					$('#fail_scheduler').append(item);										
		        }
	           		           	
	        },
	        error: function(e) {
	        	 $('#fail_scheduler').html('');
	        	 $('#fail_scheduler').html('数据加载失败');
	        	
	        }
	    });

	 $('#fail_scheduler').html('数据加载中...');	    
	    					
}


function getSuccessProjectList()
{
	 $.ajax({
	        type: 'get',
	        url: '/monitor/getSuccessProjectList',
	        dataType: 'json',
	        success: function(data) {
	            $('#success_scheduler').html('');
	            for(var i=0; i<data.length;++i)
	            {
	            	var item = $('<div class="item">'+
	                        '<div style="width:70px;" class="project_id"></div>'+
	                       '<div style="width:150px;height:35px;overflow:hidden;">'+
	                            '<a target="_blank" class="name"></a>'+
	                        '</div>'+
	                        '<div style="width:200px;height:35px;overflow:hidden;">'+
	                            '<a  target="_blank" class="domain"></a>'+
	                        '</div>'+
	                        '<div style="width:80px;float:right;"><span class="label label-success" ></span></div>'+
	                    '</div>');
					
					item.find(".project_id").html(data[i]["project_id"]);
					item.find(".name").attr("href","/project/items?project_id="+data[i]["project_id"]);
					item.find(".name").html(data[i]["name"]);
					item.find(".domain").attr("href","http://"+data[i]["domain"]);
					item.find(".domain").html(data[i]["domain"]);
					item.find(".label").html(data[i]["Count"]);
					$('#success_scheduler').append(item);										
		        }
	           		           	
	        },
	        error: function(e) {
	        	 $('#success_scheduler').html('');
	        	 $('#success_scheduler').html('数据加载失败');
	        	
	        }
	    });

	 $('#success_scheduler').html('数据加载中...');	    
	    					
}



function getUserInfo()
{
	 $.ajax({
	        type: 'get',
	        url: '/monitor/getUserInfo',
	        dataType: 'json',
	        success: function(data) {
	        	$('#userInfo').html('');
		        var e = $('<div class="item">'+
                		'<div style="width:300px;height:35px;overflow:hidden;font-weight:bold;">责任人</div>'+              
                		'<div style="width:80px;float:right;font-weight:bold;">完成总数</div>'+
                		'<div style="width:80px;float:right;font-weight:bold;">今日完成</div>'+
            			'</div>');
		        $('#userInfo').append(e);
	            for(var key in data)
	            {
	            	var item = $('<div class="item">'+
                					'<div style="width:300px;height:35px;overflow:hidden;">'+
                    					'<a target="_blank" href="/project/items?user=' +data[key]["Name"]+ '">' + data[key]["Name"] + '</a>' + 
                					'</div>'+
               
                					'<div style="width:80px;float:right;"><span class="label label-primary">'+data[key]["Total"]+'</span></div>'+
                					'<div style="width:80px;float:right;"><span class="label label-success">'+data[key]["Today"]+'</span></div>'+
            					'</div>');
					
					$('#userInfo').append(item);										
		        }
	           		           	
	        },
	        error: function(e) {
	        	$('#userInfo').html('');	        	
	        	 $('#userInfo').append('数据加载失败');
	        	
	        }
	    });

	 $('#userInfo').append('数据加载中...');	    
	    					
}


function getResponseStatistics()
{
	$.ajax({
        type: 'get',
        url: '/monitor/getResponseStatistics',
        dataType: 'json',
        success: function(data) {   
        	$('#statics').html('');        
            var item = $('<canvas id="myChart" width="580" height="255"></canvas>');		
			$('#statics').append(item);										
			var data = {
					labels : data["labels"],
					datasets : [
						{
							fillColor : "rgba(151,187,205,0.5)",
							strokeColor : "rgba(151,187,205,1)",
							pointColor : "rgba(151,187,205,1)",
							pointStrokeColor : "#fff",
							data : data["data"]["pub_time"]
						},
						{
							fillColor : "rgba(220,220,220,0.5)",
							strokeColor : "rgba(220,220,220,1)",
							pointColor : "rgba(220,220,220,1)",
							pointStrokeColor : "#fff",
							data : data["data"]["create_time"]
						},
						
					]
				}

			var ctx = $("#myChart").get(0).getContext("2d");
			new Chart(ctx).Line(data);          		           	
        },
        error: function(e) {
        	 $('#statics').html('');
        	 $('#statics').html('数据加载失败');
        	
        }
    });

 	$('#statics').html('数据加载中...');	 
}

function GetCrawlArticalInfo()
{
	$.ajax({
        type: 'get',
        url: '/monitor/getCrawlArticalInfo',
        dataType: 'json',
        success: function(data) {   
        	$('#news_statics').html('');        
            var item = $('<canvas id="CrawlArticalChart" width="580" height="255"></canvas>');		
			$('#news_statics').append(item);										
			var data = {
					labels : data["labels"],
					datasets : [
						{
							fillColor : "rgba(151,187,205,0.5)",
							strokeColor : "rgba(151,187,205,1)",
							pointColor : "rgba(151,187,205,1)",
							pointStrokeColor : "#fff",
							data : data["data"]["effective_num"]
						},
						/*
						{
							fillColor : "rgba(220,220,220,0.5)",
							strokeColor : "rgba(220,220,220,1)",
							pointColor : "rgba(220,220,220,1)",
							pointStrokeColor : "#fff",
							data : data["data"]["crawl_num"]
						},
							*/				
					]
				}

			var ctx = $("#CrawlArticalChart").get(0).getContext("2d");
			new Chart(ctx).Line(data);          		           	
        },
        error: function(e) {
        	 $('#news_statics').html('');
        	 $('#news_statics').html('数据加载失败');
        	
        }
    });

 	$('#news_statics').html('数据加载中...');	 
}



function getNetFlowStatistics()
{
	$.ajax({
        type: 'get',
        url: '/monitor/getNetFlowStatics',
        dataType: 'json',
        success: function(data) {   
        	$('#netflow').html('');        
            var item = $('<canvas id="netChart" width="580" height="255"></canvas>');		
			$('#netflow').append(item);										
			var data = {
					labels : data["time"],
					datasets : [
						{
							fillColor : "rgba(151,187,205,0.5)",
							strokeColor : "rgba(151,187,205,1)",
							pointColor : "rgba(151,187,205,1)",
							pointStrokeColor : "#fff",
							data : data["netFlow"]
						},
						
					]
				}

			var ctx = $("#netChart").get(0).getContext("2d");
			new Chart(ctx).Line(data);          		           	
        },
        error: function(e) {
        	 $('#netflow').html('');
        	 $('#netflow').html('数据加载失败');
        	
        }
    });

 	$('#netflow').html('数据加载中...');	 
}



</script>





