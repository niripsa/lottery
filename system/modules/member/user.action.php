<?php
class user extends SystemAction
{
    public function login()
    {
        $user = System::load_app_class( 'UserCheck', 'common' );

        if ( $user->GetUserCheckToBool() ) 
        {
            $url = ( $this->segment( 4 ) ? urldecode( $this->segment( 4 ) ) : G_WEB_PATH );
            $this->SendStatus( 301, $url );
        }

        seo( 'title', _cfg('web_name') . '_登录' );
        seo( 'keywords', _cfg('web_name') . '_登录' );
        seo( 'description', _cfg('web_name') . '_登录' );
        $this->view->debug( false )->show();
    }

    /**
     * 用户注册
     */
    public function register()
    {
        seo("title", _cfg("web_name") . "_注册");
        seo("keywords", _cfg("web_name") . "_注册");
        seo("description", _cfg("web_name") . "_注册");
        $user = System::load_app_class("UserCheck", "common");

        if ( $user->GetUserCheckToBool() )
        {
            $url = ($this->segment(4) ? urldecode($this->segment(4)) : G_WEB_PATH);
            $this->SendStatus(301, G_WEB_PATH);
        }
        if ( $this->segment(4) )
        {
            _setcookie( 'yaoqing_uid', $this->segment(4), 86400 * 30 );
            $this->view->data( "decode", $this->segment(4) );
        }
        if ( $this->segment(5) )
        {
            $res = _setcookie( 'area_id', $this->segment(5) );
            $this->view->data( "area_id", $this->segment(5) );
        }
        
        $this->view->show();
    }

    /**
     * 会员退出
     */
    public function logout()
    {
        _setcookie("ushell", NULL, -1);
        _setcookie("uid", NULL, -1);
        _setcookie("username", NULL, -1);
        _message(L("user.out"), G_WEB_PATH);
    }

    public function loge()
    {
        _message(L("user.out"), G_WEB_PATH);
    }

    public function AjaxUserCheckToBool()
    {
        $UserCheck = System::load_app_class( "UserCheck", "common" );

        if ( $UserCheck->GetUserCheckToBool() )
        {
            $this->SendMsgJson("status", 1, 1);
        }
        else
        {
            $this->SendMsgJson("status", 0, 1);
        }
    }

    /**
     * 用户执行登陆
     */
    public function UserLogin()
    {
        $user     = (isset($_POST["user"]) ? $_POST["user"] : "");
        $pass     = (isset($_POST["pass"]) ? $_POST["pass"] : "");
        $savetime = (isset($_POST["savetime"]) ? $_POST["savetime"] : 86400 * 5);
        $captcha  = (isset($_POST["captcha"]) ? $_POST["captcha"] : false);
        $userdb = system::load_app_model( "user", "common" );
        if ( ! $user || ! $pass ) 
        {
            $this->SendMsgJson( "msg", L("user.account.no") );
            $this->SendMsgJson( "status", -1, 1 );
        }

        if ( ! _ifcookiecode( $captcha, "Captcha" ) ) 
        {
            $this->SendMsgJson( "msg", L("captche.no") );
            $this->SendMsgJson( "status", -1, 1 );
        }

        $UserCheck = System::load_app_class( "UserCheck", "common" );
        $return = $UserCheck->UserLogin( $user, $pass );

        if ( $return["status"] != 1 ) 
        {
            $this->SendMsgJson( "msg", $return["msg"] );
            $this->SendMsgJson( "status", $return["status"], 1 );
        }

        $usertype = ( _checkemail( $user ) ? "emailcode" : "mobilecode" );

        if ( $return["user"][$usertype] != 1 ) 
        {
            $this->SendMsgJson( "msg", L("user.mailnum.err") );
            $this->SendMsgJson( "status", -1, 1 );
        }

        $user = $return["user"];

        if ( $return["status"] == 1 ) 
        {
            $time    = time();
            $user_ip = _get_ip_dizhi();
            $setkey  = "`user_ip` = '$user_ip',`login_time` = '$time'";
            $where   = "`uid` = '{$user["uid"]}'";
            $userdb->UpdateUser( $setkey, $where );
        }

        $UserCheck->UserLoginStatus( $user, $savetime );
        $this->SendMsgJson( "msg", $return["msg"] );
        $this->SendMsgJson( "uid", $user["uid"] );
        $this->SendMsgJson( "status", $return["status"], 1 );
    }

    /**
     * PC 执行注册
     */
    public function UserRegister()
    {
        seo("title", _cfg("web_name") . "_注册");
        seo("keywords", _cfg("web_name") . "_注册");
        seo("description", _cfg("web_name") . "_注册");
        $UserCheck     = System::load_app_class("UserCheck", "common");
        $userdb        = system::load_app_model("user", "common");
        $config_email  = System::load_sys_config("email");
        $config_mobile = System::load_sys_config("mobile");

        if ( $UserCheck->GetUserCheckToBool() )
        {
            header("Location:" . WEB_PATH . "/member/home/userindex");
            exit();
        }

        $user     = (isset($_POST["user"])  ? $_POST["user"] : "");
        $pass     = (isset($_POST["pass"])  ? $_POST["pass"] : "");
        $pass2    = (isset($_POST["pass2"]) ? $_POST["pass2"] : "");
        $code     = (isset($_POST["code"])  ? $_POST["code"] : "");
        $savetime = (isset($_POST["savetime"]) ? $_POST["savetime"] : 86400 * 5);
        if ( ! $user || ! $pass )
        {
            $this->SendMsgJson("msg", "填写会员长号&密码！");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( $pass2 != $pass )
        {
            $this->SendMsgJson("msg", "两次密码不一样！");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( ! _ifcookiecode( $code, "Captcha" ) )
        {
            $this->SendMsgJson("msg", "验证码错误！");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( (strlen($pass) < 6) || (20 < strlen($pass)) )
        {
            $this->SendMsgJson("msg", "密码小于6位或大于20位");
            $this->SendMsgJson("status", -1, 1);
        }

        $regtype = NULL;
        if ( _checkmobile( $user ) )
        {
            $regtype = "mobile";
            $cfg_mobile_type = "cfg_mobile_" . $config_mobile["cfg_mobile_on"];
            $config_mobile   = $config_mobile[$cfg_mobile_type];
            if ( empty( $config_mobile["mid"] ) || empty( $config_mobile["mpass"] ) )
            {
                $this->SendMsgJson("msg", "系统短信配置不正确!");
                $this->SendMsgJson("status", -1, 1);
            }
        }

        if ( _checkemail( $user ) )
        {
            $regtype = "email";
            if ( empty( $config_email["user"] ) || empty( $config_email["pass"] ) )
            {
                $this->SendMsgJson("msg", "系统邮箱配置不正确!");
                $this->SendMsgJson("status", -1, 1);
            }
        }

        $regtype_val = findconfig("reg", "reg_email") + findconfig("reg", "reg_mobile");
        $regtype_val = intval( $regtype_val );
        if ( empty( $regtype ) || ($regtype_val != 0) )
        {
            if ( $regtype == "email" )
            {
                $regtype_val = findconfig( "reg", "reg_email" );
                if ( ($regtype_val == 0) || ! $regtype_val )
                {
                    $this->SendMsgJson("msg", "网站未开启邮箱注册!");
                    $this->SendMsgJson("status", -1, 1);
                }
            }

            if ( $regtype == "mobile" )
            {
                $regtype_val = findconfig("reg", "reg_mobile");
                if ( ($regtype_val == 0) || ! $regtype_val )
                {
                    $this->SendMsgJson("msg", $regtype_val . "网站未开启手机注册!");
                    $this->SendMsgJson("status", -1, 1);
                }
            }
        }
        else
        {
            $this->SendMsgJson("msg", "您注册的类型不正确!");
            $this->SendMsgJson("status", -1, 1);
        }

        $where = "`$regtype` = '$user' or `reg_key` = '$user'";
        $member = $userdb->SelectUser( $where );

        $register_type = "def";
        if ( is_array( $member ) && ($member["reg_key"] == $user) )
        {
            $b_uid  = $member["uid"];
            $where  = "`b_uid` = '$b_uid'";
            $b_user = $userdb->UserBand( $where );

            if ( is_array( $b_user ) )
            {
                $this->SendMsgJson("msg", "该账号已被注册!");
                $this->SendMsgJson("status", -1, 1);
            }

            $register_type = "for";
        }

        $time     = time();
        $codetype = $regtype . "code";
        $decode   = _encrypt( _getcookie( 'yaoqing_uid' ), "DECODE" );
        $decode   = intval( $decode );

        if ( $register_type == "def" )
        {
            $ip = _get_ip();
            $day_time = strtotime(date("Y-m-d"));
            $where = "`time` > '$day_time' and `user_ip` LIKE '%$ip%'";
            $member_reg_num = $userdb->UserSum($where);

            if ( findconfig("reg", "reg_num") <= $member_reg_num )
            {
                $this->SendMsgJson("msg", "您今日注册会员数已经达到上限！!");
                $this->SendMsgJson("status", -1, 1);
            }
            $insertinfo = array();
            if ( $decode )
            {
                $insertinfo["yaoqing"] = $decode;
                /* 需要查出邀请人的地区 */
                $insertinfo['area_id'] = get_user_key( $decode, 'area_id' );
                $return = $UserCheck->UserRegister($user, $pass, $insertinfo);
            }
            else
            {
                $return = $UserCheck->UserRegister($user, $pass);
            }
            /* 注册返现 */
            $this->reg_money( $return['uid'] );
            /* 检查分销商 */
            $this->_check_distributor( $return['uid'] );

            $sqlreg     = $return["status"];
            $check_code = serialize( array( "name" => $user, "time" => $time ) );
            $check_code = _encrypt( $check_code, "ENCODE", "", 3600 * 24 );
        }
        else if ( $register_type == "for" )
        {
            $sqlreg     = true;
            $check_code = serialize(array("name" => $user, "time" => $member["time"]));
            $check_code = _encrypt($check_code, "ENCODE", "", 3600 * 24);
        }
        if ( $sqlreg )
        {
            $uid = (isset($return["uid"]) ? $return["uid"] : $member["uid"]);
            $this->SendMsgJson("uid", $uid);
            $this->SendMsgJson("url", WEB_PATH . "/member/user/" . $regtype . "check/" . $check_code);
            $this->SendMsgJson("status", 1, 1);
        }
        else
        {
            $this->SendMsgJson("msg", "注册失败!");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( $return["status"] != 1 )
        {
            $this->SendMsgJson("msg", $return["msg"]);
            $this->SendMsgJson("status", $return["status"], 1);
        }
    }

    /**
     * M 端 执行手机注册
     */
    public function MobileRegister()
    {
        seo("title", _cfg("web_name") . "_注册");
        seo("keywords", _cfg("web_name") . "_注册");
        seo("description", _cfg("web_name") . "_注册");
        $UserCheck     = System::load_app_class("UserCheck", "common");
        $userdb        = system::load_app_model("user", "common");
        $config_mobile = System::load_sys_config("mobile");

        if ( $UserCheck->GetUserCheckToBool() )
        {
            header("Location:" . WEB_PATH . "/member/home/userindex");
            exit();
        }

        $user = (isset($_POST["user"]) ? $_POST["user"] : "");
        $pass = (isset($_POST["pass"]) ? $_POST["pass"] : "");
        $savetime = (isset($_POST["savetime"]) ? $_POST["savetime"] : 86400 * 5);
        if ( ! $user || ! $pass )
        {
            $this->SendMsgJson("msg", "填写手机号&密码！");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( (strlen($pass) < 6) || (20 < strlen($pass)) )
        {
            $this->SendMsgJson("msg", "密码小于6位或大于20位");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( _checkemail( $user ) )
        {
            $this->SendMsgJson("msg", "请填写手机号码注册！");
            $this->SendMsgJson("status", -1, 1);
        }

        $regtype_val = findconfig("reg", "reg_mobile");
        $regtype_val = intval($regtype_val);
        $regtype = NULL;

        if ( _checkmobile( $user ) )
        {
            $regtype         = "mobile";
            $cfg_mobile_type = "cfg_mobile_" . $config_mobile["cfg_mobile_on"];
            $config_mobile   = $config_mobile[$cfg_mobile_type];
            if ( empty( $config_mobile["mid"] ) && empty( $config_email["mpass"] ) )
            {
                $this->SendMsgJson("msg", "系统短信配置不正确!");
                $this->SendMsgJson("status", -1, 1);
            }
        }

        if ( empty( $regtype ) || ($regtype_val != 0) )
        {
            if ( $regtype == "mobile" )
            {
                $regtype_val = findconfig( "reg", "reg_mobile" );
                if ( ($regtype_val == 0) || ! $regtype_val )
                {
                    $this->SendMsgJson("msg", $regtype_val . "网站未开启手机注册!");
                    $this->SendMsgJson("status", -1, 1);
                }
            }
        }
        else
        {
            $this->SendMsgJson("msg", "您注册的类型不正确!");
            $this->SendMsgJson("status", -1, 1);
        }

        $where = "`$regtype` = '$user' or `reg_key` = '$user'";
        $member = $userdb->SelectUser( $where );

        $register_type = "def";
        if ( is_array( $member ) && ($member["reg_key"] == $user) )
        {
            $b_uid = $member["uid"];
            $where = "`b_uid` = '$b_uid'";
            $b_user = $userdb->UserBand( $where );

            if ( is_array( $b_user ) )
            {
                $this->SendMsgJson("msg", "该账号已被注册!");
                $this->SendMsgJson("status", -1, 1);
            }

            $register_type = "for";
        }

        $time     = time();
        $codetype = $regtype . "code";
        $decode   = _encrypt( _getcookie( 'yaoqing_uid' ), "DECODE" );
        $decode   = intval( $decode );

        if ( $register_type == "def" )
        {
            $ip = _get_ip();
            $day_time = strtotime(date("Y-m-d"));
            $where = "`time` > '$day_time' AND `user_ip` LIKE '%$ip%'";
            $member_reg_num = $userdb->UserSum( $where );

            if ( findconfig("reg", "reg_num") <= $member_reg_num )
            {
                $this->SendMsgJson("msg", "您今日注册会员数已经达到上限！!");
                $this->SendMsgJson("status", -1, 1);
            }

            $insertinfo = array();
            if ( $decode )
            {
                $insertinfo["yaoqing"] = $decode;
                /* 需要查出邀请人的地区 */
                $insertinfo['area_id'] = get_user_key( $decode, 'area_id' );
                $return = $UserCheck->UserRegister($user, $pass, $insertinfo);
            }
            else
            {
                $return = $UserCheck->UserRegister( $user, $pass );
            }
            /* 注册返现 */
            $this->reg_money( $return['uid'] );
            /* 检查分销商 */
            $this->_check_distributor( $return['uid'] );

            $sqlreg = $return["status"];
            $check_code = serialize( array("name" => $user, "time" => $time) );
            $check_code = _encrypt( $check_code, "ENCODE", "", 3600 * 24 );
        }
        else if ( $register_type == "for" )
        {
            $sqlreg = true;
            $check_code = serialize( array("name" => $user, "time" => $member["time"]) );
            $check_code = _encrypt( $check_code, "ENCODE", "", 3600 * 24 );
        }

        if ( $sqlreg )
        {
            $uid = $return["uid"];
            $this->SendMsgJson("uid", $uid);
            $this->SendMsgJson("url", WEB_PATH . "/member/user/" . $regtype . "check/" . $check_code);
            $this->SendMsgJson("status", 1, 1);
        }
        else
        {
            $this->SendMsgJson("msg", "注册失败!");
            $this->SendMsgJson("status", -1, 1);
        }

        if ( $return["status"] != 1 )
        {
            $this->SendMsgJson("msg", $return["msg"]);
            $this->SendMsgJson("status", $return["status"], 1);
        }
    }

    public function emailcheck()
    {
        seo("title", _cfg("web_name") . "_邮箱验证");
        seo("keywords", _cfg("web_name") . "_邮箱验证");
        seo("description", _cfg("web_name") . "_邮箱验证");
        $userdb     = System::load_app_model("user", "common");
        $title      = "邮箱验证 -" . _cfg("web_name");
        $check_code = _encrypt($this->segment(4), "DECODE");
        $check_code = @unserialize( $check_code );
        if ( ! $check_code || ! isset( $check_code["name"] ) || ! isset( $check_code["time"] ) ) {
            _message("参数不正确或者验证已过期!", WEB_PATH . "/register");
        }

        $where = "`reg_key` = '{$check_code["name"]}' and `time` = '{$check_code["time"]}'";
        $info = $userdb->SelectUser($where);

        if ( ! $info ) {
            _message("错误的来源!", WEB_PATH . "/register");
        }

        $emailurl    = explode("@", $info["reg_key"]);
        $name        = $info["reg_key"];
        $enname      = $this->segment(4);
        $reg_message = "";

        if ( $info["emailcode"] == "1" ) {
            _message("恭喜您,验证成功!", WEB_PATH . "/login");
        }

        if ( $info["emailcode"] == "-1" ) {
            $reg_message = send_email_reg($info["reg_key"], $info["uid"]);
        }
        else if ( 3600 < ( time() - $check_code["time"] ) ) {
            $reg_message = send_email_reg( $info["reg_key"], $info["uid"] );
        }

        
        $this->view->data( "enname", $enname );
        $this->view->data( "useremail", $info["email"] );
        $this->view->data( "emailurl", $emailurl );
        $this->view->data( "reg_message", $reg_message );
        $this->view->show( "user.emailcheck" );
    }

    public function sendemail()
    {
        seo("title", _cfg("web_name") . "_邮箱验证");
        seo("keywords", _cfg("web_name") . "_邮箱验证");
        seo("description", _cfg("web_name") . "_邮箱验证");
        $userdb = System::load_app_model("user", "common");
        $check_code = _encrypt($this->segment(4), "DECODE");
        $check_code = @unserialize($check_code);
        if (!$check_code || !isset($check_code["name"]) || !isset($check_code["time"])) {
            _message("参数不正确或者验证已过期!", WEB_PATH . "/register");
        }

        $where = "`reg_key` = '{$check_code["name"]}' and `time` = '{$check_code["time"]}'";
        $member = $userdb->SelectUser($where);

        if (!$member) {
            _message("错误的来源!", WEB_PATH . "/register");
        }

        if ($member["emailcode"] == "1") {
            _message("邮箱已验证", WEB_PATH . "/member/home");
        }

        $setkey = "emailcode='-1'";
        $where = "`uid`='{$member["uid"]}'";
        $userdb->UpdateUser($setkey, $where);
        _message("正在重新发送...", WEB_PATH . "/member/user/emailcheck/" . $this->segment(4));
        exit();
    }

    public function emailok()
    {
        seo("title", _cfg("web_name") . "_邮箱验证成功");
        seo("keywords", _cfg("web_name") . "_邮箱验证成功");
        seo("description", _cfg("web_name") . "_邮箱验证成功");
        $userdb       = System::load_app_model("user", "common");
        $UserCheck    = System::load_app_class("UserCheck", "common");
        $member_model = System::load_app_model("member", "common");
        $order_model  = System::load_app_model("order", "common");
        $check_code = _encrypt( $this->segment(4), "DECODE" );
        $check_code = @unserialize( $check_code );
        if ( ! isset( $check_code["email"] ) || ! isset( $check_code["code"] ) || ! isset( $check_code["time"] ) )
        {
            _message( "未知的来源1!", WEB_PATH, "/register" );
        }

        $sql_code = $check_code["code"] . "|" . $check_code["time"];
        $where    = "`reg_key`='{$check_code["email"]}' AND `emailcode`= '$sql_code'";
        $member   = $userdb->SelectUser($where);

        if ( ! $member ) {
            _message("未知的来源2!", WEB_PATH, "/register");
        }

        $timec = time() - $check_code["time"];

        if ( $timec < (3600 * 24) )
        {
            $title   = "邮件激活成功";
            $tiebu   = "完成注册";
            $success = "邮件激活成功";
            $fili_cfg = System::load_sys_config("user_fufen");
            if ( $member["yaoqing"] )
            {
                $time       = time();
                $yaoqinguid = $member["yaoqing"];
                if ( $fili_cfg["f_visituser"] )
                {
                    $data["uid"]     = $yaoqinguid;
                    $data["type"]    = "1";
                    $data["pay"]     = "福分";
                    $data["content"] = "邀请好友奖励";
                    $data["money"]   = $fili_cfg[f_visituser];
                    $data["time"]    = $time;
                    $userdb->Insert_user_account( $data );
                }
                $setkey = "`score`=`score`+'{$fili_cfg["f_visituser"]}',`jingyan`=`jingyan`+'{$fili_cfg["z_visituser"]}'";
                $where = "`uid`='$yaoqinguid'";
                $userdb->UpdateUser( $setkey, $where );
            }

            $setkey = "`emailcode`='1',`email` = '{$member["reg_key"]}'";
            $where = "`uid`='{$member["uid"]}'";
            $userdb->UpdateUser($setkey, $where);
            $UserCheck->UserLoginUID($member["uid"]);
            $this->view->data("tiebu", $tiebu);
            $this->view->data("guoqi", $guoqi);
            $this->view->show("user.emailok");
        }
        else {
            $title = "邮箱验证失败";
            $tiebu = "验证失败,请重发验证邮件";
            $guoqi = "对不起，验证码已过期或不正确！";
            $this->db->Query("UPDATE `@#_member` SET emailcode='-1' where `uid`='{$member["uid"]}'");
            $name = array("name" => $member["reg_key"], "time" => $member["time"]);
            $name = _encrypt(serialize($name), "ENCODE");
            $this->view->show("user.emailok");
        }
    }

    public function sendmobile()
    {
        $userdb = System::load_app_model("user", "common");
        seo("title", _cfg("web_name") . "_手机验证");
        seo("keywords", _cfg("web_name") . "_手机验证");
        seo("description", _cfg("web_name") . "_手机验证");
        $check_code = _encrypt($this->segment(4), "DECODE");
        $check_code = @unserialize($check_code);
        if ( ! $check_code || ! isset($check_code["name"]) || ! isset($check_code["time"] ) ) {
            _message("参数不正确或者验证已过期!", WEB_PATH . "/register");
        }

        $name   = $check_code["name"];
        $where  = "`reg_key` = '{$check_code["name"]}' and `time` = '{$check_code["time"]}'";
        $member = $userdb->SelectUser($where);

        if ( ! $member ) {
            _message("参数不正确!");
        }

        if ( $member["mobilecode"] == "1" ) {
            _message("该账号验证成功,请直接登录！", WEB_PATH . "/login");
        }

        $checkcode = explode( "|", $member["mobilecode"] );
        $times     = time() - $checkcode[1];

        if ( 120 < $times ) {
            $sendok = send_mobile_reg_code( $member["reg_key"], $member["uid"] );

            if ( $sendok[0] != 1 ) {
                _message("短信发送失败,代码:" . $sendok[1]);
                exit();
            }

            _message("正在重新发送...", WEB_PATH . "/member/user/mobilecheck/" . $this->segment(4));
        }
        else {
            _message("重发时间间隔不能小于2分钟!", WEB_PATH . "/member/user/mobilecheck/" . $this->segment(4));
        }
    }

    public function mobilecheck()
    {
        seo("title", _cfg("web_name") . "_手机验证");
        seo("keywords", _cfg("web_name") . "_手机验证");
        seo("description", _cfg("web_name") . "_手机验证");
        $userdb     = System::load_app_model("user", "common");
        $UserCheck  = System::load_app_class("UserCheck", "common");
        $check_code = _encrypt($this->segment(4), "DECODE");
        $no_send    = $this->segment( 5 );
        $check_code = @unserialize($check_code);
        if (!$check_code || !isset($check_code["name"]) || !isset($check_code["time"])) {
            _message($check_code["name"] . "_" . $check_code["time"], WEB_PATH . "/register");
            _message("参数不正确或者验证已过期!", WEB_PATH . "/register");
        }

        $name   = $check_code["name"];
        $where  = "`reg_key` = '{$check_code["name"]}' and `time` = '{$check_code["time"]}'";
        $member = $userdb->SelectUser( $where );

        if ( ! $member ) {
            _message("未知的来源!", WEB_PATH . "/register");
        }

        if ( $member["mobilecode"] == "1" ) {
            _message("该账号已注册，请重新登录", WEB_PATH . "/login");
        }

        if ( isset( $_POST["submit"] ) )
        {
            $referer = $_SERVER['HTTP_REFERER'] . '/no';
            $checkcodes = (isset($_POST["checkcode"]) ? $_POST["checkcode"] : _message("参数不正确!", $referer));

            if ( strlen($checkcodes) != 6 ) {
                _message("验证码输入不正确!", $referer);
            }

            $usercode = explode("|", $member["mobilecode"]);

            if ( $checkcodes != $usercode[0] ) {
                _message("验证码输入不正确!", $referer);
            }

            $fili_cfg = System::load_sys_config("user_fufen");

            if ( $member["yaoqing"] ) {
                $time = time();
                $yaoqinguid = $member["yaoqing"];

                if ( $fili_cfg["f_visituser"] ) {
                    $data["uid"] = $yaoqinguid;
                    $data["type"] = "1";
                    $data["pay"] = "福分";
                    $data["content"] = "邀请好友奖励";
                    $data["money"] = $fili_cfg[f_visituser];
                    $data["time"] = $time;
                    $userdb->Insert_user_account($data);
                }

                $setkey = "`score`=`score`+'{$fili_cfg["f_visituser"]}',`jingyan`=`jingyan`+'{$fili_cfg["z_visituser"]}'";
                $where = "`uid`='$yaoqinguid'";
                $userdb->UpdateUser( $setkey, $where );
            }

            $setkey = "`mobilecode`='1',`mobile` = '{$member["reg_key"]}'";
            $where  = "`uid`='{$member["uid"]}'";
            $check  = $userdb->UpdateUser($setkey, $where);

            if ( $check ) {
                $UserCheck->UserLoginUID($member["uid"]);
                _message("验证成功", WEB_PATH);
            }

            $time = time();
        }
        else
        {
            if ( $member["mobilecode"] != "1" && $no_send != 'no' )
            {
                $sendok = send_mobile_reg_code( $member["reg_key"], $member["uid"] );
                if ( $sendok[0] != 1 ) {
                //  _message($sendok[1]);//验证码发送不及时会导致空白提示
                }
            }
        }

        
        $enname  = substr($name, 0, 3) . "****" . substr($name, 7, 10);
        $time    = 120;
        $namestr = $this->segment(4);

        $this->view->data("namestr", $namestr);
        $this->view->data("enname", $enname);
        $this->view->data("check_code", $this->segment(4));
        $this->view->data("time", $time);
        $this->view->show("user.mobilecheck");
    }

    /* 注册返现 */
    public function reg_money( $uid )
    {
        $userdb       = System::load_app_model("user", "common");
        $member_model = System::load_app_model("member", "common");
        $order_model  = System::load_app_model("order", "common");
        $first_reg = _app_cfg( 'money', 'first_reg' );
        if ( $first_reg > 0 )
        {
            $acc_arr["uid"]     = $uid;
            $acc_arr["type"]    = 1;
            $acc_arr["pay"]     = "账户";
            $acc_arr["content"] = "首次注册";
            $acc_arr["money"]   = $first_reg;
            $acc_arr["time"]    = time();
            $text = "首次注册赠送:" . $first_reg;
            $order_model->user_add_chongzhi( $uid, $first_reg, $text );
            $member_model->user_account_add( $acc_arr );
            $where = "`uid` = '{$uid}'";
            $user_data = "`money` = `money` + {$first_reg}";
            $userdb->UpdateUser( $user_data, $where );
        }
    }

    /**
     * 检查分销商邀请
     */
    private function _check_distributor( $uid )
    {
        $user_model = System::load_app_model( 'user', 'common' );
        $parent_id = _getcookie( 'distrobutor' );
        if ( ! is_numeric( $parent_id ) ) { return; }
        $user = $user_model->SelectUserUid( $uid );
        $res = join_distribution( $user, $parent_id );
        if ( $res )
        {
            _setcookie( 'distrobutor', NULL, -1 );
        }
    }
}