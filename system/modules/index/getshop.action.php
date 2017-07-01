<?php
class getshop extends SystemAction
{
    public function lottery_shop_json()
    {
        if (!isset($_GET["gid"])) {
            echo json_encode(array("error" => "1"));
            return NULL;
            exit();
        }

        $gid = trim($_GET["gid"]);
        $times = (int) System::load_sys_config("system", "goods_end_time");

        if (!$times) {
            $times = 1;
        }

        $db = System::load_sys_class("model");
        $gid = safe_replace($gid);
        $gid = str_ireplace("select", "", $gid);
        $gid = str_ireplace("union", "", $gid);
        $gid = str_ireplace("'", "", $gid);
        $gid = str_ireplace("%27", "", $gid);
        $gid = trim($gid, ",");

        if (!$gid) {
            $info = $db->GetOne("select qishu,xsjx_time,id,zongrenshu,q_uid,q_user,q_user_code,q_end_time,q_external_time,g_thumb,g_title,g_money from `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid where `q_showtime` = 'Y' order by `q_end_time` ASC limit 0,3");
        }
        else {
            $infos = $db->GetList("select  qishu,xsjx_time,id,zongrenshu,q_uid,q_user,q_user_code,q_end_time,q_external_time,g_thumb,g_title,g_money from `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `q_showtime` = 'Y' order by `q_end_time` ASC limit 0,3");
            $gid = @explode("_", $gid);
            $info = false;

            foreach ($infos as $infov ) {
                if (!in_array($infov["id"], $gid)) {
                    $info = $infov;
                    break;
                }
            }
        }

        if (!$info) {
            echo json_encode(array("error" => "1"));
            return NULL;
            exit();
        }

        if ($info["xsjx_time"]) {
            $info["q_end_time"] = $info["q_end_time"];
        }

        $user       = unserialize($info["q_user"]);
        $uphoto     = $user["img"];
        $user       = get_user_name($info["q_uid"], "username");
        $uid        = $info["q_uid"];
        $upload     = G_UPLOAD_PATH;
        $money      = $info["g_money"];
        $q_end_time = $info["q_end_time"];
        $city       = get_ip(Getuserinfo($uid, "user_ip"), "ipcity");
        $q_time     = substr($info["q_external_time"], 0, 10);
        $times      = $info["q_external_time"];
        $shopsum    = go_count_record($info["id"], $info["q_uid"], "s");

        if ($q_time <= time()) {
            $db->Query("update `@#_cloud_goods` SET `q_showtime` = 'N' where `id` = '{$info["id"]}' and `q_showtime` = 'Y' and `q_uid` is not null");
            echo json_encode(array("error" => "-1"));
            return NULL;
            exit();
        }

        $huafei = go_count_record($info["id"], $info["q_uid"], "m");
        $huibaolv = go_count_record($info["id"], $info["q_uid"], "l");
        $currency = L("cgoods.currency");
        $times = $q_time - time();
        echo json_encode(array("error" => "0", "city" => $city, "shopsum" => $shopsum, "uphoto" => $uphoto, "money" => $money, "zongrenshu" => $info["zongrenshu"], "q_user_code" => $info["q_user_code"], "qishu" => $info["qishu"], "upload" => $upload, "thumb" => $info["g_thumb"], "id" => $info["id"], "uid" => "$uid", "title" => $info["g_title"], "user" => $user, "times" => $times, "huafei" => $huafei, "huibaolv" => $huibaolv, "currency" => $currency));
        exit();
    }

    public function lottery_shop_jsonlottery()
    {
        if (!isset($_GET["gid"])) {
            echo json_encode(array("error" => "1"));
            return NULL;
            exit();
        }

        $gid = trim($_GET["gid"]);
        $times = (int) System::load_sys_config("system", "goods_end_time");

        if (!$times) {
            $times = 1;
        }

        $db = System::load_sys_class("model");
        $gid = safe_replace($gid);
        $gid = str_ireplace("select", "", $gid);
        $gid = str_ireplace("union", "", $gid);
        $gid = str_ireplace("'", "", $gid);
        $gid = str_ireplace("%27", "", $gid);
        $gid = trim($gid, ",");

        if (!$gid) {
            $info = $db->GetOne("select qishu,xsjx_time,id,zongrenshu,q_uid,q_user,q_user_code,q_end_time,q_external_time,g_thumb,g_title,g_money from `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid where `q_showtime` = 'Y' order by `q_end_time` ASC");
        }
        else {
            $infos = $db->GetList("select qishu,xsjx_time,id,zongrenshu,q_uid,q_user,q_user_code,q_end_time,q_external_time,g_thumb,g_title,g_money from `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `q_showtime` = 'Y' order by `q_end_time` ASC limit 0,100");
            $gid = @explode("_", $gid);
            $info = false;

            foreach ($infos as $infov ) {
                if (!in_array($infov["id"], $gid)) {
                    $info = $infov;
                    break;
                }
            }
        }

        if (!$info) {
            echo json_encode(array("error" => "1"));
            return NULL;
            exit();
        }

        if ($info["xsjx_time"]) {
            $info["q_end_time"] = $info["q_end_time"];
        }

        $user = unserialize($info["q_user"]);
        $user = get_user_name($info["q_uid"], "username");
        $uid = $info["q_uid"];
        $upload = G_UPLOAD_PATH;

        if (get_user_key($info["q_uid"], "img", "8080") == "null") {
            $u_thumb = "/photo/member.jpg.8080.jpg";
        }
        else {
            $u_thumb = get_user_key($lotterylist["q_uid"], "img", "8080");
        }

        $q_time = substr($info["q_external_time"], 0, 10);
        $times = $info["q_external_time"];

        if ($q_time <= time()) {
            $db->Query("update `@#_cloud_goods` SET `q_showtime` = 'N' where `id` = '{$info["id"]}' and `q_showtime` = 'Y' and `q_uid` is not null");
            echo json_encode(array("error" => "-1"));
            return NULL;
            exit();
        }

        $shopsum = go_count_record($info["id"], $info["q_uid"], "s");
        $info["q_external_time"] = microt($info["q_external_time"], "r");
        $currency = L("cgoods.currency");
        $times = $q_time - time();
        echo json_encode(array("error" => "0", "user" => "$user", "shopsum" => "$shopsum", "q_external_time" => $info["q_external_time"], "g_money" => $info["g_money"], "zongrenshu" => $info["zongrenshu"], "q_user_code" => $info["q_user_code"], "qishu" => $info["qishu"], "upload" => $upload, "thumb" => $info["g_thumb"], "id" => $info["id"], "uid" => "$uid", "title" => $info["g_title"], "user" => $user, "u_thumb" => $u_thumb, "times" => $times, "currency" => $currency));
        exit();
    }

    public function lottery_shop_set()
    {
        if (isset($_POST["lottery_sub"])) {
            $db = System::load_sys_class("model");
            $times = (int) System::load_sys_config("system", "goods_end_time");

            exit();
            $gid = (isset($_POST["gid"]) ? abs(intval($_POST["gid"])) : true);
            $info = $db->GetOne("select id,xsjx_time,q_uid,q_user,q_end_time,q_external_time from `@#_cloud_goods` where `id` ='$gid'");
            if (!$info || empty($info["q_end_time"])) {
                echo "0";
                exit();
            }

            if ($info["xsjx_time"]) {
                $info["q_end_time"] = $info["q_end_time"];
            }

            $times = str_ireplace(".", "", $info["q_end_time"]);
            $q_time = substr($info["q_external_time"], 0, 10);
            $q = false;

            if ($q_time <= time() + 2) {
                $q = $db->Query("update `@#_cloud_goods` SET `q_showtime` = 'N' where `id` = '$gid' and `q_showtime` = 'Y' and `q_uid` is not null");
            }

            if ($q) {
                echo "1";
            }
            else {
                echo "0";
            }
        }
    }

    public function lottery_shop_get()
    {
        if ( isset( $_POST["lottery_shop_get"] ) ) {
            $db = System::load_sys_class("model");
            $times = (int) System::load_sys_config("system", "goods_end_time");

            $gid = (isset($_POST["gid"]) ? abs(intval($_POST["gid"])) : true);
            $sql = "SELECT id,xsjx_time,q_end_time,q_external_time FROM `@#_cloud_goods` WHERE `id` ='$gid' and `q_showtime` = 'Y'";
            $info = $db->GetOne( $sql );
            if ( ! $info ) {
                exit( "no" );
            }

            if ( $info["xsjx_time"] ) {
                $info["q_end_time"] = $info["q_end_time"] + $times;
            }

            $q_time = intval(substr($info["q_external_time"], 0, 10));

            if ( $q_time <= time() ) {
                $db->Query("UPDATE `@#_cloud_goods` SET `q_showtime` = 'N' WHERE `id` = '{$info["id"]}' AND `q_showtime` = 'Y' AND `q_uid` IS NOT NULL");
            }

            echo $q_time - time();
            exit();
        }
    }

    public function lottery_shop_user()
    {
        $gid = $_GET["gid"] ? : false;
        if ( ! $gid ) {
            $this->SendMsgJson("data", 0, 1);
        }

        $db = System::load_sys_class("model");
        $info = $db->GetOne("SELECT ogid as gid,\r\n\t\t \t\t\t\t\t\t og_title as title,\r\n\t\t \t\t\t\t\t\t     ouid as uid,\r\n\t\t\t\t\t\t\t\t  ou_name as username,\r\n\t\t\t\t\t\t\t\t  onum,owin\r\n\t\t \t\t\t\t\t\t     FROM `@#_cloud_order` WHERE `ogid` = '$gid' AND `owin` IS NOT NULL");

        if ( ! $info ) {
            $this->SendMsgJson("data", 0, 1);
        }

        $info["title"] = unserialize($info["title"]);
        $info["thumb"] = $info["title"]["g_thumb"];
        $info["title"] = $info["title"]["g_title"];
        $this->SendMsgJson("data", $info, 1);
    }

    public function lottery_shop_getjson()
    {
        if ( ! isset( $_POST["gid"] ) ) {
            echo json_encode( array( "error" => "1" ) );
            return NULL;
            exit();
        }
        else {
            $gid = trim( $_POST["gid"] );
        }

        $db = System::load_sys_class("model");
        $gid = safe_replace($gid);
        $gid = str_ireplace("select", "", $gid);
        $gid = str_ireplace("union", "", $gid);
        $gid = str_ireplace("'", "", $gid);
        $gid = str_ireplace("%27", "", $gid);
        $gid = trim($gid, ",");
        $info = $db->GetOne("select qishu,xsjx_time,id,zongrenshu,q_uid,q_user,q_user_code,q_end_time,q_external_time,g_thumb,g_title,g_money from `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `q_uid` is not null and `id`='$gid'");
        $user = unserialize($info["q_user"]);
        $uphoto = $user["img"];
        $user = get_user_name($info["q_uid"], "username");
        $uid = $info["q_uid"];
        $upload = G_UPLOAD_PATH;

        if (get_user_key($info["q_uid"], "img", "8080") == "null") {
            $u_thumb = "/photo/member.jpg.8080.jpg";
        }
        else {
            $u_thumb = get_user_key($lotterylist["q_uid"], "img", "8080");
        }

        $money                   = $info["g_money"];
        $q_end_time              = $info["q_end_time"];
        $huafei                  = go_count_record($info["id"], $info["q_uid"], "m");
        $huibaolv                = go_count_record($info["id"], $info["q_uid"], "l");
        $currency                = L("cgoods.currency");
        $q_time                  = substr($info["q_external_time"], 0, 10);
        $times                   = $info["q_external_time"];
        $shopsum                 = go_count_record($info["id"], $info["q_uid"], "s");
        $info["q_external_time"] = microt($info["q_external_time"], "r");
        $city                    = get_ip(Getuserinfo($uid, "user_ip"), "ipcity");
        echo json_encode(array("error" => "0", "city" => $city, "q_end_time" => $q_end_time, "shopsum" => $shopsum, "uphoto" => $uphoto, "money" => $money, "zongrenshu" => $info["zongrenshu"], "q_user_code" => $info["q_user_code"], "qishu" => $info["qishu"], "upload" => $upload, "thumb" => $info["g_thumb"], "id" => $info["id"], "uid" => "$uid", "title" => $info["g_title"], "user" => $user, "times" => $times, "huafei" => $huafei, "huibaolv" => $huibaolv, "currency" => $currency, "q_external_time" => $info["q_external_time"]));
        exit();
    }
}