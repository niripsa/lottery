<?php
class share extends SystemAction
{
    public function init()
    {
        seo("title", _cfg("web_name") . "_" . L("share.init"));
        seo("keywords", L("share.init"));
        seo("description", L("share.init"));
        $share_db = System::load_app_model("share", "common");
        $num = 20;
        $total = $share_db->share_sumCount($num);
        $page = System::load_sys_class("page");
        $page->config($total, $num);
        $shaidan = $share_db->sharelistpage($page->setlimit());
        $lie = 4;
        $sum = $num;
        $yushu = $total % $num;
        $yeshu = floor($total / $num) + 1;
        if ((0 < $yushu) && ($yeshu == $pagenum)) {
            $sum = $yushu;
        }

        $sa_one   = array();
        $sa_two   = array();
        $sa_three = array();
        $sa_for   = array();

        foreach ( $shaidan as $sk => $sv )
        {
            $shaidan[$sk]["sd_title"]      = _htmtocode($sv["sd_title"]);
            $shaidan[$sk]["sd_username"]   = get_user_name($sv["sd_userid"], "username");
            $shaidan[$sk]["sd_userthumbs"] = (Getuserimg($sv["sd_userid"]) ? Getuserimg($sv["sd_userid"]) : "photo/member.jpg");
            $shaidan[$sk]["sd_content"]    = _strcut($sv["sd_content"], 30);
        }

        if ( $shaidan ) {
            for ( $i = 0; $i < $lie; $i++ ) {
                $n = $i;
                while ( $n < $sum ) {
                    if ( $i == 0 ) {
                        $sa_one[] = $shaidan[$n];
                    }
                    else if ( $i == 1 ) {
                        $sa_two[] = $shaidan[$n];
                    }
                    else if ( $i == 2 ) {
                        $sa_three[] = $shaidan[$n];
                    }
                    else if ( $i == 3 ) {
                        $sa_for[] = $shaidan[$n];
                    }

                    $n += $lie;
                }
            }
        }
        /* M版去除晒单内容的html标签 */
        if ( IS_MOBILE )
        {
            foreach ( $shaidan as $k => $v )
            {
                $shaidan[ $k ]['sd_content'] = strip_tags( $v['sd_content'] );
            }
        }

        $moudle_title = "晒单分享<br>";
        $this->view->data("num", $num);
        $this->view->data("moudle_title", $moudle_title);
        $this->view->data("total", $total);
        $this->view->data("page", $page);
        $this->view->data("shaidan", $shaidan);
        $this->view->data("sa_one", $sa_one);
        $this->view->data("sa_two", $sa_two);
        $this->view->data("sa_three", $sa_three);
        $this->view->data("sa_for", $sa_for);
        $this->view->show("share.share");
    }

    public function detail()
    {
        seo("title", _cfg("web_name") . "_" . L("share.detail"));
        seo("keywords", L("share.detail"));
        seo("description", L("share.detail"));
        $moudle_title = "晒单详请<br>";
        $share_db = System::load_app_model("share", "common");
        $s_user_record = System::load_app_model("cloud_goods", "common");
        $userinfo = System::load_app_class("UserCheck", "common");
        $order_db = System::load_app_model("order", "common");
        $user = $userinfo->UserInfo;
        $share_id = abs(intval(safe_replace($this->segment(4))));
        $share_detail = $share_db->GetShareDetail($share_id);
        $wherewords = "`ogid`='{$share_detail["sd_shopid"]}' and `owin` is not null";
        $share_user_record = $order_db->ready_order($wherewords, 1);
        $share_user_record = $share_user_record[0];
        $cloud_goodsdetail = $s_user_record->cloud_goodsdetail($share_detail["sd_shopid"]);
        $share_detail["sd_photolist"] = explode(";", rtrim($share_detail["sd_photolist"], ";"));

        if (isset($_POST["share_submit"])) {
            if (!G_IS_MOBILE || G_IS_TEMPSKIN) {
                if (!_ifcookiecode($_POST["sdhf_code"], "Captcha")) {
                    _message(L("captche.no"), WEB_PATH . "/index/share/detail/" . $share_id);
                }
            }

            $sdhf_id = $share_detail["sd_id"];
            $sdhf_userid = $user["uid"];
            $sdhf_content = $_POST["sdhf_content"];
            $sdhf_time = time();
            $sdhf_username = _htmtocode(get_user_name($user));
            $sdhf_img = _htmtocode($user["img"]);

            if (empty($sdhf_content)) {
                _message(L("html.err"), WEB_PATH . "/index/share/detail/" . $share_id);
            }

            $Insert_html = "('$sdhf_id','$sdhf_userid','$sdhf_content','$sdhf_time','$sdhf_username','$sdhf_img')";
            $share_Insert = $share_db->InsertShare_post($Insert_html);
            $sd_ping = $share_detail["sd_ping"] + 1;
            $setwords = "`sd_ping`='$sd_ping'";
            $share_update = $share_db->UpdateShare($sdhf_id, $setwords);
            if ($share_Insert && $share_update) {
                _message(L("share.comment.suc"), WEB_PATH . "/index/share/detail/" . $share_id);
            }
        }

        $substr = substr($shaidan["sd_photolist"], 0, -1);
        $sd_photolist = explode(";", $substr);
        $this->view->show("share.detail")->data("num", "20")->data("moudle_title", $moudle_title)->data("share_detail", $share_detail)->data("share_user_record", $share_user_record)->data("cloud_goodsdetail", $cloud_goodsdetail);
    }

    public function Share_Envy()
    {
        $sd_id = intval($_POST["Share_Envyid"]);
        $share_db = System::load_app_model("share", "common");
        $share_envy = $share_db->GetShareDetail($sd_id);
        $sd_zhan = $share_envy["sd_zhan"] + 1;
        $setwords = "`sd_zhan`='" . $sd_zhan . "'";
        $share_update = $share_db->UpdateShare($sd_id, $setwords);

        if ($share_update) {
            echo $sd_zhan;
        }
    }

    public function share_iframe()
    {
        $itemid = abs(intval(safe_replace($this->segment(4))));
        $share_db = System::load_app_model("share", "common");
        $user = System::load_app_model("user", "common");
        $page = System::load_sys_class("page");

        if (!$itemid) {
            $error = 1;
        }
        else {
            $error = 0;
            $where = "`id` = '$itemid'";
            $shopinfo = $share_db->shopid_gid($where, "gid");
            $total = $share_db->GetShareList_gidcount($shopinfo["gid"]);

            if (!$total) {
                $error = 1;
            }

            if (isset($_GET["p"])) {
                $pagenum = $_GET["p"];
            }
            else {
                $pagenum = 1;
            }

            $num = 5;
            $page->config($total, $num);
            $where = "b.`gid`='{$shopinfo["gid"]}' ORDER BY a.`sd_id` DESC ";
            $shareiframe = $share_db->GetShareList_gid($where, $page->setlimit());

            foreach ($shareiframe as $key => $val ) {
                $shareiframe[$key]["sd_photolist"] = explode(";", trim($val["sd_photolist"], ";"));
            }

            foreach ($shareiframe as $key => $val ) {
                $member_info = $user->SelectUserUid($val["sd_userid"]);
                $member_img[$val["sd_id"]] = $member_info["img"];
                $member_id[$val["sd_id"]] = $member_info["uid"];
                $member_username[$val["sd_id"]] = $member_info["username"];

                if ($totalpl == "") {
                    $totalpl = $val["sd_ping"];
                }
                else {
                    $totalpl = ($totalpl * 1) + $val["sd_ping"];
                }
            }
        }

        $this->view->show("index.share_iframe")->data("shareiframe", $shareiframe)->data("total", $total)->data("num", $num)->data("page", $page)->data("error", $error)->data("totalpl", $totalpl)->data("member_info", $member_info)->data("member_img", $member_img)->data("member_id", $member_id)->data("member_username", $member_username);
    }
}


