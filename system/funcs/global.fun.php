<?php

function dump($data = NULL, $exit = false)
{
    file_put_contents( 'yusure_debug.txt' , var_export( $data, true ) . PHP_EOL, FILE_APPEND );

    if ($exit) {
        exit();
    }
}

function _callback($fun)
{
    $fun();
}

function L($key = "")
{
    static $lang;
    if ( ! $lang ) 
    {
        $lang = include G_LANGUAGES . "zh_cn.php";
    }

    if ( isset( $lang[$key] ) ) 
    {
        return $lang[$key];
    }
    else 
    {
        return "NULL";
    }
}

function _SendMsgJson($key = "", $val = "", $th = 0)
{
    static $msg;
    if (empty($key) && empty($val) && isset($msg["status"])) {
        exit(json_encode($msg));
    }

    if (empty($key)) {
        exit("{\"status\":\"-1\",\"msg\":\"SendMsgJson key not null!\"}");
    }

    $msg[$key] = $val;

    if ($th == 1) {
        exit(json_encode($msg));
    }
}

function _thumb($url, $size)
{
    return G_UPLOAD_PATH . "/" . $url . "_" . $size . "." . end(explode(".", $url));
}

function filter_exp(&$value)
{
    if (in_array(strtolower($value), array("exp", "or"))) {
        $value .= " ";
    }
}

function _post($name = "")
{
    return _input_filter($_POST, $name);
}

function _get($name = "")
{
    return _input_filter($_GET, $name);
}

function _request($name = "")
{
    return _input_filter($_REQUEST, $name);
}

function _input_filter($input, $name)
{
    array_walk_recursive($input, "filter_exp");

    if (empty($name)) {
        $data = $input;
        $filters = (isset($filter) ? $filter : "htmlspecialchars");

        if ($filters) {
            $filters = explode(",", $filters);

            foreach ($filters as $filter ) {
                $data = @array_map($filter, $data);
            }
        }
    }
    else if (isset($input[$name])) {
        $data = $input[$name];
        $filters = (isset($filter) ? $filter : "htmlspecialchars");

        if ($filters) {
            $filters = explode(",", $filters);

            foreach ($filters as $filter ) {
                if (function_exists($filter)) {
                    $data = (is_array($data) ? @array_map($filter, $data) : $filter($data));
                }
                else {
                    $data = filter_var($data, is_int($filter) ? $filter : filter_id($filter));

                    if (false === $data) {
                        return isset($default) ? $default : NULL;
                    }
                }
            }
        }
    }
    else {
        $data = (isset($default) ? $default : NULL);
    }

    return $data;
}

function _arr2to1($arr, $key, $value)
{
    foreach ($arr as $row ) {
        $tmp[$row[$key]] = $row[$value];
    }

    return $tmp;
}

function _get_file_type($file)
{
    return substr(strrchr($file, "."), 1);
}

function _serialize($obj)
{
    return base64_encode(gzcompress(serialize($obj)));
}

function _unserialize($txt)
{
    return unserialize(gzuncompress(base64_decode($txt)));
}

function _unser($string, $key = "")
{
    $tmp = unserialize($string);

    if ($key != "") {
        return $tmp[$key];
    }
    else {
        return $tmp;
    }
}

function is_php($version = "5.0.0")
{
//  $_is_php = &$_is_php;
    static $_is_php;
    $version = (string) $version;

    if (!isset($_is_php[$version])) {
        $_is_php[$version] = (version_compare(PHP_VERSION, $version) < 0 ? false : true);
    }

    return $_is_php[$version];
}

function new_addslashes($string)
{
    if (!is_array($string)) {
        return addslashes($string);
    }

    foreach ($string as $key => $val ) {
        $string[$key] = new_addslashes($val);
    }

    return $string;
}

function Array2String($Array)
{
    if (!$Array) {
        return false;
    }

    $Return = "";
    $NullValue = "^^^";

    foreach ($Array as $Key => $Value ) {
        if (is_array($Value)) {
            $ReturnValue = "^^array^" . Array2String($Value);
        }
        else {
            $ReturnValue = (0 < strlen($Value) ? $Value : $NullValue);
        }

        $Return .= urlencode(base64_encode($Key)) . "|" . urlencode(base64_encode($ReturnValue)) . "||";
    }

    return urlencode(substr($Return, 0, -2));
}

function String2Array($String)
{
    if (NULL == $String) {
        return false;
    }

    $Return = array();
    $String = urldecode($String);
    $TempArray = explode("||", $String);
    $NullValue = urlencode(base64_encode("^^^"));

    foreach ($TempArray as $TempValue ) {
        list($Key, $Value) = explode("|", $TempValue);
        $DecodedKey = base64_decode(urldecode($Key));

        if ($Value != $NullValue) {
            $ReturnValue = base64_decode(urldecode($Value));

            if (substr($ReturnValue, 0, 8) == "^^array^") {
                $ReturnValue = String2Array(substr($ReturnValue, 8));
            }

            $Return[$DecodedKey] = $ReturnValue;
        }
        else {
            $Return[$DecodedKey] = NULL;
        }
    }

    return $Return;
}

function str_join($op, $str1, $str2 = "", $str3 = "", $str4 = "", $str5 = "")
{
    $str = $str1;

    if (!empty($str2)) {
        $str .= $op . $str2;
    }

    if (!empty($str3)) {
        $str .= $op . $str3;
    }

    if (!empty($str4)) {
        $str .= $op . $str4;
    }

    if (!empty($str5)) {
        $str .= $op . $str5;
    }

    return $str;
}

function _json_encode($array)
{
    return preg_replace("#\\\\u([0-9a-f]{4})#ie", "iconv('UCS-2BE','UTF-8',pack('H4', '\1'))", json_encode($array));
}

function _json_decode($string)
{
    return (array) json_decode($string);
}

function Preg_Files($zhengze = "", $dir = "")
{
    if (empty($dir) || empty($zhengze) || !is_dir($dir)) {
        return array();
    }

    $html_arr = scandir($dir);

    if (!is_array($html_arr)) {
        return array();
    }

    $html = array();

    if (!$zhengze) {
        return array();
    }

    $zhengzes = $zhengze;

    foreach ($html_arr as $html_path ) {
        preg_match($zhengzes, $html_path, $matches);

        if ($matches != NULL) {
            $html[] = $matches;
        }
    }

    if (!count($html)) {
        return array();
    }

    return $html;
}

function filter_html($str)
{
    $str = preg_replace("@<script(.*?)</script>@is", "", $str);
    $str = preg_replace("@<iframe(.*?)</iframe>@is", "", $str);
    $str = preg_replace("@<style(.*?)</style>@is", "", $str);
    $str = preg_replace("@<(.*?)>@is", "", $str);
    return $str;
}

function safe_replace($string)
{
    $string = str_replace("%20", "", $string);
    $string = str_replace("%27", "'", $string);
    $string = str_replace("%2527", "'", $string);
    $string = str_replace("%2a", "*", $string);
    $string = str_replace("\\", "", $string);
    $string = str_replace("*", "", $string);
    $string = str_replace("\"", "&quot;", $string);
    $string = str_replace("'", "\'", $string);
    $string = str_replace(";", "", $string);
    $string = str_replace("<", "&lt;", $string);
    $string = str_replace(">", "&gt;", $string);
    return $string;
}

function get_web_url()
{
    $sys_protocal = (isset($_SERVER["SERVER_PORT"]) && ($_SERVER["SERVER_PORT"] == "443") ? "https://" : "http://");
    $php_self = ($_SERVER["PHP_SELF"] ? safe_replace($_SERVER["PHP_SELF"]) : safe_replace($_SERVER["SCRIPT_NAME"]));
    $path_info = (isset($_SERVER["PATH_INFO"]) ? safe_replace($_SERVER["PATH_INFO"]) : "");
    $relate_url = (isset($_SERVER["REQUEST_URI"]) ? safe_replace($_SERVER["REQUEST_URI"]) : $php_self . (isset($_SERVER["QUERY_STRING"]) ? "?" . safe_replace($_SERVER["QUERY_STRING"]) : $path_info));
    return $sys_protocal . (isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : "") . $relate_url;
}

function get_home_url()
{
    $sys_protocal = (isset($_SERVER["SERVER_PORT"]) && ($_SERVER["SERVER_PORT"] == "443") ? "https://" : "http://");
    $path = explode("/", safe_replace($_SERVER["SCRIPT_NAME"]));

    if (count($path) == 3) {
        return $sys_protocal . $_SERVER["HTTP_HOST"] . "/" . $path[1];
    }

    if (count($path) == 2) {
        return $sys_protocal . $_SERVER["HTTP_HOST"];
    }
}

function editor_safe_replace($content)
{
    $tags = array("'<iframe[^>]*?>.*?</iframe>'is", "'<frame[^>]*?>.*?</frame>'is", "'<script[^>]*?>.*?</script>'is", "'<head[^>]*?>.*?</head>'is", "'<title[^>]*?>.*?</title>'is", "'<meta[^>]*?>'is", "'<link[^>]*?>'is");
    return preg_replace($tags, "", $content);
}

function _htmtocode($content)
{
    $content = str_replace("%", "%&lrm;", $content);
    $content = str_replace("<", "&lt;", $content);
    $content = str_replace(">", "&gt;", $content);
    $content = str_replace("\n", "<br/>", $content);
    $content = str_replace(" ", "&nbsp;", $content);
    $content = str_replace("\"", "&quot;", $content);
    $content = str_replace("'", "&#039;", $content);
    $content = str_replace("\$", "&#36;", $content);
    $content = str_replace("}", "&rlm;}", $content);
    return $content;
}

function _checkmobile($mobilephone = "")
{
    if (strlen($mobilephone) != 11) {
        return false;
    }

    if (preg_match("/^13[0-9]{1}[0-9]{8}$|15[0-9]{1}[0-9]{8}$|14[0-9]{1}[0-9]{8}$|18[0-9]{1}[0-9]{8}$|17[0-9]{1}[0-9]{8}$/", $mobilephone)) {
        return true;
    }
    else {
        return false;
    }
}

function _checkemail($email = "")
{
    if (mb_strlen($email) < 5) {
        return false;
    }

    $res = "/^([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+@([a-zA-Z0-9]+[_|\_|\.]?)*[a-zA-Z0-9]+\.[a-zA-Z]{2,3}$/";

    if (preg_match($res, $email)) {
        return true;
    }
    else {
        return false;
    }
}

function _encrypt($string, $operation = "ENCODE", $key = "", $expiry = 0)
{
    if ($operation == "DECODE") {
        $string = str_replace("_", "/", $string);
    }

    $key_length = 4;
    $key = md5($key != "" ? $key : System::load_sys_config("system", "cryptkey"));
    $fixedkey = md5($key);
    $egiskeys = md5(substr($fixedkey, 16, 16));
    $runtokey = ($key_length ? ($operation == "ENCODE" ? substr(md5(microtime(true)), -$key_length) : substr($string, 0, $key_length)) : "");
    $keys = md5(substr($runtokey, 0, 16) . substr($fixedkey, 0, 16) . substr($runtokey, 16) . substr($fixedkey, 16));
    $string = ($operation == "ENCODE" ? sprintf("%010d", $expiry ? $expiry + time() : 0) . substr(md5($string . $egiskeys), 0, 16) . $string : base64_decode(substr($string, $key_length)));
    $i = 0;
    $result = "";
    $string_length = strlen($string);

    for ($i = 0; $i < $string_length; $i++) {
        $result .= chr(ord($string[$i]) ^ ord($keys[$i % 32]));
    }

    if ($operation == "ENCODE") {
        $retstrs = str_replace("=", "", base64_encode($result));
        $retstrs = str_replace("/", "_", $retstrs);
        return $runtokey . $retstrs;
    }
    else {
        if (((substr($result, 0, 10) == 0) || (0 < (substr($result, 0, 10) - time()))) && (substr($result, 10, 16) == substr(md5(substr($result, 26) . $egiskeys), 0, 16))) {
            return substr($result, 26);
        }
        else {
            return "";
        }
    }
}

function _encookiecode($code)
{
    $cookie = System::load_sys_config("system");
    $hash = $cookie["cookie_hash"];
    return md5($hash . md5($code));
}

/**
 * 生成昵称
 */
function _customUsername()
{
    return l("custom.username") . mt_rand( 100, 9999 );
}

function _enusername($user)
{
    if (strpos($user, "@") === false) {
        return substr_replace($user, "****", 3, 4);
    }

    $n = strpos($user, "@");

    if ($n < 3) {
        return substr_replace($user, "****", $n, 0);
    }
    else if ($n < 6) {
        return substr_replace($user, "****", 2, $n - 2);
    }
    else {
        return substr_replace($user, "****", 2, 4);
    }
}

function _strlen($str = "")
{
    if (empty($str)) {
        return 0;
    }

    if (!_is_utf8($str)) {
        $str = iconv("GBK", "UTF-8", $str);
    }

    return ceil((strlen($str) + mb_strlen($str, "utf-8")) / 2);
}

function _strcut( $string, $length, $dot = "..." )
{
    $string = trim($string);
    if ( $length && ($length < strlen($string) ) ) {
        $wordscut = "";

        if ( strtolower( G_CHARSET ) == "utf-8" ) {
            $n   = 0;
            $tn  = 0;
            $noc = 0;

            while ( $n < strlen($string) ) {
                $t = ord($string[$n]);
                if (($t == 9) || ($t == 10) || ((32 <= $t) && ($t <= 126))) {
                    $tn = 1;
                    $n++;
                    $noc++;
                }
                else {
                    if ((194 <= $t) && ($t <= 223)) {
                        $tn = 2;
                        $n += 2;
                        $noc += 2;
                    }
                    else {
                        if ((224 <= $t) && ($t < 239)) {
                            $tn = 3;
                            $n += 3;
                            $noc += 2;
                        }
                        else {
                            if ((240 <= $t) && ($t <= 247)) {
                                $tn = 4;
                                $n += 4;
                                $noc += 2;
                            }
                            else {
                                if ((248 <= $t) && ($t <= 251)) {
                                    $tn = 5;
                                    $n += 5;
                                    $noc += 2;
                                }
                                else {
                                    if (($t == 252) || ($t == 253)) {
                                        $tn = 6;
                                        $n += 6;
                                        $noc += 2;
                                    }
                                    else {
                                        $n++;
                                    }
                                }
                            }
                        }
                    }
                }

                if ( $length <= $noc ) {
                    break;
                }
            }

            if ( $length < $noc ) {
                $n -= $tn;
            }

            $wordscut = substr( $string, 0, $n );
        }
        else {
            for ( $i = 0; $i < ($length - 1); $i++ ) {
                if ( 127 < ord($string[$i]) ) {
                    $wordscut .= $string[$i] . $string[$i + 1];
                    $i++;
                }
                else {
                    $wordscut .= $string[$i];
                }
            }
        }

        $string = $wordscut . $dot;
    }

    return trim( $string );
}

function _get_ip()
{
    if ( isset($_SERVER["HTTP_CLIENT_IP"]) && strcasecmp($_SERVER["HTTP_CLIENT_IP"], "unknown") )
    {
        $ip = $_SERVER["HTTP_CLIENT_IP"];
    }
    else
    {
        if ( isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && strcasecmp($_SERVER["HTTP_X_FORWARDED_FOR"], "unknown") )
        {
            $ip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }
        else
        {
            if ( isset($_SERVER["REMOTE_ADDR"]) && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown") )
            {
                $ip = $_SERVER["REMOTE_ADDR"];
            }
            else
            {
                if ( isset($_SERVER["REMOTE_ADDR"]) && isset($_SERVER["REMOTE_ADDR"]) && strcasecmp($_SERVER["REMOTE_ADDR"], "unknown") )
                {
                    $ip = $_SERVER["REMOTE_ADDR"];
                }
                else
                {
                    if ( isset($_SERVER["HTTP_X_REAL_IP"]) && strcasecmp($_SERVER["HTTP_X_REAL_IP"], "unknown") )
                    {
                        $ip = $_SERVER["HTTP_X_REAL_IP"];
                    }
                    else
                    {
                        $ip = "";
                    }
                }
            }
        }
    }

    return $ip;
}

function _get_ip_dizhi( $ip = NULL )
{
    $opts = array(
        "http" => array( "method" => "GET", "timeout" => 5 )
    );
    $context = stream_context_create( $opts );

    if ( $ip )
    {
        $ipmac = $ip;
    }
    else
    {
        $ipmac = _get_ip();
    }
    if ( strpos( $ipmac, "127.0.0." ) !== false )
    {
        return "";
    }

    $url_ip = "http://ip.taobao.com/service/getIpInfo.php?ip=" . $ipmac;
    $str = @file_get_contents( $url_ip, false, $context );
    if ( ! $str )
    {
        return "";
    }

    $json = json_decode( $str, true );
    if ( $json["code"] == 0 )
    {
        $json["data"]["region"] = addslashes( _htmtocode($json["data"]["region"]) );
        $json["data"]["city"]   = addslashes( _htmtocode($json["data"]["city"]) );
        $ipcity = $json["data"]["region"] . $json["data"]["city"];
        $ip = $ipcity . "," . $ipmac;
    }
    else
    {
        $ip = "";
    }

    return $ip;
}

function _is_utf8( $string )
{
    return preg_match("%^(?:\n\t\t\t\t\t[\\x09\\x0A\\x0D\\x20-\\x7E] # ASCII\n\t\t\t\t\t| [\\xC2-\\xDF][\\x80-\\xBF] # non-overlong 2-byte\n\t\t\t\t\t| \\xE0[\\xA0-\\xBF][\\x80-\\xBF] # excluding overlongs\n\t\t\t\t\t| [\\xE1-\\xEC\\xEE\\xEF][\\x80-\\xBF]{2} # straight 3-byte\n\t\t\t\t\t| \\xED[\\x80-\\x9F][\\x80-\\xBF] # excluding surrogates\n\t\t\t\t\t| \\xF0[\\x90-\\xBF][\\x80-\\xBF]{2} # planes 1-3\n\t\t\t\t\t| [\\xF1-\\xF3][\\x80-\\xBF]{3} # planes 4-15\n\t\t\t\t\t| \\xF4[\\x80-\\x8F][\\x80-\\xBF]{2} # plane 16\n\t\t\t\t\t)*$%xs", $string);
}

function _sendemail( $email, $username = NULL, $title = "", $content = "", $yes = "", $no = "" )
{
    System::load_sys_class( "email", "sys", "no" );
    $config = System::load_sys_config("email");

    if ( ! $username )
    {
        $username = "";
    }

    if ( ! $yes )
    {
        $yes = "发送成功!";
    }

    if ( ! $no )
    {
        $no = "发送失败，请重新点击发送";
    }

    if ( ! _checkemail( $email ) )
    {
        return false;
    }

    email::config( $config );
    if ( is_array( $email ) )
    {
        email::adduser( $email );
    }
    else
    {
        email::adduser( $email, $username );
    }

    $if = email::send( $title, $content );
    if ( $if )
    {
        return $yes;
    }
    else
    {
        return $no;
    }
}

function _sendmobile( $mobiles = "", $content = "" )
{
    $mobiles = str_replace( "，", ",", $mobiles );
    $mobiles = str_replace( " ", "", $mobiles );
    $mobiles = trim( $mobiles, " " );
    $mobiles = trim( $mobiles, "," );
    $sends  = System::load_sys_class( "sendmobile" );
    $config = array();
    $config["mobile"]  = $mobiles;
    $config["content"] = $content;
    $config["ext"]   = "";
    $config["stime"] = "";
    $config["rrid"]  = "";
    $cok = $sends->init( $config );
    if ( ! $cok )
    {
        return array( "-1", "配置不正确!" );
    }
    $sends->send();
    $sendarr = array( $sends->error, $sends->v );
    return $sendarr;
}

function _get_end_time()
{
    $EndTime   = explode(" ", microtime());
    $StartTime = explode(" ", G_START_TIME);
    return (intval($EndTime[1] - $StartTime[1]) + ($EndTime[0] - $StartTime[0])) . "/S";
}

function _get_end_memory()
{
    $memory = memory_get_usage();
    $memory = $memory / 1024;
    return round($memory, 2) . "/KB";
}

function _message( $string = NULL, $defurl = NULL, $time = 2, $config = NULL, $right = false )
{
    if ( $time < 2 )
    {
        $time = 2;
    }

    ob_clean();
    //if ((G_IS_MOBILE || G_IS_TEMPSKIN) && isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && (strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "XMLHttpRequest")) {
    //  echo _json_encode(array("string" => $string, "defurl" => $defurl));
    //  exit();
    //}
    //else {
        if (empty($defurl)) {
            $defurl = ":js:";
        }

        $view = System::load_sys_class("view", "sys");
        $view->data("string", $string);
        $view->data("defurl", $defurl);
        $view->data("time", $time);
        $view->data("config", $config);
        $view->data("right", $right);
        $view->show("system.message")->commit();
        exit();
    // }
}

/**
 * 成功信息json输出
 */
function json_success( $data = '' )
{
    $result = array();
    $result['status'] = 1;
    $result['data'] = $data;
    exit( json_encode( $result ) );
}

/**
 * 错误信息json输出
 */
function json_error( $data = '' )
{
    $result = array();
    $result['status'] = 0;
    $result['data'] = $data;
    exit( json_encode( $result ) );
}

function _SendStatus( $status = 404, $data = "" )
{
    $view = System::load_sys_class( "view", "sys" );

    switch ( $status )
    {
        case 404:
            header("HTTP/1.1 404 Not Found");
            header("status: 404 Not Found");
            $view->show("system.404")->commit();
            break;

        case 301:
            header("HTTP/1.1 301 Moved Permanently");
            header("location:$data");
            break;

        case "location":
            header("location:$data");
            break;
    }

    exit();
}

function _error( $title, $content )
{
    if ( empty( $title ) )
    {
        $title = "404 Page Not Found";
    }

    if ( empty( $content ) )
    {
        $content = "The page you requested was not found.";
    }

    echo "<!DOCTYPE html><html lang=\"en\"><head><title>" . $title . "</title><style type=\"text/css\">::selection{ background-color: #E13300; color: white; }\n::moz-selection{ background-color: #E13300; color: white; }::webkit-selection{ background-color: #E13300; color: white; }\nbody {\tbackground-color: #fff;\tmargin: 40px;\tfont: 13px/20px normal Helvetica, Arial, sans-serif;\tcolor: #4F5155;}\na {\tcolor: #003399;\tbackground-color: transparent;\tfont-weight: normal;}\nh1 {\tcolor: #444;\tbackground-color: transparent;\tborder-bottom: 1px solid #D0D0D0;\tfont-size: 19px;\tfont-weight: normal;\n\tmargin: 0 0 14px 0;\tpadding: 14px 15px 10px 15px;}\ncode {\tfont-family: Consolas, Monaco, Courier New, Courier, monospace;\tfont-size: 12px;\n\tbackground-color: #f9f9f9;\tborder: 1px solid #D0D0D0;\tcolor: #002166;\tdisplay: block;\tmargin: 14px 0 14px 0;\tpadding: 12px 10px 12px 10px;}\n#container {\tmargin: 10px;\tborder: 1px solid #D0D0D0;\t-webkit-box-shadow: 0 0 8px #D0D0D0;}\np {\tmargin: 12px 15px 12px 15px;}</style><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head>\n<body>\t<div id=\"container\">\t\t<h1>" . $title . "</h1>\t\t<p>" . $content . "</p></div></body></html>";
    exit();
}

function _is_ie()
{
    $useragent = strtolower($_SERVER["HTTP_USER_AGENT"]);
    if ((strpos($useragent, "opera") !== false) || (strpos($useragent, "konqueror") !== false)) {
        return false;
    }

    if (strpos($useragent, "msie ") !== false) {
        return true;
    }

    return false;
}

function _ob_gzhandler( $buffer, $mod )
{
    if ( extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip") )
    {
        header(base64_decode("Q29udGVudC1FbmNvZGluZzogZ3ppcA=="));
        header(base64_decode("WC1Qb3dlcmVkLUJ5OiBnYW95aXBpbi5jb20="));
        return gzencode( $buffer, 9, FORCE_GZIP );
    }
}

function _error_handler()
{
    if ( _db_cfg( 'debug' ) )
    {
        ini_set( 'display_errors', 1 );
        error_reporting( E_ALL & ~E_NOTICE );
    }
    else
    {
        ini_set( 'display_errors', 'OFF' );
        error_reporting( E_ALL & ~E_NOTICE );
    }
    ini_set( "error_log", G_CACHES . "error." . date("Y-m-d") . ".logs" );
}

function _getcode($n = 10)
{
    $num = (intval($n) ? intval($n) : 10);

    if ( 44 < $num )
    {
        $codestr = base64_encode(md5(time()) . md5(time()));
    }
    else
    {
        $codestr = base64_encode(md5(time()));
    }

    $temp = array();
    $temp["code"] = substr($codestr, 0, $num);
    $temp["time"] = time();
    return $temp;
}

function _cfg($name = "")
{
    return System::load_sys_config("system", $name);
}

function _db_cfg($name = "")
{
    $arr = System::load_sys_config("database");
    return $arr["default"][$name];
}

function _g_triggerRequest($url, $io = false, $post_data = array(), $cookie = array())
{
    $method = (empty($post_data) ? "GET" : "POST");
    $url_array = parse_url($url);
    $port = (isset($url_array["port"]) ? $url_array["port"] : 80);

    if (function_exists("fsockopen")) {
        $fp = @fsockopen($url_array["host"], $port, $errno, $errstr, 30);
    }
    else if (function_exists("pfsockopen")) {
        $fp = @pfsockopen($url_array["host"], $port, $errno, $errstr, 30);
    }
    else if (function_exists("stream_socket_client")) {
        $fp = @stream_socket_client($url_array["host"] . ":" . $port, $errno, $errstr, 30);
    }
    else {
        $fp = false;
    }

    if ( ! $fp ) {
        return false;
    }

    $url_array["query"] = (isset($url_array["query"]) ? $url_array["query"] : "");
    $getPath = $url_array["path"] . "?" . $url_array["query"];
    $header = $method . " " . $getPath . " ";
    $header .= "HTTP/1.1\r\n";
    $header .= "Host: " . $url_array["host"] . "\r\n";
    $header .= "Pragma: no-cache\r\n";

    if ( ! empty( $cookie ) ) {
        $_cookie_s = strval(NULL);

        foreach ( $cookie as $k => $v ) {
            $_cookie_s .= $k . "=" . $v . "; ";
        }

        $_cookie_s  = rtrim($_cookie_s, "; ");
        $cookie_str = "Cookie: " . $_cookie_s . " \r\n";
        $header     .= $cookie_str;
    }

    $post_str = "";

    if ( ! empty( $post_data ) ) {
        $_post = strval(NULL);

        foreach ( $post_data as $k => $v ) {
            $_post .= $k . "=" . urlencode($v) . "&";
        }

        $_post    = rtrim($_post, "&");
        $header   .= "Content-Type: application/x-www-form-urlencoded\r\n";
        $header   .= "Content-Length: " . strlen($_post) . " \r\n";
        $post_str = $_post . "\r\n";
    }

    $header .= "Connection: Close\r\n\r\n";
    $header .= $post_str;
    $fw = fwrite( $fp, $header );
    sleep( 1 );

    if ( $io == "return" ) {
        $return = "";

        while ( ! feof( $fp ) ) {
            $return .= fgets($fp, 1024);
        }

        fclose($fp);
        echo $return;
        exit();
        return $return;
    }
    else if ( $io ) {
        while ( ! feof( $fp ) ) {
            echo fgets( $fp, 1024 );
        }
    }

    fclose( $fp );
    return true;
}

function _put_time($time = 0, $test = "")
{
    if (empty($time)) {
        return $test;
    }

    $time = substr($time, 0, 10);
    $ttime = time() - $time;
    if (($ttime <= 0) || ($ttime < 60)) {
        return "几秒前";
    }

    if ((60 < $ttime) && ($ttime < 120)) {
        return "1分钟前";
    }

    $i = floor($ttime / 60);
    $h = floor($ttime / 60 / 60);
    $d = floor($ttime / 86400);
    $m = floor($ttime / 2592000);
    $y = floor($ttime / 60 / 60 / 24 / 365);

    if ($i < 30) {
        return $i . "分钟前";
    }

    if ((30 < $i) && ($i < 60)) {
        return "一小时内";
    }

    if ((1 <= $h) && ($h < 24)) {
        return $h . "小时前";
    }

    if ((1 <= $d) && ($d < 30)) {
        return $d . "天前";
    }

    if ((1 <= $m) && ($m < 12)) {
        return $m . "个月前";
    }

    if ($y) {
        return $y . "年前";
    }

    return "";
}

function _ifcookiecode( $input, $cookiename )
{
    $cookie = System::load_sys_config( 'system' );
    $hash = $cookie["cookie_hash"];
    $cookiename = $cookie["cookie_pre"] . $cookiename;
    $cookieval = ( isset( $_COOKIE[$cookiename] ) ? $_COOKIE[$cookiename] : NULL );
    return md5($hash . md5(strtolower($input))) == $cookieval ? true : false;
}

function _getcookie( $name )
{
    $cookie = System::load_sys_config("system");
    $name   = $cookie["cookie_pre"] . $name;

    if ( isset( $_COOKIE[$name] ) ) 
    {
        return $_COOKIE[$name];
    }
    else 
    {
        return false;
    }
}

function _setcookie( $name, $value, $time = NULL, $path = NULL, $domain = NULL )
{
    $cookie = System::load_sys_config( 'system' );
    $name = $cookie["cookie_pre"] . $name;
    $time = ($time ? time() + $time : $cookie["cookie_ttl"]);
    $path = ($path ? $path : $cookie["cookie_path"]);
    $domain = ($domain ? $domain : $cookie["cookie_domain"]);
    $_COOKIE[$name] = $value;
    $s = ($_SERVER["SERVER_PORT"] == "443" ? 1 : 0);
    return setcookie( $name, $value, $time, $path, $domain, $s );
}

function _session_start( $bool = NULL )
{
    if ( $bool ) 
    {
        $cookie = System::load_sys_config("system");
        session_set_cookie_params($cookie["session_ttl"]);
        session_save_path("1;" . G_CACHES . "caches_session");
        session_name($cookie["cookie_pre"] . "_");
    }
    else 
    {
        session_start();
    }
}

function _session_destroy()
{
    session_destroy();
}

function _xml_to_array($xml)
{
    $reg = "/<(\w+)[^>]*>([\\x00-\\xFF]*)<\/\1>/";

    if (preg_match_all($reg, $xml, $matches)) {
        $count = count($matches[0]);

        for ($i = 0; $i < $count; $i++) {
            $subxml = $matches[2][$i];
            $key = $matches[1][$i];

            if (preg_match($reg, $subxml)) {
                $arr[$key] = _xml_to_array($subxml);
            }
            else {
                $arr[$key] = $subxml;
            }
        }
    }

    return $arr;
}

function _GetVersion()
{
    echo system::load_sys_config("version", "version");
}

function _GetSysInfo()
{
    $sys_info["os"] = PHP_OS;
    $sys_info["zlib"] = function_exists("gzclose");
    $sys_info["safe_mode"] = (bool) ini_get("safe_mode");
    $sys_info["safe_mode_gid"] = (bool) ini_get("safe_mode_gid");
    $sys_info["timezone"] = (function_exists("date_default_timezone_get") ? date_default_timezone_get() : "no_timezone");
    $sys_info["socket"] = function_exists("fsockopen");
    $web = explode(" ", $_SERVER["SERVER_SOFTWARE"]);
    $sys_info["web_server"] = $web[0];
    $sys_info["phpv"] = phpversion();
    $sys_info["fileupload"] = (@ini_get("file_uploads") ? ini_get("upload_max_filesize") : "unknown");
    $sys_info["set_time_limit"] = (function_exists("set_time_limit") ? true : false);
    $sys_info["fsockopen"] = (function_exists("fsockopen") ? true : false);
    return $sys_info;
}

function _header( $message )
{
    // if ((G_IS_MOBILE || G_IS_TEMPSKIN) && isset($_SERVER["HTTP_X_REQUESTED_WITH"]))
    // {
    //     echo _json_encode(array("string" => NULL, "defurl" => preg_replace("/^location(\s?)\:(\s?)/i", "", $message)));
    //     exit();
    // }
    // else
    // {
        header( $message );
    // }
}
/**
 * 加密函数
 *
 * @param string $txt 需要加密的字符串
 * @param string $key 密钥
 * @return string 返回加密结果
 */
function encrypt($txt, $key = ''){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(MD5_KEY);
    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $nh1 = rand(0,64);
    $nh2 = rand(0,64);
    $nh3 = rand(0,64);
    $ch1 = $chars{$nh1};
    $ch2 = $chars{$nh2};
    $ch3 = $chars{$nh3};
    $nhnum = $nh1 + $nh2 + $nh3;
    $knum = 0;$i = 0;
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum%8,$knum%8 + 16);
    $txt = base64_encode(time().'_'.$txt);
    $txt = str_replace(array('+','/','='),array('-','_','.'),$txt);
    $tmp = '';
    $j=0;$k = 0;
    $tlen = strlen($txt);
    $klen = strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = ($nhnum+strpos($chars,$txt{$i})+ord($mdKey{$k++}))%64;
        $tmp .= $chars{$j};
    }
    $tmplen = strlen($tmp);
    $tmp = substr_replace($tmp,$ch3,$nh2 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch2,$nh1 % ++$tmplen,0);
    $tmp = substr_replace($tmp,$ch1,$knum % ++$tmplen,0);
    return $tmp;
}

/**
 * 解密函数
 *
 * @param string $txt 需要解密的字符串
 * @param string $key 密匙
 * @return string 字符串类型的返回结果
 */
function decrypt($txt, $key = '', $ttl = 0){
    if (empty($txt)) return $txt;
    if (empty($key)) $key = md5(MD5_KEY);

    $chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-_.";
    $ikey ="-x6g6ZWm2G9g_vr0Bo.pOq3kRIxsZ6rm";
    $knum = 0;$i = 0;
    $tlen = @strlen($txt);
    while(isset($key{$i})) $knum +=ord($key{$i++});
    $ch1 = @$txt{$knum % $tlen};
    $nh1 = strpos($chars,$ch1);
    $txt = @substr_replace($txt,'',$knum % $tlen--,1);
    $ch2 = @$txt{$nh1 % $tlen};
    $nh2 = @strpos($chars,$ch2);
    $txt = @substr_replace($txt,'',$nh1 % $tlen--,1);
    $ch3 = @$txt{$nh2 % $tlen};
    $nh3 = @strpos($chars,$ch3);
    $txt = @substr_replace($txt,'',$nh2 % $tlen--,1);
    $nhnum = $nh1 + $nh2 + $nh3;
    $mdKey = substr(md5(md5(md5($key.$ch1).$ch2.$ikey).$ch3),$nhnum % 8,$knum % 8 + 16);
    $tmp = '';
    $j=0; $k = 0;
    $tlen = @strlen($txt);
    $klen = @strlen($mdKey);
    for ($i=0; $i<$tlen; $i++) {
        $k = $k == $klen ? 0 : $k;
        $j = strpos($chars,$txt{$i})-$nhnum - ord($mdKey{$k++});
        while ($j<0) $j+=64;
        $tmp .= $chars{$j};
    }
    $tmp = str_replace(array('-','_','.'),array('+','/','='),$tmp);
    $tmp = trim(base64_decode($tmp));

    if (preg_match("/\d{10}_/s",substr($tmp,0,11))){
        if ($ttl > 0 && (time() - substr($tmp,0,11) > $ttl)){
            $tmp = null;
        }else{
            $tmp = substr($tmp,11);
        }
    }
    return $tmp;
}

/**
 * 计算百分比
 * @param  row      单个数
 * @param  sum      总数
 * @return [type]   百分比
 */
function percentage( $row, $sum )
{
    if ( ! $sum ) { return '0％'; }
    return round( $row / $sum * 100 , 2 ) . "%";
}

/**
 * 判断是否微信浏览器
 */
function is_weixin()
{
    if ( strpos($_SERVER['HTTP_USER_AGENT'], 'MicroMessenger') !== false )
    {
        return true;
    }
    return false;
}

/**
 * [中文段落截取]
 * @author Yusure  http://yusure.cn
 * @date   2016-04-19
 * @param  [param]
 * @param  [type]     $string [待处理字符]
 * @param  integer    $length [需要长度]
 * @param  string     $ext    [省略符号]
 * @return [type]             [description]
 */
function my_substr( $string, $length = 50, $ext = '……' )
{
    if ( mb_strlen( $string, 'utf8' ) > $length )
    {
        $string = mb_substr( $string, 0, $length, 'utf8' ) . $ext;
    }
    return $string;
}

/**
 * POST方式进行curl传输
 */
function curl_post( $url, $data )
{
    $ch = curl_init();
    curl_setopt( $ch, CURLOPT_URL, $url );
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
    // post数据
    curl_setopt( $ch, CURLOPT_POST, 1 );
    // post的变量
    curl_setopt( $ch, CURLOPT_POSTFIELDS, $data );
    $output = curl_exec( $ch );
    curl_close( $ch );
    return $output;
}

/**
 * 价格格式化
 */
function format_price( $price )
{
    $price_format   = number_format($price,2,'.','');
    return $price_format;
}