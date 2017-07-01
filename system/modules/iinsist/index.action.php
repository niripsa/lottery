<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class index extends admin
{
    private $db;
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
        $this->model = System::load_app_model("index", "common");
        $this->auth = System::load_app_model("auth", "common");
    }

    public function manage()
    {
        $uid   = $this->segment(4);
        $check = $this->CheckAdminInfo();

        if ( $check )
        {
            $savetime  = 86400 * 5;
            $UserCheck = System::load_app_class("UserCheck", "common");
            $User      = System::load_app_model("member", "common");
            $user_info = $User->get_user_one("`uid`='" . $uid . "'");
            $UserCheck->UserLoginStatus($user_info, $savetime);
            $url = WEB_PATH . "/member/home/userindex";
            echo "<script language='javascript' type='text/javascript'>";
            echo "window.location.href='" . $url . "';";
            echo "</script>";
            exit();
        }
    }

    public function init()
    {
        $info = $this->AdminInfo;

        if (G_CHARSET == "utf-8") {
            $path .= "utf8/";
        }
        else if (G_CHARSET == "gbk") {
            $path .= "gbk/";
        }

        $stauts     = 1;
        $version    = System::load_sys_config("version");
        $v_time     = $version["release"];
        $v_version  = $version["version"];
        $upfile_url = $path;
        $version    = System::load_sys_config("version", "release");
        $content    = @file_get_contents($upfile_url);
        $pathlist   = false;

        if ( ! $content )
        {
            $stauts = -1;
        }
        else
        {
            $key = -1;
            $allpathlist = $pathlist = array();
            preg_match_all("/>(patch_[\w_]+\.zip)</", $content, $allpathlist);
            $allpathlist = $allpathlist[1];

            foreach ( $allpathlist as $k => $v )
            {
                if ( strstr( $v, "patch_" . $version ) )
                {
                    $key = $k;
                    break;
                }
            }

            $key = ($key < 0 ? 9999 : $key);

            foreach ( $allpathlist as $k => $v )
            {
                if ( $key <= $k )
                {
                    $pathlist[$k] = $v;
                }
            }
        }

        $group_info = $this->model->get_admin_group($info["gid"]);
        $upfile_num = count($pathlist);
        $mange_path = G_MODULE_PATH . "/";
        $menu_arr = $this->model->get_admin_menu($group_info["ids"]);
        $treeObj  = system::load_sys_class("tree");
        $treeObj->init($menu_arr);
        $treeObj->set_pid("pid");
        $menu = $treeObj->get_array(0, "\$name");
        $group = $this->auth->get_group_one("`gid`='" . $info["gid"] . "'", "`name`");
        $info["group_name"] = $group["name"];
        $ip_info = file_get_contents("http://ip.taobao.com/service/getIpInfo.php?ip=" . $info["loginip"]);
        $ip_info = json_decode($ip_info);
        $last_ip_addr = $ip_info->data->region . " " . $ip_info->data->city;

        if ($last_ip_addr == " ") {
            $last_ip_addr = "未知";
        }

        $sys_note = '您好，欢迎您的使用！';
        $this->view->data("sys_note", $sys_note);
        $this->view->data("info", $info);
        $this->view->data("last_ip_addr", $last_ip_addr);
        $this->view->data("mange_path", $mange_path);
        $this->view->data("menu", $menu);
        $this->view->tpl("admin.index");
    }

    public function Tdefault()
    {
        $info = $this->model->goods_sales_top();
        $this->view->data("info", $info);
        $web_acc = $this->model->web_acc();
        $this->view->data("web_acc", $web_acc);
        $sales_list = $this->model->sales_list();
        $this->view->data("sales_list", $sales_list);
        $this->view->tpl("admin.default");
    }

    public function map()
    {
        $info = $this->AdminInfo;
        $mange_path = G_MODULE_PATH . "/";
        $menu_arr = $this->model->get_admin_menu("all");
        $treeObj = system::load_sys_class("tree");
        $treeObj->init($menu_arr);
        $treeObj->set_pid("pid");
        $menu = $treeObj->get_array(0, "\$name");
        $this->view->data("mange_path", $mange_path);
        $this->view->tpl("admin.map")->data("info", $info)->data("menu", $menu);
    }
}