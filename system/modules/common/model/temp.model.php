<?php
System::load_sys_class("model", "sys", "no");
class temp_model extends model
{
    public function sharenum($itemid = "")
    {
        if (!$itemid) {
            return array("sd_total" => 0, "sd_totalpl" => 0);
        }
        else {
            $share_db = System::load_app_model("share", "common");
            $user = System::load_app_model("user", "common");
            $where = "`id` = '$itemid'";
            $shopinfo = $share_db->shopid_gid($where, "gid");
            $total = 0;
            $totalpl = 0;
            $total = $share_db->GetShareList_gidcount($shopinfo["gid"]);
            $where = "b.`gid`='{$shopinfo["gid"]}' ORDER BY a.`sd_id` DESC ";
            $shareiframe = $share_db->GetShareList_gid($where);

            foreach ($shareiframe as $key => $val ) {
                if ($totalpl == "") {
                    $totalpl = $val["sd_ping"];
                }
                else {
                    $totalpl = ($totalpl * 1) + $val["sd_ping"];
                }
            }

            return array("sd_total" => $total, "sd_totalpl" => $totalpl);
        }
    }
}

