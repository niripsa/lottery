<?php

class share_model extends model
{
    public function share_sumCount($num = "20")
    {
        $page = System::load_sys_class("page");
        $total = $this->GetCount("select * from `@#_share` where \t1");

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

        return $total;
    }

    public function sharelistpage($limitnum = "")
    {
        $shaidansql = "select * from `@#_share` order by `sd_id` DESC";

        if ($limitnum) {
            $shaidansql .= " " . $limitnum;
        }

        $shaidan = $this->GetList($shaidansql);
        return $shaidan;
    }

    public function sharelist($first_num = "0", $second_num = "")
    {
        $shaidansql = "select * from `@#_share` order by `sd_id` DESC";

        if ($second_num) {
            $shaidansql .= " limit " . $first_num . "," . $second_num;
        }

        $shaidan = $this->GetList($shaidansql);
        return $shaidan;
    }

    public function GetShareDetail($sd_id)
    {
        $gsdsql = "SELECT * FROM `@#_share` WHERE `sd_id`='$sd_id'";
        $gsdsql = $this->GetOne($gsdsql);
        return $gsdsql;
    }

    public function UpdateShare($sd_id, $setwords)
    {
        $ussql = "UPDATE `@#_share` SET $setwords where `sd_id`='" . $sd_id . "'";
        return $this->Query($ussql);
    }

    public function InsertShare_post($Insert_html)
    {
        $ispsql = "INSERT INTO `@#_share_post`(`sdhf_id`,`sdhf_userid`,`sdhf_content`,`sdhf_time`,`sdhf_username`,`sdhf_img`)VALUES" . $Insert_html;
        return $this->Query($ispsql);
    }

    public function sharedetail($d_shopid, $sd_userid)
    {
        $sdsql = "SELECT * FROM `@#_share` WHERE `sd_shopid`='$d_shopid' and `sd_userid`='$sd_userid'";
        return $this->GetOne($sdsql);
    }

    public function InsetSharelist($insert_html)
    {
        $issql = "INSERT INTO `@#_share` (`sd_userid`,`sd_shopid`,`sd_ip`,`sd_title`,`sd_thumbs`,`sd_content`,`sd_photolist`,`sd_time`) VALUES ";
        $issql .= $insert_html;
        return $this->Query($issql);
    }

    public function GetUserShareList($uid)
    {
        $sql = "SELECT * FROM `@#_share` WHERE `sd_userid`='$uid' ORDER BY `sd_id` DESC";
        return $this->GetList($sql);
    }

    public function GetUserShareListCount($id, $uid)
    {
        $sql = "SELECT id FROM `@#_user_record` WHERE `shopid` in ($id) and `uid`='$uid' AND `huode`>'10000000'";
        return $this->GetCount($sql);
    }

    public function GetShareList_shopid($shopid = "", $SelectType = "GetCount")
    {
        if ($SelectType == "GetList") {
            $sql = "SELECT * FROM `@#_share` as a left join `@#_cloud_goods` as b on  b.`id`=a.sd_shopid where a.`sd_shopid`='$shopid'  ORDER BY a.`sd_id` DESC ";
        }
        else {
            $sql = "SELECT * FROM `@#_share` WHERE `sd_shopid`='$shopid' ORDER BY `sd_id` DESC ";
        }

        return $this->{$SelectType}($sql);
    }

    public function GetShareList_gidcount($gid = "")
    {
        $sql = "SELECT `sd_id` FROM `@#_share` as a left join `@#_cloud_goods` as b on  b.`id`=a.sd_shopid where  b.`gid`='$gid' ORDER BY a.`sd_id` DESC ";
        return $this->GetCount($sql);
    }

    public function GetShareList_gid($where = 1, $limit = "", $key = "*")
    {
        $sql = "SELECT $key FROM `@#_share` as a left join `@#_cloud_goods` as b on  b.`id`=a.sd_shopid where $where " . $limit;
        return $this->GetList($sql);
    }

    public function shopid_gid($where = 1, $key = "*")
    {
        $sql = "SELECT $key FROM `@#_cloud_goods` where $where ";
        return $this->GetOne($sql);
    }

    public function GetShareListnew($num = "100")
    {
        $gslnsql = "SELECT * FROM `@#_share` WHERE 1  order by sd_time desc limit " . $num;
        $GetShareListnew = $this->GetList($gslnsql);

        foreach ($GetShareListnew as $key => $v ) {
            $GetShareListnew[$key]["sd_photolist"] = array();
            $GetShareListnew[$key]["sd_photolist"] = explode(";", rtrim($v["sd_photolist"], ";"));
        }

        return $GetShareListnew;
    }

    public function GetSharePost($sid = "", $num = "100")
    {
        $gspsql = "SELECT * FROM `@#_share_post` WHERE `sdhf_id`=$sid  order by sdhf_time desc limit " . $num;
        $GetSharePost = $this->GetList($gspsql);

        foreach ($GetSharePost as $k => $v ) {
            $GetSharePost[$k]["sdhf_content"] = _htmtocode($GetSharePost[$k]["sdhf_content"]);
        }

        return $GetSharePost;
    }

    public function get_share($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("share", $where, $field, $order, $num);
        return $res;
    }

    public function get_share_num($where)
    {
        $res = $this->data_num("share", $where);
        return $res;
    }

    public function get_share_one($where, $field = "*")
    {
        $res = $this->data_one("share", $where, $field);
        return $res;
    }

    public function share_del($where)
    {
        return $this->data_del("share", $where);
    }

    public function get_share_msg($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("share_post", $where, $field, $order, $num);
        return $res;
    }

    public function get_share_msg_num($where)
    {
        $res = $this->data_num("share_post", $where);
        return $res;
    }

    public function get_share_msg_one($where, $field = "*")
    {
        $res = $this->data_one("share_post", $where, $field);
        return $res;
    }

    public function share_msg_del($where)
    {
        return $this->data_del("share_post", $where);
    }
}


?>
