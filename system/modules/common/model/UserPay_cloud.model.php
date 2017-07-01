<?php

class UserPay_cloud_model extends model
{
    public function SelectCgoods($shopids, $key = "*")
    {
        $gsql = "SELECT $key FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `g_status`='1' and `g_type`='3' and a.id in($shopids) and `q_uid` is null for update";
        return $this->GetList($gsql, array("key" => "id"));
    }

    public function UpdateCgoods($update_cgoods)
    {
        $ucgsql = "UPDATE `@#_cloud_goods` SET " . $update_cgoods;
        $updatecgoods = $this->Query($ucgsql);
        return $updatecgoods;
    }

    public function SelectCgoods_gid($gid, $key = "*")
    {
        $scgsql = "SELECT $key FROM `@#_cloud_goods`  where `gid` = $gid   order by `qishu` DESC LIMIT 1";
        return $this->GetOne( $scgsql );
    }
}