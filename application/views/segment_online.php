<style>
.item {
    height: 35px;
    line-height: 35px;
    border-bottom:1px dotted #ccc;
    padding: 0 5px;
}

</style>


<div class="panel panel-default" style="width:600px;float:left;margin-left:10px;height:750px;">
	
	<div class="panel-body">
		<div style="font-size:20pt;margin-bottom:20px;text-align:center">网页内容提取</div>
		<table sytle="width:550px">
		<tr>
        	<td>
				<div class="input-group has-feedback" style="margin-bottom:10px;width:550px" id="seed_input">
	       			<span class="input-group-addon">URL</span>
	        		<input type="text" class="form-control"  placeholder="请输入分词链接" onpaste="" id="input_url" value="<?php echo $url ?>">
	        		<span class="form-control-feedback" style="margin-right:50px;width:100px;overflow:hidden"></span>
	        		<span class="input-group-btn"><button class="btn btn-default" onclick="javascript:ExtractText();" type="button">正文提取</button></span>
        		</div>
        	</td>
        </tr>
        
        <tr>
        	<td>
        		<div  style="margin-bottom:5px;height:40px;overflow-y:scroll;border:1px solid #ccc;display:none" id="extracttitle"></div>
        		<div  style="margin-bottom:5px;height:40px;overflow-y:scroll;border:1px solid #ccc;width:550px" id="segmenttitle"></div>
        		
        	</td>
        </tr>
        
        <tr>
        	<td>
        		<div  style="margin-top:5px;height:550px;overflow-y:scroll;border:1px solid #ccc;display:none" id="extractcontent"></div>
        		<div  style="margin-top:5px;height:550px;overflow-y:scroll;border:1px solid #ccc;width:550px" id="segmentcontent"></div>
        		
        	</td>
        </tr>
        
		</table>
		
	</div>

</div>


<div class="panel panel-default" style="width:600px;float:left;margin-left:10px;height:750px;">
	
	<div class="panel-body">		
		<div style="font-size:12pt;margin-bottom:5px;font-weight:bold;"><button type="button" onclick="javascript:SegmentWord();" class="btn btn-success">分词提取</button></div>
        <div  style="margin-top:5px;height:660px;overflow-y:scroll;border:1px solid #ccc;" id="segmentwordlist"></div>		
	</div>

</div>





<script type="text/javascript">

var blackword = new Array();
blackword[0] = "未知词性";
blackword[1] = "形容词";
blackword[2] = "副形词";
blackword[3] = "名形词";
blackword[4] = "区别词";
blackword[5] = "连词";
blackword[6] = "副词";
blackword[7] = "叹词";
blackword[8] = "方位词";
blackword[9] = "语素词";
blackword[10] = "前接成分";
blackword[11] = "成语";
blackword[12] = "简称略语";
blackword[13] = "后接成分";
blackword[14] = "习用语";
blackword[15] = "数词";
blackword[16] = "名词";
blackword[17] = "人名";
blackword[18] = "姓";
blackword[19] = "名";
blackword[20] = "地名";
blackword[21] = "机构团体";
blackword[22] = "其他专名";
blackword[23] = "非汉字串";
blackword[24] = "拟声词";
blackword[25] = "介词";
blackword[26] = "量词";
blackword[27] = "代词";
blackword[28] = "处所词";
blackword[29] = "时间词";
blackword[30] = "助词";
blackword[31] = "动词";
blackword[32] = "副动词";
blackword[33] = "名动词";
blackword[34] = "标点符号";
blackword[35] = "非语素字";
blackword[36] = "语气词";
blackword[37] = "状态词";
blackword[38] = "形语素";
blackword[39] = "区别语素";
blackword[40] = "副语素";
blackword[41] = "数词性语素";
blackword[42] = "名语素";
blackword[43] = "量语素";
blackword[44] = "代语素";
blackword[45] = "时语素";
blackword[46] = "动语素";
blackword[47] = "语气词语素";
blackword[48] = "状态词语素";
blackword[49] = "开始词";
blackword[55] = "结束词";



function ExtractText()
{
	var fetchurl = $.trim($("#input_url").val());
	if(fetchurl=='')
	{
		$("#input_url").attr("style","border:#ff0000 1px solid");
		$('#segmentcontent').html("url不能为空");
		return;
	}

	if(fetchurl.substr(0,24) != 'http://kuaibao.qq.com/s/')
	{
		$('#segmentcontent').html("输入的url只能为快豹链接");
		return;
	}
	
	$.ajax({
		type: "get",
		url: "/segment/ExtractText",
		data:{'fetchurl':fetchurl},
		dataType: 'json',
		success: function(data)
		{
			$('#extracttitle').html(data["title"]);
			$('#extractcontent').html(data["content"]);

			$.ajax({
				type: "post",
				url: "/segment/SegmentWord",
				data:{'title':data["title"], 's':1,'content':data["content"]},
				dataType: 'json',
				success: function(data1)
				{
					$("#segmentcontent").html("");
					$("#segmenttitle").html("");
					
					if(data1["error"] != 0)
					{
						$("#segmentwordlist").html("分词失败");
						return;
					}

					var title = data1['TI'];
					var content = data1["CT"];
					var titleStr = '';
					for(var i=0; i<title.length; i++)
					{
						titleStr += '<span title="'+blackword[title[i]["PO"]]+'">'+title[i]["WD"]+'</span>';
						titleStr += '/';						
					}
					$("#segmenttitle").html(titleStr);

					var contentStr = '';
					for(var i=0; i<content.length; i++)
					{
						

						if(content[i]["WD"]==" " )
						{
							contentStr += "&nbsp";
							continue;
						}

						if(content[i]["WD"]=="\t" )
						{
							contentStr += "&nbsp&nbsp&nbsp&nbsp&nbsp";
							continue;
						}
						
						if(content[i]["WD"]=="\n")
						{							
							contentStr += "<br><br>";
							continue;	
						}

						contentStr += '<span title="'+blackword[content[i]["PO"]]+'">'+content[i]["WD"]+'</span>';
						contentStr += "/";
						
					}
					$("#segmentcontent").html(contentStr);
					
				},
				error: function(e)
				{
					$("#segmentcontent").html("分词失败");
				}

				});
				$("#segmentcontent").html("分词进行中。。。");
						
		},
		error: function(e)
		{
			$('#segmentcontent').html("数据提取失败");
		}

		});
	$('#segmenttitle').html("");
	$('#segmentcontent').html("数据提取中");
}

	function SegmentWord()
	{
		var title = $('#extracttitle').html();
		var content = $('#extractcontent').html();
		
		$.ajax({
			type: "post",
			url: "/segment/SegmentWord",
			data:{'title':title, 'content':content},
			dataType: 'json',
			success: function(data)
			{
				$("#segmentwordlist").html("");
				if(data["error"] != 0)
				{
					$("#segmentwordlist").html("分词失败");
					return;
				}
				var length = data["TN"].length;
				for(var i=0; i<length; i++)
				{
					var item = $('<div class="item">'+
							'<div class="wd" style="width:200px; float:left;overflow:hidden"></div>'+							
							'<div class="sc" style="width:120px; float:left"></div>'+
							'<div style="width:200px; float:left">'+
							'<a style="display:none" href="#" class="po'+ data["TN"][i]["PO"] +'" onclick="javascript:AddBlackPos('+data["TN"][i]["PO"]+');">词性</a>&nbsp'+
							'<a style="display:none" href="#" id="wd'+ i +'" onclick="javascript:AddStopWord(\''+data["TN"][i]["WD"]+'\','+i+');">停用词</a>'+
							'</div>'+							
			                        
                    '</div>');
                    var sc = '' + data["TN"][i]["SC"];
                    var po = data["TN"][i]["PO"];
                    item.find(".sc").html(sc.substr(0,7));
                    item.find(".wd").html(data["TN"][i]["WD"]+'('+blackword[po]+')');
                    if(i<20)
                    {
						item.attr("style","background-color:#CAFFB3");
                    }
                    $("#segmentwordlist").append(item);
				}
				
			},
			error: function(e)
			{
				$("#segmentwordlist").html("分词失败");
			}

			});
		$("#segmentwordlist").html("分词进行中。。。");
		
	}

	function AddBlackPos(po)
	{
		$.ajax({
				type:"get",
				url: "/segment/AddBlackPos",
				data:{'word_id':po,'table':'segment_blackword'},
				success:function(data)
				{
					var pos = '.po'+po;
					$(pos).each(
							function(index, element)
							{
								$(this).attr("style","display:none");
							});
				},
				error:function(e)
				{
					alert("添加失败");
				}
			});
	}

	function AddStopWord(word, i)
	{
		
		$.ajax({
			type:"get",
			url: "/segment/AddStopWord",
			data:{'word':word, 'table':'segment_stopword'},
			success:function(data)
			{
				var pos = '#wd'+i;
				$(pos).attr("style","display:none");
				
			},
			error:function(e)
			{
				alert("添加失败");
			}
		});
	}



</script>
