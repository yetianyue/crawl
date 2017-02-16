<style>
.main{
  margin-left: 35px;
}
.nums {
  margin-top:10px;
  margin-left:14px;
  margin-bottom:0px;
  font-size: 12px;
  color: #999;
}
.page{
  margin-bottom: 20px;
}

.item {
  margin-bottom:10px;
  width:600px;
}

.title{
  font-size: 16px;
  font-weight: normal;
  line-height: 26px;
  zoom: 1;
  white-space: nowrap;
  word-wrap: normal;
}

.info {
  color: #008000;
  font-size: 12px;
}

.sim {
  color: #666666;
}

.page {
  margin-left:18px;
}

</style>
<div class="main">
<div style="width:600px;margin:14px" class="input-group has-feedback" >
    <input type="text" class="form-control" id="searchbox" value="<?php echo $query ?>" onkeydown="javascript:if(event.keyCode == 13){OnSearch();}">
    <span class="input-group-btn"><button class="btn btn-default" type="button" onclick="javascript:OnSearch()">搜索</button></span>
</div>
<div >
    <div class="nums">
        共找到结果<?php echo $total?>个&nbsp耗时<?php echo $time?>秒
    </div>
    <div class="panel-body">
            <?php if(!empty($resultList)):?>
            <?php foreach($resultList as $key=>$val): ?>
            <div class="item">
                <div class="title">
                    <a target="_blank" href="<?php echo $val["url"];?>"><?php echo $val["title"]?></a></br>
                </div>
                <div class="info">
                    <?php echo $val["src"]?>&nbsp<?php echo $val["pubtime"]?>&nbsp<?php echo $val["url"]?>&nbsp
                    <a class="sim" target="_blank" href="<?php echo $val["sim_url"];?>">相似文章</a></br>
                </div>
            </div>
            <?php endforeach;?>
            <?php endif;?>    
    </div>

    <nav class="page">
    <ul class="pagination" style="margin:0px;">
        <li>
        <a href="javascript:GetPreviousPage()" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
        </a>
        </li>
        <?php for($i=$pageInfo["start"];$i<=$pageInfo["end"];$i++):?>
            <?php if($i==$pageInfo["curPage"]):?>
                <li><a href="#" style="color:black"><strong><?php echo $i?></strong></a></li>
            <?php else:?>
                <li><a href="<?php echo $pageInfo["url"].$i?>"><?php echo $i?></a></li>
            <?php endif;?>
        <?php endfor;?>
        <li>
        <a href="javascript:GetNextPage()" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
        </a>
        </li>
    </ul>
    </nav>
</div>
</div>

<script type="text/javascript">

function OnSearch()
{
    var query = $('#searchbox').val();
    window.location.href = "/search/OnSearch?query="+query+"&page=1"; 
}

function GetPreviousPage()
{
    var curPage = <?php echo $pageInfo["curPage"]?>;
    if (curPage == 1)
    { 
        return;
    }
    else
    {
        var prePage = curPage - 1;
        var query = <?php echo $query?>;
        window.location.href = "/search/OnSearch?query="+query+"&page="+ prePage;
    }
}

function GetNextPage()
{
    var curPage = <?php echo $pageInfo["curPage"]?>;
    var end = <?php echo $pageInfo["end"]?>;
    if (curPage == end)
    {
        return;
    }
    else
    {
        var nextPage = curPage + 1;
        var query = <?php echo $query?>;
        window.location.href = "/search/OnSearch?query="+query+"&page="+ nextPage; 
    }
}
$(document).ready(function(){
    $('#searchbox').focus();
});
</script>
