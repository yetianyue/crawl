<style>
.content tr {
height:34px;
line-height:34px;
 border:1px dotted #ccc;
}
</style>

<div class="panel panel-default" style="width:400px;float:left;margin-left:10px; height:744px;">
    <div class="panel-body">
    	<div style="text-align:center;"><h2>词性黑名单</h2></div>
        <div style="margin-bottom:20px; text-align: center;">
        	<table style="margin-bottom: 10px;">
        		<tr>
        			<td style="width:70px;"><button type="button" onclick="javascript:AddBlackword();" class="btn btn-success">添加</button></td>
        			<td style="width:70px;"><button type="button" onclick="javascript:UpdateBlackword();" class="btn btn-success">更新</button></td>
        			<td><select style="width:150px;" class="form-control"  id="Blackword_select"></select></td>        			       			
        		</tr>              		        		      		
        	</table>
        	<div id="Blackword_remind" style="text-align: right;">
        		
        	</div>
        </div>
      	
      	<div style="margin-bottom:20px; text-align: center;" class="content">
      		<table style="margin-bottom: 10px; width:350px;" id="Blackword_table">      		
      		</table>
      		<div style="margin-bottom:20px; text-align: center;" >
      			<nav>
      				<ul class="pagination" style="margin:0px;">
      					<li id="Blackword_page_pre"><a href="#"><span>上一页</span></a></li>
      					<li id="Blackword_page_next"><a href="#"><span>下一页</span></a></li>
      					<li id="Blackword_page_info" style="height:35px;line-height:35px;"></li>
      				</ul>
      			</nav>
      		</div>
      	</div> 
      	 
    </div>
</div>





<script type="text/javascript">

	GetBlackword();
	
	function GetBlackword(page)
	{
		
		$.ajax({
				type: 'get',
		        url: '/segment/getBlackword',
		        data:{'page':page, 'table':'segment_blackword'},
		        dataType: 'json',
		        success:function(data)
		        {
					$("#Blackword_select").html("");
					var result = data["result_unadd"];
					for(var i=0; i<result.length; i++)
					{
						var item = $(
									'<option value="'+result[i]["word_id"]+'" id="blackword_option_'+result[i]["word_id"]+'">'+result[i]["word_id"]+'--'+result[i]["word"]+'</option>'
								);
						
						$("#Blackword_select").append(item);
					}
			        
			        $("#Blackword_table").html("");			        
			        result = data["result_add"];
			        for(var i=0; i<result.length; i++)
			        {
						var item = $('<tr id="tr_blackword_'+result[i]["word_id"]+'">'+
									'<td style="width:70px">'+result[i]["word_id"]+'</td>'+
									'<td style="width:120px">'+result[i]["word"]+'</td>'+									
									'<td ><a href="#" onclick="DelBlackWord('+result[i]["word_id"]+')">删除</a></td>'+
								'</tr>');
						
						if(i%2==1)
						{
					
							item.attr('style','background-color:#EEEEE0');
						}
											
															
						$("#Blackword_table").append(item);
				    }
				    var prePage = Number(data['curPage'])-1;
				    var nextPage = Number(data['curPage'])+1;
				    $('#Blackword_page_pre').find('a').attr('onclick','javascript:GetBlackword('+prePage+')');
				    $('#Blackword_page_next').find('a').attr('onclick','javascript:GetBlackword('+nextPage+')');
				    $('#Blackword_page_info').html('&nbsp页数：'+nextPage+'/'+data['pageNum']+'&nbsp总数：'+data['itemNum']);

				    if(data['curPage']<1)
				    {
				    	$('#Blackword_page_pre').attr('style','display:none');
					}
				    else
				    {
				    	$('#Blackword_page_pre').attr('style','display:inline');
					}

				    if(nextPage>=data['pageNum'])
				    {
				    	$('#Blackword_page_next').attr('style','display:none');
					}
				    else
				    {
				    	$('#Blackword_page_next').attr('style','display:inline');
					}
			        
			     },
			    error: function(e)
			    {
			    	$('#Blackword_table').html('');
		        	$('#Blackword_table').html('数据加载失败');
				}
		});
		$('#Blackword_remind').html("");	
	}
	
	function AddBlackword()
	{
		var select = $.trim($('#Blackword_select').val());		
		$.ajax({
				type: 'get',
		        url: '/segment/addBlackword',
		        data:{'word_id':select,'table':'segment_blackword'},		       
		        success:function(data)
		        {
			        
			        GetBlackword(0);
			        $('#Blackword_remind').html('添加成功');
				    
		        	
			    },
			    error: function(e)
			    {
			    	window.alert("添加失败");
				}			    
			});
		
	}
	
	function DelBlackWord(id)
	{
		$.ajax({
				type: "get",
				url: '/segment/delBlackword',
				data:{'id':id,'table':'segment_blackword'},
				success:function(data)
				{		
					GetBlackword(0);
			        $('#Blackword_remind').html('删除成功');	
				},
				error:function(e)
				{
					window.alert("删除失败");
				}
			});
	}

	function UpdateBlackword()
	{
		$.ajax({
			type: "get",
			url: '/segment/UpdateBlackword',
			data: {'table':'segment_blackword'},
			success:function(data)
			{
				
				$('#Blackword_remind').html('词性黑名单同步成功');	
			},
			error:function(e)
			{
				window.alert("更新失败");
			}
		});

		$('#Blackword_remind').html('词性黑名单同步中。。。');
	}
	
            
</script>















