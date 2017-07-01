<?php
class user_model extends model
{
    public function SelectUserOne($user, $where, $key = "*")
    {
        $where = $this->sql_and($where);

        if (!empty($user)) {
            if (strpos($user, "@") === false) {
                $fu = "mobile";
            }
            else {
                $fu = "email";
            }

            if ($where) {
                $sql = "SELECT $key FROM `@#_user` where `$fu` = '$user' and $where LIMIT 1";
            }
            else {
                $sql = "SELECT $key FROM `@#_user` where `$fu` = '$user' LIMIT 1";
            }

            return $this->GetOne($sql);
        }
        else {
            return true;
        }
    }

    public function SelectWeixin($openid)
    {
        $sql = "SELECT * from `@#_weixin` where `openid` = '" . $openid . "'";
        return $this->GetOne($sql);
    }

    public function SelectUserExist( $username )
    {
        $sql = "SELECT * from `@#_user` where `username` = '" . $username . "'";
        return $this->GetOne($sql);
    }

    public function BondWeixin($openid, $uid)
    {
        $info = $this->SelectWeixin($openid);

        if ($info) {
            if ($info["user_id"] != $uid) {
                $sql = "UPDATE `@#_weixin` SET  `user_id`='" . $uid . "' where `openid`='" . $openid . "'";
            }
            else {
                $sql = "";
            }
        }
        else {
            $sql = "INSERT INTO `@#_weixin` (`openid`,`user_id`,`addtime`) VALUES ('" . $openid . "','" . $uid . "'," . time() . ")";
        }

        if ($sql != "") {
            $reg = $this->Query($sql);
        }
    }

    public function SelectUser($where = "", $key = "*")
    {
        $sql = "SELECT $key FROM `@#_user` where $where";
        return $this->GetOne($sql);
    }

    public function UpdateUser($setkey = "", $where = "")
    {
        $sql = "UPDATE `@#_user` SET  $setkey where $where";
        return $this->Query($sql);
    }

    public function SelectUserUid($uid, $key = "*")
    {
        $sql = "SELECT $key FROM `@#_user` where `uid` = '$uid' LIMIT 1";
        return $this->GetOne($sql);
    }

    public function AddYiPinUserOne( $data )
    {
        $arr = array();
        $arr["username"] = $data['member_name'];
        $arr["password"] = $data['member_passwd'];
        $arr["email"]    = $data['member_email'];
        $arr["mobile"]   = $data['member_mobile'];
        $arr["reg_key"]  = $data['member_mobile'] ? $data['member_mobile'] : $data['member_email'];     
        $arr["img"]      = "photo/member.jpg";
        $arr["time"]     = $data['member_time'];
        $arr["user_ip"]  = $data['member_login_ip'];

        $ret = $this->sql_insert($arr);
        $sql = "INSERT INTO `@#_user` ({$ret["keys"]}) VALUES ({$ret["vals"]})";
        $reg = $this->Query($sql);

        if ($reg) {
            return $this->GetInsertId();
        }
        else {
            return false;
        }
    }

    public function AddUserOne($user, $pass, $data = NULL)
    {
        if (strpos($user, "@") === false) {
            $u_email = "";
            $u_phone = $user;
        }
        else {
            $u_email = $user;
            $u_phone = "";
        }

        $arr               = array();
        $arr["password"]   = md5( md5($pass) . md5($pass) );
        $arr["email"]      = $u_email;
        $arr["mobile"]     = $u_phone;
        $arr["reg_key"]    = $user;
        $arr["username"]   = _customUsername();
        $arr["img"]        = "photo/member.jpg";
        $arr["time"]       = time();
        $arr["user_ip"]    = _get_ip_dizhi();
        $arr["emailcode"]  = "-1";
        $arr["mobilecode"] = "-1";

        if ( $data ) {
            foreach ( $data as $k => $v ) {
                $arr[$k] = $v;
            }
        }

        $ret = $this->sql_insert( $arr );
        $sql = "INSERT INTO `@#_user` ({$ret["keys"]}) VALUES ({$ret["vals"]})";
        $reg = $this->Query($sql);

        if ($reg) {
            return $this->GetInsertId();
        }
        else {
            return false;
        }
    }

    public function GetUserGroup($exp)
    {
        $sql = "SELECT * FROM `@#_user_group` WHERE `jingyan_start` <= '$exp' and `jingyan_end` >= '$exp'";
        return $this->GetOne($sql);
    }

    public function GetMaxUserGroup($id)
    {
        $sql = "SELECT * FROM `@#_user_group` WHERE `groupid` > '$id' ORDER BY `groupid` ASC LIMIT 1";
        return $this->GetOne($sql);
    }

    public function UserBand($where = "", $key = "*")
    {
        $ubsql = "SELECT $key FROM `@#_user_band` WHERE $where";
        return $this->GetOne($ubsql);
    }

    public function UserSum($where = "")
    {
        $ubsql = "SELECT `uid` FROM `@#_user` WHERE $where";
        return $this->GetCount($ubsql);
    }

    public function GetUserYaoqingUid($uid)
    {
        $sql = "SELECT * FROM `@#_user` WHERE `yaoqing`='$uid' ORDER BY `time` DESC";
        return $this->GetList($sql);
    }

    public function Insert_user_account($data = "")
    {
        if ($data) {
            $ret = $this->sql_insert($data);
            $sql = "INSERT INTO `@#_user_account` ({$ret["keys"]}) VALUES ({$ret["vals"]})";
            return $reg = $this->Query($sql);
        }
    }

    public function Insert_user_recodes($data = "")
    {
        if ($data) {
            $ret = $this->sql_insert($data);
            $sql = "INSERT INTO `@#_user_recodes` ({$ret["keys"]}) VALUES ({$ret["vals"]})";
            return $reg = $this->Query($sql);
        }
    }

    public function Get_user_recodes($where = "", $key = "*")
    {
        $sql = "SELECT $key FROM `@#_user_recodes` where $where";
        return $this->GetList($sql);
    }

    public function num_user_recodes($where = "", $key = "*")
    {
        $sql = "SELECT $key FROM `@#_user_recodes` where $where";
        return $this->GetCount($sql);
    }

    public function Get1_user_recodes($uid = "", $num = "", $key = "*")
    {
        $sql = "SELECT $key FROM `@#_user_recodes` where `uid`='$uid'" . $num;
        return $this->GetList($sql);
    }

    public function num1_user_recodes($uid = "", $key = "*")
    {
        $sql = "SELECT $key FROM `@#_user_recodes` where `uid`='$uid'";
        return $this->GetCount($sql);
    }

    /**
     * 获取会员账户明细
     */
    public function Get_user_account( $where = "", $key = "*", $order = '', $limitnum )
    {
        $sql = "SELECT $key FROM `@#_user_account` WHERE $where";
        if ( $order != '' )
        {
            $sql .= " " . $order;
        }
        $sql .= " " . $limitnum;
        return $this->GetList( $sql );
    }

    public function Get_user_accountn( $where = "", $key = "*" )
    {
        $sql = "SELECT $key FROM `@#_user_account` WHERE $where";
        return $this->GetCount( $sql );
    }

    public function GetUserAccount($uid, $limitnum)
    {
        $sql = "SELECT * FROM `@#_user_account` WHERE `uid` = '$uid' AND `pay` = '账户' AND `type`!='0' ORDER BY `time` DESC";
        $sql .= " " . $limitnum;
        return $this->GetList( $sql );
    }

    /**
     * 是否有记录
     */
    public function has_account( $uid, $type = '-1' )
    {
        $sql = "SELECT * FROM `@#_user_account` WHERE `uid` = '$uid' AND `pay` = '账户' AND `type` = '$type' ORDER BY `time` DESC LIMIT 0, 1";
        return $this->GetList( $sql );
    }

    public function GetUserAccountCount($uid)
    {
        $sql = "SELECT * FROM `@#_user_account` WHERE `uid`='$uid' AND `pay` = '账户' and `type`!='0' ORDER BY `time` DESC";
        return $this->GetCount($sql);
    }

    /**
     * 单条支付信息
     * @author Yusure  http://yusure.cn
     * @date   2015-10-28
     * @param  [param]
     * @param  [type]     $condition [description]
     */
    public function GetOnePay( $condition, $field = '*' )
    {
        $paysql = "SELECT {$field} FROM `@#_payment` WHERE {$condition}";
        return $this->GetOne($paysql);
    }

    public function GetPaylist($type = "1")
    {
        if (G_IS_MOBILE || G_IS_TEMPSKIN) {
            $type = 2;
        }

        $paysql = "SELECT * FROM `@#_payment` WHERE `pay_start`='1'  and `pay_mobile` LIKE '%$type%' ORDER BY `pay_id` asc ";
        return $this->GetList($paysql);
    }

    public function plaingoodsnum($ouid = "")
    {
        $pgsql = "SELECT * FROM `@#_orders` WHERE `ouid`='$ouid' and `ostatus`='2' and `otype`='2'";
        return $this->GetCount($pgsql);
    }

    public function plaingoodslist($ouid = "", $limitnum = "")
    {
        $pgsql = "SELECT * FROM `@#_orders` WHERE `ouid`='$ouid' and `ostatus`='2' and `otype`='2'" . $limitnum;
        $orderinfo = $this->GetList($pgsql);

        foreach ($orderinfo as $key => $v ) {
            $pgsqla = "SELECT * FROM `@#_orders_info` WHERE `oid`='{$v["oid"]}'";
            $info = $this->GetOne($pgsqla);
            $orderinfo[$key]["otext"] = unserialize($info["otext"]);
        }

        return $orderinfo;
    }
}