<?php

class sendmobile
{
    public $error = "";
    public $v = "";
    private $client;
    private $mobile;
    private $config;
    private $op;

    public function init($config = NULL, $mobiles = NULL, $key = NULL)
    {
        if (!$config) {
            return false;
        }

        if ($config["mobile"] == NULL) {
            return false;
        }

        if ($config["content"] == NULL) {
            return false;
        }

        $this->config = $config;

        if (!$mobiles) {
            $this->mobile = System::load_sys_config("mobile");
        }

        if (intval($key) && isset($this->mobile["cfg_mobile_" . $key]) && method_exists($this, "cfg_seting_" . $key)) {
            $op = $key;
            $func = "cfg_seting_" . $key;
        }
        else {
            $op = $this->mobile["cfg_mobile_on"];
            $func = "cfg_seting_" . $this->mobile["cfg_mobile_on"];
        }

        $this->op = $op;
        return $this->{$func}();
    }

    public function send()
    {
        $func = "cfg_send_" . $this->op;
        return $this->{$func}();
    }

    private function cfg_seting_1()
    {
        return true;
    }

    private function cfg_send_1()
    {
        $mobile = $this->mobile["cfg_mobile_1"];
        $name = urlencode($mobile["mid"]);
        $pwd = $mobile["mpass"];
        $haoma = $this->config["mobile"];
        $content = iconv("UTF-8", "gb2312//IGNORE", $this->config["content"]);
        $content = urlencode($content);
        $fp = fopen("http://203.81.21.34/send/gsend.asp?name=$name&pwd=$pwd&dst=$haoma&msg=$content", "r");

        if (!$fp) {
            $fp = fopen("http://203.81.21.13/send/gsend.asp?name=$name&pwd=$pwd&dst=$haoma&msg=$content", "r");
        }

        if (!$fp) {
            fclose($fp);
            $this->error = -1;
            $this->v = "打开文件发送失败";
            return NULL;
        }

        $ret = "";

        while (!feof($fp)) {
            $ret .= fgets($fp, 1024);
        }

        if ($ret) {
            $ret = $this->exp_url($ret);
            $this->error = $ret["num"];
            $this->v = $ret["err"];
        }
        else {
            $this->error = -1;
            $this->v = "未获取到返回值";
            return NULL;
        }

        return $ret;
    }

    public function cfg_getdata_1()
    {
        $this->mobile = System::load_sys_config("mobile");
        $mobile = $this->mobile["cfg_mobile_1"];
        $name = urlencode($mobile["mid"]);
        $pwd = $mobile["mpass"];
        $fp = fopen("http://203.81.21.34/send/getfee.asp?name=$name&pwd=$pwd", "r");

        if (!$fp) {
            $fp = fopen("http://203.81.21.13/send/getfee.asp?name=$name&pwd=$pwd", "r");
        }

        if (!$fp) {
            $fp = fopen("http://www.139000.com/send/getfee.asp?name=$name&pwd=$pwd", "r");
        }

        if (!$fp) {
            fclose($fp);
            return array("-1", "打开文件发送失败");
        }

        $ret = "";

        while (!feof($fp)) {
            $ret .= fgets($fp, 1024);
        }

        if ($ret) {
            $ret = $this->exp_url($ret);
        }
        else {
            return array("-1", "未获取到返回值");
        }

        if (($ret["id"] == "-9999") || ($ret["id"] == "0")) {
            $ret["id"] = 0;
        }
        else {
            $ret["id"] = intval($ret["id"]) / 10;
        }

        $this->v = $ret["id"];
        $this->error = $ret["errid"];
        return $ret;
    }

    private function cfg_seting_2()
    {
        return true;
    }

    private function cfg_send_2()
    {
        $mobile = $this->mobile["cfg_mobile_2"];
        $config = $this->config;
        $config["sn"] = $mobile["mid"];
        $config["pwd"] = strtoupper(md5($mobile["mid"] . $mobile["mpass"]));
        $params = "";
        $config["content"] = iconv("UTF-8", "gb2312//IGNORE", $config["content"] . $mobile["mqianming"]);
        $argv = $config;
        $flag = 0;

        foreach ($argv as $key => $value ) {
            if ($flag != 0) {
                $params .= "&";
                $flag = 1;
            }

            $params .= $key . "=";
            $params .= urlencode($value);
            $flag = 1;
        }

        $length = strlen($params);
        ($fp = fsockopen("sdk2.zucp.net", 80, $errno, $errstr, 10)) || exit($errstr . "--->" . $errno);
        $header = "POST /webservice.asmx/mt HTTP/1.1\r\n";
        $header .= "Host:sdk2.zucp.net\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . $length . "\r\n";
        $header .= "Connection: Close\r\n\r\n";
        $header .= $params . "\r\n";
        fputs($fp, $header);
        $inheader = 1;

        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && (($line == "\n") || ($line == "\r\n"))) {
                $inheader = 0;
            }

            if ($inheader == 0) {
            }
        }

        $line = str_replace("<string xmlns=\"http://tempuri.org/\">", "", $line);
        $line = str_replace("</string>", "", $line);
        $result = explode("-", $line);

        if (1 < count($result)) {
            $this->v = $line;
            $this->error = -1;
        }
        else {
            $this->v = $line;
            $this->error = 1;
        }
    }

    public function cfg_getdata_2()
    {
        $this->mobile = System::load_sys_config("mobile");
        $flag = 0;
        $mobile = $this->mobile["cfg_mobile_2"];
        if (($mobile["mid"] == NULL) || ($mobile["mpass"] == NULL)) {
            $this->error = -2;
            $this->v = "短信账户或者密码不能为空!";
            return NULL;
        }

        $argv = array("sn" => $mobile["mid"], "pwd" => $mobile["mpass"]);
        $params = "";

        foreach ($argv as $key => $value ) {
            if ($flag != 0) {
                $params .= "&";
                $flag = 1;
            }

            $params .= $key . "=";
            $params .= urlencode($value);
            $flag = 1;
        }

        $length = strlen($params);
        ($fp = fsockopen("sdk2.zucp.net", 8060, $errno, $errstr, 10)) || exit($errstr . "--->" . $errno);
        $header = "POST /webservice.asmx/GetBalance HTTP/1.1\r\n";
        $header .= "Host:sdk2.zucp.net:8060\r\n";
        $header .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header .= "Content-Length: " . $length . "\r\n";
        $header .= "Connection: Close\r\n\r\n";
        $header .= $params . "\r\n";
        fputs($fp, $header);
        $inheader = 1;

        while (!feof($fp)) {
            $line = fgets($fp, 1024);
            if ($inheader && (($line == "\n") || ($line == "\r\n"))) {
                $inheader = 0;
            }

            if ($inheader == 0) {
            }
        }

        $line = str_replace("<string xmlns=\"http://tempuri.org/\">", "", $line);
        $line = str_replace("</string>", "", $line);
        $result = explode("-", $line);

        if (1 < count($result)) {
            $this->v = $line;
            $this->error = -1;
        }
        else {
            $this->v = $line;
            $this->error = 1;
        }

        return array($this->error, $this->v);
    }

    private function cfg_seting_3()
    {
        return true;
    }

    private function cfg_send_3($post_data = NULL, $target = NULL, $get_key = NULL)
    {
        $config = $this->config;
        $account = $this->mobile["cfg_mobile_3"]["mid"];
        $password = $this->mobile["cfg_mobile_3"]["mpass"];
        $config["content"] = rawurlencode($config["content"]);

        if (!$get_key) {
            $post_data = "account=$account&password=$password&mobile=" . $config["mobile"] . "&content=" . $config["content"];
            $target = "http://106.ihuyi.cn/webservice/sms.php?method=Submit";
        }

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $target);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        $return_str = curl_exec($curl);
        curl_close($curl);
        $arr = _xml_to_array($return_str);

        if ($get_key) {
            $this->error = $arr["GetNumResult"]["code"];
            $this->v = $arr["GetNumResult"]["num"];
            return $arr;
        }

        if ($arr["SubmitResult"]["code"] == 2) {
            $this->v = $arr["SubmitResult"]["msg"];
            $this->error = 1;
        }
        else {
            $this->v = $arr["SubmitResult"]["msg"];
            $this->error = -1;
        }

        return $arr;
    }

    public function cfg_getdata_3()
    {
        $this->mobile = System::load_sys_config("mobile");
        $account = $this->mobile["cfg_mobile_3"]["mid"];
        $password = $this->mobile["cfg_mobile_3"]["mpass"];
        $post_data = "account=$account&password=$password";
        $target = "http://106.ihuyi.cn/webservice/sms.php?method=GetNum";
        return $this->cfg_send_3($post_data, $target, true);
    }

    private function mobile_con_check($content = NULL)
    {
        $this->mobile = $mobile = System::load_sys_config("mobile");
        $mobile = $this->mobile["cfg_mobile_1"];
        $name = urlencode($mobile["mid"]);
        $pwd = $mobile["mpass"];
        $content = iconv("UTF-8", "gb2312//IGNORE", $content);
        $content = urlencode($content);
        $con_check = fopen("http://www.139000.com/send/checkcontent.asp?name=$name&pwd=$pwd&content=$content", "r");

        if (!$con_check) {
            fclose($con_check);
        }

        $rets = "";

        while (!feof($con_check)) {
            $rets .= fgets($con_check, 1024);
        }

        if ($rets) {
            $rets = $this->exp_url($rets);

            if ($rets["errid"] == "0") {
                return array("1", "新短信接口内容合法");
            }
            else {
                return array("-1", "内容检测失败,不能包含:【" . $rets["err"] . "】");
            }
        }
        else {
            return array("-1", "检测失败");
        }
    }

    private function cfg_seting_4()
    {
        require_once G_PLUGIN . DIRECTORY_SEPARATOR . "Smessage" . DIRECTORY_SEPARATOR . "Emay" . DIRECTORY_SEPARATOR . "Client.php";
        $this->mobile = System::load_sys_config("mobile");
        $serialNumber = $this->mobile["cfg_mobile_4"]["mid"];
        $num = explode("-", $serialNumber);

        if ($num[2] == "6699") {
            $gwUrl = "http://hprpt2.eucp.b2m.cn:8080/sdk/SDKService?wsdl";
        }
        else {
            $gwUrl = "http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService";
        }

        $password = $this->mobile["cfg_mobile_4"]["mpass"];

        if ($num[2] == "6699") {
            $sessionKey = "123456";
        }
        else {
            $sessionKey = $this->mobile["cfg_mobile_4"]["mpass"];
        }

        $connectTimeOut = 2;
        $readTimeOut = 10;
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;
        $this->client = new Client($gwUrl, $serialNumber, $password, $sessionKey, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);
        $this->client->setOutgoingEncoding("utf-8");

        if (empty($this->mobile["cfg_mobile_switch_4"])) {
            $statusCode = $this->client->login();
            $html = file_get_contents(G_CONFIG . DIRECTORY_SEPARATOR . "mobile.inc.php");
            str_replace("'cfg_mobile_on' => 4,", "'cfg_mobile_on' => 4,\n'cfg_mobile_switch_4' => 'on'\n", $html);
        }

        if (($statusCode != NULL) && ($statusCode == "0")) {
            $this->v = $statusCode;
            $this->error = 1;
        }
        else {
            $this->v = $statusCode;
            $this->error = -1;
        }

        return array($this->v, $this->error);
    }

    private function cfg_send_4($post_data = NULL, $target = NULL, $get_key = NULL)
    {
        $statusCode = $this->client->sendSMS(array($this->config["mobile"]), $this->mobile["cfg_mobile_4"]["mqianming"] . $this->config["content"]);
        if (($statusCode != NULL) && ($statusCode == "0")) {
            $this->v = $statusCode;
            $this->error = 1;
        }
        else {
            $this->v = $statusCode;
            $this->error = -1;
        }

        return array($this->v, $this->error);
    }

    public function cfg_getdata_4()
    {
        require_once G_PLUGIN . DIRECTORY_SEPARATOR . "Smessage" . DIRECTORY_SEPARATOR . "Emay" . DIRECTORY_SEPARATOR . "Client.php";
        $this->mobile = System::load_sys_config("mobile");
        $serialNumber = $this->mobile["cfg_mobile_4"]["mid"];
        $num = explode("-", $serialNumber);

        if ($num[2] == "6699") {
            $gwUrl = "http://hprpt2.eucp.b2m.cn:8080/sdk/SDKService?wsdl";
        }
        else {
            $gwUrl = "http://sdk4report.eucp.b2m.cn:8080/sdk/SDKService";
        }

        $password = $this->mobile["cfg_mobile_4"]["mpass"];

        if ($num[2] == "6699") {
            $sessionKey = "123456";
        }
        else {
            $sessionKey = $this->mobile["cfg_mobile_4"]["mpass"];
        }

        $connectTimeOut = 2;
        $readTimeOut = 10;
        $proxyhost = false;
        $proxyport = false;
        $proxyusername = false;
        $proxypassword = false;
        $this->client = new Client($gwUrl, $serialNumber, $password, $sessionKey, $proxyhost, $proxyport, $proxyusername, $proxypassword, $connectTimeOut, $readTimeOut);
        $tiaoshu = $this->client->getBalance();
        $this->v = $tiaoshu * 10;
        $this->error = 1;
        return array($this->v, $this->error);
    }

    /* 安徽创瑞 */
    private function cfg_seting_5()
    {
        return true;
    }

    private function cfg_send_5()
    {
        $flag   = 0; 
        $params = '';//要post的数据 
        $mobile = $this->mobile["cfg_mobile_5"];
        $name   = $mobile["mid"];
        $pwd    = $mobile["mpass"];
        $sign   = $mobile['mqianming'];
        $phone_number = $this->config["mobile"];
        $content = $this->config["content"];
        dump( $content );
        
        $argv = array( 
            'name'    => $name,     //必填参数。用户账号
            'pwd'     => $pwd,     //必填参数。（web平台：基本资料中的接口密码）
            'content' => $content,   //必填参数。发送内容（1-500 个汉字）UTF-8编码
            'mobile'  => $phone_number,   //必填参数。手机号码。多个以英文逗号隔开
            'stime'   => '',   //可选参数。发送时间，填写时已填写的时间发送，不填时为当前时间发送
            'sign'    => $sign,    //必填参数。用户签名。
            'type'    => 'pt',  //必填参数。固定值 pt
            'extno'   => ''    //可选参数，扩展码，用户定义扩展码，只能为数字
        ); 

        foreach ( $argv as $key=>$value ) { 
            if ( $flag != 0 ) { 
                $params .= "&"; 
                $flag = 1; 
            } 
            $params.= $key."="; $params.= urlencode($value);// urlencode($value); 
            $flag = 1; 
        } 
        $url = "http://web.cr6868.com/asmx/smsservice.aspx?".$params; //提交的url地址
        $con = substr( file_get_contents($url), 0, 1 );  //获取信息发送后的状态
        if ( $con == '0' ) {
            $this->error = 1;
            $this->v = 1;
        }
        else {
            $this->error = -1;
            $this->v = "未获取到返回值";
            return NULL;
        }

        return $ret;
    }

    public function cfg_getdata_5()
    {
        $this->mobile = System::load_sys_config("mobile");
        $mobile = $this->mobile["cfg_mobile_1"];
        $name = urlencode($mobile["mid"]);
        $pwd = $mobile["mpass"];
        $fp = fopen("http://203.81.21.34/send/getfee.asp?name=$name&pwd=$pwd", "r");

        if (!$fp) {
            $fp = fopen("http://203.81.21.13/send/getfee.asp?name=$name&pwd=$pwd", "r");
        }

        if (!$fp) {
            $fp = fopen("http://www.139000.com/send/getfee.asp?name=$name&pwd=$pwd", "r");
        }

        if (!$fp) {
            fclose($fp);
            return array("-1", "打开文件发送失败");
        }

        $ret = "";

        while (!feof($fp)) {
            $ret .= fgets($fp, 1024);
        }

        if ($ret) {
            $ret = $this->exp_url($ret);
        }
        else {
            return array("-1", "未获取到返回值");
        }

        if (($ret["id"] == "-9999") || ($ret["id"] == "0")) {
            $ret["id"] = 0;
        }
        else {
            $ret["id"] = intval($ret["id"]) / 10;
        }

        $this->v = $ret["id"];
        $this->error = $ret["errid"];
        return $ret;
    }

    private function exp_url($url = "")
    {
        if (!empty($url)) {
            $ret = iconv("GB2312", "UTF-8", $url);
            $ret = explode("&", $ret);

            foreach ($ret as $k => $v ) {
                $v = explode("=", $v);
                $ret[$v[0]] = $v[1];
            }

            return $ret;
        }
        else {
            return false;
        }
    }
}


