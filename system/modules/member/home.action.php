<?php
System::load_app_class( 'UserAction', 'common', 'no' );

class home extends UserAction
{
    public function ApiUserPhoto()
    {
        $IMG  = ( isset( $_POST['src'] ) ? safe_replace( $_POST['src'] ) : $this->SendMsgJson( 'status', -1, 1 ) );
        $PATH = G_UPLOAD . $IMG;
        if ( ! @getimagesize( $PATH ) || ! file_exists( $PATH ) ) 
        {
            _sendmsgjson( "status", -1, 1 );
        }

        $uid    = $this->Userid;
        $homedb = System::load_app_model( 'member_home', 'common' );
        $a      = $homedb->member_imgedit( $uid, $IMG );
        _sendmsgjson( 'status', 1, 1 );
    }

    /**
     * 个人中心主页
     */
    public function userindex()
    {
        $clubdb   = System::load_app_model( 'club_db', 'common' );
        $userdb   = System::load_app_model( 'user', 'common' );
        $member   = $this->UserInfo;
        $jingyan  = $member['jingyan'];
        $dengji_1 = $userdb->GetUserGroup( $jingyan );
        $max_id   = $dengji_1['groupid'];
        $dengji_2 = $userdb->GetMaxUserGroup( $max_id );

        if ( $dengji_2 ) 
        {
            $dengji_x = $dengji_2['jingyan_start'] - $jingyan;
        }
        else
        {
            $dengji_x = $dengji_1['jingyan_end'] - $jingyan;
        }

        $home_title    = findconfig( 'seo', 'user_title' );
        $home_keywords = findconfig( 'seo', 'user_keywords' );
        $home_desc     = findconfig( 'seo', 'user_desc' );

        if ( ! $home_title ) 
        {
            $home_title = _cfg("web_name") . "_" . l("user.userindex");
            $home_keywords = _cfg("web_name") . "_" . l("user.userindex");
            $home_desc = _cfg("web_name") . "_" . l("user.userindex");
        }

        $seoinfo = array();
        $seoinfo["username"] = $member["username"];
        $seoinfo["qianming"] = $member["qianming"];
        seo( 'title', $home_title,       'user', $seoinfo );
        seo( 'keywords', $home_keywords, 'user', $seoinfo );
        seo( 'description', $home_desc,  'user', $seoinfo );
        $this->view->data( "dengji_1", $dengji_1 );
        $this->view->data( "dengji_2", $dengji_2 );
        $this->view->data( "dengji_x", $dengji_x );
        $this->view->data( "member", $this->UserInfo );
        $this->view->show( "user.index" );
    }

    public function userphoto()
    {
        $member = $this->UserInfo;
        $home_title = $member["username"] . "__" . l("user.userphoto") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.userphoto") . "_" . _cfg("web_name");
        $home_desc = $member["username"] . "__" . l("user.userphoto") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $this->view->show("user.photo")->data("member", $this->UserInfo);
    }

    /**
     * 修改个人资料
     */
    public function modify()
    {
        $member        = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.modify") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.modify") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.modify") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);

        if ( isset( $_POST["submit"] ) )
        {
            $data = array();
            $data['username']     = _post( 'username' );
            $data['card_no']      = _post( 'card_no' );
            $data['bank_account'] = _post( 'bank_account' );
            
            $modify = System::load_app_model( "member", "common" );
            $res = $modify->user_save( "uid=" . $member["uid"], $data );
            /* 更新其他表 冗余查询用的用户名 */
            $this->_update_membername( $member['uid'], $data['username'] );
            /* 手机跳回个人中心，PC还是原来页面 */
            $defurl = G_IS_MOBILE ? WEB_PATH . "/member/home/userindex" : WEB_PATH . "/member/home/modify";
            if ( $res !== false )
            {
                $blessing = System::load_app_model("UserBlessing", "common");
                $blessing->add_blessing( $member["uid"], "f_overziliao", "z_overziliao", "资料昵称完善奖励" );
                _message(l("user.modify.ok"), $defurl, 2);
            }
            else
            {
                _message(l("user.modify.no"), $defurl, 2);
            }
        }

        $this->view->data( "member", $member );
        $this->view->show( "user.modify" );        
    }

    /**
     * 更新用户名
     */
    private function _update_membername( $member_id, $member_name )
    {
        $where = 'uid = ' . $member_id;
        $data = array();
        $data['username'] = $member_name;

        /* distributor  uid->username  parent_id->parent_username */
        $distributor_model = System::load_app_model( 'distributor', 'common' );
        $distributor_model->save( $data, $where );
        $distributor_model->save( array( 'parent_username' => $member_name ), "parent_id = {$member_id}" );

        /* dis_money_log  uid->username  */
        $money_model = System::load_app_model( 'dis_money_log', 'common' );
        $money_model->save( $data, $where );

        /* distributor_apply  uid->username  */
        $apply_model = System::load_app_model( 'distributor_apply', 'common' );
        $apply_model->save( $data, $where );

        /* withdrawals  uid->username  */
        $withdrawals_model = System::load_app_model( 'withdrawals', 'common' );
        $withdrawals_model->save( $data, $where );
    }

    public function oauth()
    {
        $this->view->show("user.oauth")->data("member", $this->UserInfo);
    }

    public function mobilechecking()
    {
        $member = $this->UserInfo;
        $home_title = $member["username"] . "__" . l("user.mobilechecking") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.mobilechecking") . "_" . _cfg("web_name");
        $home_desc = $member["username"] . "__" . l("user.mobilechecking") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        if ($member["mobile"] && ($member["mobilecode"] == 1)) {
            _message(l("user.mobilechecking.ed"));
        }

        $this->view->show("user.mobilechecking")->data("member", $this->UserInfo);
    }

    public function mobilesuccess()
    {
        $title = "手机验证";
        $member = $this->UserInfo;

        if (isset($_POST["submit"])) {
            $mobile = (isset($_POST["mobile"]) ? $_POST["mobile"] : "");
            if (!_checkmobile($mobile) || ($mobile == NULL)) {
                _message(l("user.mobilechecking.err"), NULL, 3);
            }

            $modify = System::load_app_model("member", "common");
            $member2 = $modify->get_user_one("mobile=" . $mobile . " and uid!=" . $member["uid"]);
            if ($member2 && ($member2["mobilecode"] == 1)) {
                _message(l("user.mobilechecking.rep"));
            }

            if ($member["mobilecode"] != 1) {
                $date = explode("|", $member["mobilecode"]);
                $times = time() - $date[1];

                if ($times < 120) {
                    _message(l("user.mobilesend.timeed"), WEB_PATH . "/member/home/mobilechecking");
                }
                else {
                    $ok = send_mobile_reg_code($mobile, $member["uid"]);

                    if ($ok[0] != 1) {
                        _message("发送失败,失败状态:" . $ok[1]);
                    }
                    else {
                        _setcookie("mobilecheck", base64_encode($mobile));
                    }
                }
            }

            $time = 120;
            $this->view->show("user.mobilesuccess")->data("mobile", $mobile);
            $this->view->data("time", $time);
        }
    }

    public function sendmobile()
    {
        $member = $this->UserInfo;
        $mobilecodes = rand(100000, 999999) . "|" . time();

        if ($member["mobilecode"] == 1) {
            _message(l("user.mobilechecking.ok"), WEB_PATH . "/member/home/userindex");
        }

        $checkcode = explode("|", $member["mobilecode"]);
        $times = time() - $checkcode[1];

        if (120 < $times) {
            $ok = send_mobile_reg_code($member["mobile"], $member["uid"]);

            if ($ok[0] != 1) {
                _message("发送失败,失败状态:" . $ok[1]);
            }

            _message("正在重新发送...", WEB_PATH . "/member/user/mobilecheck/" . _encrypt($member["mobile"]), 2);
        }
        else {
            _message(l("user.mobilesend.timeed"), WEB_PATH . "/member/user/mobilecheck/" . _encrypt($member["mobile"]));
        }
    }

    public function mobilecheck()
    {
        $userdb = System::load_app_model("user", "common");
        $member = $this->UserInfo;

        if ( isset( $_POST["submit"] ) ) {
            
            $shoujimahao = base64_decode(_getcookie("mobilecheck"));

            if ( ! _checkmobile( $shoujimahao ) ) {
                _message( l("user.mobilechecking.err") );
            }

            $checkcodes = (isset($_POST["mobile"]) ? $_POST["mobile"] : _message(l("html.key.err")));

            if ( strlen($checkcodes) != 6 ) {
                _message(l("captche.no"));
            }

            $usercode = explode("|", $member["mobilecode"]);

            if ( $checkcodes != $usercode[0] ) {
                _message(l("captche.no"));
            }

            $modify = System::load_app_model("member", "common");
            $data   = array("mobilecode" => 1, "mobile" => $shoujimahao);
            $modify->user_save("uid=" . $member["uid"], $data);
            $config = System::load_sys_config("user_fufen");
            $setkey = "`score`=`score`+'{$config["f_phonecode"]}',`jingyan`=`jingyan`+'{$config["z_phonecode"]}'";
            $where = "`uid`='{$member["uid"]}'";
            $userdb->UpdateUser( $setkey, $where );
            $where = "`content`='手机认证完善奖励' and `type`='1' and `uid`='{$member["uid"]}' and (`pay`='经验' or `pay`='福分')";
            $type = "`uid`";
            $isset_user = $userdb->Get_user_account( $where, $type );

            if ( empty( $isset_user ) ) {
                $time            = time();
                $data["uid"]     = $member["uid"];
                $data["type"]    = "1";
                $data["pay"]     = "福分";
                $data["content"] = "手机认证完善奖励";
                $data["money"]   = $config["f_phonecode"];
                $data["time"]    = $time;
                $userdb->Insert_user_account( $data );
                $data["uid"]     = $member["uid"];
                $data["type"]    = "1";
                $data["pay"]     = "经验";
                $data["content"] = "手机认证完善奖励";
                $data["money"]   = $config["f_phonecode"];
                $data["time"]    = $time;
                $userdb->Insert_user_account($data);
            }

            $blessing = System::load_app_model("userblessing", "common");
            $blessing->add_blessing($member["uid"], "f_phonecode", "z_phonecode", "手机认证完善奖励");
            _message(l("user.check.ok"), WEB_PATH . "/member/home/modify");
        }
        else {
            _message(l("html.err"), NULL, 3);
        }
    }

    public function mailchackajax()
    {
        $modify = System::load_app_model("member", "common");
        $member = $this->UserInfo;
        $member = $modify->get_user_one("email=" . _post("param") . " and uid!=" . $member["uid"]);

        if (!empty($member)) {
            echo l("user.mailchecking.rep");
        }
        else {
            echo "{\r\n\t\t\t\t\t\"info\":\"\",\r\n\t\t\t\t\t\"status\":\"y\"\r\n\t\t\t\t}";
        }
    }

    public function mailchecking()
    {
        $title = l("user.mailchecking");
        $member = $this->UserInfo;
        if ($member["email"] && ($member["emailcode"] == 1)) {
            _message(l("user.mailchecking.ed"));
        }

        $this->view->show("user.mailchecking")->data("member", $this->UserInfo)->data("title", $title);
    }

    public function sendsuccess()
    {
        if (!isset($_POST["submit"])) {
            _message(l("html.key.err"), WEB_PATH . "/member/home/modify");
        }

        if (!isset($_POST["email"]) || empty($_POST["email"])) {
            _message(l("user.mail.emp"), WEB_PATH . "/member/home/modify");
        }

        if (!_checkemail($_POST["email"])) {
            _message(l("user.mail.err"), WEB_PATH . "/member/home/modify");
        }

        $config_email = System::load_sys_config("email");
        if (empty($config_email["user"]) && empty($config_email["pass"])) {
            _message(l("user.mailtype.err"), WEB_PATH . "/member/home/modify");
        }

        $member = $this->UserInfo;
        $title = "发送成功";
        $email = $_POST["email"];
        $modify = System::load_app_model("member", "common");
        $modify->user_save("uid=" . $member["uid"], $data);
        $member2 = $modify->get_user_one("email=" . $email . " and uid != " . $member["uid"]);
        if (!empty($member2) && ($member2["emailcode"] == 1)) {
            _message(l("user.mailchecking.rep"), WEB_PATH . "/member/home/modify");
        }

        $strcode1 = $email . "," . $member["uid"] . "," . time();
        $strcode = _encrypt($strcode1);
        $tit = $this->_cfg["web_name_two"] . "激活注册邮箱";
        $content = "<span>请在24小时内绑定邮箱</span>，点击链接：<a href=\"" . WEB_PATH . "/member/home/emailcheckingok/" . $strcode . "\">";
        $content .= WEB_PATH . "/member/home/emailcheckingok/" . $strcode . "</a>";
        $succ = _sendemail($email, "", $tit, $content, "yes", "no");

        if ($succ == "no") {
            _message(l("user.mailsend.err"), WEB_PATH . "/member/home/modify", 30);
        }
        else {
            $this->view->show("user.sendsuccess")->data("title", $title);
            $this->view->data("member", $member);
        }
    }

    public function emailcheckingok()
    {
        $member = $this->UserInfo;
        $key = $this->segment(4);

        if ($this->segment(5)) {
            $key .= "/" . $this->segment(5);
        }

        $emailcode = _encrypt($key, "DECODE");

        if (empty($emailcode)) {
            _message(l("user.mailnum.err"), NULL, 3);
        }

        $memberx = explode(",", $emailcode);
        $email   = $memberx[0];
        $timec   = (time() - $memberx[2]) / (60 * 60);
        $modify  = System::load_app_model("member", "common");
        $qmember = $modify->get_user_one("email='" . $email . "' and uid!=" . $member["uid"]);
        if ( $qmember && ($qmember["emailcode"] == 1) ) {
            _message( l("user.mailchecked.ed"), WEB_PATH . "/member/home/userindex" );
        }

        if ( $timec < 24 ) {
            $data = array( "email" => $memberx[0], "emailcode" => 1 );
            $modify->user_save( "uid=" . $member["uid"], $data );
            $title = "邮箱验证完成";

            $this->view->data("title", $title);
            $this->view->data("member", $member);
            $this->view->data("memberx", $memberx);
            $this->view->show("user.sendsuccess2");
        }
        else {
            _message(l("user.mailtime.out"), NULL, 3);
        }
    }

    public function useraddress()
    {
        $address  = System::load_app_model( 'member', 'common' );
        $userinfo = $this->UserInfo;
        $home_title    = $member['username'] . '__' . l( 'user.useraddress' ) . '_' . _cfg( 'web_name' );
        $home_keywords = $member['username'] . '__' . l( 'user.useraddress' ) . '_' . _cfg( 'web_name' );
        $home_desc     = $member['username'] . '__' . l( 'user.useraddress' ) . '_' . _cfg( 'web_name' );
        seo( 'title', $home_title);
        seo( 'keywords', $home_keywords);
        seo( 'description', $home_desc);

        if ( isset( $_POST['submit'] ) ) 
        {
            $data         = _post();
            $data["uid"]  = $userinfo["uid"];
            $data["time"] = time();
            $list = $address->get_user_addr_list( "uid=" . $userinfo["uid"] );

            if ( ! empty( $list ) ) 
            {
                $data["default"] = "N";
            }
            else 
            {
                $data["default"] = "Y";
            }

            unset($data["submit"]);
            $res = $address->user_addr_add($data);

            if ($res) {
                _message(l("user.useraddress.addo"), WEB_PATH . "/member/home/useraddress", 3);
            }
            else {
                _message(l("user.useraddress.addn"), WEB_PATH . "/member/home/useraddress", 3);
            }
        }
        else {
            $where = "uid=" . $userinfo["uid"];
            $data = $address->get_user_addr_list($where);
            $this->view->data("data", $data);
        }

        $this->view->show("user.address")->data("member", $this->UserInfo);
    }

    public function morenaddress()
    {
        $address = System::load_app_model("member", "common");
        $member = $this->UserInfo;
        $where = "uid=" . $member["uid"];
        $data["default"] = "N";
        $address->user_addr_save($where, $data);
        $id = $this->segment(4);
        $id = abs(intval($id));

        if (isset($id)) {
            $awhere = "id=" . $id;
            $sdata["default"] = "Y";
            $address->user_addr_save($awhere, $sdata);
            echo _message(l("update.ok"), WEB_PATH . "/member/home/useraddress", 3);
        }
    }

    public function updateddress()
    {
        $address = System::load_app_model("member", "common");

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            unset($data["id"]);
            $data["time"] = time();
            $id = $this->segment(4);
            $id = abs(intval($id));

            if (isset($id)) {
                $where = "id=" . $id;
                $res = $address->user_addr_save($where, $data);
            }

            if ($res) {
                echo _message(l("update.ok"), WEB_PATH . "/member/home/useraddress", 3);
            }
            else {
                echo _message(l("update.no"), WEB_PATH . "/member/home/useraddress", 3);
            }
        }
    }

    public function deladdress()
    {
        $address = System::load_app_model("member", "common");
        $member = $this->UserInfo;
        $id = $this->segment(4);
        $id = abs(intval($id));
        $where = "uid=" . $member["uid"] . " and id=" . $id;
        $dizhi = $address->get_user_addr_list($where);

        if (!empty($dizhi)) {
            $address->user_addr_del($where);
            header("location:" . WEB_PATH . "/member/home/useraddress");
        }
        else {
            echo _message(l("del.no"), WEB_PATH . "/member/home/useraddress", 0);
        }
    }

    /**
     * 密码修改
     */
    public function userpassword()
    {
        $member = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.userpassword") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.userpassword") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.userpassword") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $this->view->data("member", $this->UserInfo);
        $this->view->data("title", $title);
        $this->view->show("user.password");
    }

    /**
     * 执行修改密码
     */
    public function updatepassword()
    {
        $address       = System::load_app_model("member", "common");
        $member        = $this->UserInfo;
        $password      = (isset($_POST["password"]) ? $_POST["password"] : "");
        $userpassword  = (isset($_POST["userpassword"]) ? $_POST["userpassword"] : "");
        $userpassword2 = (isset($_POST["userpassword2"]) ? $_POST["userpassword2"] : "");
        if (($password == NULL) || ($userpassword == NULL) || ($userpassword2 == NULL)) {
            echo l("pass.update.emp");
            exit();
        }

        if ((strlen($_POST["password"]) < 6) || (20 < strlen($_POST["password"]))) {
            echo l("pass.update.err");
            exit();
        }

        if ($_POST["userpassword"] !== $_POST["userpassword2"]) {
            echo l("user.pass2.no");
            exit();
        }

        $password = md5(md5($password) . md5($password));
        $data["password"] = md5(md5($userpassword) . md5($userpassword));

        if ($member["password"] != $password) {
            echo _message(l("user.pass1.no"), WEB_PATH . "/member/home/userpassword", 3);
        }
        else {
            $address->user_save("uid=" . $member["uid"], $data);
            echo _message(l("pass.update.ok"), WEB_PATH . "/member/home/userpassword", 3);
        }
    }

    /**
     * M 版修改密码
     */
    public function mobile_up_pwd()
    {
        $member_model = System::load_app_model("member", "common");
        $member       = $this->UserInfo;
        $password         = (isset($_POST["password"]) ? $_POST["password"] : "");
        $confirm_password = (isset($_POST["confirm_password"]) ? $_POST["confirm_password"] : "");
        if ( ! $password )
        {
            _message( '请填写密码', WEB_PATH . "/member/home/userpassword" );
        }
        if ( $password != $confirm_password )
        {
            _message( '两次密码输入不一致', WEB_PATH . "/member/home/userpassword" );
        }
        $data["password"] = md5( md5( $password ) . md5( $password ) );
        $res = $member_model->user_save( "uid=" . $member["uid"], $data );
        if ( $res !== false )
        {
            _message( '修改成功', WEB_PATH . "/member/home/userindex" );
        }
        else
        {
            _message( '修改失败', WEB_PATH . "/member/home/userindex" );
        }
    }

    public function oldpassword()
    {
        $member = $this->UserInfo;

        if ($member["password"] == md5(md5(_post("param")) . md5(_post("param")))) {
            echo "{\r\n                    \"info\":\"\",\r\n                    \"status\":\"y\"\r\n                }";
        }
        else {
            echo l("user.pass1.no");
        }
    }
}