<?php

class finduser extends SystemAction
{
    public function __construct()
    {
        $this->userdb = System::load_app_model("user", "common");
    }

    /**
     * 找回密码
     */
    public function findpassword()
    {
        if ( isset( $_POST["name"] ) ) {
            $name = (isset($_POST["name"]) ? $_POST["name"] : "");
            $txtRegSN = strtoupper($_POST["txtRegSN"]);

            if ( ! _ifcookiecode( $txtRegSN, "Captcha" ) ) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", l("captche.no"), 1);
            }

            $regtype = NULL;

            if ( _checkmobile( $name ) ) {
                $regtype = "mobile";
            }

            if ( _checkemail( $name ) ) {
                $regtype = "email";
            }

            if ($regtype == NULL) {
                _message("帐号类型不正确!", NULL, 3);
            }

            $where = "$regtype = '$name'";
            $info = $this->userdb->SelectUser( $where );

            if ( ! $info ) {
                _message("帐号不存在");
            }

            header("location:" . WEB_PATH . "/member/finduser/find" . $regtype . "check/" . _encrypt($name));
        }

        $title = "找回密码";
        $this->view->show("user.findpassword");
    }

    public function findsendmobile()
    {
        $name = _encrypt($this->segment(4), "DECODE");
        $where = "`mobile` = '$name'";
        $member = $this->userdb->SelectUser($where);

        if (!$member) {
            _message("参数不正确!");
        }

        $checkcode = explode("|", $member["mobilecode"]);
        $times = time() - $checkcode[1];

        if ( 120 < $times ) {
            $mobile_code = rand(100000, 999999);
            $mobile_time = time();
            $mobilecodes = $mobile_code . "|" . $mobile_time;
            $setkey = "passcode='$mobilecodes'";
            $where = "`uid`='{$member["uid"]}'";
            $this->userdb->UpdateUser($setkey, $where);
            $temp_m_pwd = findconfig("template", "m_pwd_temp");
            $text = str_replace( '000000', $mobile_code, $temp_m_pwd );
            $sendok = _sendmobile( $name, $text );
            if ( $sendok[0] != 1 ) {
                _message( $sendok[1] );
            }

            _message("正在重新发送...", WEB_PATH . "/member/finduser/findmobilecheck/" . _encrypt($member["mobile"]), 2);
        }
        else {
            _message("重发时间间隔不能小于2分钟!", WEB_PATH . "/member/finduser/findmobilecheck/" . _encrypt($member["mobile"]));
        }
    }

    public function findmobilecheck()
    {
        $title = "手机找回密码";
        $time = 120;
        $namestr = $this->segment(4);
        $name = _encrypt($namestr, "DECODE");

        if (strlen($name) != 11) {
            _message("参数错误！");
        }

        $where = "`mobile` = '$name'";
        $member = $this->userdb->SelectUser($where);

        if (!$member) {
            _message("参数不正确!");
        }

        if ($member["passcode"] == -1) {
            $randcode = rand(100000, 999999);
            $checkcodes = $randcode . "|" . time();
            $setkey = "passcode='$checkcodes'";
            $where = "`uid`='{$member["uid"]}'";
            $this->userdb->UpdateUser($setkey, $where);
            $temp_m_pwd = findconfig("template", "m_pwd_temp");
            $text = str_replace("000000", $randcode, $temp_m_pwd);
            $sendok = _sendmobile($name, $text);

            if ($sendok[0] != 1) {
                _message($sendok[1]);
            }

            header("location:" . WEB_PATH . "/member/finduser/findmobilecheck/" . _encrypt($member["mobile"]));
            exit();
        }

        if (isset($_POST["ok"]) || isset($_POST["submit"])) {
            $checkcodes = (isset($_POST["checkcode"]) ? $_POST["checkcode"] : _message("参数不正确!"));

            if (strlen($checkcodes) != 6) {
                _message("验证码输入不正确!");
            }

            $usercode = explode("|", $member["passcode"]);

            if ($checkcodes != $usercode[0]) {
                _message("验证码输入不正确!");
            }

            $urlcheckcode = _encrypt($member["mobile"] . "|" . $member["passcode"]);
            _setcookie("uid", _encrypt($member["uid"]));
            _setcookie("ushell", _encrypt(md5($member["uid"] . $member["password"])));
            header("location:" . WEB_PATH . "/member/finduser/findok/" . $urlcheckcode);
            exit();
        }

        $enname = substr($name, 0, 3) . "****" . substr($name, 7, 10);
        $time = 120;
        $this->view->data("enname", $enname);
        $this->view->data("namestr", $namestr);
        $this->view->data("time", $time);
        $this->view->show("user.findmobilecheck");
    }

    public function findsendemail()
    {
        $name = _encrypt($this->segment(4), "DECODE");
        $where = "`email` = '$name'";
        $member = $this->userdb->SelectUser($where);

        if (!$member) {
            _message("参数错误!");
        }

        $setkey = "`passcode`='-1'";
        $where = "`uid`='{$member["uid"]}'";
        $this->userdb->UpdateUser($setkey, $where);
        _message("正在重新发送...", WEB_PATH . "/member/finduser/findemailcheck/" . $this->segment(4), 2);
        exit();
    }

    public function findemailcheck()
    {
        $title = "通过邮箱找回密码";
        $name = _encrypt($this->segment(4), "DECODE");
        $where = "`email` = '$name'";
        $info = $this->userdb->SelectUser($where);

        if (!$info) {
            _message("未知错误!");
        }

        $emailurl = explode("@", $info["email"]);

        if ($info["passcode"] == -1) {
            $passcode = _getcode(10);
            $passcode = $passcode["code"] . "|" . $passcode["time"];
            $urlcheckcode = _encrypt($info["email"] . "|" . $passcode);
            $url = WEB_PATH . "/member/finduser/findok/" . $urlcheckcode;
            $setkey = "`passcode`='$passcode'";
            $where = "`uid`='{$info["uid"]}'";
            $this->userdb->UpdateUser($setkey, $where);
            $tit = _cfg("web_name") . "邮箱找回密码";
            $con = "<a href=\"" . WEB_PATH . "/member/finduser/findok/" . $urlcheckcode . "\">";
            $con .= $url;
            $con .= "</a>";
            $e_pwd_temp = findconfig("template", "e_pwd_temp");
            $content = str_replace("{地址}", $con, $e_pwd_temp);
            _sendemail($info["email"], "", $tit, $content);
        }

        $this->view->data( 'en_email', $this->segment(4) );
        $this->view->data( "email", $info["email"] );
        $this->view->data( "emailurl", $emailurl );
        $this->view->show( "user.findemailcheck" );
    }

    public function findok()
    {
        $key = $this->segment(4);

        if (empty($key)) {
            _message("未知错误");
        }
        else {
            $key = $this->segment(4);
        }

        $checkcode = explode("|", _encrypt($key, "DECODE"));

        if (count($checkcode) != 3) {
            _message("未知错误", NULL, 3);
        }

        $emailurl = explode("@", $checkcode[0]);

        if ($emailurl[1]) {
            $where = "`email`='$checkcode[0]' AND `passcode`= '$checkcode[1]|$checkcode[2]'";
        }
        else {
            $where = "`mobile`='$checkcode[0]' AND `passcode`= '$checkcode[1]|$checkcode[2]'";
        }

        $member = $this->userdb->SelectUser($where);

        if (!$member) {
            _message("帐号或验证码错误", NULL, 2);
        }

        $usercheck = explode("|", $member["passcode"]);
        $timec = time() - $usercheck[1];

        if ($timec < (3600 * 24)) {
            $title = "重置密码";
            $this->view->show("user.findok")->data("checkcode", $checkcode)->data("key", $key);
        }
        else {
            $title = "验证失败";
            $this->view->show("user.finderror")->data("key", $key);
        }
    }

    public function resetpassword()
    {
        if (isset($_POST["ok"]) || isset($_POST["submit"])) {
            $key = $_POST["hidKey"];
            $password = md5(md5($_POST["userpassword"]) . md5($_POST["userpassword"]));
            $checkcode = explode("|", _encrypt($key, "DECODE"));

            if (count($checkcode) != 3) {
                _message("未知错误", NULL, 3);
            }

            $emailurl = explode("@", $checkcode[0]);

            if ($emailurl[1]) {
                $where = "`email`='$checkcode[0]' AND `passcode`= '$checkcode[1]|$checkcode[2]'";
            }
            else {
                $where = "`mobile`='$checkcode[0]' AND `passcode`= '$checkcode[1]|$checkcode[2]'";
            }

            $member = $this->userdb->SelectUser($where);

            if (!$member) {
                _message("未知错误!");
            }

            $setkey = "`password`='$password',`passcode`='-1'";
            $where = "`uid`='{$member["uid"]}'";
            $this->userdb->UpdateUser($setkey, $where);
            _message("密码重置成功", WEB_PATH . "/login");
        }
    }
}


?>
