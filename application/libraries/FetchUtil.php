<?php
abstract class Model_Task
{
    public $FetchList = array();
    public $ResultList = array();

    public $IsError = FALSE;
    public $Layer = 0;
    public $ProjectId = 0;
    public $CurrentTaskId = 0;
    public $CurrentUrl = '';
    public $CurrentDomain = '';

    abstract public function OnStart();

    function Fetch($url, $callback, $options = array())
    {
        if(empty($url) || empty($callback))
            return;

        if(substr($url, 0, 7) != "http://")
        {
            if(substr($url, 0, 1) == "/")
                $url = $this->CurrentDomain.substr($url, 1);
            else
                $url = $this->CurrentDomain.$url;
        }

        $this->FetchList[] = array(
            'url' => $url,
            'referer' => $this->CurrentUrl,
            'callback' => $callback,
            'project_id' => $this->ProjectId,
            'options' => $options,
        );
    }

    function AddResult($data)
    {
        $this->ResultList[] = array(
            "project_id" => $this->ProjectId,
            "url" => $this->CurrentUrl,
            "content" => json_encode($data),
        );
    }

    public function GetDomain($url)
    {
        if(empty($url))
            return $this->CurrentDomain;

        preg_match("/http:\/\/[^\/]+\//", $url, $domain);
        return $domain[0];
    }

    function SetError($error = TRUE)
    {
        $this->IsError = $error;
    }

    public function GetUrl()
    {
        return $this->CurrentUrl;
    }

}


class Model_Response
{
    protected $Html;

    public function __construct($html)
    {
		require_once dirname(dirname(__FILE__))."/third_party/simple_html_dom.php";
        $this->Html = $html;
        $this->Dom = new simple_html_dom();
		$html = str_replace("alt=\"\\\"","",$html);
        $this->Dom->load($html);
    }

    public function __destruct()
    {
        $this->Dom->clear();
    }
	
	public function GetDom()
    {
        return $this->Dom;
    }

	public function GetOriHtml()
    {
    	return $this->Html;
    }

    public function GetHtml()
    {
        return $this->Dom->outertext;
    }

    public function Doc($selector)
    {
        return $this->Dom->find($selector);
    }
}
?>