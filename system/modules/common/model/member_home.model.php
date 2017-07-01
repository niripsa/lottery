<?php

class member_home_model extends model
{
    public function init()
    {
    }

    public function member_check($uid, $email = "", $mobile = "", $uersqq = "")
    {
        $member_checksql = "select email,emailcode,mobile ,mobilecode,qq,uid from `@#_member` where `uid` != '$uid'";

        if ($email) {
            $member_checksql .= " and `email`='$email'";
        }

        if ($mobile) {
            $member_checksql .= " and `mobile`='$mobile'";
        }

        if ($uersqq) {
            $member_checksql .= " and `qq`='$uersqq'";
        }

        $memberinfo = $this->db->GetOne($member_checksql);
        return $memberinfo;
    }

    public function member_imgedit($uid, $mimg)
    {
        $imgedit = $this->Query("UPDATE `@#_user` SET img='$mimg' where uid='$uid'");
        return $imgedit;
    }
}


?>
