<?php
class DatacleanScript
{
    public static function produce($option)
    {
        $tab = "    ";
        $break = "\n";
        $script = "class ProjectHandle extends Model_Task{$break}{{$break}";
        $script .= "{$tab}public function OnStart(){$break}{$tab}{{$break}";

        if(!empty($option["OnStart"]))
        {
            foreach ($option["OnStart"] as $url=>$age)
            {
                $script .= "{$tab}{$tab}\$this->Fetch('{$url}','DrawContent',array());{$break}";		
            }
        }

        $script .= "{$tab}}{$break}{$break}";
        $script .= "{$tab}public function DrawContent(\$response, \$options){$break}{$tab}{{$break}";

        $script .= "{$tab}{$tab}\$contentsRes = \$response->Doc('".$option['DrawContent']['doc']."');{$break}";


        $script .= "{$tab}{$tab}\$this->DetailPage(\$contentsRes, \$options);{$break}{$tab}}{$break}";


        //DetailPage
        $script .= "{$tab}public function DetailPage(\$responseRes, \$options){$break}{$tab}{{$break}";

        $script .= "{$tab}{$tab}if(!is_array(\$responseRes)){$break}";
        $script .= "{$tab}{$tab}{{$break}";
        $script .= "{$tab}{$tab}{$tab}return ;{$break}";
        $script .= "{$tab}{$tab}}{$break}";

        $script .= "{$tab}{$tab}\$start = ".$option["DetailPage"]["contentStart"].";{$break}";
        $script .= "{$tab}{$tab}\$end = ".$option["DetailPage"]["contentEnd"].";{$break}";
        $script .= "{$tab}{$tab}\$result = array();{$break}";

        $script .= "{$tab}{$tab}if(method_exists(\$responseRes[0],children)){$break}";
        $script .= "{$tab}{$tab}{{$break}";

        $script .= "{$tab}{$tab}{$tab}\$contentChildren = \$responseRes[0]->children();{$break}";
        $script .= "{$tab}{$tab}{$tab}\$contentCount = count(\$contentChildren);{$break}";
        $script .= "{$tab}{$tab}{$tab}for(\$i = \$start; \$i <= (\$contentCount + \$end); ++\$i){$break}";
        $script .= "{$tab}{$tab}{$tab}{{$break}";

        if($option["DetailPage"]["config"])
        {
            $script .= "{$tab}{$tab}{$tab}{$tab}if(\$contentChildren[\$i]->outertext && \$this->isFilter(\$contentChildren[\$i]->outertext)){$break}";
            $script .=  "{$tab}{$tab}{$tab}{$tab}{ {$break}";
            $script .=  "{$tab}{$tab}{$tab}{$tab}{$tab}continue;{$break}";
            $script .=  "{$tab}{$tab}{$tab}{$tab}} {$break}";

        }
        $script .= "{$tab}{$tab}{$tab}{$tab}\$result['content'] .= trim(\$contentChildren[\$i]->outertext);{$break}";
        $script .= "{$tab}{$tab}{$tab}}{$break}";

        $script .= "{$tab}{$tab}{$tab}\$this->AddResult(\$result);{$break}{$tab}{$tab}}{$break}{$tab}}";


        $script .= "{$break}";
        $script .= "{$break}";
        if($option["DetailPage"]["config"])
        {
            $script .= "{$tab}public function isFilter(\$text){$break}";
            $script .="{$tab}{{$break}";

            $tmp = "{$tab}{$tab}\$fileArr = array(";

            foreach($option["DetailPage"]["config"] as $key=>$value )
            {

                if(trim($value))
                {

                    $tmp .= "'{$value}',";
                }
            }
            $tmp = trim($tmp,',');
            $tmp .= ");{$break}";

            $script .= $tmp;
            $script .="{$tab}{$tab}foreach(\$fileArr as \$key=>\$value){$break}";

            $script .="{$tab}{$tab}{{$break}";
            $script .="{$tab}{$tab}{$tab}if(!(strpos(\$text,\$value) === false)){$break}";
            $script .="{$tab}{$tab}{$tab}{{$break}";

            $script .="{$tab}{$tab}{$tab}{$tab}return true;{$break}";
            $script .="{$tab}{$tab}{$tab}}{$break}";
            $script .="{$tab}{$tab}}{$break}";
            $script .="{$tab}{$tab}return false;{$break}";
            $script .="{$tab}}{$break}";
        }

        $script .= "{$break}}{$break}{$break}";

        return $script;
    }

	
}


