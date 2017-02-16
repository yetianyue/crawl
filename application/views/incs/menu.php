    <div style="height:60px;">
        <nav class="navbar navbar-fixed-top navbar-inverse navbar-static-top" style="margin-bottom:10px;">
            <div class="navbar-header">
                <a href="/" style="font-family:微软雅黑;" class="navbar-brand">抓取平台</a>
            </div>
            <ul class="nav navbar-nav">
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 12) == '/project/add') echo "active" ?>">
                    <a href="/project/add">新建项目</a>
                </li>
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 14) == '/project/items') echo "active" ?>">
                    <a href="/project/items">项目列表</a>
                </li>
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 11) == '/task/items') echo "active" ?>">
                    <a href="/task/items">任务列表</a>
                </li>
                
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 15) == '/response/items') echo "active" ?>">
                    <a href="/response/items">结果列表</a>
                </li>
                
                 <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 15) == '/task/failitems') echo "active" ?>">
                    <a href="/task/failitems">失败任务表</a>
                </li>
                
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 7) == '/search') echo "active" ?>">
                    <a href="/search">搜索文章</a>
                </li>
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 8) == '/monitor') echo "active" ?>">
                    <a href="/monitor">系统监控</a>
                </li>
                <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 10) == '/dataclean') echo "active" ?>">
                             <a href="/dataclean/add">数据清洗</a>
                </li>
                 <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 8) == '/segment') echo "active" ?>">
                             <a href="/segment/match">数据排重</a>
                </li>
             </ul>
        </nav>
    </div>

<?php

if(substr($_SERVER['REQUEST_URI'], 0, 10) == '/dataclean')
{

$headEle = ' <div style="height:50px;width:100%">
 <ul  class="nav navbar-nav">
                         <li  class="active" >
                             <a href="/dataclean/add">新建项目</a>
                        </li>
                         <li  class="active">
                             <a href="/dataclean/items">项目列表</a>
                        </li>
                         <li  class="active">
                             <a href="/dataclean/result">结果列表</a>
                        </li>
                    </ul>
    </div>



';

echo $headEle;
}

    
?>
