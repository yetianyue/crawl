var CONFIG_SUM = 0;
var FILTER_SUM = 0;
var nameArr = ['title','content','contentStart','contentEnd','pubtime','source','author','count']; 

function AddConfigItem()
{
    ++CONFIG_SUM;
    var ele = $('<tr id=Config_'+ CONFIG_SUM +'>'+
        '<td style="text-align:right">'+
            '<span style="width:100px;text-align:right;height34px;line-height:34px;">自定义'+ CONFIG_SUM +'：</span>'+
            '<br>'+
            '<span style="margin-right:30px;margin-top:15px;cursor:pointer;" onclick="javascript:DeleteConfigItem(this);" class="glyphicon glyphicon-trash"></span>'+
        '</td>'+
        '<td>'+
            '<div class="ConfigItem">'+
            '<div class="input-group has-feedback">'+
            '<span class="input-group-addon">@</span>'+
            '<input id="ConfigName" type="text" class="form-control" placeholder="请输入字段名">'+
            '<span class="form-control-feedback"></span>'+
            '</div>'+
            '<div class="input-group has-feedback" style="margin-top: 5px;">'+
            '<span class="input-group-addon">$</span>'+
            '<input id="ConfigDom" type="text" class="form-control" placeholder="请输入筛选路径">'+
            '<span class="form-control-feedback"></span>'+
            '</div>'+
            '</div>'+
        '</td>'+
        '</tr>');

    var config = $('#detail_table');
    config.append(ele);

}

function GetConfigItem()
{
    var nameArrTmp = nameArr.concat();
    var configArray = new Object();
    var errorFlag = 0;
    $('.ConfigItem').each(function(index)
    {
        var name = $(this).find('#ConfigName').val();
        var dom = $(this).find('#ConfigDom').val();
        if(name == '' || dom == '' || nameArrTmp.indexOf(name) != -1)
        {
            errorFlag = 1;
            $(this).parent().addClass("form-group has-error");
            return;
        }
        else
        {
            $(this).parent().removeClass("form-group has-error");
            nameArrTmp.push(name);
            configArray[name] = dom;
        }
    });
    nameArrTmp = [];

    if(errorFlag == 1)
    {
        return;
    }
    var configStr = JSON.stringify(configArray);
    return configStr;
    
}

function AddFilterItem()
{
    
    ++FILTER_SUM;
    var ele = $('<tr id=Config_'+ FILTER_SUM +'>'+
        '<td style="text-align:right">'+
            '<span style="width:100px;text-align:right;height34px;line-height:34px;">过滤'+ FILTER_SUM +'：</span>'+
            '<br>'+
            '<span style="margin-right:30px;margin-top:15px;cursor:pointer;" onclick="javascript:DeleteConfigItem(this);" class="glyphicon glyphicon-trash"></span>'+
        '</td>'+
        '<td>'+
            '<div class="FilterItem">'+
            '<div class="input-group has-feedback" style="margin-top: 5px;">'+
            '<span class="input-group-addon">$</span>'+
            '<input id="FilterDom" type="text" class="form-control" placeholder="请输入过滤关键字">'+
            '<span class="form-control-feedback"></span>'+
            '</div>'+
            '</div>'+
        '</td>'+
        '</tr>');

    var config = $('#filter_table');
    config.append(ele);
    
}

function GetFilterItem()
{
    var configArray = new Object();
    var errorFlag = 0;
    var i = 0;
    $('.FilterItem').each(function(index)
    {
        var dom = $(this).find('#FilterDom').val();
        if( dom == '' )
        {
            errorFlag = 1;
            $(this).parent().addClass("form-group has-error");
            return;
        }
        else
        {
            $(this).parent().removeClass("form-group has-error");
            configArray[i] =  dom;
            ++i;
        }
    });

    if(errorFlag == 1)
    {
        return;
    }
    var configStr = JSON.stringify(configArray);
    return configStr;
    
}

function AddDataCleanConfigItem(start)
{
    if(start)
    {
     CONFIG_SUM=start;
    }
    else
    {
        ++CONFIG_SUM;
    }
    var ele = $('<tr id=Config_'+ CONFIG_SUM +'>'+
        '<td style="text-align:right">'+
            '<span style="width:100px;text-align:right;height34px;line-height:34px;">过滤'+ CONFIG_SUM +'：</span>'+
            '<br>'+
            '<span style="margin-right:30px;margin-top:15px;cursor:pointer;" onclick="javascript:DeleteConfigItem(this);" class="glyphicon glyphicon-trash"></span>'+
        '</td>'+
        '<td>'+
            '<div class="ConfigItem">'+
            '<div class="input-group has-feedback" style="margin-top: 5px;">'+
            '<span class="input-group-addon">$</span>'+
            '<input id="ConfigDom" type="text" class="form-control" placeholder="请输入过滤关键字">'+
            '<span class="form-control-feedback"></span>'+
            '</div>'+
            '</div>'+
        '</td>'+
        '</tr>');

    var config = $('#detail_table');
    config.append(ele);

}


var CONFIG_FILTER_SUM = 0;
function GetDatacleanConfigItem()
{
    var configArray = new Object();
    var errorFlag = 0;
    $('.ConfigItem').each(function(index)
    {
        var dom = $(this).find('#ConfigDom').val();
        if( dom == '' )
        {
            errorFlag = 1;
            $(this).parent().addClass("form-group has-error");
            return;
        }
        else
        {
            $(this).parent().removeClass("form-group has-error");
            configArray[CONFIG_FILTER_SUM] = dom;
            ++CONFIG_FILTER_SUM;
        }
    });

    if(errorFlag == 1)
    {
        return;
    }
    var configStr = JSON.stringify(configArray);
    return configStr;
    
}


function DeleteConfigItem(ele)
{
    $(ele).parent().parent().remove();
}

