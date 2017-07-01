<?php

function css($src = NULL, $path = NULL)
{
    static $css = array();

    if (!empty($src)) {
        $css[] = (empty($path) ? G_TEMPLATES_CSS . "/" . $src . ".css" : trim($path, "/") . "/" . $src . ".css");
        return NULL;
    }

    foreach ($css as $v ) {
        echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"" . $v . "\"/>" . PHP_EOL;
    }
}

function js($src = NULL, $path = NULL)
{
    static $js = array();

    if (!empty($src)) {
        $js[] = (empty($path) ? G_TEMPLATES_JS . "/" . $src . ".js" : trim($path, "/") . "/" . $src . ".js");
        return NULL;
    }

    foreach ($js as $v ) {
        echo "<script type=\"text/javascript\" src=\"" . $v . "\"></script>" . PHP_EOL;
    }
}

function color_css()
{
    $colorcss = System::load_sys_config("view");
    $temp = $colorcss["skin"]["pc"];

    if (isset($colorcss["templates"][$temp]["colorname"])) {
        return $colorcss["templates"][$temp]["colorname"];
    }
    else {
        return "color";
    }
}

function login($a = "")
{
    $user = System::load_app_class("UserCheck", "common")->UserInfo;
    $ret = array("我的" . _cfg("web_name_two") => WEB_PATH . "/member/home/userindex", L("html.key") . "记录" => WEB_PATH . "/member/shop/userbuylist", "获得商品" => WEB_PATH . "/member/shop/orderlist", "帐户充值" => WEB_PATH . "/member/account/userrecharge", "个人设置" => WEB_PATH . "/member/home/modify");

    if ($a == "bool") {
        if ($user) {
            return array("name" => $user["username"], "uid" => $user["uid"], "url" => WEB_PATH . "/member/home/userindex");
        }
        else {
            return false;
        }
    }

    if ($a == "list") {
        return $ret;
    }

    if ($a == "view") {
        return array("注册" => WEB_PATH . "/register", "登录" => WEB_PATH . "/login");
    }
}

function getimgshow($content = NULL, $path = G_UPLOAD_PATH)
{
    return $path . "/" . $content;
}

function seo($keys = NULL, $vals = NULL, $seotype = NULL, $typeinfo = NULL)
{
    $config = System::load_sys_config("system");
    static $seo = array();
    if (!$keys && !$vals) {
        foreach ($seo as $key => $val ) {
            if ($key == "title") {
                echo "<title>$val</title>" . PHP_EOL;
            }
            else {
                echo "<meta name=\"$key\" content=\"$val\" />" . PHP_EOL;
            }
        }

        return "";
    }

    $vals = str_replace("{Web_name}", $config["web_name"], $vals);
    $vals = str_replace("{Web_name_two}", $config["web_name_two"], $vals);

    if ($seotype) {
        switch ($seotype) {
        case "index":
            $vals = str_replace("{Web_name}", $config["web_name"], $vals);
            $vals = str_replace("{Web_name_two}", $config["web_name_two"], $vals);
            break;

        case "glist":
            if ($typeinfo) {
                $vals = str_replace("{Goods_brand}", $typeinfo["brand"], $vals);
                $vals = str_replace("{Goodscete_name}", $typeinfo["title"], $vals);
                $vals = str_replace("{Goodscete_keyword}", $typeinfo["keyword"], $vals);
                $vals = str_replace("{Goodscete_desc}", $typeinfo["description"], $vals);
            }

            break;

        case "gitem":
            if ($typeinfo) {
                $vals = str_replace("{Goods_brand}", $typeinfo["g_brand"], $vals);
                $vals = str_replace("{Goods_name}", $typeinfo["g_title"], $vals);
                $vals = str_replace("{Goods_keyword}", $typeinfo["g_keyword"], $vals);
                $vals = str_replace("{Goods_desc}", $typeinfo["g_description"], $vals);
            }

            break;

        case "user":
            if ($typeinfo) {
                $vals = str_replace("{User_name}", $typeinfo["username"], $vals);
                $vals = str_replace("{User_sign}", $typeinfo["qianming"], $vals);
            }

            break;

        case "article":
            if ($typeinfo) {
                $vals = str_replace("{Article_title}", $typeinfo["title"], $vals);
                $vals = str_replace("{Article_keyword}", $typeinfo["keywords"], $vals);
                $vals = str_replace("{Article_desc}", $typeinfo["description"], $vals);
            }

            break;

        case "share":
            if ($typeinfo) {
                $vals = str_replace("{User_name}", $addwords, $vals);
                $vals = str_replace("{User_sign}", $addwords, $vals);
            }

            break;
        }
    }

    $vals = str_replace("{Web_name}", "", $vals);
    $vals = str_replace("{Web_name_two}", "", $vals);
    $vals = str_replace("{Goods_name}", "", $vals);
    $vals = str_replace("{Goods_brand}", "", $vals);
    $vals = str_replace("{Goodscete_name}", "", $vals);
    $vals = str_replace("{Goodscete_keyword}", "", $vals);
    $vals = str_replace("{Goodscete_desc}", "", $vals);
    $vals = str_replace("{Goods_keyword}", "", $vals);
    $vals = str_replace("{Goods_desc}", "", $vals);
    $vals = str_replace("{User_name}", "", $vals);
    $vals = str_replace("{User_sign}", "", $vals);
    $vals = str_replace("{Article_title}", "", $vals);
    $vals = str_replace("{Article_desc}", "", $vals);
    $vals = str_replace("{Article_keyword}", "", $vals);
    $seo[$keys] = $vals;
}

function findconfig($module, $key = "")
{
    $mysql_model = System::load_sys_class("model");

    if ($key == "") {
        $sql = "SELECT * FROM `@#_config` WHERE `modules`='" . $module . "'";
        $res = $mysql_model->GetList($sql);

        if ($res) {
            return _arr2to1($res, "name", "value");
        }
        else {
            return "";
        }
    }
    else {
        $sql = "SELECT * FROM `@#_config` WHERE `name`='" . $key . "' AND `modules`='" . $module . "'";
        $res = $mysql_model->GetOne($sql);

        if ($res) {
            if (G_IS_MOBILE && ($key == "pay_bank_type")) {
                $type = 2;
                $sql = "SELECT pay_id FROM `@#_payment` WHERE `pay_class`='{$res["value"]}' and `pay_start`='1'  and `pay_mobile` LIKE '%$type%'";
                $paymobile = $mysql_model->GetOne($sql);

                if ($paymobile) {
                    return $res["value"];
                }
                else {
                    return "";
                }
            }
            else {
                return $res["value"];
            }
        }
        else {
            return "";
        }
    }
}

function readapp($num = "3")
{
    $weixin = array();

    for ($i = 1; $i <= $num; $i++) {
        if (findconfig("app", "name_" . $i)) {
            $weixin[$i]["name"] = findconfig("app", "name_" . $i);

            if (findconfig("app", "img_" . $i)) {
                $weixin[$i]["img"] = findconfig("app", "img_" . $i);
            }
        }
    }

    return $weixin;
}

function readad($type = "img", $aid = "")
{
    $mysql_model = System::load_sys_class("model");
    $time = time();

    if ($aid) {
        $sql = "SELECT * FROM `@#_ad_contents` WHERE `aid`='$aid' and  `type`='$type' and `checked`='1' and `endtime`<=$time";
    }
    else {
        $sql = "SELECT * FROM `@#_ad_contents` WHERE  `type`='$type' and `checked`='1'";
    }

    $readad = $mysql_model->GetList($sql);

    if ($readad) {
        $i = 0;

        foreach ($readad as $key => $v ) {
            $readad[$key]["key"] = $i;
            $i++;
        }

        return $readad;
    }
    else {
        return "";
    }
}

function create_table($old_table, $new_table)
{
    $mysql_model = System::load_sys_class("model");
    $sql = "create table `" . $new_table . "` LIKE `" . $old_table . "`";
    return $mysql_model->Query($sql);
}

function Getlogo()
{
    $mysql_model = System::load_sys_class("model");
    $web_logo = $mysql_model->GetOne("select * from `@#_config` where `name`='web_logo'");
    return $web_logo["value"];
}

function Getheader($type = "index", $focus_urlstyle = "")
{
    $mysql_model = System::load_sys_class("model");
    $navigation = $mysql_model->GetList("select * from `@#_nav` where `status`='Y' and `type` = '$type' order by `sort` DESC");

    if (!$navigation) {
        return false;
    }

    $urld = get_web_url();

    if ($type == "foot") {
        foreach ($navigation as $key => $v ) {
            $navigation[$key]["url_target"] = "_blank";
            $navigation[$key]["focus_urlstyle"] = "";

            if ($v["url"] == $urld) {
                $navigation[$key]["focus_urlstyle"] = $focus_urlstyle;
            }

            if (!strstr($v["url"], "http")) {
                $navigation[$key]["url"] = WEB_PATH . $v["url"];
                $navigation[$key]["url_target"] = "_parent";
            }
        }
    }
    else {
        foreach ($navigation as $key => $v ) {
            $navigation[$key]["url_target"] = "_blank";
            $navigation[$key]["focus_urlstyle"] = "";

            if ($v["url"] == $urld) {
                $navigation[$key]["focus_urlstyle"] = $focus_urlstyle;
            }

            if (!strstr($v["url"], "http")) {
                $navigation[$key]["url"] = WEB_PATH . $v["url"];
                $navigation[$key]["url_target"] = "_parent";
            }
        }
    }

    return $navigation;
}

function GetCate($pid = 0, $num = 100, $sub_num = 0, $sub_type = 0, $sort = "DESC")
{
    $mysql_model = System::load_sys_class("model");
    $sql = "select `cateid`,`name`,`info` from `@#_cate` where `model`='1' and `parentid` = '" . $pid . "' order by `sort` " . $sort . " LIMIT 0," . $num;

    if ($pid != 0) {
        $sql = "select `cateid`,`name` from `@#_cate`  where  `parentid` = '" . $pid . "' order by `sort` " . $sort . " LIMIT 0," . $num;
    }

    $cate = $mysql_model->GetList($sql);

    if (!$cate) {
        return false;
    }

    if (0 < $sub_num) {
        if ($sub_type == 0) {
            foreach ($cate as &$row ) {
                $row["sub_cate"] = $mysql_model->GetList("select id,cateid,name from `@#_brand` where  CONCAT(',',`cateid`,',') LIKE '%," . $row["cateid"] . ",%' order by `sort` DESC limit 0," . $sub_num);
            }
        }
        else {
            foreach ($cate as &$row ) {
                $row["sub_cate"] = $mysql_model->GetList("select * from `@#_article` where `cateid`='" . $row["cateid"] . "' limit 0," . $sub_num);
            }
        }
    }

    return $cate;
}

function _app_cfg($model, $key)
{
    $mysql_model = System::load_sys_class("model");
    $arr = $mysql_model->GetOne("SELECT * FROM `@#_config` WHERE `modules` = '" . $model . "' and `name`='" . $key . "'");
    return $arr["value"];
}

function Getslide($num = 5, $type = 1)
{
    $mysql_model = System::load_sys_class("model");
    $slidessql = "select * from `@#_slide` where `type`=" . $type . " order by id desc limit 0," . $num;
    $slides = $mysql_model->GetList($slidessql);
    return $slides;
}

function Getsearch()
{
    $mysql_model = System::load_sys_class("model");
    $slides = $mysql_model->GetOne("select * from `@#_config` where `name`='searchtag'");
    $slides = explode("|", $slides["value"]);
    return $slides;
}

function fund_is_off()
{
    $mysql_model = System::load_sys_class("model");
    $recordx = $mysql_model->GetOne("select fund_off from `@#_fund` limit 1");
    return $recordx["fund_off"];
}

function help($cateid = "", $aid = "")
{
    $mysql_model = System::load_sys_class("model");
    $bangzhu = $mysql_model->GetList("select * from `@#_article` where `cateid`='$cateid'");

    if (G_IS_MOBILE) {
        $adb = System::load_app_model("article", "common");
        ($article = $adb->GetArticleAid($cateid)) || $this->SendStatus(404);
        return $article["content"];
    }
    else {
        $li = "";

        foreach ($bangzhu as $bangzhutu ) {
            if ($bangzhutu["id"] == $aid) {
                $li .= "<li><a href=\"" . WEB_PATH . "/article-" . $bangzhutu["id"] . ".html\" class=\"cur1\"><b></b>" . $bangzhutu["title"] . "</a></li>";
            }
            else {
                $li .= "<li><a href=\"" . WEB_PATH . "/article-" . $bangzhutu["id"] . ".html\" class=\"cur2\"><b></b>" . $bangzhutu["title"] . "</a></li>";
            }
        }

        return $li;
    }
}

function go_count_renci()
{
    $mysql_model = System::load_sys_class("model");
    $recordx = $mysql_model->GetOne("select * from `@#_config` where `name` = 'goods_count_num'");
    $q_rc = $recordx["value"];
    $q_rc_num_arr = array();
    $q_rc_num = strlen($recordx["value"]);

    for ($q_i = 0; $q_i < $q_rc_num; $q_i++) {
        $q_rc_num_arr[$q_i] = substr($q_rc, $q_i, 1);
    }

    return $q_rc_num_arr;
}

function go_count_record($shopid, $uid, $type)
{
    $mysql_model = System::load_sys_class("model");
    $order_db = System::load_app_model("order", "common");
    $selectwords = "`ogid` = $shopid and `ouid` = $uid";
    $count_record = $order_db->ready_order($selectwords, 1);

    if ($count_record) {
        $cgoodsinfo = $mysql_model->GetOne("select * from `@#_cloud_goods` where `id` = $shopid");

        foreach ($count_record as $v ) {
            $unum += $v["onum"];
        }

        if ($type == "s") {
            return $unum;
        }

        if ($type == "m") {
            $sum_countm = $cgoodsinfo["price"] * $unum;
            return $sum_countm;
        }

        if ($type == "l") {
            $sum_countl = $cgoodsinfo["zongrenshu"] / $unum;
            return sprintf("%.3f", $sum_countl);
        }
    }
    else {
        return "";
    }
}

function cattype($n = 0)
{
    if (0 < $n) {
        return "<font>内部栏目</font>";
    }

    if ($n == -1) {
        return "<font color=\"#ff0000\">单网页</font>";
    }

    if ($n == -2) {
        return "<font color=\"#09f\">链接</font>";
    }
}

function indexTemplate($zhengze = "")
{
    $html_root = G_TEMPLATES . DIRECTORY_SEPARATOR . G_STYLE . DIRECTORY_SEPARATOR . G_STYLE_HTML;
    $html_arr = scandir($html_root);

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

function Getuserinfo($uid = "", $key = "*")
{
    $mysql_model = System::load_sys_class("model");
    $userinfo = $mysql_model->GetOne("select $key from `@#_user` where `uid` = '$uid'");
    return $userinfo[$key];
}

function Getusername($uid)
{
    $mysql_model = System::load_sys_class("model");
    $userinfo = $mysql_model->GetOne("select `username` from `@#_user` where `uid` = '$uid'");
    return $userinfo["username"];
}

function Getuserimg($uid)
{
    $mysql_model = System::load_sys_class("model");
    $userinfo = $mysql_model->GetOne("select `img` from `@#_user` where `uid` = '$uid'");
    return $userinfo["img"];
}

function GetConfig($cname)
{
    $config = System::load_sys_config("system");

    if ($config[$cname]) {
        return $config[$cname];
    }
    else {
        return "";
    }
}

function GetConfiga($filename = "")
{
    if (file_exists(G_CONFIG . $filename . ".inc.php")) {
        $acc_code = file_get_contents(G_CONFIG . $filename . ".inc.php");
        $acc_code = stripslashes($acc_code);
        return $acc_code;
    }
    else {
        return "";
    }
}

function GetConfigarray($filename = "", $name = "")
{
    if (file_exists(G_CONFIG . $filename . ".inc.php")) {
        $acc_code = System::load_sys_config($filename);
        return $acc_code[$name];
    }
    else {
        return "";
    }
}

function GetOrders($oid = "", $name = "")
{
    $mysql_model = System::load_sys_class("model");
    $orderinfo = $mysql_model->GetOne("select * from `@#_orders` where `oid` = '$oid'");

    if ($name == "ofstatus") {
        if ($orderinfo && $orderinfo[$name]) {
            switch ($orderinfo[$name]) {
            case "1":
                return "等待发货";
                break;

            case "2":
                return "已发货";
                break;

            case "3":
                return "已收货";
                break;
            }

            return "无状态";
        }
    }
    else {
        if ($orderinfo && $orderinfo[$name]) {
            return $orderinfo[$name];
        }
    }

    return "";
}

function Getships($oid = "", $name = "", $stype = "3")
{
    $mysql_model = System::load_sys_class("model");
    $shipinfo = $mysql_model->GetOne("select * from `@#_ship` where `oid` = '$oid' and `etype`=$stype");
    if ($shipinfo && $shipinfo[$name]) {
        if ($name == "eid") {
            $emsinfo = $mysql_model->GetOne("select * from `@#_ems` where `eid` = '$shipinfo[$name]'");
            if ($emsinfo && $emsinfo["ename"]) {
                return $emsinfo["ename"];
            }
            else {
                return "";
            }
        }

        return $shipinfo[$name];
    }
    else {
        return "";
    }
}

function useri_title($g_title = "", $type = "")
{
    $g_title = unserialize($g_title);

    if (is_object($g_title)) {
        $g_title = (array) $g_title;
    }

    return $g_title[$type];
}


