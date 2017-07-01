<?php
class UserPay
{
    private $db;
    private $members;
    private $MoenyCount;
    private $shops;
    private $pay_type;
    private $fukuan_type;
    private $dingdan_query = true;
    public $pay_type_bank = false;
    public $scookie;
    public $fufen = 0;
    public $fufen_to_money = 0;
    public $oid;

    public $callback_info = array();

    public function init( $uid = NULL, $pay_type = NULL, $fukuan_type = "", $addmoney = "" )
    {
        $this->db      = System::load_sys_class( "model" );
        $userpaydb     = System::load_app_model( "UserPay", "common" );
        $this->members = $userpaydb->SelectUserUid( $uid );
        $this->db->sql_begin();

        if ( $this->pay_type_bank )
        {
            $pay_class      = $this->pay_type_bank;
            $this->pay_type = $userpaydb->SelectPayclass( $pay_class );
            $this->pay_type["pay_bank"] = $pay_type;
        }

        if ( is_numeric( $pay_type ) )
        {
            $this->pay_type = $userpaydb->SelectPayid( $pay_type );
            $this->pay_type["pay_bank"] = "DEFAULT";
        }

        $this->fukuan_type = $fukuan_type;

        if ( $fukuan_type == "go_record" )
        {
            return $this->go_record();
        }
        /* 充值 + 加钱记录 */
        if ( $fukuan_type == "addmoney_record" )
        {
            return $this->addmoney_record( $addmoney );
        }

        if ( $fukuan_type == "go_goods" )
        {
            return $this->go_goods();
        }

        return false;
    }

    private function go_goods()
    {
        $userpay_db = System::load_app_model("goods", "common");

        if (is_array($this->scookie)) {
            $Cartlist = $this->scookie;
        }
        else {
            $Cartlist = json_decode(stripslashes(_getcookie("Cartlista")), true);
        }

        $shopids = "";

        if (is_array($Cartlist)) {
            foreach ($Cartlist as $key => $val ) {
                $shopids .= intval($key) . ",";
            }

            $shopids = str_replace(",0", "", $shopids);
            $shopids = trim($shopids, ",");
        }

        $shoplist = array();

        if ($shopids != NULL) {
            $wheres = " a.`gid` in (" . $shopids . ")";
            $shoplist = $userpay_db->get_goods_any($wheres);
        }
        else {
            $this->db->sql_rollback();
            return L("cgoods.cartlist.no");
        }

        $MoenyCount = 0;
        $shopguoqi = 0;

        if (1 <= count($shoplist)) {
            $scookies_arr = array();
            $scookies_arr["MoenyCount"] = 0;

            foreach ($Cartlist as $key => $val ) {
                $key = intval($key);

                if (isset($shoplist[$key])) {
                    $shoplist[$key]["cart_gorenci"] = ($val["num"] ? intval($val["num"]) : 1);
                    $MoenyCount += $shoplist[$key]["g_money"] * $shoplist[$key]["cart_gorenci"];
                    $shoplist[$key]["cart_xiaoji"] = $shoplist[$key]["g_money"] * $shoplist[$key]["cart_gorenci"];
                    $scookies_arr[$key]["num"] = $shoplist[$key]["cart_gorenci"];
                    $scookies_arr[$key]["money"] = intval($shoplist[$key]["g_money"]);
                    $scookies_arr["MoenyCount"] += intval($shoplist[$key]["cart_xiaoji"]);
                }
                else {
                    unset($shoplist[$key]);
                }
            }

            if (count($shoplist) < 1) {
                $scookies_arr = "0";
                $this->db->sql_rollback();

                if ($shopguoqi) {
                    return L("cgoods.autolottery.no");
                }
                else {
                    return L("cgoods.cartlist.no");
                }
            }
        }
        else {
            $scookies_arr = "0";
            $this->db->sql_rollback();
            return L("cgoods.cartlist.err");
        }

        $this->MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);

        if ($this->fufen) {
            if ($this->members["score"] <= $this->fufen) {
                $this->fufen = $this->members["score"];
            }

            $fufen = findconfig("fufen", "fufen_yuan");

            if ($fufen) {
                $this->fufen_to_money = intval($this->fufen / $fufen);

                if ($this->MoenyCount <= $this->fufen_to_money) {
                    $this->fufen_to_money = $this->MoenyCount;
                    $this->fufen = $this->fufen_to_money * $fufen;
                }
            }
            else {
                $this->fufen_to_money = 0;
                $this->fufen = 0;
            }
        }
        else {
            $this->fufen_to_money = 0;
            $this->fufen = 0;
        }

        $this->shoplist = $shoplist;
        $this->scookies_arr = $scookies_arr;
        return "ok";
    }

    private function gpay_bag()
    {
        $userpay_clouddb = System::load_app_model("UserPay_cloud", "common");
        $userpaydb = System::load_app_model("UserPay", "common");
        $fufen = System::load_sys_config("user_fufen");
        $time = sprintf("%.3f", microtime(true));
        $uid = $this->members["uid"];
        $query_1 = $this->set_gdingdan("账户", "A");

        if (!$this->MoenyCount) {
            return false;
        }

        $Money = ($this->members["money"] - $this->MoenyCount) + $this->fufen_to_money;
        $query_fufen = true;

        if ($this->fufen_to_money) {
            $userscore = $this->members["score"];
            $score = $userscore - $this->fufen;
            $setwords = "`score`='$score'";
            $query_fufen = $userpaydb->UpdateUserInfo($uid, $setwords);
        }

        $query_fufen = true;
        $pay_zhifu_name = "账户";
        $setwords = "`money`='$Money'";
        $query_2 = $userpaydb->UpdateUserInfo($uid, $setwords);
        $query_3 = $info = $userpaydb->SelectUserUid($uid);
        $insert_html = "'$uid', '-1', '$pay_zhifu_name', '购买了商品', '$this->MoenyCount', '$time'";
        $query_4 = $userpaydb->InsertAccount($insert_html);
        $query_5 = true;
        $query_insert = true;
        $goods_count_num = 0;
        $jingyan = $this->members["jingyan"] + ($fufen["z_shoppay"] * $goods_count_num);
        $setwords = "`jingyan`='$jingyan'";
        $query_jingyan = $userpaydb->SelectUserUid($uid, $setwords);
        $dingdancode = $this->dingdancode;
        $dingdanoid = $this->oid;
        $setwords = "`ofstatus`='1',`ostatus`='2'";
        $query_6 = $userpaydb->UpdateOrders($uid, $dingdanoid, $setwords);
        $query_7 = $this->dingdan_query;
        $this->goods_count_num = $goods_count_num;
        if ($query_jingyan && $query_1 && $query_2 && $query_3 && $query_4 && $query_5 && $query_6 && $query_7 && $query_insert) {
            $this->db->sql_commit();
            return true;
        }
        else {
            $this->db->sql_rollback();
            return false;
        }
    }

    private function set_gdingdan($pay_type = "", $dingdanzhui = "")
    {
        $userpaydb = System::load_app_model("UserPay", "common");
        $user_clouddb = System::load_app_model("UserPay_cloud", "common");
        $uid = $this->members["uid"];
        $uphoto = $this->members["img"];
        $username = addslashes(get_user_name($this->members));
        $insert_html = "";
        $this->dingdancode = $dingdancode = pay_get_dingdan_code($dingdanzhui);

        if (1 < count($this->shoplist)) {
            $dingdancode_tmp = 1;
        }
        else {
            $dingdancode_tmp = 0;
        }

        $ip = $this->members["user_ip"];
        $time = sprintf("%.3f", microtime(true));
        $money = $this->MoenyCount;

        foreach ($this->shoplist as $key => $shop ) {
            $shop["g_title"] = addslashes($shop["g_title"]);
            $this->shoplist[$key] = $shop;
            $InsertOrders_html = "('2','$uid','$dingdancode','$money','0','账户','普通商品购买','$time'),";
            $InsertOrders = $userpaydb->InsertOrders($InsertOrders_html);
            $SelectOrders = $userpaydb->SelectOrders($dingdancode, $time);
            $ordershopinfo = serialize($shop);
            $this->oid = $SelectOrders["oid"];
            $InsertOrdersinfo = $userpaydb->InsertOrdersinfo($SelectOrders["oid"], $ordershopinfo);
        }

        return $InsertOrdersinfo;
    }

    /**
     * 支付成功之后的订单操作
     */
    public function pay_success_order( $ocode, $pay_id, $payclass )
    {
        $userpaydb    = System::load_app_model("UserPay", "common");
        $this->db     = System::load_sys_class("model");
        $userdb       = System::load_app_model("user", "common");
        $member_model = System::load_app_model("member", "common");
        $order_model  = System::load_app_model("order", "common");
        $this->db->sql_begin();
        $dingdaninfo = $userpaydb->get_order( $ocode );
        $time = time();

        if ( ! $dingdaninfo ) {
            $recorddingdan = $userpaydb->get_money_record( $ocode );
            if ( $recorddingdan["status"] == 2 ) {
                exit();
            }

            $uid = $recorddingdan["uid"];
            $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + " . $recorddingdan["money"] . " where (`uid` = '" . $uid . "')");
            $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('" . $uid . "', '1', '账户', '充值', '" . $recorddingdan["money"] . "', '" . $time . "')");
            $scookies = unserialize($recorddingdan["scookies"]);
            $this->scookie = $scookies;
            $ok = $this->init($uid, $pay_id, "go_record");

            if ($ok != "ok") {
                _setcookie("Cartlist", NULL);
                $wxstatus = array("status" => "fail", "code" => "", "msg" => "fail");
                echo $wxstatus = json_encode($wxstatus);
                exit();
            }

            $check = $this->go_pay(1);
            if ( $check && $up_q2 && $up_q3 ) {
                $recorddel = $this->db->Query("UPDATE  `@#_user_money_record` set `status` ='2' WHERE (`id`='$recorddingdan[id]')");
                $this->db->sql_commit();
                $wxstatus = array( "status" => "success", "code" => 4, "msg" => "支付成功！" );
            }
            else {
                $this->db->sql_rollback();
                $wxstatus = array("status" => "fail", "code" => "", "msg" => "fail");
            }
            exit( json_encode( $wxstatus ) );
        }
        else {
            $uid = $dingdaninfo["ouid"];
            $up_q11 = "update `@#_orders` SET `opay` = '" . $payclass . "',`ostatus` = '2'  where `oid` = '" . $dingdaninfo["oid"] . "' and `ocode` = '" . $ocode . "'";
            $up_q22 = "update `@#_user` SET `money` = `money` + " . $dingdaninfo["omoney"] . "  where `uid` = '" . $uid . "'";
            $up_q33 = "insert into `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('" . $uid . "', '1', '账户', '充值', '" . $dingdaninfo["omoney"] . "', '" . $time . "')";
            $up_q1 = $this->db->Query( $up_q11 );
            $up_q2 = $this->db->Query( $up_q22 );
            $up_q3 = $this->db->Query( $up_q33 );
            /* 充值按比例返现 */
            $rebate = _app_cfg( 'money', 'rebate' );
            /* 光100的整数才算 */
            $back_money = intval($dingdaninfo["omoney"] / 100) * 100;
            if ( $rebate > 0 && $back_money > 0 )
            {
                $back_money = $back_money / 100 * $rebate;
                $acc_arr["uid"]     = $uid;
                $acc_arr["type"]    = 1;
                $acc_arr["pay"]     = "账户";
                $acc_arr["content"] = "充值返现";
                $acc_arr["money"]   = $back_money;
                $acc_arr["time"]    = time();
                $text = "充值返现:" . $back_money;
                $order_model->user_add_chongzhi( $uid, $back_money, $text );
                $member_model->user_account_add( $acc_arr );
                $where = "`uid` = '{$uid}'";
                $user_data = "`money` = `money` + {$back_money}";
                $userdb->UpdateUser( $user_data, $where );
            }
            if ( $up_q1 && $up_q2 && $up_q3 )
            {
                $this->db->sql_commit();
                $wxstatus = 'success';
                /* 开始分佣 */
                distribute_money( $uid, $dingdaninfo['omoney'], 'wxpay' );
            }
            else
            {
                $this->db->sql_rollback();
                $wxstatus = 'fail';
            }
            exit( $wxstatus );
        }
        exit();
    }

    /**
     * 手动设置支付成功后的后续操作 由后台手动设置为充值完毕后调用
     */
    public function pay_success_recharge_order( $oid, $pay_id = 0, $payclass = '' )
    {
        $userpaydb    = System::load_app_model("UserPay", "common");
        $this->db     = System::load_sys_class("model");
        $userdb       = System::load_app_model("user", "common");
        $member_model = System::load_app_model("member", "common");
        $order_model  = System::load_app_model("order", "common");
        
        $dingdaninfo = $userpaydb->get_recharge_order( $oid );
        if(empty($dingdaninfo)){
            $dingdaninfo = $userpaydb->get_recharge_order_by_code( $oid );
        }
        $time = time();
        if ( ! $dingdaninfo ) {
            exit('no order info');
        }else {
            //判断回调money跟订单money是否一致
            if(!empty($this->callback_info)){      
                $callback_money = $this->callback_info['total_fee']/100;
                
                if(abs($callback_money - $dingdaninfo['omoney']) > 1e-4){
                    $up_q10 = "update `@#_orders` SET `ostatus` = '5', `ocallback_money` = ${callback_money}  where `oid` = '" . $dingdaninfo["oid"] . "'";
                    $this->db->Query( $up_q10 );

                    file_put_contents("/home/dev/pay_notify_" . date('Ymd') . ".log", json_encode($this->callback_info).PHP_EOL, FILE_APPEND);
                    exit('success');
                }

                $callback_info_str = json_encode($this->callback_info, JSON_UNESCAPED_UNICODE);
            }else{
                $callback_info_str = '';
                $callback_money = 0;
            }
            
            $this->db->sql_begin();
            $uid = $dingdaninfo["ouid"];
            $up_q11 = "update `@#_orders` SET `ostatus` = '2',`ocallback_money` = ${callback_money},`ocallback_info` = '${callback_info_str}'  where `oid` = '" . $dingdaninfo["oid"] . "'";
            $up_q22 = "update `@#_user` SET `money` = `money` + " . $dingdaninfo["omoney"] . "  where `uid` = '" . $uid . "'";
            $up_q33 = "insert into `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('" . $uid . "', '1', '账户', '充值', '" . $dingdaninfo["omoney"] . "', '" . $time . "')";
            $up_q1 = $this->db->Query( $up_q11 );
            $up_q2 = $this->db->Query( $up_q22 );
            $up_q3 = $this->db->Query( $up_q33 );
            /* 充值按比例返现 */
            $rebate = _app_cfg( 'money', 'rebate' );
            /* 光100的整数才算 */
            $back_money = intval($dingdaninfo["omoney"] / 100) * 100;
            if ( $rebate > 0 && $back_money > 0 )
            {
                $back_money = $back_money / 100 * $rebate;
                $acc_arr["uid"]     = $uid;
                $acc_arr["type"]    = 1;
                $acc_arr["pay"]     = "账户";
                $acc_arr["content"] = "充值返现";
                $acc_arr["money"]   = $back_money;
                $acc_arr["time"]    = time();
                $text = "充值返现:" . $back_money;
                $order_model->user_add_chongzhi( $uid, $back_money, $text );
                $member_model->user_account_add( $acc_arr );
                $where = "`uid` = '{$uid}'";
                $user_data = "`money` = `money` + {$back_money}";
                $userdb->UpdateUser( $user_data, $where );
            }
            if ( $up_q1 && $up_q2 && $up_q3 )
            {
                $this->db->sql_commit();
                $wxstatus = 'success';
                /* 开始分佣 */
                distribute_money( $uid, $dingdaninfo['omoney'], $payclass );
                return true;
            }
            else
            {
                $this->db->sql_rollback();
                $wxstatus = 'fail';
            }
        }
        return false;
    }

    private function go_record()
    {
        $userpay_clouddb = System::load_app_model("UserPay_cloud", "common");

        if (is_array($this->scookie)) {
            $Cartlist = $this->scookie;
        }
        else {
            $Cartlist = json_decode(stripslashes(_getcookie("Cartlist")), true);
        }

        $shopids = "";

        if (is_array($Cartlist)) {
            foreach ($Cartlist as $key => $val ) {
                $shopids .= intval($key) . ",";
            }

            $shopids = str_replace(",0", "", $shopids);
            $shopids = trim($shopids, ",");
        }

        $shoplist = array();

        if ($shopids != NULL) {
            $shoplist = $userpay_clouddb->SelectCgoods($shopids);
        }
        else {
            return L("cgoods.cartlist.no");
        }

        $MoenyCount = 0;
        $shopguoqi = 0;

        if (1 <= count($shoplist)) {
            $scookies_arr = array();
            $scookies_arr["MoenyCount"] = 0;

            foreach ($Cartlist as $key => $val ) {
                $key = intval($key);
                if (isset($shoplist[$key]) && ($shoplist[$key]["shenyurenshu"] != "0")) {
                    if (($shoplist[$key]["xsjx_time"] != "0") && ($shoplist[$key]["xsjx_time"] < time())) {
                        unset($shoplist[$key]);
                        $shopguoqi = 1;
                        continue;
                    }

                    if (isset($val["num_qishu"])) {
                        $shoplist[$key]["cart_gorenci"] = ($val["num"] ? intval($val["num"]) : 1);
                        $shoplist[$key]["cart_qishu"] = ($val["num_qishu"] ? intval($val["num_qishu"]) : 1);

                        if ($shoplist[$key]["shenyurenshu"] <= $shoplist[$key]["cart_gorenci"]) {
                            $shoplist[$key]["cart_gorenci"] = $shoplist[$key]["shenyurenshu"];
                        }

                        $MoenyCount += $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"] * $shoplist[$key]["cart_qishu"];
                        $shoplist[$key]["cart_xiaoji"] = substr(sprintf("%.3f", $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"] * $shoplist[$key]["cart_qishu"]), 0, -1);
                        $shoplist[$key]["cart_shenyu"] = $shoplist[$key]["zongrenshu"] - $shoplist[$key]["canyurenshu"];
                        $scookies_arr[$key]["shenyu"] = $shoplist[$key]["cart_shenyu"];
                        $scookies_arr[$key]["num"] = intval($shoplist[$key]["cart_gorenci"]);
                        $scookies_arr[$key]["num_qishu"] = intval($shoplist[$key]["cart_qishu"]);
                        $scookies_arr[$key]["money"] = intval($shoplist[$key]["price"]);
                        $scookies_arr["MoenyCount"] += intval($shoplist[$key]["cart_xiaoji"]);
                    }
                    else {
                        $shoplist[$key]["cart_gorenci"] = ($val["num"] ? intval($val["num"]) : 1);

                        if ($shoplist[$key]["shenyurenshu"] <= $shoplist[$key]["cart_gorenci"]) {
                            $shoplist[$key]["cart_gorenci"] = $shoplist[$key]["shenyurenshu"];
                        }

                        $MoenyCount += $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"];
                        $shoplist[$key]["cart_xiaoji"] = substr(sprintf("%.3f", $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"]), 0, -1);
                        $shoplist[$key]["cart_shenyu"] = $shoplist[$key]["zongrenshu"] - $shoplist[$key]["canyurenshu"];
                        $scookies_arr[$key]["shenyu"] = $shoplist[$key]["cart_shenyu"];
                        $scookies_arr[$key]["num"] = intval($shoplist[$key]["cart_gorenci"]);
                        $scookies_arr[$key]["money"] = intval($shoplist[$key]["price"]);
                        $scookies_arr["MoenyCount"] += intval($shoplist[$key]["cart_xiaoji"]);
                    }
                }
                else {
                    unset($shoplist[$key]);
                }
            }

            if (count($shoplist) < 1) {
                $scookies_arr = "0";
                $this->db->sql_rollback();

                if ($shopguoqi) {
                    return L("cgoods.autolottery.no");
                }
                else {
                    return L("cgoods.cartlist.no");
                }
            }
        }
        else {
            $scookies_arr = "0";
            $this->db->sql_rollback();
            return L("cgoods.cartlist.err");
        }

        $this->MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);

        if ($this->fufen) {
            if ($this->members["score"] <= $this->fufen) {
                $this->fufen = $this->members["score"];
            }

            $fufen = findconfig("fufen", "fufen_yuan");

            if ($fufen) {
                $this->fufen_to_money = intval($this->fufen / $fufen);

                if ($this->MoenyCount <= $this->fufen_to_money) {
                    $this->fufen_to_money = $this->MoenyCount;
                    $this->fufen = $this->fufen_to_money * $fufen;
                }
            }
            else {
                $this->fufen_to_money = 0;
                $this->fufen = 0;
            }
        }
        else {
            $this->fufen_to_money = 0;
            $this->fufen = 0;
        }

        $this->shoplist = $shoplist;
        $this->scookies_arr = $scookies_arr;
        return "ok";
    }

    /**
     * 添加充值记录 + 去充值
     */
    private function addmoney_record( $money = NULL, $data = NULL )
    {
        $userpaydb   = System::load_app_model( "UserPay", "common" );
        $uid         = $this->members["uid"];
        $dingdancode = pay_get_dingdan_code("C");

        if ( ! is_array( $this->pay_type ) )
        {
            return "not_pay";
        }
        else
        {
            $pay_type = $this->pay_type["pay_id"];
            $time = time();

            if ( ! empty( $data ) )
            {
                $scookies = $data;
            }
            else
            {
                $scookies = "0";
            }

            $score = $this->fufen;

            if ( $scookies )
            {
                $value = "'$uid','$dingdancode','$money','1','$time','$scookies'";
                $query = $userpaydb->insert_money_record( $value );
            }
            else
            {
                $sRemark = $this->pay_type['pay_name'] . '充值';
                $InsertOrders_html = "('1','$uid','$dingdancode','$money','1','$pay_type','$sRemark','$time'),";
                $query = $userpaydb->InsertOrders( $InsertOrders_html );
            }

            if ( $query )
            {
                $this->db->sql_commit();
            }
            else
            {
                $this->db->sql_rollback();
                return false;
            }

            $pay_type = $this->pay_type;
            $config = array();
            $config["pay_id"]        = $pay_type["pay_id"];
            $config["pay_class"]     = $pay_type["pay_class"];
            $config["pay_shouname"]  = _cfg("web_name");
            $config["pay_title"]     = _cfg("web_name");
            $config["pay_money"]     = $money;
            $config["pay_bank"]      = $this->pay_type["pay_bank"];
            $config["pay_code"]      = $dingdancode;
            $config['pay_ReturnUrl'] = G_WEB_PATH.'/index.php/plugin-Pay-return-Recharge-alipayReturnUrl?';
            $config['pay_NotifyUrl'] = G_WEB_PATH.'/index.php/plugin-Pay-return-Recharge-alipayNotifyUrl?';


            switch ($pay_type['pay_class']) {
                case 'wxpay':
                    $pay_title = "微信支付";
                    $pay_pic = "wxlogo_pay.png";
                    $pay_name = "微信";
                    if(is_weixin()){
                        //如果是微信 则走公众号支付
                        $_POST['pay_method'] = "pay.weixin.jspay";
                    }else{
                        //否则走正常的pc网页支付
                        $_POST['pay_method'] = "pay.weixin.native";
                    }

                    $_POST['notify_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/?/member/pay_notify/wxpay_notify&id=" . time();
                    break;
                case 'alipay':
                    $pay_title = "支付宝支付";
                    $pay_pic = "alipay.gif";
                    $pay_name = "支付宝";
                    $_POST['pay_method'] = "pay.alipay.native";
                    $_POST['notify_url'] = "http://" . $_SERVER['HTTP_HOST'] . "/?/member/pay_notify/alipay_notify&id=" . time();
                    break;
                default:
                    exit;
            }
        
            //导入统一支付接口 第三方做的集成阿里支付、微信支付的工具
            include G_PLUGIN . "Pay/unipay/request.php";
            $_POST['out_trade_no'] = $dingdancode;
            $_POST['body'] = '夺宝币充值';
            $_POST['total_fee'] = $money*100;
            $_POST['mch_create_ip'] = $_SERVER['SERVER_ADDR'];
            $pay_class = new Request();
            $aPayInfo = $pay_class->submitOrderInfo();
            file_put_contents("/home/dev/test.log", 'get_pay_info:' . json_encode($aPayInfo).PHP_EOL, FILE_APPEND);

            if(isset($aPayInfo['token_id'])){
                $sTokenId = $aPayInfo['token_id'];
                $pay_url = "https://pay.swiftpass.cn/pay/jspay?token_id=${sTokenId}&showwxtitle=1";
                header("Location:$pay_url");
                exit;
            }
            
            include G_PLUGIN."Pay/unipay/pay_display.php";
            return true;

/*            if(!in_array(intval($money), array(10,20,50,100,200,))){
                $pay_subfix = 'any';
            }else{
                $pay_subfix = $money;
            }
            $config['jump_url'] = '/system/static/images/' . $config["pay_class"] . '_' . $pay_subfix . '.jpg';
            include G_PLUGIN . "Pay/" . $config["pay_class"] . "/lib/" . $config["pay_class"] . ".class.php";
            $apiclass = new $config["pay_class"]();
            $apiclass->config( $config );
            $apiclass->send_pay();*/
        }
    }

    /**
     * 发起支付
     * @author Yusure  http://yusure.cn
     * @date   2015-10-28
     * @param  [param]
     * @return [type]     [description]
     */
    private function third_pay($money = NULL, $data = NULL, $pay_sn)
    {

        $pay_type = $this->pay_type;
        $config = array();
        $config["pay_id"] = $pay_type["pay_id"];
        $config["pay_class"] = $pay_type["pay_class"];
        $config["pay_shouname"] = _cfg("web_name");
        $config["pay_title"] = _cfg("web_name");
        $config["pay_money"] = $money;
        $config["pay_bank"] = $this->pay_type["pay_bank"];
        $config["pay_code"] = $pay_sn;
        $config['order_type'] = 'cloud_order';
        /* 手机支付宝，换回调地址 */
        if ( $config['pay_class'] == 'malipay' )
        {
            $config['pay_ReturnUrl'] = G_WEB_PATH.'/index.php/plugin-Pay-return-WAPalipayReturnUrl?';
            $config['pay_NotifyUrl'] = G_WEB_PATH.'/index.php/plugin-Pay-return-WAPalipayNotifyUrl?';
        }

        include G_PLUGIN . "Pay/" . $config["pay_class"] . "/lib/" . $config["pay_class"] . ".class.php";
        $apiclass = new $config["pay_class"]();
        $apiclass->config($config);
        $apiclass->send_pay();
    }

    /**
     * 生成订单
     */
    private function set_dingdan($pay_type = "", $dingdanzhui = "")
    {
        $userpaydb    = System::load_app_model("UserPay", "common");
        $user_clouddb = System::load_app_model("UserPay_cloud", "common");
        $order_db     = System::load_app_model("order", "common");

        if ( file_exists( G_PLUGIN . "MulBuy/MulBuy.model.php" ) ) {
            include_once G_PLUGIN . "MulBuy/MulBuy.model.php";
            $MulBuydb = new MulBuy_model();
        }

        $uid         = $this->members["uid"];
        $uphoto      = $this->members["img"];
        $username    = addslashes(get_user_name($this->members));
        $insert_html = "";

        if ( 1 < count( $this->shoplist ) ) {
            $dingdancode_tmp = 1;
        }
        else {
            $dingdancode_tmp = 0;
        }

        $ip                = $this->members["user_ip"];
        $time              = sprintf( "%.3f", microtime( true ) );
        $this->MoenyCount  = 0;
        $insert_html       = array();
        $codenum           = 0;
        $this->dingdancode = array();

        foreach ( $this->shoplist as $key => $shop ) {
            $ret_data = array();
            pay_get_shop_codes( $shop["cart_gorenci"], $shop, $ret_data );
            $this->dingdan_query = $ret_data["query"];

            if ( ! $ret_data["query"] ) {
                $this->dingdan_query = false;
            }

            $codes                   = $ret_data["user_code"];
            $codes_len               = intval($ret_data["user_code_len"]);
            $shop["canyurenshu"]     = intval($shop["canyurenshu"]) + $codes_len;
            $shop["goods_count_num"] = $codes_len;
            $shop["g_title"]         = addslashes($shop["g_title"]);
            $this->shoplist[$key]    = $shop;
            $orderinfo               = array();
            $orderinfo["ostatus"]    = "0";
            $orderinfo["opay"]       = "账户";
            $orderinfo["otime"]      = $time;
            $orderinfo["ouid"]       = $uid;
            $orderinfo["ou_name"]    = $username;
            $orderinfo["ogid"]       = $shop["id"];
            $orderinfo['area_id']    = $shop['area_id'];
            $shopinfo                = array();
            $shopinfo["g_title"]     = $shop["g_title"];
            $shopinfo["g_thumb"]     = $shop["g_thumb"];
            $orderinfo["og_title"]   = serialize($shopinfo);
            $orderinfo["oqishu"]     = $shop["qishu"];
            $orderinfo["oip"]        = $ip;
            if ( $this->members['yaoqing'] > 0 )
            {
                /* 获取三级管理商ID */
                $manage_id = get_manage_id( $this->members['yaoqing'] );
                if ( $manage_id )
                {
                    $orderinfo['manage1_id'] = $manage_id['1'];
                    $orderinfo['manage2_id'] = $manage_id['2'];
                    $orderinfo['manage3_id'] = $manage_id['3'];
                }
            }

            /* 夺宝次数小于 3000 */
            if ( $codes_len <= 3000 ) {
                $this->dingdancode[$codenum]["ocode"] = $dingdancode = pay_get_dingdan_code( $dingdanzhui );
                $orderinfo["ocode"]                   = $dingdancode;
                $orderinfo["onum"]                    = $codes_len;
                $orderinfo["ogocode"]                 = $codes;
                $money                                = $codes_len * $shop["price"];
                $this->MoenyCount                     += $money;
                $orderinfo["omoney"]                  = $money;
                /* 写入订单 */                
                $InsertOrders                         = $order_db->write_order( $orderinfo );
                if ( $MulBuydb && ( 1 < $shop["cart_qishu"] ) ) {
                    $this->MoenyCount        += $money * ($shop["cart_qishu"] - 1);
                    $orderinfo["omoney"]     = $money * $shop["cart_qishu"];
                    $orderinfo["cart_qishu"] = $shop["cart_qishu"];
                    $InsertMulOrders         = $MulBuydb->write_mul_will($orderinfo);
                }

                if ( $InsertOrders ) {
                    $codenum = $codenum + 1;
                }
            }
            else {
                $codearray = explode( ",", $codes );
                $num       = ceil( $codes_len / 3000 );
                $j         = 0;
                $codesum   = count( $codearray );

                for ( $i = 1; $i <= $num; $i++ ) {
                    $this->dingdancode[$codenum]["ocode"] = $dingdancode = pay_get_dingdan_code($dingdanzhui);
                    $orderinfo["ocode"]                   = $dingdancode;

                    if ( $codesum <= $i * 3000 ) {
                        $limitnum = $codesum;
                    }
                    else {
                        $limitnum = $i * 3000;
                    }

                    $codes1               = array_slice( $codearray, $j * 3000, $limitnum - 1 );
                    $codes2               = implode(",", $codes1);
                    $sublen               = $limitnum - ($j * 3000);
                    $money                = $sublen * $shop["price"];
                    $this->MoenyCount     += $money;
                    $orderinfo["omoney"]  = $money;
                    $orderinfo["ocode"]   = $dingdancode;
                    $orderinfo["onum"]    = $sublen;
                    $orderinfo["ogocode"] = $codes2;
                    /* 写入订单 */
                    $InsertOrders         = $order_db->write_order( $orderinfo );
                    if ( $MulBuydb && (1 < $shop["cart_qishu"]) ) {
                        $this->MoenyCount        += $money * ($shop["cart_qishu"] - 1);
                        $orderinfo["omoney"]     = $money * $shop["cart_qishu"];
                        $orderinfo["cart_qishu"] = $shop["cart_qishu"];
                        $InsertMulOrders         = $MulBuydb->write_mul_will( $orderinfo );
                    }

                    if ( $InsertOrders ) {
                        $j++;
                        $codenum = $codenum + 1;
                    }
                }
            }
        }

        return $InsertOrders;
    }

    /**
     * 去支付
     */
    public function go_pay( $pay_checkbox, $shoptype = "" )
    {
        $uid = $this->members["uid"];

        if ( ! is_array( $this->pay_type ) ) {
            return "not_pay";
        }

        if ( is_array( $this->scookies_arr ) ) {
            $scookie = serialize( $this->scookies_arr );
        }
        else {
            $scookie = "0";
        }

        /* 夺宝币购买 */
        if ( $pay_checkbox ) {
            // 福分购买就减掉福分
            if ($this->fufen) {
                $lamoney = $this->MoenyCount - $this->fufen_to_money;
            }
            else {
                $lamoney = $this->MoenyCount;
            }

            // 判断用户的余额大于商品价格才执行购买 
            if ( $lamoney <= $this->members["money"] ) {
                if ( $shoptype == "go_goods" ) {
                    $pay_1 = $this->gpay_bag();
                }
                else {
                    /* 生成订单 */
                    $pay_1       = $this->pay_bag();
                    $dingdancode = $this->dingdancode;
                    /* 更新基金 */
                    $pay_2       = pay_go_fund( $this->goods_count_num );
                    /* 发放佣金 */
                    $pay_3       = pay_go_yongjin( $uid, $dingdancode );
                }

                if ( ! $pay_1 ) {
                    return $pay_1;
                }

                return $pay_1;
            }
            else {
                _message( '余额不足，请充值！', WEB_PATH . "/member/cart/cartlist" );
                $money = $lamoney - $this->members["money"];
                return $this->addmoney_record( $money, $scookie );
            }
        }
        /* 福分购买 */
        else if ($this->fufen) {
            $money = $this->MoenyCount - $this->fufen_to_money;

            if ( 0 < $money ) {
                return $this->addmoney_record($money, $scookie);
            }
            else {
                if ($shoptype == "go_goods") {
                    $pay_1 = $this->gpay_bag();
                }
                else {
                    $pay_1 = $this->pay_bag();
                    $dingdancode = $this->dingdancode;
                    $pay_2 = pay_go_fund($this->goods_count_num);
                    $pay_3 = pay_go_yongjin($uid, $dingdancode);
                }

                if (!$pay_1) {
                    return $pay_1;
                }

                return $pay_1;
            }
        }
        else
        {
            return "not_pay";
        }
        /* 第三方购买 例如：支付宝 注于：20161029 客户不需要，只用夺宝币购买 */
        // else {
        //     /* 新增插入订单逻辑 */
        //     $pay_sn = $this->set_third_order();
        //     return $this->third_pay( $this->MoenyCount, $scookie, $pay_sn );
        // }

        exit();
    }


    /**
     * 第三方支付订单
     * @author Yusure  http://yusure.cn
     * @date   2015-10-28
     * @param  [param]
     */
    private function set_third_order()
    {
        $userpaydb = System::load_app_model("UserPay", "common");
        $user_clouddb = System::load_app_model("UserPay_cloud", "common");
        $order_db = System::load_app_model("order", "common");
        $uid = $this->members["uid"];
        $uphoto = $this->members["img"];
        $username = addslashes(get_user_name($this->members));

        $this->MoenyCount = 0;

        $codenum = 0;
        $this->dingdancode = array();
        $userdb = System::load_app_model("user", "common");
        /* 各种银行统一置成 网银在线 */
        if ( strstr( $_POST['account'], 'cbpay' ) )
        {
            $_POST['account'] = 3;
        }
        $pay_res = $userdb->GetOnePay( 'pay_id = '.$_POST['account'], 'pay_name' );

        $ip = $this->members["user_ip"];
        $time = sprintf("%.3f", microtime(true));

        // 产生订单编号
        $pay_sn = make_paysn( $uid );
        foreach ($this->shoplist as $key => $shop ) 
        {

            $orderinfo = array();
            $orderinfo['pay_sn']      = $pay_sn;
            $orderinfo['codes_table'] = $shop['codes_table'];
            $orderinfo["opay"]        = $pay_res['pay_name'];
            $orderinfo["otime"]       = $time;
            $orderinfo["ouid"]        = $uid;
            $orderinfo["ou_name"]     = $username;
            $orderinfo["ogid"]        = $shop["id"];
            $shopinfo = array();
            $shopinfo["g_title"]   = addslashes($shop["g_title"]);
            $shopinfo["g_thumb"]   = $shop["g_thumb"];
            $orderinfo["og_title"] = serialize($shopinfo);
            $orderinfo["oqishu"]   = $shop["qishu"];
            $orderinfo["oip"]      = $ip;


            $orderinfo["ocode"] = pay_get_dingdan_code();
            $orderinfo["onum"] = $shop["cart_gorenci"];
            $money = $shop["cart_gorenci"] * $shop["price"];
            $this->MoenyCount += $money;
            $orderinfo["omoney"] = $money;

            $InsertOrders = $order_db->write_third_order($orderinfo);

        }
        return $pay_sn;

    }

    /**
     * 付钱
     * $query_1  生成订单
     * $query_2  减去用户的钱 更新
     * $query_3  查询用户信息
     * $query_4  插入记录 会员账户明细
     * $query_5  更新 yg_cloud_goods 的 canyurenshu shengyurenshu 的人数
     * $query_6  yg_cloud_select   ofstatus = 0 发货状态(1未发货,2已发货,3已收货)     ostatus = 2 付款状态(1未付款,2已付款,3退款中.4退款成功)
     * $query_7  cloud_codes_1   产生夺宝码
     * $query_8  更新 全站总夺宝次数
     */
    private function pay_bag()
    {
        $userpay_clouddb = System::load_app_model("UserPay_cloud", "common");
        $userpaydb       = System::load_app_model("UserPay", "common");
        $order_db        = System::load_app_model("order", "common");
        $fufen           = System::load_sys_config("user_fufen");
        $time    = time();
        $uid     = $this->members["uid"];
        $query_1 = $this->set_dingdan("账户", "A");

        if ( ! $this->MoenyCount ) {
            return false;
        }

        $Money = ($this->members["money"] - $this->MoenyCount) + $this->fufen_to_money;
        $pay_zhifu_name = "账户";
        $query_fufen    = true;

        if ( $this->fufen_to_money ) {
            $pay_zhifu_name   = "福分";
            $this->MoenyCount = $this->fufen;
            $userscore        = $this->members["score"];
            $score            = $userscore - $this->fufen;
            $setwords         = "`score`='$score'";
            $query_fufen      = $userpaydb->UpdateUserInfo( $uid, $setwords );
        }

        $setwords        = "`money`='$Money'";
        $query_2         = $userpaydb->UpdateUserInfo( $uid, $setwords );
        $query_3         = $info = $userpaydb->SelectUserUid( $uid );
        $insert_html     = "'$uid', '-1', '$pay_zhifu_name', '参与夺宝商品', '$this->MoenyCount', '$time'";
        $query_4         = $userpaydb->InsertAccount( $insert_html );
        $query_5         = true;
        $query_insert    = true;
        $goods_count_num = 0;

        foreach ( $this->shoplist as $shop ) {
            if ( ($shop["zongrenshu"] <= $shop["canyurenshu"]) && ($shop["qishu"] <= $shop["maxqishu"]) ) {
                $update_cgoods = "`canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '{$shop["id"]}'";
                $userpay_clouddb->UpdateCgoods($update_cgoods);
            }
            else {
                $shenyurenshu  = $shop["zongrenshu"] - $shop["canyurenshu"];
                $update_cgoods = "`canyurenshu` = '{$shop["canyurenshu"]}',`shenyurenshu` = '$shenyurenshu' WHERE `id`='{$shop["id"]}'";
                $query = $userpay_clouddb->UpdateCgoods( $update_cgoods );

                if ( ! $query ) {
                    $query_5 = false;
                }
            }

            $goods_count_num += $shop["goods_count_num"];
        }

        $jingyan       = $this->members["jingyan"] + ($fufen["z_shoppay"] * $goods_count_num);
        $setwords      = "`jingyan`='$jingyan'";
        $query_jingyan = $userpaydb->UpdateUserInfo( $uid, $setwords );

        if ( ! $this->fufen_to_money ) {
            $mygoscore         = $fufen["f_shoppay"] * $goods_count_num;
            $myscore           = $this->members["score"] + $mygoscore;
            $setwords          = "`score`='$myscore'";
            $query_add_fufen_1 = $userpaydb->UpdateUserInfo($uid, $setwords);
            $mygoscore_text    = "夺宝了$goods_count_num人次商品";
            $insert_html       = "'$uid', '1', '福分', '$mygoscore_text', '$mygoscore', '$time'";
            $query_add_fufen_2 = $userpaydb->InsertAccount($insert_html);
            $query_fufen       = $query_add_fufen_1 && $query_add_fufen_2;
        }

        $dingdancode = $this->dingdancode;

        foreach ( $dingdancode as $key => $v ) {
            $where            = "`ocode`='{$v["ocode"]}'";
            $data             = array();
            $data["ofstatus"] = "0";
            $data["ostatus"]  = "2";
            $query_6          = $order_db->update_order($where, $data);
        }

        $query_7 = $this->dingdan_query;
        $query_8 = $userpaydb->UpdateRecordSum($goods_count_num);
        $this->goods_count_num = $goods_count_num;
        if ( $query_fufen && $query_jingyan && $query_1 && $query_2 && $query_3 && $query_4 && $query_5 && $query_6 && $query_7 && $query_8 && $query_insert ) {
            if ( $info["money"] == $Money ) {
                $this->db->sql_commit();

                foreach ( $this->shoplist as $shop ) {
                    if ( ($shop["zongrenshu"] <= $shop["canyurenshu"]) && ($shop["qishu"] <= $shop["maxqishu"]) ) {
                        $this->db->sql_begin();
                        $loconfig = System::load_sys_config("lotteryway");
                        $userpaydb->UpdateUserInfo( $uid, $setwords );
                        $json_shop = json_encode( $shop );
                        $json_shop = base64_encode( $json_shop );
                        $post_arr  = array("shop" => $json_shop, "lotteryway" => $loconfig["lotteryway"]["opennow"]);
                        _g_triggerRequest( WEB_PATH . "/plugin-CloudWay-optway", false, $post_arr );

                        if ( ! $query_insert ) {
                            return false;
                            $this->db->sql_rollback();
                        }
                        else {
                            $this->db->sql_commit();
                        }
                    }
                }

                return true;
            }
            else {
                $this->db->sql_rollback();
                return false;
            }
        }
        else {
            $this->db->sql_rollback();
            return false;
        }
    }

    public function pay_user_go_shop($action = NULL, $name = NULL, $value = NULL)
    {
        if ($action == "set") {
            $this->$name = $value;
            return NULL;
        }

        if ($action == "call") {
            return $this->{$name}($value);
        }
    }
}


