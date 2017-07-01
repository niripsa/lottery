<?php
System::load_app_class("UserAction", "common", "no");
class club extends UserAction
{
    public function joingroup()
    {
        seo("title", _cfg("web_name") . "_" . l("club.joingroup"));
        seo("keywords", l("club.joingroup"));
        seo("description", l("club.joingroup"));
        $clubdb = System::load_app_model("club_db", "common");
        $member = $this->UserInfo;
        $addclub = rtrim($member["addclub"], ",");

        if ($addclub) {
            $club = $clubdb->GetUserClubList($addclub);
        }
        else {
            $club = NULL;
        }

        $this->view->data("club", $club);
        $this->view->data("member", $member);
        $this->view->show("user.joingroup");
    }

    public function topic()
    {
        seo("title", _cfg("web_name") . "_" . l("club.topic"));
        seo("keywords", l("club.topic"));
        seo("description", l("club.topic"));
        $clubdb = System::load_app_model("club_db", "common");
        $member = $this->UserInfo;
        $tiezi = $clubdb->GetUserClubPost($member["uid"], 1);
        $huifu = $clubdb->GetUserClubPost($member["uid"], 2);
        $this->view->data("huifu", $huifu);
        $this->view->data("tiezi", $tiezi);
        $this->view->data("title", $title);
        $this->view->data("member", $member);
        $this->view->show("user.topic");
    }

    public function tiezidel()
    {
        $clubdb = System::load_app_model("club_db", "common");
        $member = $this->UserInfo;
        $id = $this->segment(4);
        $id = abs(intval($id));
        $tiezi = $clubdb->DelUserClub($member["uid"], $id, 1);

        if ($tiezi) {
            _message(l("club.del.ok"), WEB_PATH . "/member/club/topic");
        }
        else {
            _message(l("club.del.no"), WEB_PATH . "/member/club/topic");
        }
    }

    public function huifudel()
    {
        $clubdb = System::load_app_model("club_db", "common");
        $member = $this->UserInfo;
        $id = $this->segment(4);
        $id = abs(intval($id));
        $tiezi = $clubdb->DelUserClub($member["uid"], $id, 2);

        if ($tiezi) {
            _message(l("club.del.ok"), WEB_PATH . "/member/club/topic");
        }
        else {
            _message(l("club.del.no"), WEB_PATH . "/member/club/topic");
        }
    }
}
?>
