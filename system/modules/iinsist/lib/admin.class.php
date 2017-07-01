<?php

class admin extends SystemAction
{
    protected $AdminInfo;
    private $db;
    private $model;

    public function __construct()
    {
        _session_start();
        $this->model = System::load_app_model("index", "common");
        $this->CheckAdmin();
        $this->auth_check();
    }

    /* 权限检查 */
    final protected function auth_check()
    {
        $group_info = $this->model->get_admin_group( $this->AdminInfo["gid"] );
        if ( (ROUTE_C != "index") && (ROUTE_C != "user") )
        {
            if ( $group_info["auth_content"] != "all" )
            {
                $d = ROUTE_P;
                $auth_str = ROUTE_C . "-" . ROUTE_A;

                if ( ! empty( $d ) )
                {
                    $auth_str1 = ROUTE_C . "-" . ROUTE_A . "-" . $d;
                }
                else
                {
                    $auth_str1 = $auth_str;
                }

                $auth_list = explode( ",", $group_info["auth_content"] );

                if ( ! empty( $auth_str ) )
                {
                    if ( ! in_array( $auth_str, $auth_list ) && ! in_array( $auth_str1, $auth_list ) )
                    {
                        exit("你没有权限操作此功能！");
                    }
                }
            }
        }
    }

    final protected function CheckAdmin()
    {
        if ( ROUTE_A != "login" )
        {
            $check = $this->CheckAdminInfo();

            if ( ! $check )
            {
                _message("请登录后在查看页面", WEB_PATH . "/" . G_ADMIN_DIR . "/user/login");
            }
        }
    }

    final protected function CheckAdminInfo()
    {
        if (!isset($_SESSION["AID"])) {
            return false;
        }

        $this->AdminInfo = $_SESSION["AINFO"];
        return true;
    }

    final static public function StaticCheckAdminInfo()
    {
        _session_start();

        if (!isset($_SESSION["AID"])) {
            return false;
        }

        return $_SESSION["AINFO"];
    }

    final protected function headerment($ments = NULL)
    {
        $html = "";
        $html_l = "";
        $URL = trim(get_web_url(), "/");

        if (is_array($ments)) {
            $ment = $ments;
        }
        else {
            if (!isset($this->ment)) {
                return false;
            }

            $ment = $this->ment;
        }

        foreach ($ment as $k => $v ) {
            if ((WEB_PATH . "/" . $v[2]) == $URL) {
                $html_l = "<h3 class=\"nav_icon\">" . $v[1] . "</h3><span class=\"span_fenge lr10\"></span>";
            }

            if (!isset($v[3])) {
                $html .= "<a href=\"" . WEB_PATH . "/" . $v[2] . "\">" . $v[1] . "</a>";
                $html .= "<span class=\"span_fenge lr5\">|</span>";
            }
        }

        return $html_l . $html;
    }

    final protected function AdminLoginStatus($admin)
    {
        $_SESSION["AID"] = $admin["mid"];
        $_SESSION["AINFO"] = $admin;
    }

    public function __destruct()
    {
        $this->admin_log();
    }

    private function admin_log()
    {
        $db = System::load_sys_class("model");

        if (empty($db->sql_log)) {
            return NULL;
        }

        $date = date("Y-m-d");
        $path = G_CACHES . "caches_log/admin.log." . $date . ".php";

        if (!file_exists($path)) {
            $html = "<?php exit; ?> \n\n\n\n";
            file_put_contents($path, $html);
        }

        if (!is_writable($path)) {
            exit("admin.log.$date.php 没有写入权限");
        }

        $date = date("Y-m-d H:i:s");
        $html = "$date------>" . var_export($db->sql_log, true);
        $html .= " \n\n\n\n";
        file_put_contents($path, $html, FILE_APPEND);
    }
}

defined("G_IN_ADMIN") ? true : define("G_IN_ADMIN", true);
define("G_ADMIN_PATH", WEB_PATH . "/" . G_ADMIN_DIR);

