<?php

function send_mobile_reg_code($mobile = NULL, $uid = NULL)
{
    if (!$uid) {
        _message("发送用户手机认证码,用户ID不能为空！");
    }

    if (!$mobile) {
        _message("发送用户手机认证码,手机号码不能为空!");
    }

    $db = System::load_sys_class("model");
    $checkcodes = rand(100000, 999999) . "|" . time();
    $db->Query("UPDATE `@#_user` SET mobilecode='$checkcodes' where `uid`='$uid'");
    $checkcodes = explode("|", $checkcodes);
    $template = $db->GetOne("select * from `@#_config` where `name` = 'm_reg_temp'");

    if (!$template) {
        $content = "您在" . _cfg("web_name") . "的短信验证码是:" . strtolower($checkcodes[0]);
    }

    if (empty($template["value"])) {
        $content = "您在" . _cfg("web_name") . "的短信验证码是:" . strtolower($checkcodes[0]);
    }
    else if (strpos($template["value"], "000000") == true) {
        $content = str_ireplace("000000", strtolower($checkcodes[0]), $template["value"]);
    }
    else {
        $content = $template["value"] . strtolower($checkcodes[0]);
    }

    return _sendmobile($mobile, $content);
}

function send_mobile_shop_code($mobile = NULL, $uid = NULL, $code = NULL, $shoptitle = NULL)
{
    if (!$uid) {
        _message("发送用户手机获奖短信,用户ID不能为空！");
    }

    if (!$mobile) {
        _message("发送用户手机获奖短信,手机号码不能为空!");
    }

    if (!$code) {
        _message("发送用户手机获奖短信,中奖码不能为空!");
    }

    $db = System::load_sys_class("model");
    $template = $db->GetOne("select * from `@#_config` where `name` = 'm_shop_temp'");

    if (!$template) {
        $template = array();
        $content = "你在" . _cfg("web_name") . "够买的商品已中奖,中奖码是:" . $code;
    }

    if (empty($template["value"])) {
        $content = "你在" . _cfg("web_name") . "够买的商品已中奖,中奖码是:" . $code;
    }
    else if (strpos($template["value"], "00000000") == true) {
        $content = str_ireplace("00000000", $code, $template["value"]);
        if ( strpos($content, "{商品名称}") == true )
        {
            $content = str_ireplace("{商品名称}", $shoptitle, $content);
        }
    }
    else {
        $content = $template["value"] . $code;
    }

    return _sendmobile($mobile, $content);
}

function send_email_reg($email = NULL, $uid = NULL)
{
    $db = System::load_sys_class("model");
    $checkcode = _getcode(10);
    $checkcode_sql = $checkcode["code"] . "|" . $checkcode["time"];
    $check_code = serialize(array("email" => $email, "code" => $checkcode["code"], "time" => $checkcode["time"]));
    $check_code_url = _encrypt($check_code, "ENCODE", "", 3600 * 24);
    $clickurl = WEB_PATH . "/member/user/emailok/" . $check_code_url;
    $db->Query("UPDATE `@#_user` SET `emailcode`='$checkcode_sql' where `uid`='$uid'");
    $web_name = _cfg("web_name");
    $title = _cfg("web_name") . "激活注册邮箱";
    $template = $db->GetOne("select * from `@#_config` where `name` = 'e_reg_temp'");
    $url = "<a href=\"";
    $url .= $clickurl . "\">";
    $url .= $clickurl . "</a>";
    $template["value"] = str_ireplace("{地址}", $url, $template["value"]);
    return _sendemail($email, "", $title, $template["value"]);
}

function send_email_code($email = NULL, $username = NULL, $uid = NULL, $code = NULL, $shoptitle = NULL)
{
    $db = System::load_sys_class("model");
    $template = $db->GetOne("select * from `@#_config` where `name` = 'e_shop_temp'");

    if (!$template) {
        $template = array();
        $template["value"] = "恭喜您：$username,你在" . _cfg("web_name") . "够买的商品$shoptitle已中奖,中奖码是:" . $code;
    }
    else {
        $template["value"] = str_ireplace("{用户名}", $username, $template["value"]);
        $template["value"] = str_ireplace("{商品名称}", $shoptitle, $template["value"]);
        $template["value"] = str_ireplace("{中奖码}", $code, $template["value"]);
    }

    $title = "恭喜您!!! 您在" . _cfg("web_name") . "够买的商品中奖了!!!";
    return _sendemail($email, "", $title, $template["value"]);
}