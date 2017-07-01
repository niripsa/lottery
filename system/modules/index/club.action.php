<?php
class club extends SystemAction
{
    private function user_add_club($cid)
    {
        $Check = System::load_app_class("UserCheck", "common");

        if (!$Check->UserInfo) {
            return false;
        }

        $this->users = $Check->UserInfo;
        $addids = trim($Check->UserInfo["addclub"], ",") . ",";

        if (strpos($addids, $cid . ",") === false) {
            return false;
        }

        return true;
    }

    public function init()
    {
        seo("title", _cfg("web_name") . "_圈子列表");
        seo("keywords", _cfg("web_name") . "_圈子列表");
        seo("description", _cfg("web_name") . "_圈子列表");
        $this->view->show("club.index");
    }

    public function show()
    {
        $club = System::load_app_model("club_db", "common");
        $cid = abs(intval($this->segment(4)));
        $quanzi = $club->GetClubOne($cid);

        if (!$quanzi) {
            _message(L("club.no"));
        }

        seo("title", $quanzi["title"] . "_" . _cfg("web_name"));
        seo("keywords", $quanzi["jianjie"]);
        seo("description", $quanzi["gongao"]);

        if (!$this->user_add_club($cid)) {
            $addgroup = false;
        }
        else {
            $addgroup = true;
        }

        $num = 10;
        $total = $club->GetClubPostCont($cid);
        $page = System::load_sys_class("page");
        $page->config($total, $num);
        $qz = $club->GetClubPostPage($cid, $page->setlimit(), 1);
        $uid = $this->users["uid"];
        $this->view->data("page", $page);
        $this->view->data("uid", $uid);
        $this->view->data("addgroup", $addgroup);
        $this->view->data("quanzi", $quanzi);
        $this->view->data("qz", $qz);
        $this->view->data("num", $num);
        $this->view->data("total", $total);
        $this->view->show("club.show");
    }

    public function AddOrOut()
    {
        $Check = System::load_app_class("UserCheck", "common");
        $club = System::load_app_model("club_db", "common");
        $uid = $Check->UserInfo["uid"];

        if (!$uid) {
            exit();
        }

        $cid = intval($_POST["id"]);
        $text = $_POST["text"];
        $chengyuan = $club->GetClubOne($cid);

        if (!$chengyuan) {
            return NULL;
        }

        if ($text == "退出") {
            if (!$this->user_add_club($cid)) {
                return NULL;
            }

            $iqroup = str_ireplace($cid . ",", "", $Check->UserInfo["addclub"]);
            $cy = $chengyuan["chengyuan"] - 1;
        }
        else {
            if ($this->user_add_club($cid)) {
                return NULL;
            }

            $iqroup = $Check->UserInfo["addclub"] . $cid . ",";
            $cy = $chengyuan["chengyuan"] + 1;
        }

        $club->ClubEdit($uid, $iqroup);
        $club->UpClubNum($cid, "chengyuan", $cy);
    }

    public function IntShow()
    {
        $Check = System::load_app_class("UserCheck", "common");
        $club = System::load_app_model("club_db", "common");

        if (!$Check->UserInfo) {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", L("user.login.wno"), 1);
        }

        if (isset($_POST["title"])) {
            $uid = $Check->UserInfo["uid"];
            $cid = intval($_POST["qzid"]);
            $title = htmlspecialchars($_POST["title"]);
            $content = editor_safe_replace(stripslashes($_POST["neirong"]));

            if (!$this->user_add_club($cid)) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("club.go.no"), 1);
            }

            if (!_ifcookiecode($_POST["verify"], "Captcha")) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("captche.no"), 1);
            }

            $quanzi = $club->GetClubOne($cid);

            if (!$quanzi) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("club.no"), 1);
            }

            if (($quanzi["glfatie"] == "N") && ($quanzi["guanli"] != $uid)) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("club.user.no"), 1);
            }

            if (($title == NULL) || ($content == NULL)) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("club.content.emp"), 1);
            }

            $tiezi = $club->GetClubPosttiezi($uid, $cid, $title, $content);

            if ($tiezi) {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("club.post.ed"), 1);
            }

            if ($quanzi["shenhe"] == "Y") {
                $shenhe = "N";
            }
            else {
                $shenhe = "Y";
            }

            $club->AddPublish($uid, $cid, $title, $content, $shenhe);
            $num = $quanzi["tiezi"] + 1;
            $temp = $club->UpClubNum($cid, "tiezi", $num);

            if ($temp) {
                $this->SendMsgJson("status", 1);
                $this->SendMsgJson("info", L("club.add.ok"), 1);
            }
            else {
                $this->SendMsgJson("status", 0);
                $this->SendMsgJson("info", L("club.add.no"), 1);
            }
        }
    }

    public function nei()
    {
        $Check = System::load_app_class("UserCheck", "common");
        $club = System::load_app_model("club_db", "common");
        $uid = $Check->UserInfo["uid"];
        $id = abs(intval($this->segment(4)));

        if (!$id) {
            _message("页面错误");
        }

        $tiezi = $club->GetClubPostOne($id, 1);

        if (!$tiezi) {
            _message("页面错误");
        }

        $dianji = $tiezi["dianji"] + 1;
        $club->UpClubPostNum($id, "dianji", $dianji);
        seo("title", $tiezi["title"] . "_" . _cfg("web_name"));
        seo("keywords", $tiezi["title"]);
        seo("description", _htmtocode(_strcut($tiezi["content"], 0, 250)));
        $quanzi = $club->GetClubOne($tiezi["cid"]);
        $num = 10;
        $total = $club->GetClubPostCont($tiezi["cid"], $id);
        $page = System::load_sys_class("page");
        $page->config($total, $num);

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, $num, $pagenum, "0");

        if ($page->page < $pagenum) {
            $pagenum = $page->page;
        }

        $huifu = $club->GetClubPostPage($id, $page->setlimit(), 2);
        $this->view->data("uid", $uid);
        $this->view->data("quanzi", $quanzi);
        $this->view->data("tiezi", $tiezi);
        $this->view->data("huifu", $huifu);
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->show("club.nei");
    }

    public function ClubPost()
    {
        $Check = System::load_app_class("UserCheck", "common");
        $club = System::load_app_model("club_db", "common");
        $uid = $Check->UserInfo["uid"];

        if ($uid == NULL) {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", L("user.login.wno"), 1);
        }

        if (!isset($_POST["huifu"])) {
            exit();
        }

        if (!_ifcookiecode($_POST["group_code"], "Captcha")) {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", L("captche.no"), 1);
        }

        $cid = intval($_POST["qzid"]);
        $qzinfo = $club->GetClubOne($cid);
        if (!$qzinfo || ($qzinfo["huifu"] == "N")) {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", "该圈子禁用回复!", 1);
        }

        $content = _htmtocode($_POST["huifu"]);

        if ($content == NULL) {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", "内容不能为空!", 1);
        }

        $tzid = intval($_POST["tzid"]);

        if ($tzid <= 0) {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", "错误!", 1);
        }

        if ($qzinfo["shenhe"] == "Y") {
            $shenhe = "N";
        }
        else {
            $shenhe = "Y";
        }

        $temp = $club->AddReply($uid, $cid, $content, $tzid, $shenhe);

        if ($temp) {
            $tiezi = $club->GetClubPostOne($tzid, 1);
            $num = $tiezi["huifu"] + 1;
            $club->UpClubPostNum($tzid, "huifu", $num);

            if ($qzinfo["shenhe"] == "Y") {
                $this->SendMsgJson("status", 1);
                $this->SendMsgJson("info", "添加成功,需要管理员审核", 1);
            }

            $this->SendMsgJson("status", 1);
            $this->SendMsgJson("info", "添加成功", 1);
        }
        else {
            $this->SendMsgJson("status", 0);
            $this->SendMsgJson("info", "添加失败", 1);
        }
    }
}


