<style>
.content tr {
height:34px;
line-height:34px;
 border:1px dotted #ccc;
}
</style>

<div class="panel panel-default" style="width:400px;float:left;margin-left:10px;height:744px;">
    <div class="panel-body">
    	<div style="text-align:center;"><h2>自定义词</h2></div>
        <div style="margin-bottom:20px; text-align: center;">
        	<table style="margin-bottom: 10px;">
        		<tr>
        			<td style="width:70px;"><button type="button" onclick="javascript:AddCustomword();" class="btn btn-success">添加</button></td>
        			<td style="width:70px;"><button type="button" onclick="javascript:UpdateCustomword();" class="btn btn-success">更新</button></td>
        			<td><input type="text" class="form-control" placeholder="请输入自定义词" name="Customword" id="Customword_input"></td>
        			<td style="width:70px;"><button type="button" onclick="javascript:QueryCustomword();" class="btn btn-success">查找</button></td>
        		</tr>              		        		      		
        	</table>
        	<div id="Customword_remind" style="text-align: right;">
        		
        	</div>
        </div>
      	
      	<div style="margin-bottom:20px; text-align: center;" class="content">
      		<table style="margin-bottom: 10px; width:350px;" id="Customword_table">      		
      		</table>
      		<div style="margin-bottom:20px; text-align: center;" >
      			<nav>
      				<ul class="pagination" style="margin:0px;">
      					<li id="Customword_page_pre"><a href="#"><span>上一页</span></a></li>
      					<li id="Customword_page_next"><a href="#"><span>下一页</span></a></li>
      					<li id="Customword_page_info" style="height:35px;line-height:35px;"></li>
      				</ul>
      			</nav>
      		</div>
      	</div> 
      	 
    </div>
</div>





<script type="text/javascript">

	GetCustomword();
	
	function GetCustomword(page,word)
	{
		
		$.ajax({
				type: 'get',
		        url: '/segment/getWord',
		        data:{'page':page,'word':word,'table':'segment_customword'},
		        dataType: 'json',
		        success:function(data)
		        {
			        $("#Customword_table").html("");			        
			        var result = data["result"];
			        for(var i=0; i<result.length; i++)
			        {
						var item = $('<tr id="tr_customword_'+result[i]["id"]+'">'+
									'<td style="width:70px;display:none">'+result[i]["id"]+'</td>'+
									'<td style="width:150px">'+result[i]["word"]+'</td>'+
									'<td ><a href="#" onclick="DelCustomWord('+result[i]["id"]+')">删除</a></td>'+
								'</tr>');
						
						if(i%2==1)
						{
						
							item.attr('style','background-color:#EEEEE0');
						}
															
						$("#Customword_table").append(item);
				    }
				    var prePage = Number(data['curPage'])-1;
				    var nextPage = Number(data['curPage'])+1;
				    $('#Customword_page_pre').find('a').attr('onclick','javascript:GetCustomword('+prePage+')');
				    $('#Customword_page_next').find('a').attr('onclick','javascript:GetCustomword('+nextPage+')');
				    $('#Customword_page_info').html('&nbsp页数：'+nextPage+'/'+data['pageNum']+'&nbsp总数：'+data['itemNum']);

				    if(data['curPage']<1)
				    {
				    	$('#Customword_page_pre').attr('style','display:none');
					}
				    else
				    {
				    	$('#Customword_page_pre').attr('style','display:inline');
					}

				    if(nextPage>=data['pageNum'])
				    {
				    	$('#Customword_page_next').attr('style','display:none');
					}
				    else
				    {
				    	$('#Customword_page_next').attr('style','display:inline');
					}
			        
			     },
			    error: function(e)
			    {
			    	$('#Customword_table').html('');
		        	$('#Customword_table').html('数据加载失败');
				}
		});
		$('#Customword_remind').html("");	
	}
	
	function AddCustomword()
	{
		var input = $.trim($('#Customword_input').val());
		if(input=='')
		{
			$('#Customword_input').attr('style','border:#ff0000 1px solid');			
			return;
		}
		else
		{
			$.ajax({
				type: 'get',
		        url: '/segment/addWord',
		        data:{'word':input,'table':'segment_customword'},		       
		        success:function(data)
		        {
			        if(data==0)
			        {
			        	GetCustomword(0);
			        	$('#Customword_remind').html('添加成功');
				    }
			        else
			        {
			        	$('#Customword_remind').html('该词已在自定义词库中');
				    }
		        	
			    },
			    error: function(e)
			    {
			    	window.alert("添加失败");
				}			    
			});
		}
	}

	function QueryCustomword()
	{
		var input = $.trim($('#Customword_input').val());
		if(input=='')
		{
			$('#Customword_input').attr('style','border:#ff0000 1px solid');
			GetCustomword(0);
			return;
		}
		else
		{
			$('#Customword_input').attr('style','');
		}
		GetCustomword(0,input);
	}

	function DelCustomWord(id)
	{
		$.ajax({
				type: "get",
				url: '/segment/delWord',
				data:{'id':id,'table':'segment_customword'},
				success:function(data)
				{
					$('#tr_customword_'+id).html('');
					$('#Customword_remind').html('删除成功');	
				},
				error:function(e)
				{
					window.alert("删除失败");
				}
			});
	}

	function UpdateCustomword()
	{
		$.ajax({
			type: "get",
			url: '/segment/UpdateCustomword',
			data: {'table':'segment_customword'},
			success:function(data)
			{
				
				$('#Customword_remind').html('自定义词同步成功');	
			},
			error:function(e)
			{
				window.alert("更新失败");
			}
		});

		$('#Customword_remind').html('自定义词同步中。。。');
	}
            
</script>















