<?php

class param
{
    private $route_config;
    private $domain;
    private $expstr    = "/";
    private $route     = array();
    private $route_url = array();
    private $param_url = "";
    private $plugin    = false;

    public function __construct()
    {
        $this->route_config = System::load_sys_config( 'param' );
        $this->domain       = System::load_sys_config( 'domain' );
        $this->prourl();
        $this->setDefine();
        $this->sub_addslashes();
    }

    public function __get( $key )
    {
        if ( isset( $this->$key ) ) {
            return $this->$key;
        }
        else {
            return NULL;
        }
    }

    private function setDefine()
    {
        if (!defined("G_IS_MOBILE")) {
            $this->isMobile() ? define("G_IS_MOBILE", 1) : define("G_IS_MOBILE", 0);
        }

        if (isset($_SERVER["HTTP_X_REQUESTED_WITH"]) && (strtolower($_SERVER["HTTP_X_REQUESTED_WITH"]) == "xmlhttprequest")) {
            define("G_IS_AJAX", 1);
        }
        else {
            define("G_IS_AJAX", 0);
        }
    }

    private function prourl()
    {
        $is_m_domain = isset($this->domain[$_SERVER['HTTP_HOST']]['type']) ? define('G_IS_MOBILE', 1) : false;

        if ( isset( $_SERVER['REDIRECT_PATH_INFO'] ) && ! isset( $_SERVER['PATH_INFO'] ) ) 
        {
            $_SERVER['PATH_INFO'] = $_SERVER['REDIRECT_PATH_INFO'] == $_SERVER['PHP_SELF'] 
            ? '' 
            : $_SERVER['REDIRECT_PATH_INFO'];
            reset( $_SERVER );
        }

        if ( isset( $_SERVER['ORIG_PATH_INFO'] ) && ! isset( $_SERVER['PATH_INFO'] ) ) 
        {
            $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'] == $_SERVER['PHP_SELF'] 
            ? '' 
            : $_SERVER['ORIG_PATH_INFO'];
            reset( $_SERVER );
        }

        if ( isset($_SERVER['PATH_INFO']) && ( $_SERVER['PATH_INFO'] != '/' ) && ! empty( $_SERVER['PATH_INFO'] ) ) 
        {
            return $this->prourlexp( 'pathinfo', $_SERVER['PATH_INFO'] );
        }

        if ( isset( $_SERVER['QUERY_STRING'] ) && ! empty( $_SERVER['QUERY_STRING'] ) ) 
        {
            $this->prourlexp( 'query', $_SERVER['QUERY_STRING'] );
            if ( ! empty( $this->route_url[1] ) ) 
            {
                return NULL;
            }
        }

        if ( isset( $this->domain[$_SERVER['HTTP_HOST']] ) ) 
        {
            if ( $is_m_domain ) 
            {
                return NULL;
            }
            else 
            {
                $this->route_url[1] = $this->domain[$_SERVER['HTTP_HOST']]['module'];
                $this->route_url[2] = $this->domain[$_SERVER['HTTP_HOST']]['action'];
                $this->route_url[3] = $this->domain[$_SERVER['HTTP_HOST']]['func'];
                return NULL;
            }
        }

        if ( ! $is_m_domain ) 
        {
            $this->isMobile() ? define( 'G_IS_MOBILE', 1 ) : define( 'G_IS_MOBILE', 0 );
        }

        if ( G_IS_MOBILE ) 
        {
            foreach ( $this->domain as $key => $v ) 
            {
                if ( isset( $v['type'] ) && ( $v['type'] == 'mobile' ) ) 
                {
                    header( 'location: ' . dirname( G_HTTP . $key . $_SERVER['SCRIPT_NAME'] ) );
                    exit();
                }
            }
        }

        return NULL;
    }

    private function prourlexp( $type, $path )
    {
        if ( stripos( trim( $path, '/' ), $this->route_config['plugin_begin_route'] ) === 0 ) 
        {
            $type = 'plugin';
        }

        $path = ltrim( $path, '/' );

        switch ( $type ) 
        {
            case "pathinfo":
                $path = ltrim( $path, "/" );
                $path = preg_replace( "/^index.php\//i", "", $path );
                $path = rtrim( $path, $this->expstr );
            break;

            case "query":
                $path = ltrim( $path, "/" );
                $path = rtrim( $path, $this->expstr );
                list( $key, $val ) = each( $_GET );
                if ( $key && !$val ) 
                {
                    $path = trim($key, "/");
                }
            break;

            case "plugin":
                return $this->plugin = trim( $path, "/" );
            break;

            default:

            break;
        }

        $this->param_url = $path;
        define( 'G_PARAM_URL', $this->param_url );

        if (isset($this->route_config["routes"])) {
            if (isset($this->route_config["routes"][$path])) {
                $path = $this->route_config["routes"][$path];
            }
            else {
                $path .= $this->expstr;

                foreach ($this->route_config["routes"] as $key => $val ) {
                    $key = str_replace(":any", ".*", str_replace(":num", "[0-9]+", $key)) . "\/?\$";

                    if (preg_match("#^" . $key . "$#", $path)) {
                        if ((strpos($val, "\$") !== false) && (strpos($key, "(") !== false)) {
                            $val = preg_replace("#^" . $key . "$#", $val, $path);
                        }

                        $path = $val;
                    }
                }
            }
        }

        $this->route_url = explode($this->expstr, trim($path, $this->expstr));
        array_unshift($this->route_url, NULL);
        unset($this->route_url[0]);
        $end = end($this->route_url);

        if (stripos($end, ".") !== false) {
            $end = explode(".", $end);
            $this->route_url[count($this->route_url)] = $end[0];
        }

        if ((count($this->route_url) == 1) && (strpos($this->route_url[1], "=") !== false)) {
            $this->route_url[1] = NULL;
            $this->route_url[2] = NULL;
            $this->route_url[3] = NULL;
        }
    }

    private function sub_addslashes()
    {
        if (!get_magic_quotes_gpc()) {
            $_POST = new_addslashes($_POST);
            $_GET = new_addslashes($_GET);
            $_REQUEST = new_addslashes($_REQUEST);
            $_COOKIE = new_addslashes($_COOKIE);
            $this->route_url = new_addslashes($this->route_url);
        }
        else {
            $this->route_url = new_addslashes($this->route_url);
        }
    }

    /**
     * 模型model
     */
    public function route_m()
    {
        if ( empty( $this->route_url[1] ) ) 
        {
            $this->route_url[1] = $this->route_config['default']['m'];
        }

        define( 'G_MODULE_PATH', WEB_PATH . '/' . $this->route_url[1] );

        return $this->route_url[1];
    }


    /**
     * 控制器
     */
    public function route_c()
    {
        if ( empty( $this->route_url[2] ) ) 
        {
            $this->route_url[2] = $this->route_config["default"]["c"];
        }

        return $this->route_url[2];
    }

    /**
     * 方法
     */
    public function route_a()
    {
        if ( empty( $this->route_url[3] ) ) 
        {
            $this->route_url[3] = $this->route_config["default"]["a"];
        }

        return $this->route_url[3];
        preg_match( "/-[0-9]{1,10}$/i", $this->route_url[3], $matches );

        if ( isset( $matches[0] ) ) 
        {
            $this->route_url["page"] = abs($matches[0]);
        }
        else 
        {
            $this->route_url["page"] = 1;
        }

        $this->route_url["url"] = $this->param_url;
        $this->route_url[3]     = explode( "-", $this->route_url[3] );
        return $this->route_url[3] = $this->route_url[3][0];
    }

    /**
     * 是否手机访问
     */
    private function isMobile()
    {
        if ( isset( $_SERVER["HTTP_X_WAP_PROFILE"] ) ) 
        {
            return true;
        }

        if ( isset( $_SERVER["HTTP_VIA"] ) ) 
        {
            return stristr($_SERVER["HTTP_VIA"], "wap") ? true : false;
        }

        if ( isset( $_SERVER["HTTP_USER_AGENT"] ) ) 
        {
            $clientkeywords = array( "nokia", "sony", "ericsson", "mot", "samsung", "htc", "sgh", "lg", "sharp", "sie-", "philips", "panasonic", "alcatel", "lenovo", "iphone", "ipod", "blackberry", "meizu", "android", "netfront", "symbian", "ucweb", "windowsce", "palm", "operamini", "operamobi", "openwave", "nexusone", "cldc", "midp", "wap", "mobile", "iOS" );

            if ( preg_match("/(" . implode("|", $clientkeywords) . ")/i", strtolower($_SERVER["HTTP_USER_AGENT"]))) 
            {
                return true;
            }
        }

        if ( isset( $_SERVER["HTTP_ACCEPT"] ) ) 
        {
            if ( (strpos($_SERVER["HTTP_ACCEPT"], "vnd.wap.wml") !== false) && ((strpos($_SERVER["HTTP_ACCEPT"], "text/html") === false) || (strpos($_SERVER["HTTP_ACCEPT"], "vnd.wap.wml") < strpos($_SERVER["HTTP_ACCEPT"], "text/html"))) ) 
            {
                return true;
            }
        }

        return false;
    }
}