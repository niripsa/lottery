<?php
System::load_app_class("UserAction", "common", "no");
class order extends UserAction
{
    public function userbuylist()
    {
        $mysql_model = System::load_sys_class("model");
        $member = $this->userinfo;
        $uid = $member["uid"];
        $title = "夺宝记录 - " . _cfg("web_name");
        $total = $this->db->GetCount("select * from `@#_member_go_record` where `uid`='$uid' order by `id` DESC");
        $page = System::load_sys_class("page");

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, 10, $pagenum, "0");
        $record = $this->db->GetPage("select * from `@#_member_go_record` where `uid`='$uid' order by `id` DESC", array("num" => 10, "page" => $pagenum, "type" => 1, "cache" => 0));
        $this->view->show("member.userbuylist")->data("member", $this->UserInfo);
    }

    public function userbuydetail()
    {
        $mysql_model = System::load_sys_class("model");
        $title = "夺宝详情";
        $crodid = intval($this->segment(4));
        $record = $mysql_model->GetOne("select * from `@#_member_go_record` where `id`='$crodid' and `uid`='{$member["uid"]}' LIMIT 1");

        if (!$record) {
            _message(l("html.err"), WEB_PATH . "/member/home/userbuylist", 3);
        }

        $shopinfo = $mysql_model->GetOne("select thumb from `@#_shoplist` where `id`='{$record["shopid"]}' LIMIT 1");
        $record["thumb"] = $shopinfo["thumb"];

        if (0 < $crodid) {
            $this->view->show("member.userbuydetail")->data("member", $this->UserInfo);
        }
        else {
            _message(l("html.err"), WEB_PATH . "/member/home/userbuylist", 3);
        }
    }

    public function orderlist()
    {
        $uid = $this->UserInfo["uid"];
        $title = "获得的商品 - " . _cfg("web_name");
        $total = $this->db->GetCount("select * from `@#_member_go_record` where `uid`='$uid' and `huode`>'10000000'");
        $page = System::load_sys_class("page");

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, 10, $pagenum, "0");
        $record = $this->db->GetPage("select * from `@#_member_go_record` where `uid`='$uid' and `huode`>'10000000' ORDER BY id DESC", array("num" => 10, "page" => $pagenum, "type" => 1, "cache" => 0));

        foreach ($record as $ckey => $cord ) {
            $jiexiao = get_shop_if_jiexiao($cord["shopid"]);

            if (!$jiexiao) {
                unset($record[$ckey]);
            }
        }

        $this->view->show("member.orderlist")->data("member", $this->UserInfo);
    }

    public function userbalance()
    {
        $this->view->show();
    }

    public function userrecharge()
    {
        $this->view->show();
    }
}
?>
