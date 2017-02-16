<div style="margin:300px auto;width:600px;">
    <div class="input-group">
        <input type="text" class="form-control" id="searchbox" onkeydown="javascript:if(event.keyCode == 13){OnSearch();}"/>
        <span class="input-group-btn"><button class="btn btn-default" type="button" onclick="javascript:OnSearch();">搜索</button><span>
    </div>
<div>
<script type="text/javascript">

function OnSearch()
{
    var query = $('#searchbox').val();
    if(query=='')
    {
        return;
    }
    window.location.href = "/search/OnSearch?query="+query+"&page=1"; 
}

$(document).ready(function(){
    $('#searchbox').focus();
});
</script>
