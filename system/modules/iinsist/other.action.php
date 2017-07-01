<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", "", "no");
class other extends admin
{
    private $ments;
    private $ment;
    private $club_ments;
    private $ad_ments;
    private $model;
    private $setting;

    public function __construct()
    {
        parent::__construct();
        $this->model = System::load_app_model("other", "common");
        $this->setting = System::load_app_model("setting", "common");
        $this->ments = array(
    array("navigation", "幻灯管理", ROUTE_M . "/" . ROUTE_C . "/slide"),
    array("navigation", "添加幻灯片", ROUTE_M . "/" . ROUTE_C . "/slide_add")
    );
        $this->ment = array(
    array("navigation", "导航条管理", ROUTE_M . "/" . ROUTE_C . "/nav"),
    array("addnavigation", "添加导航条", ROUTE_M . "/" . ROUTE_C . "/nav_add")
    );
        $this->club_ments = array(
    array("lists", "圈子管理", ROUTE_M . "/" . ROUTE_C . "/club"),
    array("addcate", "添加圈子", ROUTE_M . "/" . ROUTE_C . "/club_add")
    );
        $this->ad_ments = array(
    array("lists", "广告位管理", ROUTE_M . "/" . ROUTE_C . "/ad_pos"),
    array("doadarea", "广告位添加", ROUTE_M . "/" . ROUTE_C . "/ad_pos_add"),
    array("admanage", "广告管理", ROUTE_M . "/" . ROUTE_C . "/ad"),
    array("adadd", "广告添加", ROUTE_M . "/" . ROUTE_C . "/ad_add")
    );
    }

    public function slide()
    {
        $lists = $this->model->get_slide();
        $this->view->data("lists", $lists);
        $this->view->tpl("other.slide_list")->data("ments", $this->ments);
    }

    public function slide_add()
    {
        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $res = $this->model->slide_add($data);

            if ($res) {
                _message("添加成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/slide");
            }
            else {
                _message("添加失败");
            }
        }

        $this->view->data("side_type", json_encode(array(1 => "PC端", 2 => "手机端")));
        $this->view->tpl("other.slide_add")->data("ments", $this->ments);
    }

    public function slide_del()
    {
        $id = intval($this->segment(4));
        $res = $this->model->slide_del("id=" . $id);

        if ($res) {
            _message("删除成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/slide");
        }
        else {
            _message("删除失败");
        }
    }

    public function slide_update()
    {
        $id = intval($this->segment(4));
        $slideone = $this->model->get_slide_one("`id`='" . $id . "'");

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $res = $this->model->slide_save("`id`='" . $id . "'", $data);

            if ( $res !== false ) {
                _message("修改成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/slide");
            }
            else {
                _message("修改失败");
            }
        }

        $this->view->data("side_type", json_encode(array(1 => "PC端", 2 => "手机端")));
        $this->view->data("slideone", $slideone);
        $this->view->tpl("other.slide_add")->data("ments", $this->ments);
    }

    public function nav()
    {
        $lists = $this->model->get_nav("", "", "sort desc");
        $this->view->data("lists", $lists);
        $this->view->tpl("other.nav")->data("ments", $this->ment);
    }

    public function nav_add()
    {
        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $data["status"] = ($data["status"] == "Y" ? "Y" : "N");
            $data["sort"] = (intval($data["sort"]) ? intval($data["sort"]) : 1);
            $res = $this->model->nav_add($data);

            if ($res) {
                _message("添加成功！", WEB_PATH . "/" . ROUTE_M . "/other/nav");
            }
            else {
                _message("添加失败！");
            }
        }

        $this->view->tpl("other.nav_add")->data("ments", $this->ment);
    }

    public function nav_edit()
    {
        $cid = $this->segment(4);

        if (intval($cid) <= 0) {
            _message("参数错误");
        }

        $info = $this->model->get_nav_one("`cid`='" . $cid . "'");

        if (!$info) {
            _message("参数错误");
        }

        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $data["status"] = ($data["status"] == "Y" ? "Y" : "N");
            $data["sort"] = (intval($data["sort"]) ? intval($data["sort"]) : 1);
            $res = $this->model->nav_save("`cid`='" . $cid . "'", $data);

            if ($res) {
                _message("修改成功", WEB_PATH . "/" . ROUTE_M . "/other/nav");
            }
            else {
                _message("修改失败");
            }
        }

        $this->view->data("info", $info);
        $this->view->tpl("other.nav_add")->data("ments", $this->ment);
    }

    public function nav_del()
    {
        $cid = $this->segment(4);

        if (intval($cid) <= 0) {
            _message("参数错误");
        }

        $res = $this->model->nav_del("`cid`='" . $cid . "'");

        if ($res) {
            _message("删除成功", WEB_PATH . "/" . ROUTE_M . "/other/nav");
        }
        else {
            _message("删除失败");
        }
    }

    public function template()
    {
        $temp_config = $this->model->get_template_config();
        $templates = $temp_config["templates"];
        $curr_pc = $temp_config["skin"]["pc"];
        $curr_mobile = $temp_config["skin"]["mobile"];
        $dir = opendir(G_TEMPLATES);
        $new_temp = false;
        $del_temp = false;

        while (($file = readdir($dir)) !== false) {
            if (($file != ".") && ($file != "..")) {
                if (is_dir(G_TEMPLATES . $file) && !isset($templates[$file])) {
                    $templates[$file] = array("name" => "未填写", "dir" => $file, "html" => "未填写", "author" => "未填写");
                    $new_temp = true;
                }

                if (isset($templates[$file]) && !file_exists(G_TEMPLATES . $file)) {
                    unset($templates[$file]);
                    $del_temp = true;
                }
            }
        }

        closedir($dir);
        if ($del_temp || $new_temp) {
            $html = "<?php \n defined('G_IN_SYSTEM') or exit('No permission resources.');";
            $html .= "\n return " . var_export($templates, true) . ";";
            $html .= "\n ?>";
        }

        $this->view->data("curr_pc", $curr_pc);
        $this->view->data("curr_mobile", $curr_mobile);
        $this->view->data("templates", $templates);
        $this->view->tpl("other.template");
    }

    public function off()
    {
        $temp = $this->segment(4);
        $temp_config = $this->model->get_template_config();
        $templates = $temp_config["templates"];

        if (!isset($templates[$temp])) {
            _message("没有这个模板");
        }

        if ($templates[$temp]["html"] == "未填写") {
            _message("该模板还未添加进系统!");
        }

        $skin = $temp_config["skin"];
        $type = $templates[$temp]["type"];
        $skin[$type] = $temp;
        $res = $this->model->edit_template_config("skin", $skin);
        $msg = "修改失败！";

        if ($res) {
            $msg = "修改成功！";
        }

        echo "<script>\talert('" . $msg . "');window.location.href='" . G_MODULE_PATH . "/other/template/';</script>";
    }

    public function template_edit()
    {
        $temp = $this->segment(4);
        $temp_config = $this->model->get_template_config();
        $templates = $temp_config["templates"];

        if (!isset($templates[$temp])) {
            _message("没有这个模板");
        }

        $template = $templates[$temp];

        if (isset($template["colorlist"])) {
            $colorlist = explode(",", $template["colorlist"]);
        }

        if (!is_writable(G_CONFIG . "view.inc.php")) {
            _message("Please chmod  templates  to 0777 !");
        }

        if (isset($_POST["dosubmit"])) {
            $data = _post();
            $templates[$data["dir"]] = $data;

            if (strstr($data["dir"], "diy")) {
                $colordemo = file_get_contents(G_TEMPLATES . $data["dir"] . "/css/colordemo.css");
                $newcolor = preg_replace("/#color1#/", $data["color1"], $colordemo);
                $newcolor = preg_replace("/#color2#/", $data["color2"], $newcolor);
                $newcolor = preg_replace("/#color3#/", $data["color3"], $newcolor);
                $newcolor = preg_replace("/#color4#/", $data["color4"], $newcolor);
                $newcolor = preg_replace("/#color5#/", $data["color5"], $newcolor);
                $data["colorlist"] = $data["color1"] . "," . $data["color2"] . "," . $data["color3"] . "," . $data["color4"] . "," . $data["color5"];
                unset($data["color1"]);
                unset($data["color2"]);
                unset($data["color3"]);
                unset($data["color4"]);
                unset($data["color5"]);
                $name = "colornew";
                $data["colorname"] = "colornew";
                file_put_contents(G_TEMPLATES . $data["dir"] . "/css/" . $name . ".css", $newcolor);
            }

            unset($templates[$temp]);
            unset($data["dosubmit"]);
            $res = $this->model->edit_template_config("templates", array($data["dir"] => $data), $temp);

            if ($res) {
                _message("修改成功!", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/template_edit/" . $temp);
            }
        }

        $this->view->tpl("other.template_edit")->data("template", $template)->data("colorlist", $colorlist);
    }

    public function temp()
    {
        $temp = $this->setting->ready_setting("reg");

        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->setting->write_setting("reg", $data);

            if ($res) {
                _message("邮件模板更新成功！");
            }
            else {
                _message("邮件模板更新失败！");
            }
        }

        $this->view->tpl("other.temp")->data("temp", $temp);
    }

    public function email_temp()
    {
        $temp = $this->setting->ready_setting("mobile_temp");

        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            $res = $this->setting->write_setting("mobile_temp", $data);

            if ($res) {
                _message("邮件模板更新成功！");
            }
            else {
                _message("邮件模板更新失败！");
            }
        }

        $this->view->tpl("other.email_temp")->data("temp", $temp);
    }

    public function mobile_temp()
    {
        $temp = $this->setting->ready_setting("mobile_temp");

        if (isset($_POST["dosubmit"])) {
            $data = _post();
            unset($data["dosubmit"]);
            preg_match_all("/./us", $data["m_reg_temp"], $match_reg);

            if (75 <= count($match_reg[0])) {
                _message("注册验资短信模板不能超过75个字,请检查!");
            }

            preg_match_all("/./us", $data["m_shop_temp"], $match_shop);

            if (75 <= count($match_shop[0])) {
                _message("用户获奖短信模板不能超过75个字,请检查!");
            }

            $res = $this->setting->write_setting("mobile_temp", $data);

            if ($res) {
                _message("短信模板更新成功！");
            }
            else {
                _message("短信模板更新失败！");
            }
        }

        $this->view->tpl("other.mobile_temp")->data("temp", $temp);
    }

    public function links()
    {
        $ments = array(
            array("lists", "友情链接", ROUTE_M . "/" . ROUTE_C . "/links"),
            array("addcate", "添加链接", ROUTE_M . "/" . ROUTE_C . "/link_add")
            );
        $links = $this->model->get_link();
        $this->view->data("ments", $ments);
        $this->view->tpl("other.link")->data("links", $links);
    }

    public function link_add()
    {
        $ments = array(
            array("lists", "友情链接", ROUTE_M . "/" . ROUTE_C . "/links"),
            array("addcate", "添加链接", ROUTE_M . "/" . ROUTE_C . "/link_add")
            );

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);

            if (empty($data["url"])) {
                _message("保存失败", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/links");
            }

            $data["type"] = 1;

            if (!empty($data["logo"])) {
                $data["type"] = 2;
            }

            $res = $this->model->link_add($data);

            if ($res) {
                _message("保存成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/links");
            }
            else {
                _message("保存失败");
            }
        }

        $this->view->data("ments", $ments);
        $this->view->tpl("other.link_add");
    }

    public function link_edit()
    {
        $linkid = intval($this->segment(4));
        $linkinfo = $this->model->get_link_one("`id`='" . $linkid . "'");

        if (!$linkinfo) {
            _message("参数不正确");
        }

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $data["type"] = 1;

            if (!empty($data["logo"])) {
                $data["type"] = 2;
            }

            $res = $this->model->link_save("`id`='" . $linkid . "'", $data);

            if ($res) {
                _message("修改成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/links");
            }
            else {
                _message("修改失败");
            }
        }

        $this->view->tpl("other.link_edit")->data("linkinfo", $linkinfo);
    }

    public function link_del()
    {
        $dellink = intval($this->segment(4));

        if ($dellink) {
            $res = $this->model->link_del("`id`='" . $dellink . "'");

            if ($res) {
                _message("删除成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/links");
            }
            else {
                _message("删除失败");
            }
        }
    }

    public function club()
    {
        $quanzi = $this->model->get_club();
        $this->view->data("ments", $this->club_ments);
        $this->view->tpl("other.club")->data("quanzi", $quanzi);
    }

    public function club_add()
    {
        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);

            if (empty($data["title"])) {
                _message("圈子名不能为空", NULL, 3);
            }

            $user_model = System::load_app_model("user", "common");
            $rs = $user_model->SelectUserOne($data["guanli"]);

            if ($rs) {
                $data["guanli"] = $rs["uid"];
            }

            $data["time"] = time();
            $res = $this->model->club_add($data);

            if ($res) {
                _message("添加成功！");
            }
            else {
                _message("添加失败！");
            }
        }

        $this->view->tpl("other.club_add")->data("ments", $this->club_ments);
    }

    public function club_edit()
    {
        $id = intval($this->segment(4));
        $quanzi = $this->model->get_club_one("`cid`='" . $id . "'");

        if (!$quanzi) {
            _message("参数错误");
        }

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);

            if (empty($data["title"])) {
                _message("圈子名不能为空");
            }

            $user_model = System::load_app_model("user", "common");
            $rs = $user_model->SelectUserOne($data["guanli"]);

            if ($rs) {
                $data["guanli"] = $rs["uid"];
            }

            $data["time"] = time();
            $res = $this->model->club_save("`cid`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功", WEB_PATH . "/admin/other/club");
            }
            else {
                _message("修改失败！");
            }
        }

        $this->view->data("ments", $this->club_ments);
        $this->view->tpl("other.club_add")->data("quanzi", $quanzi);
    }

    public function club_del()
    {
        $quanzi = $this->segment(4);
        $id = intval($this->segment(5));
        if (($quanzi == "quanzi") || ($quanzi == "quanzi_tiezi") || ($quanzi == "quanzi_hueifu")) {
            $res = $this->model->club_del("`cid`='" . $id . "'");

            if ($res) {
                _message("删除成功", WEB_PATH . "/admin/other/club");
            }
            else {
                _message("参数错误");
            }
        }
        else {
            _message("参数错误");
        }
    }

    public function topic()
    {
        $cid = $this->segment(4);
        $num = 20;
        $total = $this->model->get_topic_num("`cid`='" . $cid . "' and `type`=1");
        $page = System::load_sys_class("page");

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, $num, $pagenum, "0");

        if ($page->page < $pagenum) {
            $pagenum = $page->page;
        }

        $start_record = ($pagenum - 1) * $num;
        $tiezi = $this->model->get_topic("`cid`='" . $cid . "' and `type`=1", "*", "id desc", $start_record . "," . $num);
        $this->view->data("ments", $this->club_ments);
        $this->view->data("page", $page->show("one", "li"));
        $this->view->tpl("other.topic")->data("tiezi", $tiezi);
    }

    public function topic_edit()
    {
        $id = intval($this->segment(4));
        $tiezi = $this->model->get_topic_one("`id`='" . $id . "'");

        if (isset($_POST["submit"])) {
            $data = $_POST;
            unset($data["submit"]);
            if (empty($data["title"]) || empty($data["content"])) {
                _message("标题和内容均不能为空");
            }

            $data["content"] = editor_safe_replace(stripslashes($data["content"]));
            $res = $this->model->topic_save("`id`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功", G_MODULE_PATH . "/other/topic/" . $tiezi["cid"]);
            }
            else {
                _message("修改失败！");
            }
        }

        $tiezi = $this->model->get_topic_one("`id`='" . $id . "'");
        $this->view->data("ments", $this->club_ments);
        $this->view->tpl("other.topic_edit")->data("tiezi", $tiezi);
    }

    public function topic_del()
    {
        $id = intval($this->segment(4));
        $info = $this->model->get_topic_one("`id`='" . $id . "'");
        $res = $this->model->topic_del("`id`='" . $id . "'");

        if ($res) {
            _message("删除成功", G_MODULE_PATH . "/other/topic/" . $info["cid"]);
        }
        else {
            _message("参数错误");
        }
    }

    public function msg()
    {
        $id = $this->segment(4);
        $num = 20;
        $total = $this->model->get_topic_num("`huifu_id`='" . $id . "' and `type`=2");
        $page = System::load_sys_class("page");

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, $num, $pagenum, "0");

        if ($page->page < $pagenum) {
            $pagenum = $page->page;
        }

        $start_record = ($pagenum - 1) * $num;
        $huifu = $this->model->get_topic("`huifu_id`='" . $id . "' and `type`=2", "*", "id desc", $start_record . "," . $num);
        $this->view->data("ments", $this->club_ments);
        $this->view->data("page", $page->show("one", "li"));
        $this->view->tpl("other.topic_msg")->data("huifu", $huifu);
    }

    public function msg_del()
    {
        $id = intval($this->segment(4));
        $info = $this->model->get_topic_one("`id`='" . $id . "'");
        $res = $this->model->topic_del("`id`='" . $id . "'");

        if ($res) {
            _message("删除成功", G_MODULE_PATH . "/other/msg/" . $info["huifu_id"]);
        }
        else {
            _message("参数错误");
        }
    }

    public function ad_pos()
    {
        $arr = $this->model->get_ad_pos();
        $this->view->data("ments", $this->ad_ments);
        $this->view->tpl("other.ad_pos")->data("arr", $arr);
    }

    public function ad_pos_add()
    {
        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);

            if (empty($data["title"])) {
                _message("广告位名称不能为空！");
                exit();
            }

            if (!is_numeric($data["width"])) {
                _message("请输入数字");
                exit();
            }

            if (!is_numeric($data["width"])) {
                _message("请输入数字");
                exit();
            }

            $res = $this->model->ad_pos_add($data);

            if ($res) {
                _message("插入成功", G_MODULE_PATH . "/other/ad_pos/");
            }
            else {
                _message("插入失败");
            }
        }

        $this->view->tpl("other.ad_pos_add")->data("ments", $this->ad_ments);
    }

    public function ad_pos_edit()
    {
        $id = intval($this->segment(4));
        $list = $this->model->get_ad_pos_one("`aid`='" . $id . "'");

        if (!$list) {
            _message("参数不正确");
        }

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $res = $this->model->ad_pos_save("`aid`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功", G_MODULE_PATH . "/other/ad_pos/");
            }
            else {
                _message("修改失败");
            }
        }

        $this->view->data("ments", $this->ad_ments);
        $this->view->tpl("other.ad_pos_add")->data("ad", $list);
    }

    public function ad_pos_del()
    {
        $delid = intval($this->segment(4));
        $res = $this->model->ad_pos_del("`aid`='" . $delid . "'");

        if ($res) {
            $rx = $this->model->ad_del("`aid`='" . $delid . "'");
            _message("删除成功", G_MODULE_PATH . "/other/ad_pos/");
        }
        else {
            _message("删除失败");
        }
    }

    public function ad()
    {
        $arr = $this->model->get_ad();

        foreach ($arr as &$row ) {
            $tmp = $this->model->get_ad_pos_one("`aid`='" . $row["aid"] . "'");
            $row["ad_pos"] = $tmp["title"];
        }

        $this->view->data("ments", $this->ad_ments);
        $this->view->tpl("other.ad")->data("arr", $arr);
    }

    public function ad_add()
    {
        $ad_pos = $this->model->get_ad_pos();

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $data["addtime"] = strtotime($data["addtime"]);
            $data["endtime"] = strtotime($data["endtime"]);
            if (empty($data["title"]) || empty($data["type"]) || empty($data["aid"])) {
                _message("插入失败");
            }

            if ($data["type"] == "text") {
                $data["content"] = trim($data["text"]);
            }
            else if ($data["type"] == "code") {
                $data["content"] = trim($data["code"]);
            }
            else if ($data["type"] == "img") {
                $data["content"] = (isset($data["adphoto"]) ? $data["adphoto"] : "");
                $data["content"] = trim($data["content"], ".");
            }

            unset($data["text"]);
            unset($data["code"]);
            unset($data["adphoto"]);
            $res = $this->model->ad_add($data);

            if ($res) {
                _message("插入成功", G_MODULE_PATH . "/other/ad/");
            }
            else {
                _message("插入失败");
            }
        }

        $this->view->data("ad_pos", $ad_pos);
        $this->view->tpl("other.ad_add")->data("ments", $this->ad_ments);
    }

    public function ad_del()
    {
        $delid = intval($this->segment(4));
        $res = $this->model->ad_del("`id`='" . $delid . "'");

        if ($res) {
            _message("删除成功", G_MODULE_PATH . "/other/ad/");
        }
        else {
            _message("删除失败");
        }
    }

    public function ad_edit()
    {
        $id = intval($this->segment(4));
        $ad = $this->model->get_ad_one("id='" . $id . "'");

        if (!$ad) {
            _message("参数不正确");
        }

        $ad_pos = $this->model->get_ad_pos();

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            $data["addtime"] = strtotime($data["addtime"]);
            $data["endtime"] = strtotime($data["endtime"]);
            if (empty($data["title"]) || empty($data["type"]) || empty($data["aid"])) {
                _message("插入失败");
            }

            if ($data["type"] == "text") {
                $data["content"] = trim($data["text"]);
            }
            else if ($data["type"] == "code") {
                $data["content"] = trim($data["code"]);
            }
            else if ($data["type"] == "img") {
                $data["content"] = (isset($data["adphoto"]) ? $data["adphoto"] : "");
                $data["content"] = trim($data["content"], ".");
            }

            unset($data["text"]);
            unset($data["code"]);
            unset($data["adphoto"]);
            $res = $this->model->ad_save("`id`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功", G_MODULE_PATH . "/other/ad/");
            }
            else {
                _message("修改失败");
            }
        }

        $this->view->data("ad", $ad);
        $this->view->data("ad_pos", $ad_pos);
        $this->view->tpl("other.ad_add")->data("ments", $this->ad_ments);
    }

    public function upload()
    {
        if ($this->segment(4)) {
            $dir = trim($this->segment(4), "-");
            $dir = str_replace("-", DIRECTORY_SEPARATOR, $dir);
            $dirpath = G_UPLOAD . $dir;
            $ipath = G_MODULE_PATH . "/other/upload/" . $this->segment(4);
            $opath = G_UPLOAD_PATH . "/" . $dir;
        }
        else {
            $dirpath = G_UPLOAD;
            $ipath = G_MODULE_PATH . "/other/upload/";
            $opath = G_UPLOAD_PATH;
        }

        $arr = array();

        if (file_exists($dirpath)) {
            if ($dh = opendir($dirpath)) {
                while (($file = readdir($dh)) !== false) {
                    $file = mb_convert_encoding($file, "UTF-8", "GBK");
                    if (($file != ".") && ($file != "..")) {
                        if (is_dir($dirpath . DIRECTORY_SEPARATOR . $file)) {
                            $arr[] = array("type" => "目录", "name" => $file, "url" => $ipath . $file . "-");
                        }
                        else {
                            $arr[] = array("type" => "文件", "name" => $file, "url" => $opath . "/" . $file);
                        }
                    }
                }

                closedir($dh);
            }
        }

        $this->view->data("dir", explode("\\", $dir));
        $this->view->data("arr", $arr);
        $this->view->tpl("other.upload")->data("ment", $this->ment);
    }

    public function caches()
    {
        if (isset($_POST["dosubmit"])) {
            $c_ok = "";
            $data = _post("cache");

            if (isset($data["template"])) {
                $c_ok .= $this->model->tempcache();
            }

            if (isset($data["file_cache"])) {
                $c_ok .= $this->model->upfulecache();
            }

            if (isset($data["logs_cache"])) {
                $c_ok .= $this->model->uplogscache();
            }

            if (isset($data["admin_log_cache"])) {
                $c_ok .= $this->model->admin_log_cache();
            }

            _message($c_ok);
        }

        $this->view->tpl("other.cache");
    }
}
