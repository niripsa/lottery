<?php
System::load_app_class("UserAction", "common", "no");
class gcart extends SystemAction
{
    private $Cartlist;

    public function __construct()
    {
        $this->db = System::load_sys_class("model");
        $this->model = System::load_app_model("goods", "common");
        $this->Cartlist = _getcookie("Cartlista");
        $this->shoplist = array();
        $this->MoenyCount = "";
        $this->fufen_dikou = "";
        $this->Cartshopinfo = "";
        $this->Cartlistinfo();
    }

    public function cartshop()
    {
        $this->view->show();
    }

    public function cartlistgoods()
    {
        seo("title", _cfg("web_name") . "_购物车列表");
        seo("keywords", _cfg("web_name") . "_购物车列表");
        seo("description", _cfg("web_name") . "_购物车列表");
        $this->view->show("gcart.cartlist")->data("shoplist", $this->shoplist)->data("MoenyCount", $this->MoenyCount)->data("Cartshopinfo", $this->Cartshopinfo);
    }

    public function pay()
    {
        $user = System::load_app_class("UserCheck", "common");

        if (!$user->GetUserCheckToBool()) {
            _message(l("user.login.wno"), WEB_PATH . "/login");
        }
        else {
            $user = System::load_app_class("UserCheck", "common");
            $member = $user->UserInfo;

            if (1 <= count($this->shoplist)) {
            }
            else {
                $cookieinfo = System::load_sys_config("system");
                _setcookie("Cartlista", NULL);
                _message(l("cgoods.cartlist.no"), WEB_PATH);
            }

            $MoenyCount = substr(sprintf("%.3f", $this->MoenyCount), 0, -1);
            $fufen_yuan = findconfig("fufen", "fufen_yuan");
            $fufen_dikou = 0;
            $fufen_money = 0;

            if ($this->fufen_dikou) {
                if ($member["score"]) {
                    $fufen_num = intval($member["score"] / $fufen_yuan);
                    $fufen_money = $fufen_num;
                    $fufen_dikou = $fufen_yuan * $fufen_num;
                }
                else {
                    $fufen_dikou = 0;
                }
            }
            else {
                $fufen_dikou = 0;
            }

            $Money = $member["money"];
            $userdb = System::load_app_model("user", "common");
            $paylist = $userdb->GetPaylist();
            $shoplen = count($this->shoplist);
            $cookies = base64_encode($this->Cartlist);
            session_start();
            $_SESSION["submitcode"] = $submitcode = uniqid();
            $this->view->show()->data("MoenyCount", $MoenyCount)->data("paylist", $paylist)->data("fufen_dikou", $fufen_dikou)->data("member", $member)->data("shoplist", $this->shoplist)->data("Money", $Money)->data("fufen_yuan", $fufen_yuan)->data("fufen_money", $fufen_money)->data("shoplen", $shoplen)->data("submitcode", $submitcode);
        }
    }

    public function paysubmit()
    {
        seo("title", _cfg("web_name") . "_支付列表");
        seo("keywords", _cfg("web_name") . "_支付列表");
        seo("description", _cfg("web_name") . "_支付列表");

        if (!isset($_POST["submit"])) {
            _message(l("cgoods.cartlist.go"), WEB_PATH . "/member/gcart/cartlist");
            exit();
        }

        session_start();

        if (isset($_POST["submitcode"])) {
            if (isset($_SESSION["submitcode"])) {
                $submitcode = $_SESSION["submitcode"];
            }
            else {
                $submitcode = NULL;
            }

            if ($_POST["submitcode"] == $submitcode) {
                unset($_SESSION["submitcode"]);
            }
            else {
                _message(l("cgoods.submitcode.rep"), WEB_PATH . "/member/gcart/cartlist");
            }
        }
        else {
            _message(l("cgoods.cartlist.go"), WEB_PATH . "/member/gcart/cartlist");
        }

        $user = System::load_app_class("UserCheck", "common");

        if (!$user->UserInfo) {
            _message(l("user.login.wno"), WEB_PATH . "/login");
        }

        $uid = $user->UserInfo["uid"];

        if (isset($_POST["shop_score"])) {
            $fufen_cfg = findconfig("fufen", "fufen_yuan");
            $fufen = intval($_POST["shop_score_num"]);

            if ($fufen_cfg) {
                $fufen = intval($fufen / $fufen_cfg);
                $fufen = $fufen * $fufen_cfg;
            }
        }
        else {
            $fufen = 0;
        }

        $pay_checkbox = (isset($_POST["gmoneycheckbox"]) ? true : false);
        $pay_type_bank = (isset($_POST["pay_bank"]) ? $_POST["pay_bank"] : false);
        $pay_type_id = (isset($_POST["account"]) ? $_POST["account"] : false);
        $Cartlist = json_decode(stripslashes($this->Cartlist), true);
        $pay = System::load_app_class("UserPay", "common");
        $pay->fufen = $fufen;
        $pay->pay_type_bank = $pay_type_bank;
        $pay->scookie = $this->Cartlist;
        $ok = $pay->init($uid, $pay_type_id, "go_goods");

        if ($ok !== "ok") {
            $_COOKIE[$cookieinfo["cookie_pre"] . "Cartlista"] = NULL;
            _setcookie("Cartlista", NULL);
            _message($ok, G_WEB_PATH);
        }

        $check = $pay->go_pay($pay_checkbox, "go_goods");

        if ($check === "not_pay") {
            _message(l("pay.type.err"), WEB_PATH . "/member/gcart/cartlist");
        }

        if ($check === "not_money") {
            _message(l("cgoods.balance.no"), WEB_PATH . "/member/account/userrecharge");
        }

        if (!$check) {
            _message(l("cgoods.pay.err"), WEB_PATH . "/member/gcart/cartlist");
        }

        if ($check) {
            header("location: " . WEB_PATH . "/member/gcart/paysuccess");
        }
        else {
            $_COOKIE[$cookieinfo["cookie_pre"] . "Cartlista"] = NULL;
            _setcookie("Cartlista", NULL);
            header("location: " . WEB_PATH);
        }

        exit();
    }

    public function paysuccess()
    {
        seo("title", _cfg("web_name") . "_支付成功");
        seo("keywords", _cfg("web_name") . "_支付成功");
        seo("description", _cfg("web_name") . "_支付成功");
        $_COOKIE["Cartlista"] = NULL;
        _setcookie("Cartlista", NULL);
        $this->view->show();
    }

    public function Cartlistinfo()
    {
        $Cartlist = json_decode(stripslashes($this->Cartlist), true);
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
            $this->shoplist = $this->model->get_goods_any($wheres);
        }

        $shoplist = $this->shoplist;
        $MoenyCount = 0;
        $Cartshopinfo = "{";

        if (1 <= count($shoplist)) {
            foreach ($Cartlist as $key => $val ) {
                $key = intval($key);

                if (isset($shoplist[$key])) {
                    $shoplist[$key]["cart_gorenci"] = ($val["num"] ? $val["num"] : 1);
                    $MoenyCount += $shoplist[$key]["g_money"] * $shoplist[$key]["cart_gorenci"];
                    $shoplist[$key]["cart_xiaoji"] = $shoplist[$key]["g_money"] * $shoplist[$key]["cart_gorenci"];
                    $Cartshopinfo .= $shoplist[$key]["gid"] . ":{'num':" . $shoplist[$key]["cart_gorenci"] . ",'money':" . $shoplist[$key]["g_money"] . "},";

                    if ($shoplist[$key]["g_ispoints"] == "1") {
                        $fufen_dikou += 1;
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
}
?>
