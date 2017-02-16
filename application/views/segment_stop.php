<style>
.content tr {
height:34px;
line-height:34px;
 border:1px dotted #ccc;
}
</style>

<div class="panel panel-default" style="width:400px;float:left;margin-left:10px;height:744px;">
    <div class="panel-body">
    	<div style="text-align:center;"><h2>停用词</h2></div>
        <div style="margin-bottom:20px; text-align: center;">
        	<table style="margin-bottom: 10px;">
        		<tr>
        			<td style="width:70px;"><button type="button" onclick="javascript:AddStopword();" class="btn btn-success">添加</button></td>
        			<td style="width:70px;"><button type="button" onclick="javascript:UpdateStopword();" class="btn btn-success">更新</button></td>
        			<td><input type="text" class="form-control" placeholder="请输入停用词" name="stopword" id="stopword_input"></td>
        			<td style="width:70px;"><button type="button" onclick="javascript:QueryStopword();" class="btn btn-success">查找</button></td>
        		</tr>              		        		      		
        	</table>
        	<div id="stopword_remind" style="text-align: right;">
        		
        	</div>
        </div>
      	
      	<div style="margin-bottom:20px; text-align: center;" class="content">
      		<table style="margin-bottom: 10px; width:350px;" id="stopword_table">      		
      		</table>
      		<div style="margin-bottom:20px; text-align: center;" >
      			<nav>
      				<ul class="pagination" style="margin:0px;">
      					<li id="stopword_page_pre"><a href="#"><span>上一页</span></a></li>
      					<li id="stopword_page_next"><a href="#"><span>下一页</span></a></li>
      					<li id="stopword_page_info" style="height:35px;line-height:35px;"></li>
      				</ul>
      			</nav>
      		</div>
      	</div> 
      	 
    </div>
</div>





<script type="text/javascript">

	GetStopword();
	
	function GetStopword(page,word)
	{
		
		$.ajax({
				type: 'get',
		        url: '/segment/getword',
		        data:{'page':page,'word':word,'table':'segment_stopword'},
		        dataType: 'json',
		        success:function(data)
		        {
			        $("#stopword_table").html("");			        
			        var result = data["result"];
			        for(var i=0; i<result.length; i++)
			        {
						var item = $('<tr id="tr_stopword_'+result[i]["id"]+'">'+
									'<td style="width:70px;display:none">'+result[i]["id"]+'</td>'+
									'<td style="width:150px">'+result[i]["word"]+'</td>'+
									'<td ><a href="#" onclick="DelStopWord('+result[i]["id"]+')">删除</a></td>'+
								'</tr>');
						
						if(i%2==1)
						{							
							item.attr('style','background-color:#EEEEE0');
						}
															
						$("#stopword_table").append(item);
				    }
				    var prePage = Number(data['curPage'])-1;
				    var nextPage = Number(data['curPage'])+1;
				    $('#stopword_page_pre').find('a').attr('onclick','javascript:GetStopword('+prePage+')');
				    $('#stopword_page_next').find('a').attr('onclick','javascript:GetStopword('+nextPage+')');
				    $('#stopword_page_info').html('&nbsp页数：'+nextPage+'/'+data['pageNum']+'&nbsp总数：'+data['itemNum']);

				    if(data['curPage']<1)
				    {
				    	$('#stopword_page_pre').attr('style','display:none');
					}
				    else
				    {
				    	$('#stopword_page_pre').attr('style','display:inline');
					}

				    if(nextPage>=data['pageNum'])
				    {
				    	$('#stopword_page_next').attr('style','display:none');
					}
				    else
				    {
				    	$('#stopword_page_next').attr('style','display:inline');
					}
			        
			     },
			    error: function(e)
			    {
			    	$('#stopword_table').html('');
		        	$('#stopword_table').html('数据加载失败');
				}
		});
		$('#stopword_remind').html("");	
	}
	
	function AddStopword()
	{
		var input = $.trim($('#stopword_input').val());
		if(input=='')
		{
			$('#stopword_input').attr('style','border:#ff0000 1px solid');			
			return;
		}
		else
		{
			$.ajax({
				type: 'get',
		        url: '/segment/addword',
		        data:{'word':input,'table':'segment_stopword'},		       
		        success:function(data)
		        {
			        if(data==0)
			        {
			        	GetStopword(0);
			        	$('#stopword_remind').html('添加成功');
				    }
			        else
			        {
			        	$('#stopword_remind').html('该词已在停用词库中');
				    }
		        	
			    },
			    error: function(e)
			    {
			    	window.alert("添加失败");
				}			    
			});
		}
	}

	function QueryStopword()
	{
		var input = $.trim($('#stopword_input').val());
		if(input=='')
		{
			$('#stopword_input').attr('style','border:#ff0000 1px solid');
			GetStopword(0);
			return;
		}
		else
		{
			$('#stopword_input').attr('style','');
		}
		GetStopword(0,input);
	}

	function DelStopWord(id)
	{
		$.ajax({
				type: "get",
				url: '/segment/delword',
				data:{'id':id,'table':'segment_stopword'},
				success:function(data)
				{
					$('#tr_stopword_'+id).html('');
					$('#stopword_remind').html('删除成功');	
				},
				error:function(e)
				{
					window.alert("删除失败");
				}
			});
	}

	function UpdateStopword()
	{
		$.ajax({
			type: "get",
			url: '/segment/UpdateStopword',
			data: {'table':'segment_stopword'},
			success:function(data)
			{
				
				$('#stopword_remind').html('停用词同步成功');	
			},
			error:function(e)
			{
				window.alert("更新失败");
			}
		});

		$('#stopword_remind').html('停用词同步中。。。');
	}
            
</script>















