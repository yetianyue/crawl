
<html>

<head>
<title>快豹</title>
</head>
<body>

<?php 
	if(!empty($error))
	{
		echo "<p>错误原因：</p>";
		foreach ($error as $key=>$val)
		{
			echo "<p>{$val}</p>";
		}
		
	}
	
	if(!empty($resultList))
	{
		foreach($resultList as $key=>$val)
		{
			$content = json_decode($val["content"],TRUE);
			if(empty($content["title"]))
			{
				echo "<p>标题为空</p>";
			}
			
			
			if(empty($content["content"]))
			{
				echo "<p>内容为空</p>";
			}
			
			if(empty($content["url"]))
			{
				echo "<p>url为空</p>";
			}
			
			if(empty($content["pubtime"]))
			{
				echo "<p>pubtime为空</p>";
			}
			
		}
		var_dump($resultList);
	}

?>

</body>
</html>