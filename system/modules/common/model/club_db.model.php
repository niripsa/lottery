<?php

class club_db_model extends model
{
    public function GetUserClubList($num)
    {
        $sql = "SELECT * FROM `@#_club` WHERE `cid` IN ($num)";
        return $this->GetList($sql);
    }

    public function GetUserClubCont($uid)
    {
        $sql = "SELECT addclub FROM `@#_user` WHERE `uid`= $uid ";
        $num = $this->GetOne($sql);

        if (!$num["addclub"]) {
            return 0;
        }

        $num = explode(",", trim($num["addclub"], ","));
        return count($num);
    }

    public function GetUserClubPost($uid, $type)
    {
        $sql = "SELECT * FROM `@#_club_post` WHERE `huiyuan` = $uid AND `type` = $type";
        return $this->GetList($sql);
    }

    public function GetUserClubPostNum($uid, $type)
    {
        $sql = "SELECT * FROM `@#_club_post` WHERE `huiyuan` = $uid AND `type` = $type";
        return $this->GetCount($sql);
    }

    public function DelUserClub($uid, $tid, $type)
    {
        $sql1 = "SELECT * FROM `@#_club_post` WHERE `huiyuan`= $uid AND `id`= $tid AND `type` = $type";
        $temp = $this->GetOne($sql1);

        if ($temp) {
            $hid = $this->GetClubPostOne($temp["huifu_id"], 1);
            $num = $hid["huifu"] - 1;
            $hid = $hid["id"];
            $temp = $this->GetClubOne($temp["cid"]);
            $sql2 = "DELETE FROM `@#_club_post` WHERE `huiyuan`= $uid AND `id`= $tid AND `type` = $type";

            if ($type == 1) {
                $sql2 = "DELETE FROM `@#_club_post` WHERE `huiyuan`= $uid AND `id`= $tid AND `type` = $type OR `huifu_id` = $tid ";
            }

            $this->UpClubNum($temp["cid"], "tiezi", $temp["tiezi"] - 1);
            $this->UpClubPostNum($hid, "huifu", $num);
            return $this->Query($sql2);
        }

        return false;
    }

    public function GetClubList($num = "5")
    {
        if (empty($num)) {
            $num = 5;
        }

        return $this->GetList("SELECT cid,title,img,chengyuan,tiezi,jianjie FROM `@#_club` WHERE 1 LIMIT $num");
    }

    public function GetClubOne($cid)
    {
        $sql = "SELECT * FROM `@#_club` WHERE cid = $cid LIMIT 1";
        return $this->GetOne($sql);
    }

    public function GetClubPostOne($id, $type)
    {
        if ($type === 1) {
            $sql = "SELECT * FROM `@#_club_post` WHERE `id` = $id AND `type` = 1";
            return $this->GetOne($sql);
        }
        else if ($type === 3) {
            $sql = "SELECT * FROM `@#_club_post` WHERE `id` = $id";
            return $this->GetOne($sql);
        }
        else {
            $sql = "SELECT * FROM `@#_club_post` WHERE `huifu_id` = $id AND `type` = 2";
            return $this->GetList($sql);
        }
    }

    public function GetClubPostCont($cid, $tzid = "0")
    {
        if ($tzid === "0") {
            $sql = "SELECT * FROM `@#_club_post` WHERE `cid` = $cid AND `type` = 1";
        }
        else {
            $sql = "SELECT * FROM `@#_club_post` WHERE `cid` = $cid AND `type` = 2 AND `huifu_id` = $tzid";
        }

        return $this->GetCount($sql);
    }

    public function GetClubPosttiezi($uid, $cid, $title, $content)
    {
        $sql = "SELECT * FROM `@#_club_post` WHERE ";
        $sql .= "`huiyuan` = $uid AND `cid` = $cid AND `title` = '$title' AND `content` = '$content'";
        return $this->GetOne($sql);
    }

    public function UpClubNum($cid, $type, $num)
    {
        $sql = "UPDATE `@#_club` SET `$type` = $num WHERE `cid` = $cid ";
        return $this->Query($sql);
    }

    public function UpClubPostNum($id, $type, $num)
    {
        $sql = "UPDATE `@#_club_post` SET `$type` = $num WHERE `id` = $id";
        return $this->Query($sql);
    }

    public function GetClubPostPage($id, $num, $type)
    {
        $sql = "SELECT * FROM `@#_club_post` WHERE ";

        if ($type === 1) {
            $sql .= "`cid` = $id AND `type` = 1 AND `shenhe` = 'Y' ORDER BY zhiding DESC,id DESC " . $num;
        }
        else {
            $sql .= "`huifu_id` = $id AND `shenhe` = 'Y' ORDER BY id DESC " . $num;
        }

        return $this->GetList($sql);
    }

    public function AddPublish($uid, $cid, $title, $content, $shenhe)
    {
        $time = time();
        $sql = "INSERT INTO `@#_club_post`(`cid`,`type`,`huiyuan`,`title`,`content`,`shenhe`,`endtime`,`addtime`) ";
        $sql .= "VALUES('$cid','1','$uid','$title','$content','$shenhe','$time','$time')";
        return $this->Query($sql);
    }

    public function AddReply($uid, $cid, $content, $tzid, $shenhe)
    {
        $time = time();
        $sql = "INSERT INTO `@#_club_post`(`cid`,`type`,`huiyuan`,`content`,`huifu_id`,`shenhe`,`endtime`,`addtime`)";
        $sql .= "VALUES('$cid','2','$uid','$content','$tzid','$shenhe','$time','$time')";
        return $this->Query($sql);
    }

    public function ClubEdit($uid, $cid)
    {
        $sql = "UPDATE `@#_user` SET `addclub`='$cid' WHERE `uid`='$uid'";
        return $this->Query($sql);
    }

    public function GetHotClubPost($num = "5")
    {
        if (empty($num)) {
            $num = 5;
        }

        $sql = "SELECT * FROM `@#_club_post` WHERE type = 1 GROUP BY huifu ORDER BY id DESC LIMIT $num";
        return $this->GetList($sql);
    }

    public function GetNewClubPost($num = "5")
    {
        if (empty($num)) {
            $num = 5;
        }

        $sql = "SELECT * FROM `@#_club_post` WHERE type = 1 ORDER BY addtime DESC LIMIT $num";
        return $this->GetList($sql);
    }

    public function GetHotClubUser()
    {
        $sql = "SELECT * FROM `@#_club_post` GROUP BY huiyuan ORDER BY id DESC LIMIT 16";
        return $this->GetList($sql);
    }
}


?>
