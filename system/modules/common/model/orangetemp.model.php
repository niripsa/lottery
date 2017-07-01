<?php

class orangetemp_model extends model
{
    public function GetSingleShare($uid, $nowsd_id)
    {
        $sql = "SELECT * FROM `@#_share` WHERE `sd_userid`='$uid' and `sd_id`!='$nowsd_id'";
        $sheshare = $this->GetList($sql);
        return $sheshare;
    }

    public function CloudQishu($gid = "", $qishu = "")
    {
        if ($qishu) {
            $cqpsql = "SELECT id FROM `@#_cloud_goods`  where  `gid`=$gid  and  `qishu`=$qishu";
            return $this->GetOne($cqpsql);
        }
        else {
            return false;
        }
    }
}

System::load_sys_class("model", "sys", "no");

?>
