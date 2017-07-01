<?php

function hueisd_id($sd_id, $leixin = NULL)
{
    $mysql_model = System::load_sys_class("model");
    $sdhf = $mysql_model->GetOne("select * from `@#_shaidan_hueifu` where `sdhf_id`='$sd_id' order by id DESC limit 1");

    if (!$sdhf) {
        return false;
    }

    $sdhf["sdhf_content"] = _htmtocode($sdhf["sdhf_content"]);
    return $sdhf;
}

function shopimg($shopid)
{
    $shop = System::load_app_model("cloud_goods", "common");
    $shop = $shop->cloud_goodsdetail($shopid);
    return $shop["g_thumb"];
}

function shoplisext($shopid, $zd)
{
    $shop = System::load_app_model("cloud_goods", "common");
    $shop = $shop->cloud_goodsdetail($shopid);
    return $shop[$zd];
}

function huodemem($shopuserid)
{
    $mysql_model = System::load_sys_class("model");
    $userid = $mysql_model->GetOne("select * from `@#_user` where `uid`='$shopuserid'");

    if ($userid["username"]) {
        return $userid["username"];
    }
    else if ($userid["mobile"]) {
        return _strcut($userid["mobile"], 7);
    }
    else {
        return _strcut($userid["email"], 7);
    }
}

function idjia($id)
{
    return $id + 1000000000;
}

function expimage($imgstr, $n = 0)
{
    if ($imgstr != NULL) {
        $imgstr = string2array($imgstr);
        return $imgstr[$n];
    }

    return "";
}

function expimage_jpg($imgstr)
{
    $imgstr = string2array($imgstr);
    $s = explode(".", $imgstr[0]);
    return $s[1];
}

function percent($p, $t)
{
    if ($p <= 0) {
        return false;
    }

    return sprintf("%.2f%%", ($p / $t) * 100);
}

function width($p, $t, $w)
{
    if ($p <= 0) {
        return false;
    }

    return ($p / $t) * $w;
}

function coo($id)
{
    $code = _getcookie("CODE");
    $cook = explode(",", $code);
    $count = count($cook) - 1;

    for ($i = 0; $i < $count; $i++) {
        if ($id == $cook[$i]) {
            return true;
        }
    }
}

function yunjl($id, $n = 1)
{
    $mysql_model = System::load_sys_class("model");
    $shop = $mysql_model->GetOne("select * from `@#_shoplist` where `id`='" . $id . "'");
    return $shop["thumb"];
}

function microt($time, $x = NULL)
{
    $len = strlen($time);

    if ($len < 13) {
        $time = $time . ".0";
    }

    $list = explode(".", $time);

    if ($x == "L") {
        return date("His", $list[0]) . substr($list[1], 0, 3);
    }
    else if ($x == "Y") {
        return date("Y-m-d", $list[0]);
    }
    else if ($x == "h") {
        return date("H:i:s", $list[0]);
    }
    else if ($x == "r") {
        return date("Y年m月d日 H:i:s", $list[0]);
    }
    else if ($x == "m") {
        return date("m月d日 H:i:s", $list[0]);
    }
    else if ($x == "s") {
        return date("m月d日", $list[0]);
    }
    else if ($x == "H") {
        return date("H:i:s", $list[0]) . "." . substr($list[1], 0, 3);
    }
    else {
        return date("Y-m-d H:i:s", $list[0]) . "." . substr($list[1], 0, 3);
    }
}

function yunci($uid, $shopid, $shopqishu)
{
    $mysql_model = System::load_sys_class("model");
    $record2 = $mysql_model->GetList("select * from `@#_member_go_record` where `uid`='" . $uid . "' and `shopid`='" . $shopid . "' and `shopqishu`='" . $shopqishu . "'");
    $cord = "";

    foreach ($record2 as $record ) {
        $cord .= $record["goucode"];
    }

    $list = explode(",", $cord);
    return count($list) - 1;
}

function yuncifen($cord)
{
    $list = explode(",", $cord);
    return count($list) - 1;
}

function yunma($ma, $html = "span")
{
    $list = explode(",", $ma);
    $st = "";

    foreach ($list as $list2 ) {
        $st .= "<" . $html . ">" . $list2 . "</" . $html . ">";
    }

    return $st;
}

function get_shop_if_jiexiao($shopid = NULL, $type = NULL)
{
    $db = System::load_sys_class("model");
    $record = $db->GetOne("select `q_uid`,`q_end_time`,`q_user`,`q_user_code`,`q_showtime` from `@#_cloud_goods` where `id`='$shopid' and `q_uid` is not null and  `q_showtime` = 'N' LIMIT 1");

    if (!$record) {
        return false;
    }

    if ($record["q_user"]) {
        $record["q_user"] = unserialize($record["q_user"]);

        if ($type) {
            return $record[$type];
        }
        else {
            return $record;
        }
    }
    else if ($type) {
        return $record[$type];
    }
    else {
        return $record;
    }
}

function huodez($shopid, $shopqishu, $canshu = NULL)
{
    $mysql_model = System::load_sys_class("model");
    $record = $mysql_model->GetOne("select * from `@#_member_go_record` where `shopid`='$shopid' and `shopqishu`='$shopqishu' and `huode`>'10000000'");
    return $record[$canshu];
}

function toujpg($uid)
{
    $mysql_model = System::load_sys_class("model");
    $member = $mysql_model->GetOne("select * from `@#_member` where `uid`='" . $uid . "'");

    if ($member["img"] != NULL) {
        $s = explode(".", $member["img"]);
        $url = G_UPLOAD_PATH . "/" . $member["img"] . "_30." . $s[1];
        return $url;
    }
    else {
        $url = G_TEMPLATES_STYLE . "/images/prmimg.jpg";
        return $url;
    }
}

function qztitle($id)
{
    $mysql_model = System::load_sys_class("model");
    $qztit = $mysql_model->GetOne("select * from `@#_quanzi` where `id`='" . $id . "'");
    return $qztit["title"];
}

function tiezi($id)
{
    $mysql_model = System::load_sys_class("model");
    $tiezi = $mysql_model->GetList("select * from `@#_quanzi_hueifu` where `tzid`='" . $id . "'");
    return count($tiezi);
}

function headerment($ments = NULL)
{
    $html = "";
    $html_l = "";
    $URL = trim(get_web_url(), "/");

    if (is_array($ments)) {
        $ment = $ments;
    }
    else {
        if (!isset($ments)) {
            return false;
        }

        $ment = $ments;
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

function catname($id)
{
    $mysql_model = System::load_sys_class("model");
    $sql = "select `cateid`,`name` from `@#_cate` where  `cateid` = " . $id;
    $list = $mysql_model->GetOne($sql);
    return $list["name"];
}


?>
