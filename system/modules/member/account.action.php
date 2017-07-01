<?php
System::load_app_class("UserAction", "common", "no");
class account extends UserAction
{
    /**
     * 邀请好友
     */
    public function invitefriends()
    {
        seo( "title",       _cfg("web_name") . "_" . L("user.invitefriends") );
        seo( "keywords",    L("user.invitefriends") );
        seo( "description", L("user.invitefriends") );
        $member         = $this->UserInfo;
        $uid            = _encrypt( $member["uid"] );
        $notinvolvednum = 0;
        $involvednum    = 0;
        $involvedtotal  = 0;
        $userdb        = System::load_app_model( "user", "common" );
        $invifriends   = $userdb->GetUserYaoqingUid( $member["uid"] );
        $involvedtotal = count( $invifriends );

        for ( $i = 0; $i < count( $invifriends ); $i++ )
        {
            $sqluid = $invifriends[$i]["uid"];
            $sqname = get_user_name($invifriends[$i]);
            $invifriends[$i]["sqlname"] = $sqname;
            $accounts[$sqluid]          = $userdb->has_account( $sqluid );

            if ( empty( $accounts[$sqluid] ) )
            {
                $notinvolvednum   += 1;
                $records[$sqluid] = "<span style='color: red'>　　X</span>";
            }
            else
            {
                $involvednum      += 1;
                $records[$sqluid] = "<span style='color: green'>　　√</span>";
            }
        }

        $manage_rank = array( 0 => '普通会员', 1 => '一级', 2 => '二级', 3 => '三级' );

        $this->view->data( "uid", $uid );
        $this->view->data( "area_id", $member['area_id'] );
        $this->view->data( "involvedtotal", $involvedtotal );
        $this->view->data( "involvednum", $involvednum );
        $this->view->data( "invifriends", $invifriends );
        $this->view->data( "records", $records );
        $this->view->data( "member", $this->UserInfo );
        $this->view->data( "manage_rank", $manage_rank );
        $this->view->show( "user.invitefriends" );
    }

    /**
     * 账户明细
     */
    public function userbalance()
    {
        if ( IS_MOBILE )
        {
            $this->mobile_userbalance();
        }
        seo("title", _cfg("web_name") . "_" . L("user.userbalance"));
        seo("keywords", L("user.userbalance"));
        seo("description", L("user.userbalance"));
        $member       = $this->UserInfo;
        $uid          = $member["uid"];
        $page         = System::load_sys_class("page");
        $member_money = $member["money"];
        $userdb       = System::load_app_model("user", "common");
        $total        = $userdb->GetUserAccountCount( $uid );
        $num = 10;
        $page->config( $total, $num );
        $account = $userdb->GetUserAccount( $uid, $page->setlimit() );

        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("page", $page);
        $this->view->data("account", $account);
        $this->view->data("member_money", $member_money);
        $this->view->show( "user.balance" );
    }

    /**
     * 手机版 账户明细
     */
    public function mobile_userbalance()
    {
        seo("title", _cfg("web_name") . "_" . L("user.userbalance"));
        seo("keywords", L("user.userbalance"));
        seo("description", L("user.userbalance"));
        $member       = $this->UserInfo;
        $uid          = $member["uid"];
        $page         = System::load_sys_class("page");
        $member_money = $member["money"];
        $userdb       = System::load_app_model("user", "common");
        $total        = $userdb->GetUserAccountCount( $uid );
        $num = 10;
        $page->config( $total, $num );
        $consume_where = "`uid` = '$uid' AND `pay` = '账户' AND `type` = '-1'";
        $recharge_where = "`uid` = '$uid' AND `pay` = '账户' AND `type` = '1'";
        $order = "ORDER BY `time` DESC";
        $consume_account  = $userdb->Get_user_account( $consume_where, '*', $order, $page->setlimit() );
        $recharge_account = $userdb->Get_user_account( $recharge_where, '*', $order, $page->setlimit() );

        $this->view->data( 'total', $total );
        $this->view->data( 'num', $num );
        $this->view->data( 'page', $page );
        $this->view->data( 'consume_account', $consume_account );
        $this->view->data( 'recharge_account', $recharge_account );
        $this->view->data( 'member_money', $member_money);
        $this->view->show( 'user.balance' );
    }

    /**
     * 账户充值
     */
    public function userrecharge()
    {
        $userdb  = System::load_app_model("user", "common");
        $paylist = $userdb->GetPaylist();
        seo("title", _cfg("web_name") . "_" . L("user.userrecharge"));
        seo("keywords", L("user.userrecharge"));
        seo("description", L("user.userrecharge"));
        $member = $this->UserInfo;
        $this->view->data( 'member', $member );
        $this->view->data( "paylist", $paylist );
        $this->view->data( "user_money", $member["money"] );
        $this->view->show( "user.recharge" );
    }

    /**
     * 根据订单号支付
     */
    public function account_pay()
    {
        $order_model = System::load_app_model( 'order', 'common' );
        $ocode = $out_trade_no = $_GET['ocode'];
        $where = "ocode = '{$ocode}' AND ostatus = 1";
        $order_info = $order_model->get_order_one( $where, 'omoney' );
        if ( ! $order_info )
        {
            _message( '订单编号错误' );
        }
        $total_fee = $order_info['omoney'] * 100;
        $payreturn2 = array();
        $payreturn2['pay_class'] = 'wxpay';
        $payreturn2['pay_fun']   = 'houtai';
        $payreturn2              = base64_encode( json_encode( $payreturn2 ) );
        $pay_NotifyUrl = G_WEB_PATH.'/i.php?plugin=true&api=Pay&action=return&wx='.$payreturn2;

        include G_PLUGIN . "Pay/wxpay/lib/WxPay.Api.php";
        include G_PLUGIN . "Pay/wxpay/lib/WxPay.JsApiPay.php";
        //①、获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("购买/充值");
        $input->SetAttach("购买/充值");
        $input->SetOut_trade_no( $out_trade_no );
        $input->SetTotal_fee( $total_fee );
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("购买/充值");
        $input->SetNotify_url( $pay_NotifyUrl );
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        $this->printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

        $this->view->data( "jsApiParameters", $jsApiParameters );
        $this->view->show( "user.account_pay" );
    }

    //打印输出数组信息
    public function printf_info($data)
    {
        foreach($data as $key=>$value){
            echo "<font color='#00ff55;'>$key</font> : $value <br/>";
        }
    }

    public function commissions()
    {
        seo("title", _cfg("web_name") . "_" . L("user.commissions"));
        seo("keywords", L("user.commissions"));
        seo("description", L("user.commissions"));
        $userdb = System::load_app_model("user", "common");
        $page   = System::load_sys_class("page");
        $member = $this->UserInfo;
        $uid = $member["uid"];
        $num = 10;
        $recodetotal = 0;
        $shourutotal = 0;
        $zhichutotal = 0;
        $where = "`r_uid`='" . $member["uid"] . "'  ORDER BY `type` DESC,`time` DESC";
        $commissions = $userdb->Get_user_recodes($where);
        $total = $recodetotal = count($commissions);

        if (!empty($commissions)) {
            foreach ($commissions as $key => &$val ) {
                $val["username"] = get_user_name($val["uid"]);
                if (($val["uid"] == $val["r_uid"]) && ($val["type"] != 1)) {
                    $zhichutotal += $val["money"];
                }
                else {
                    $shourutotal += $val["money"];
                }
            }
        }

        $page->config($total, $num);
        $total1 = $shourutotal - $zhichutotal;
        $this->view->data("total", $total);
        $this->view->data("commissions", $commissions);
        $this->view->data("member", $member);
        $this->view->data("num", $num);
        $this->view->data("shourutotal", $shourutotal);
        $this->view->data("zhichutotal", $zhichutotal);
        $this->view->data("recodetotal", $recodetotal);
        $this->view->data("total1", $total1);
        $this->view->data("uid", $uid);
        $this->view->data("page", $page);
        $this->view->show("user.commissions");
    }

    public function comcashout()
    {
        seo("title", _cfg("web_name") . "_佣金充值到" . L("html.key") . "账户");
        seo("keywords", _cfg("web_name") . "_佣金充值到" . L("html.key") . "账户");
        seo("description", _cfg("web_name") . "_佣金充值到" . L("html.key") . "账户");
        $userdb         = System::load_app_model("user", "common");
        $member         = $this->UserInfo;
        $uid            = $member["uid"];
        $total          = 0;
        $shourutotal    = 0;
        $zhichutotal    = 0;
        $cashoutdjtotal = 0;
        $cashouthdtotal = 0;
        $where   = "`r_uid`='" . $member[uid] . "' and `type`=1 ORDER BY `time` DESC";
        $recodes = $userdb->Get_user_recodes($where);
        $where   = "`uid`='$uid' and `type`!=1 ORDER BY `time` DESC";
        $zhichu  = $userdb->Get_user_recodes($where);

        if (!empty($recodes)) {
            foreach ($recodes as $key => $val ) {
                $shourutotal += $val["money"];
            }
        }

        if (!empty($zhichu)) {
            foreach ($zhichu as $key => $val3 ) {
                $zhichutotal += $val3["money"];
            }
        }

        $cashoutdj["summoney"] = 0;
        $total = $shourutotal - $zhichutotal;
        $cashoutdjtotal = $cashoutdj["summoney"];
        $cashouthdtotal = $total - $cashoutdj["summoney"];

        if (isset($_POST["submit2"])) {
            $money = abs(intval($_POST["txtCZMoney"]));
            $type = 1;
            $pay = "佣金";
            $time = time();
            $content = "使用佣金充值到" . L("html.key") . "账户";
            if (($money <= 0) || ($total < $money)) {
                _message("佣金金额输入不正确！");
                exit();
            }

            if ($cashouthdtotal < $money) {
                _message("输入额超出活动佣金金额！");
                exit();
            }

            $data["uid"]     = $uid;
            $data["r_uid"]   = $uid;
            $data["type"]    = $type;
            $data["pay"]     = $pay;
            $data["content"] = $content;
            $data["money"]   = $money;
            $data["time"]    = $time;
            $account = $userdb->Insert_user_account($data);

            if ($account) {
                $leavemoney = $member["money"] + $money;
                $setkey     = "`money`='$leavemoney'";
                $where      = "`uid`='$uid'";
                $mrecode    = $userdb->UpdateUser($setkey, $where);
                $data_recodes["uid"]     = $uid;
                $data_recodes["type"]    = -2;
                $data_recodes["content"] = $content;
                $data_recodes["money"]   = $money;
                $data_recodes["time"]    = $time;
                $recode = $userdb->Insert_user_recodes($data_recodes);
                if ($mrecode && $recode) {
                    _message("充值成功！");
                }
                else {
                    _message("充值成功！");
                }
            }
            else {
                _message("充值失败！");
            }
        }

        $this->view->show("user.cashout")->data("total", $total)->data("cashoutdjtotal", $cashoutdjtotal)->data("cashouthdtotal", $cashouthdtotal);
    }

    public function usercredit()
    {
        $userdb = System::load_app_model("user", "common");
        $page   = System::load_sys_class("page");
        $member = $this->UserInfo;
        $uid    = $member["uid"];
        seo("title", "账户" . L("cgoods.credit") . "-" . _cfg("web_name"));
        seo("keywords", "账户" . L("html.credit") . "-" . _cfg("web_name"));
        seo("description", "账户" . L("html.credit") . "-" . _cfg("web_name"));
        $where = "`uid`='$uid' and `pay` = '福分'";
        $total = $userdb->Get_user_accountn($where);
        $num = 10;
        $page->config($total, $num);
        $where = "`uid`='$uid' and `pay` = '福分' ORDER BY time DESC";
        $account = $userdb->Get_user_account($where, "*", $page->setlimit());
        $conf = System::load_sys_config("user_fufen");
        $fufen_to_money = intval($member["score"] / $conf["fufen_yuan"]);
        $this->view->show("user.credit")->data("total", $total)->data("num", $num)->data("page", $page)->data("account", $account)->data("conf", $conf)->data("fufen_to_money", $fufen_to_money)->data("member", $member);
    }

        //查询订单是否被支付
    public function ajax_check_ispay(){
        $sOrderId = $_REQUEST['ocode'];
        if(!preg_match("#^C\d+$#i", $sOrderId)){
            exit("wrong order id!");
        }

        $userpaydb    = System::load_app_model("UserPay", "common");
        $dingdaninfo = $userpaydb->get_recharge_order_by_code( $sOrderId, 0 );

        $aRet = array();
        if($dingdaninfo['ostatus'] == 2){
            $aRet['errno'] = 0;
            $aRet['errmsg'] = '支付成功!';
        }elseif($dingdaninfo['ostatus'] == 5){
            $aRet['errno'] = 1;
            $aRet['errmsg'] = '支付异常!';
        }else{
            $aRet['errno'] = -1;
            $aRet['errmsg'] = '支付失败!';
        }

        echo json_encode($aRet);
    }
}
?>