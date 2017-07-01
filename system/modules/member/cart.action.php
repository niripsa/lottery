<?php
System::load_app_class("UserAction", "common", "no");
class cart extends SystemAction
{
    private $Cartlist;

    public function __construct()
    {
        $this->db           = System::load_sys_class("model");
        $this->model        = System::load_app_model("cloud_goods", "common");
        $this->Cartlist     = _getcookie("Cartlist");
        $this->shoplist     = array();
        $this->MoenyCount   = "";
        $this->fufen_dikou  = "";
        $this->Cartshopinfo = "";
        $this->Cartlistinfo();
    }

    public function cartheader()
    {
        $Cartlist = $this->Cartlist;
        $shoplist = $this->shoplist;
        $li = "";

        foreach ($shoplist as $st ) {
            $li .= "<dl class=\"mycartcur\" id=\"mycartcur" . $st["id"] . "\">";
            $li .= "<dt class=\"img\"><a href=\"" . WEB_PATH . "/cgoods/" . $st["id"] . "\" target=\"_blank\"><img src=\"" . G_UPLOAD_PATH . "/" . $st["g_thumb"] . "\"></a></dt>";
            $li .= "<dd class=\"title\"><a href=\"" . WEB_PATH . "/cgoods/" . $st["id"] . "\" target=\"_blank\">" . $st["g_title"] . "</a>";
            $li .= "<span >" . $st["price"] . L("cgoods.currency") . "×" . $st["cart_gorenci"] . "</span>";
            $li .= "</dd>";
            $li .= "<dd class=\"del\"><a class=\"delGood\" onclick=\"delheader(" . $st["id"] . ")\" href=\"javascript:;\">删除</a></dd>";
            $li .= "</dl>";
        }

        if (5 <= count($shoplist)) {
            $li .= "<dl class=\"mycartcur\" style=\" background:#fff;height:20px; text-align:right;\"><a style=\" color:#777;\" target=\"_blank\" href=\"" . WEB_PATH . "/member/cart/cartlist\">查看更多<i>&gt;</i></a></dl>";
        }

        $shop["li"] = $li;

        if (!is_array($Cartlist)) {
            $shop["num"] = 0;
        }
        else {
            $shop["num"] = count($Cartlist) - 1;
        }

        $shop["sum"] = $this->MoenyCount;
        echo json_encode($shop);
    }

    public function cartshop()
    {
        $this->view->show();
    }

    public function getnumber()
    {
        $Cartlist = json_decode(stripslashes($this->Cartlist), true);

        if (!is_array($Cartlist)) {
            echo 0;
        }
        else {
            echo count($Cartlist) - 1;
        }
    }

    /**
     * 第一步：提交订单 页面
     */
    public function cartlist()
    {
        seo("title", _cfg("web_name") . "_购物车列表");
        seo("keywords", _cfg("web_name") . "_购物车列表");
        seo("description", _cfg("web_name") . "_购物车列表");
        $this->view->show()->data("shoplist", $this->shoplist)->data("MoenyCount", $this->MoenyCount)->data("Cartshopinfo", $this->Cartshopinfo);
    }

    /**
     * 支付列表
     */
    public function pay()
    {
        seo("title", _cfg("web_name") . "_支付列表");
        seo("keywords", _cfg("web_name") . "_支付列表");
        seo("description", _cfg("web_name") . "_支付列表");
        $user = System::load_app_class("UserCheck", "common");

        if ( ! $user->GetUserCheckToBool() )
        {
            _message( L("user.login.wno"), WEB_PATH . "/login" );
        }
        else
        {
            $user   = System::load_app_class("UserCheck", "common");
            $member = $user->UserInfo;

            if ( 1 <= count( $this->shoplist ) )
            {

            }
            else
            {
                $cookieinfo = System::load_sys_config( "system" );
                _setcookie( $cookieinfo["cookie_pre"] . "Cartlist", NULL );
                _message( L("cgoods.cartlist.no"), WEB_PATH );
            }

            $MoenyCount = substr( sprintf("%.3f", $this->MoenyCount), 0, -1 );
            $fufen_yuan = findconfig("fufen", "fufen_yuan");
            $fufen_dikou = 0;
            $fufen_money = 0;

            if ( $this->fufen_dikou )
            {
                if ( $member["score"] )
                {
                    $fufen_num   = intval($member["score"] / $fufen_yuan);
                    $fufen_money = $fufen_num;
                    $fufen_dikou = $fufen_yuan * $fufen_num;
                }
                else
                {
                    $fufen_dikou = 0;
                }
            }
            else
            {
                $fufen_dikou = 0;
            }

            $Money   = $member["money"];
            $userdb  = System::load_app_model("user", "common");
            $paylist = $userdb->GetPaylist();
            $shoplen = count( $this->shoplist );
            $cookies = base64_encode( $this->Cartlist );
            session_start();
            $_SESSION["submitcode"] = $submitcode = uniqid();
            $this->view->data("MoenyCount", $MoenyCount);
            $this->view->data("fufen_dikou", $fufen_dikou);
            $this->view->data("fufen_yuan", $fufen_yuan);
            $this->view->data("fufen_money", $fufen_money);
            $this->view->data("shoplist", $this->shoplist);
            $this->view->data("paylist", $paylist);
            $this->view->data("member", $member);
            $this->view->data("shoplen", $shoplen);
            $this->view->data("submitcode", $submitcode);
            $this->view->show();
        }
    }

    /**
     * 支付提交
     */
    public function paysubmit()
    {
        if ( ! isset( $_POST["submit"] ) )
        {
            _message(L("cgoods.cartlist.go"), WEB_PATH . "/member/cart/cartlist");
            exit();
        }

        session_start();

        if ( isset( $_POST["submitcode"] ) ) {
            if ( isset( $_SESSION["submitcode"] ) ) {
                $submitcode = $_SESSION["submitcode"];
            }
            else {
                $submitcode = NULL;
            }

            if ( $_POST["submitcode"] == $submitcode ) {
                unset( $_SESSION["submitcode"] );
            }
            else {
                _message( L("cgoods.submitcode.rep"), WEB_PATH . "/member/cart/cartlist" );
            }
        }
        else {
            _message( L("cgoods.cartlist.go"), WEB_PATH . "/member/cart/cartlist" );
        }

        $user = System::load_app_class( "UserCheck", "common" );

        if ( ! $user->UserInfo ) {
            _message( L("user.login.wno"), WEB_PATH . "/login" );
        }

        $uid = $user->UserInfo["uid"];

        if ( isset( $_POST["shop_score"] ) ) {
            $fufen_cfg = findconfig("fufen", "fufen_yuan");
            $fufen = intval($_POST["shop_score_num"]);

            if ( $fufen_cfg ) {
                $fufen = intval($fufen / $fufen_cfg);
                $fufen = $fufen * $fufen_cfg;
            }
        }
        else {
            $fufen = 0;
        }

        $pay_checkbox  = (isset($_POST["moneycheckbox"]) ? true : false);
        $pay_type_bank = (isset($_POST["pay_bank"]) ? $_POST["pay_bank"] : false);
        $pay_type_id   = (isset($_POST["account"]) ? $_POST["account"] : false);
        $Cartlist      = json_decode(stripslashes($this->Cartlist), true);
        $pay           = System::load_app_class("UserPay", "common");
        $pay->fufen         = $fufen;
        $pay->pay_type_bank = $pay_type_bank;
        $ok = $pay->init( $uid, $pay_type_id, "go_record" );

        if ( $ok !== "ok" ) {
            $_COOKIE["Cartlist"] = NULL;
            _setcookie("Cartlist", NULL);
            _message( $ok, G_WEB_PATH );
        }

        $check = $pay->go_pay( $pay_checkbox );

        if ( $check === "not_pay" ) {
            _message(L("pay.type.err"), WEB_PATH . "/member/cart/cartlist");
        }

        if ( $check === "not_money" ) {
            _message(L("cgoods.balance.no"), WEB_PATH . "/member/account/userrecharge");
        }

        if ( ! $check ) {
            _message(L("cgoods.pay.err"), WEB_PATH . "/member/cart/cartlist");
        }

        if ( $check ) {
            _setcookie("Cartlist", NULL);
            _header("location: " . WEB_PATH . "/member/cart/paysuccess");
        }
        else {
            _setcookie("Cartlist", NULL);
            _header("location: " . WEB_PATH);
        }

        exit();
    }

    public function paysuccess()
    {
        seo("title", _cfg("web_name") . "_支付成功");
        seo("keywords", _cfg("web_name") . "_支付成功");
        seo("description", _cfg("web_name") . "_支付成功");
        $_COOKIE["Cartlist"] = NULL;
        _setcookie("Cartlist", NULL);
        $this->view->show();
    }

    /**
     * 去支付
     */
    public function addmoney()
    {
        if(isset($_GET['code'])){
            $_POST = $_REQUEST;
        }

        if ( ! isset( $_POST["submit"] ) )
        {
            _message( L("addmoney.html.go"), WEB_PATH . "/?/member/home/userrecharge" );
        }
        $user = System::load_app_class("UserCheck", "common");
        if ( ! $user )
        {
            _message( L("user.login.wno"), WEB_PATH . "/login" );
        }

        $pay_type_bank = (isset($_POST["pay_bank"]) ? $_POST["pay_bank"] : false);
        $pay_type_id   = (isset($_POST["account"]) ? $_POST["account"] : false);
        $money = intval( $_POST["money"] );
        $uid   = $user->UserInfo["uid"];
        $pay   = System::load_app_class( "UserPay", "common" );
        $pay->pay_type_bank = $pay_type_bank;
        $ok = $pay->init( $uid, $pay_type_id, "addmoney_record", $money );

        if ( $ok === "not_pay" )
        {
            _message( L("pay.type.err") );
        }
    }

    /**
     * 获取购物车中的 商品信息
     */
    public function Cartlistinfo()
    {
        $Cartlist = json_decode(stripslashes($this->Cartlist), true);
        $shopids  = "";

        if ( is_array( $Cartlist ) ) {
            foreach ( $Cartlist as $key => $val ) {
                $shopids .= intval($key) . ",";
            }

            $shopids = str_replace( ",0", "", $shopids );
            $shopids = trim( $shopids, "," );
        }

        $shoplist = array();

        if ( $shopids != NULL ) {
            $this->shoplist = $this->model->cloud_goodsm($shopids);
        }

        $shoplist     = $this->shoplist;
        $MoenyCount   = 0;
        $Cartshopinfo = "{";
        $fufen_dikou  = 0;

        if ( 1 <= count( $shoplist ) ) {
            foreach ( $Cartlist as $key => $val ) {
                $key = intval($key);

                if (isset($shoplist[$key])) {
                    if (isset($val["num_qishu"])) {
                        $shoplist[$key]["cart_gorenci"] = ($val["num"] ? $val["num"] : 1);
                        $shoplist[$key]["cart_qishu"] = ($val["num_qishu"] ? $val["num_qishu"] : 1);
                        $MoenyCount += $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"] * $shoplist[$key]["cart_qishu"];
                        $shoplist[$key]["cart_xiaoji"] = substr(sprintf("%.3f", $shoplist[$key]["price"] * $val["num"] * $val["num_qishu"]), 0, -1);
                        $shoplist[$key]["cart_shenyu"] = $shoplist[$key]["zongrenshu"] - $shoplist[$key]["canyurenshu"];
                        $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist[$key]["cart_shenyu"] . ",'num':" . $val["num"] . ",'money':" . $shoplist[$key]["price"] . ",'max_qishu':" . $val["max_qishu"] . ",'curr_qishu':" . $val["curr_qishu"] . ",'num_qishu':" . $val["num_qishu"] . "},";

                        if ($shoplist[$key]["g_ispoints"] == "1") {
                            $fufen_dikou += 1;
                        }
                    }
                    else {
                        $shoplist[$key]["cart_gorenci"] = ($val["num"] ? $val["num"] : 1);
                        $MoenyCount += $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"];
                        $shoplist[$key]["cart_xiaoji"] = substr(sprintf("%.3f", $shoplist[$key]["price"] * $val["num"]), 0, -1);
                        $shoplist[$key]["cart_shenyu"] = $shoplist[$key]["zongrenshu"] - $shoplist[$key]["canyurenshu"];
                        $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist[$key]["cart_shenyu"] . ",'num':" . $val["num"] . ",'money':" . $shoplist[$key]["price"] . "},";

                        if ($shoplist[$key]["g_ispoints"] == "1") {
                            $fufen_dikou += 1;
                        }
                    }
                }
            }
        }

        $this->shoplist = $shoplist;
        $this->MoenyCount = substr(sprintf("%.3f", $MoenyCount), 0, -1);
        $this->fufen_dikou = intval($fufen_dikou);
        $Cartshopinfo .= "'MoenyCount':$MoenyCount}";
        $this->Cartshopinfo = $Cartshopinfo;
    }

    public function Fastpay()
    {
        if (!isset($_GET["shopid"])) {
            echo json_encode(array("error" => "1"));
            return NULL;
            exit();
        }

        $user = System::load_app_class("UserCheck", "common");

        if (!$user->GetUserCheckToBool()) {
            $ustatus = 0;
            $umoney = L("user.login.wno");
        }
        else {
            $user = System::load_app_class("UserCheck", "common");
            $member = $user->UserInfo;
            $ustatus = 1;
            $umoney = $member["money"];
        }

        $shopid = trim($_GET["shopid"]);
        $cloud_goods = System::load_app_model("cloud_goods", "common");
        $info = $cloud_goods->cloud_goodsdetail($shopid);

        if ($info["shenyurenshu"] == 0) {
            $ustatus = 2;
            $umoney = L("cgoods.pay.not");
            $tishi = L("car.no");
        }
        else {
            $tishi = L("car.ok");
        }

        echo json_encode(array("error" => "0", "zongrenshu" => $info["zongrenshu"], "canyurenshu" => $info["canyurenshu"], "price" => $info["price"], "cg_title" => $info["g_title"], "ustatus" => "$ustatus", "umoney" => "$umoney", "tishi" => "$tishi"));
    }

    public function Fastpaysubmit()
    {
        if (!isset($_POST["shopid"])) {
            echo json_encode(array("error" => L("cgoods.pay.err")));
            return NULL;
            exit();
        }
        else {
            $user = System::load_app_class("UserCheck", "common");

            if (!$user->GetUserCheckToBool()) {
                echo json_encode(array("error" => L("user.login.wno")));
                return NULL;
                exit();
            }
            else if (intval($_POST["num"]) <= 0) {
                echo json_encode(array("error" => "购买商品数量必须大于0!"));
                return NULL;
                exit();
            }
            else {
                $member = $user->UserInfo;
                $umoney = $member["money"];
                $Cartlist = array();
                $shopid = $_POST["shopid"];
                $Cartlist[$shopid] = array();
                $Cartlist[$shopid]["shenyu"] = $_POST["shenyu"];
                $Cartlist[$shopid]["num"] = $_POST["num"];
                $Cartlist[$shopid]["money"] = $_POST["money"];
                $Cartlist["MoenyCount"] = $_POST["MoenyCount"];
                $shoplist = $this->model->cloud_goodsm($shopid);
                $MoenyCount = 0;
                $Cartshopinfo = "{";

                if (1 <= count($shoplist)) {
                    foreach ($Cartlist as $key => $val ) {
                        $key = intval($key);

                        if (isset($shoplist[$key])) {
                            $shoplist[$key]["cart_gorenci"] = ($val["num"] ? $val["num"] : 1);
                            $MoenyCount += $shoplist[$key]["price"] * $shoplist[$key]["cart_gorenci"];
                            $shoplist[$key]["cart_xiaoji"] = substr(sprintf("%.3f", $shoplist[$key]["price"] * $val["num"]), 0, -1);
                            $shoplist[$key]["cart_shenyu"] = $shoplist[$key]["zongrenshu"] - $shoplist[$key]["canyurenshu"];
                            $Cartshopinfo .= "'$key':{'shenyu':" . $shoplist[$key]["cart_shenyu"] . ",'num':" . $val["num"] . ",'money':" . $shoplist[$key]["price"] . "},";
                        }
                    }
                }

                $Cartshopinfo .= "'MoenyCount':$MoenyCount}";

                if (!$user->UserInfo) {
                    echo json_encode(array("error" => L("user.login.wno")));
                    return NULL;
                    exit();

                    if ($umoney < $MoenyCount) {
                        echo json_encode(array("error" => L("cgoods.balance.no")));
                        return NULL;
                        exit();
                    }
                }
                else {
                    $uid = $user->UserInfo["uid"];
                }

                $pay_checkbox = true;
                $pay_type_id = true;
                $pay = System::load_app_class("UserPay", "common");
                $pay->pay_type_bank = "zhaoshang";
                $pay->scookie = $Cartlist;
                $ok = $pay->init($uid, $pay_type_id, "go_record");

                if ($ok !== "ok") {
                    echo json_encode(array("error" => $ok));
                    return NULL;
                    exit();
                }

                $check = $pay->go_pay($pay_checkbox);

                if (!$check) {
                    echo json_encode(array("error" => L("cgoods.pay.err")));
                    return NULL;
                    exit();
                }

                if ($check) {
                    echo json_encode(array("error" => L("cgoods.pay.suc")));
                    return NULL;
                    exit();
                }
                else {
                    echo json_encode(array("error" => L("cgoods.pay.err")));
                    return NULL;
                    exit();
                }
            }
        }
    }
}
?>
