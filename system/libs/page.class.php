<?php

class page
{
    private $total;
    private $num;
    private $url;
    private $limit;
    private $page;
    private $pagetotal;
    private $pageurl;

    public function config($total, $num, $pageurl = NULL)
    {
        $this->total = $total;
        $this->num = $num;
        $this->pageurl = $pageurl;
        $this->pagetotal = ceil($this->total / $this->num);
        $this->page = $this->GetPageNum();
        $this->limit = $this->setlimit();
    }

    private function css()
    {
        $html = "<style>";
        $html .= "#Page_Ul{float:left;}";
        $html .= "#Page_Ul li{float:left;display:block}";
        $html .= "#Page_Ul #Page_Total{border:1px solid #ccc;padding:5px 8px;}";
        $html .= "#Page_Ul li a{padding:5px 8px; border:1px solid #ccc;}";
        $html .= "</style>";
        echo $html;
    }

    public function show($style = "", $css = false, $ret = "")
    {
        $css ? $this->css() : false;
        $style = strtolower(trim($style));

        switch ($style) {
        case "one":
            return $this->ordinary($ret);
            break;

        case "two":
            return $this->pagelist($ret);
            break;

        default:
            return $this->pagelist($ret);
            break;
        }
    }

    private function ordinary($ret)
    {
        $Prev = $this->page - 1;
        $next = $this->page + 1;
        $html_l = "<ul id='Page_Ul'>";
        $html = "";

        if ($Prev != 0) {
            $html .= "<li id='Page_Prev'><a href=\"{$this->url[0]}$Prev{$this->url[1]}\">上一页</a></li>";
        }
        else {
            $html .= "<li id='Page_Prev'><a href=\"javascript:void(0);\">上一页</a></li>";
        }

        if ($next <= $this->pagetotal) {
            $html .= "<li id='Page_Next'><a href=\"{$this->url[0]}$next{$this->url[1]}\">下一页</a></li>";
        }
        else {
            $html .= "<li id='Page_Next'><a href=\"javascript:void(0);\">下一页</a></li>";
        }

        $html .= "<li id='Page_One'><a href=\"{$this->url[0]}1{$this->url[1]}\">首页</a></li>";
        $html .= "<li id='Page_End'><a href=\"{$this->url[0]}$this->pagetotal{$this->url[1]}\">尾页</a></li>";
        $html_r = "</ul>";

        if ($this->total == 0) {
            return NULL;
        }
        else if ($ret == "li") {
            return $html;
        }
        else {
            return $html_l . $html . $html_r;
        }
    }

    private function pagelist($ret)
    {
        $listnum = floor(7 / 2);
        $html_l = "<ul id='Page_Ul'>";
        $html = "";
        $html .= "<li id='Page_Total'>$this->total条";
        $html .= "<li id='Page_One'><a href=\"{$this->url[0]}1{$this->url[1]}\">首页</a></li>";

        if ($this->page == 1) {
            $html .= "<li id='Page_Prev'><a href=\"{$this->url[0]}" . $this->page . $this->url[1] . "\">上一页</a></li>";
        }
        else {
            $html .= "<li id='Page_Prev'><a href=\"{$this->url[0]}" . ($this->page - 1) . $this->url[1] . "\">上一页</a></li>";
        }

        for ($i = $listnum; 1 <= $i; $i--) {
            $page = $this->page - $i;

            if ($page < 1) {
                continue;
            }
            else {
                $html .= "<li class='Page_Num'><a href=\"{$this->url[0]}$page{$this->url[1]}\">$page</a></li>";
            }
        }

        $html .= "<li class='Page_This'>$this->page</li>";

        for ($i = 1; $i <= $listnum; $i++) {
            $page = $this->page + $i;

            if ($page <= $this->pagetotal) {
                $html .= "<li class='Page_Num'><a href=\"{$this->url[0]}$page{$this->url[1]}\">$page</a></li>";
            }
            else {
                continue;
            }
        }

        if ($this->page == $this->pagetotal) {
            $html .= "<li id='Page_Next'><a href=\"{$this->url[0]}" . $this->page . $this->url[1] . "\">下一页</a></li>";
        }
        else {
            $html .= "<li id='Page_Next'><a href=\"{$this->url[0]}" . ($this->page + 1) . $this->url[1] . "\">下一页</a></li>";
        }

        $html .= "<li id='Page_End'><a href=\"{$this->url[0]}$this->pagetotal{$this->url[1]}\">尾页</a></li>";
        $html_r = "</ul>";

        if ($this->total == 0) {
            return NULL;
        }
        else if ($ret == "li") {
            return $html;
        }
        else {
            return $html_l . $html . $html_r;
        }
    }

    private function GetPageNum()
    {
        $url = G_PARAM_URL;
        $Rconfig = System::load_sys_config("param");

        if (isset($_GET[$Rconfig["page_q"]])) {
            $url = preg_replace("/&" . $Rconfig["page_q"] . "=([0-9]{1,10})/i", "&page=", $url);
            $this->url = array(WEB_PATH . "/" . $url, "");
            return abs($_GET[$Rconfig["page_q"]]);
        }

        preg_match("/\/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $url, $matches);

        if (isset($matches[1])) {
            $page = abs($matches[1]);
            $url = explode($matches[0], $url);
        }
        else {
            $page = 1;
            $url = array(G_PARAM_URL, "");
        }

        $this->url = array(WEB_PATH . "/" . $url[0] . "/" . $Rconfig["page_p"], $url[1]);
        return $page;
    }

    private function geturl()
    {
        $rouyt = ROUTE_M . "/" . ROUTE_C . "/" . ROUTE_A;

        if ($rouyt != $this->pageurl) {
            if (strpos($this->pageurl, "-") === false) {
                $this->pageurl = preg_replace("/\//", "-1/", $this->pageurl, 1);
            }
        }

        $urls = WEB_PATH . "/" . $this->pageurl;
        $urls = explode("-" . $this->page, $urls);

        if (!isset($urls[1])) {
            $urls[1] = "";
        }

        return $url = array($urls[0] . "-", $urls[1]);
        $url = array("", "");
        $urls = WEB_PATH . "/" . $this->pageurl;
        $urls = trim($urls, "/");
        $parse = parse_url($urls);

        if (isset($parse["query"])) {
            parse_str($parse["query"], $parses);
            unset($parses["p"]);

            if (empty($parses)) {
                $urls = $parse["path"] . "?";
            }
            else {
                $urls = $parse["path"] . "?" . http_build_query($parses) . "&";
                $urls = str_ireplace("%2f", "/", $urls);
                $urls = str_ireplace("=&", "/&", $urls);
            }
        }
        else {
            $urls = $parse["path"] . "?";
        }

        $urls = preg_replace("#\/\/#", "/", $urls);
        $url[0] = $urls . "p=";
        return $url;
    }

    public function setlimit($all = 0)
    {
        return $all == 1 ? (($this->page - 1) * $this->num) . "," . $this->num : " LIMIT " . (($this->page - 1) * $this->num) . "," . $this->num . " ";
    }

    public function __get($value)
    {
        return $this->$value;
    }
}


?>
