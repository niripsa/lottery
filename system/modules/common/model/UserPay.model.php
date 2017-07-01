<?php
class UserPay_model extends model
{
    public function SelectUserUid($uid = "", $key = "*")
    {
        $usql = "SELECT $key FROM `@#_user` WHERE `uid` = '$uid' for update";
        return $selectuinfo = $this->GetOne($usql);
    }

    public function UpdateUserInfo($uid = "", $setwords = "")
    {
        $usql = "UPDATE `@#_user` SET $setwords WHERE `uid`='$uid'";
        return $this->Query($usql);
    }

    public function SelectPayclass($pay_class = "", $key = "*")
    {
        $csql = "SELECT $key FROM `@#_payment` WHERE `pay_class` = '$pay_class' and `pay_start` = '1'";
        return $this->GetOne($csql);
    }

    public function SelectPayid($pay_id = "", $key = "*")
    {
        $isql = "SELECT $key FROM `@#_payment` WHERE `pay_id` = '$pay_id' and `pay_start` = '1'";
        return $this->GetOne($isql);
    }

    public function SelectRecord($selectwords = "", $key = "*", $sectype = "GetOne")
    {
        $ursql = "SELECT $key FROM  `@#_user_record` WHERE $selectwords";
        return $this->{$sectype}($ursql);
    }

    public function InsertRecord($insert_html = "")
    {
        foreach ($insert_html as $key => $v ) {
            $insert_htmla = "";
            $rsql = "INSERT INTO `@#_user_record` (`oid`,`oid_tmp`,`ur_uid`,`ur_username`,`ur_uphoto`,`ur_shopid`,`ur_shopname`,`ur_shopqishu`,`ur_moneycount`,`ur_ip`,`ur_time`,`ur_gonumber`,`ur_goucode`) VALUES ";

            if ($v["codes_len"] <= 3000) {
                $insert_htmla .= $v["value"] . ",'" . $v["codes_len"] . "','" . $v["codes"] . "'),";
                $rsql .= trim($insert_htmla, ",");
                $insetsql = $this->Query($rsql);
                $rsql = "";
                $insert_htmla = "";
            }
            else {
                $codearray = explode(",", $v["codes"]);
                $num = ceil($v["codes_len"] / 3000);
                $j = 0;
                $codesum = count($codearray);

                for ($i = 1; $i <= $num; $i++) {
                    $rsqla = $rsql;
                    $insert_htmla = "";

                    if ($codesum <= $i * 3000) {
                        $limitnum = $codesum;
                    }
                    else {
                        $limitnum = $i * 3000;
                    }

                    $codes1 = array_slice($codearray, $j * 3000, $limitnum - 1);
                    $codes2 = implode(",", $codes1);
                    $sublen = $limitnum - ($j * 3000);
                    $insert_htmla .= $v["value"] . ",'" . $sublen . "','" . $codes2 . "'),";
                    $rsqla .= trim($insert_htmla, ",");
                    $insetsql = $this->Query($rsqla);

                    if ($insetsql) {
                        $j++;
                    }
                }
            }
        }

        return $insetsql;
    }

    public function UpdateRecord($setwords = "", $wherewords = "")
    {
        $ursql = "UPDATE `@#_user_record` SET $setwords where $wherewords";
        return $this->Query($ursql);
    }

    public function SelectOrders($dingdancode, $time, $key = "*")
    {
        $sosql = "SELECT $key FROM `@#_orders`  where `ocode`='$dingdancode' and `otime`='$time'";
        $SelectOrders = $this->GetOne($sosql);
        return $SelectOrders;
    }

    public function UpdateOrders($uid = "", $dingdanoid = "", $setwords = "")
    {
        $ursql = "UPDATE `@#_orders` SET $setwords WHERE `oid`='$dingdanoid' and `ouid` = '$uid'";
        return $this->Query($ursql);
    }

    public function InsertOrders($insert_html = "")
    {
        $iosql = "INSERT INTO `@#_orders` (`otype`,`ouid`,`ocode`,`omoney`,`ostatus`,`opay`,`oremark`,`otime`) VALUES ";
        $iosql .= trim($insert_html, ",");
        return $this->Query($iosql);
    }

    public function InsertOrdersinfo($oid, $otext)
    {
        $ioisql = "INSERT INTO `@#_orders_info` (`oid`,`otext`) VALUES ('$oid','$otext')";
        return $this->Query($ioisql);
    }

    public function InsertAccount($insert_html = "")
    {
        $agsql = "INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES (";
        $agsql .= $insert_html . ")";
        return $this->Query($agsql);
    }

    public function UpdateRecordSum($goods_count_num = "0")
    {
        $urssql = "UPDATE `@#_config` SET `value`=`value` + $goods_count_num WHERE `name`='goods_count_num'";
        return $this->Query($urssql);
    }

    public function SelectConfig($name = "")
    {
        $scsql = "select * from `@#_config` WHERE `name`=$name";
        $SelectConfig = $this->GetOne($scsql);
        return $SelectConfig;
    }

    public function insert_money_record($value = "")
    {
        $imrsql = "INSERT INTO `@#_user_money_record` (`uid`,`code`,`money`,`status`,`time`,`scookies`) VALUES (" . $value . ")";
        return $this->Query($imrsql);
    }

    public function get_order($ocode, $status = 1)
    {
        $sql = "select * from `@#_orders` where `ocode`='" . $ocode . "' and `ostatus`='" . $status . "'";

        if ($status == "n") {
            $sql = "select * from `@#_orders` where `ocode`='" . $ocode . "'";
        }

        return $this->Getone($sql);
    }

    public function get_recharge_order($oid, $status = 1){
        $sql = "select * from `@#_orders` where `oid`='" . $oid . "' and `ostatus`='" . $status . "'";
        return $this->Getone($sql);
    }

    public function get_recharge_order_by_code($ocode, $status = 1){
        if(!empty($status)){
            $sql = "select * from `@#_orders` where `ocode`='" . $ocode . "' and `ostatus`='" . $status . "'";
        }else{
            $sql = "select * from `@#_orders` where `ocode`='" . $ocode . "'";
        }
        
        return $this->Getone($sql);
    }

    public function get_money_record($ocode)
    {
        $sql = "select * from `@#_user_money_record` where `code`='" . $ocode . "'";
        return $this->Getone($sql);
    }

    public function cloud_gocode($where = "")
    {
        $gosql = "SELECT * FROM `@#_cloud_select_i` WHERE " . $where;
        return $this->Getone($gosql);
    }

    public function cloud_gocodel($where = "")
    {
        $gosql = "select * from `@#_cloud_select_i` where " . $where;
        return $this->GetList($gosql);
    }
}


