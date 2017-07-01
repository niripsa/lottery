<?php

class us extends SystemAction
{
    public function uname()
    {
        $userdb = System::load_app_model("user", "common");
        $order_db = System::load_app_model("order", "common");
        $user_record_db = System::load_app_model("cloud_goods", "common");
        $uid = abs(intval($this->segment(4)));

        if (!$uid) {
            _message(L("html.key.err"));
        }

        if (1000000000 < $uid) {
            $uid = $uid - 1000000000;
        }

        $page = System::load_sys_class("page");
        $num = 10;
        $this->uid = $uid;
        $member = $userdb->SelectUserUid($uid);
        $home_title = findconfig("seo", "user_title");
        $home_keywords = findconfig("seo", "user_keywords");
        $home_desc = findconfig("seo", "user_desc");

        if (!$home_title) {
            $home_title = _cfg("web_name") . "_" . L("user.user.uname");
        }

        if (!$home_title) {
            $home_keywords = L("user.user.uname");
        }

        if (!$home_title) {
            $home_desc = L("user.user.uname");
        }

        $seoinfo = array();
        $seoinfo["username"] = $member["username"];
        $seoinfo["qianming"] = $member["qianming"];
        seo("title", $home_title, "user", $seoinfo);
        seo("keywords", $home_keywords, "user", $seoinfo);
        seo("description", $home_desc, "user", $seoinfo);
        $retotal = $order_db->ready_order_num($uid, 2);
        $page->config($retotal, $num);
        $where = "`ouid`='$uid' and `owin`>'10000000'";
        $selectwords = "`ouid`='$uid'  order by  `otime` DESC";
        $hdtotal = $order_db->ready_order_num($where, 1);
        $record = $order_db->ready_order($selectwords, 1, "", "", $page->setlimit(1));
        $record = empty($record)?array():$record;
        foreach ($record as $key => $v ) {
            $jiexiao = get_shop_if_jiexiao($v["ogid"]);

            if (!$jiexiao) {
                unset($record[$key]);
            }
            else {
                $shopinfo = $user_record_db->cloud_goodsdetail($v["ogid"]);
                $record[$key]["q_showtime"] = $shopinfo["q_showtime"];
                $record[$key]["q_end_time"] = $shopinfo["q_end_time"];
                $record[$key]["q_uid"] = $shopinfo["q_uid"];
                $record[$key]["q_user_code"] = $shopinfo["q_user_code"];
                $record[$key]["g_money"] = $shopinfo["g_money"];
                $record[$key]["q_money"] = sprintf("%.2f", $shopinfo["zongrenshu"] * $shopinfo["price"]);
                $record[$key]["g_title"] = useri_title($v["og_title"], "g_title");
                $record[$key]["g_thumb"] = useri_title($v["og_title"], "g_thumb");
                $record[$key]["g_username"] = Getusername($shopinfo["q_uid"]);
            }
        }

        $this->view->data("record", $record);
        $this->view->show("user.raffle")->data("member", $member)->data("retotal", $retotal)->data("num", $num)->data("hdtotal", $hdtotal)->data("page", $page);
    }
}


