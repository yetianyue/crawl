<div style="width:120px;float:left;margin-right:30px;margin-left:20px;">
	<ul class="nav nav-pills nav-stacked">
	
	  <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 14) == '/segment/match') echo "active" ?>">
	   		<a href="/segment/match">相似度匹配</a>
	   </li>
	   <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 22) == '/segment/onlinesegment') echo "active" ?>">
	   		<a href="/segment/onlinesegment">在线分词</a>
	   </li>
	   <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 14) == '/segment/index') echo "active" ?>">
	   		<a href="/segment/index">词库修改</a>
	   </li>
	   
	   <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 16) == '/segment/analyse') echo "active" ?>">
	   		<a href="/segment/analyse">相似度分析</a>
	   </li>
	   
	   <li class="<?php if(substr($_SERVER['REQUEST_URI'], 0, 15) == '/segment/monitor') echo "active" ?>">
	   		<a href="/segment/monitor">监控</a>
	   </li>	   
	   
	</ul>
	
</div>