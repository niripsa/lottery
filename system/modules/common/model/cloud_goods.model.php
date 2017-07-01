<?php
class cloud_goods_model extends model
{
    public function init()
    {

    }

    public function upgoods_count_num($setkey = "", $where = "")
    {
        $sql = "UPDATE `@#_config` SET  $setkey where $where";
        return $this->Query($sql);
    }

    public function cloud_goodsauto($wherewords = "", $selectwords = "*")
    {
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $cloud_goodssql = "SELECT $selectwords FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `g_status`='1' and `g_type`='3'  and `q_uid` is null and `area_id`= {$area_id} and $wherewords";
        $cloud_goods = $this->GetList($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_goodslist($c_num = "", $g_style = "0")
    {
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $cloud_goodssql = "SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `q_uid` is null  and  `g_status`='1' and `g_type`='3' and  `shenyurenshu`>'0' and `area_id`= {$area_id} ";

        if ($g_style) {
            if ($g_style == "4") {
                $timegap = time() - 86400;
                $cloud_goodssql .= "order by `g_add_time` desc";
            }
            else if ($g_style == "5") {
                $cloud_goodssql .= " order by canyurenshu/zongrenshu desc";
            }
            else if ($g_style == "1") {
                $cloud_goodssql .= " and `g_style` in (1,3) order by `g_sort`";
            }
            else if ($g_style == "2") {
                $cloud_goodssql .= " and  `g_style` in (2,3)  order by `g_sort`";
            }
            else {
                $cloud_goodssql .= " and `g_style`='" . $g_style . "'  order by `g_add_time` desc";
            }
        }

        if ($c_num) {
            $cloud_goodssql .= " LIMIT 0," . $c_num;
        }

        $cloud_goods = $this->GetList($cloud_goodssql);
        return $cloud_goods;
    }
    /**
     * ajax下拉加载
     */
    public function ajax_cloud_goodslist($c_num = "", $g_style = "0", $page = "0")
    {   
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $cloud_goodssql = "SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `q_uid` is null  and  `g_status`='1' and `g_type`='3' and  `shenyurenshu`>'0' and `area_id`= {$area_id} ";

        if ($g_style) {
            if ($g_style == "4") {
                $timegap = time() - 86400;
                $cloud_goodssql .= "order by `g_add_time` desc";
            }
            else if ($g_style == "5") {
                $cloud_goodssql .= " order by canyurenshu/zongrenshu desc";
            }
            else if ($g_style == "1") {
                $cloud_goodssql .= " and `g_style` in (1,3) order by `g_sort`";
            }
            else if ($g_style == "2") {
                $cloud_goodssql .= " and  `g_style` in (2,3)  order by `g_sort`";
            }
            else {
                $cloud_goodssql .= " and `g_style`='" . $g_style . "'  order by `g_add_time` desc";
            }
        }
        if ($c_num) {
            $limit_start = ($page - 1) * $c_num;
            $cloud_goodssql .= " LIMIT ". $limit_start . "," . $c_num;
        }
        $cloud_goods = $this->GetList($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_goodslisted($limitnum = "", $Gettype = "GetList")
    {
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $cloud_goodssql = "SELECT a.id,a.q_uid,a.q_user,a.zongrenshu,a.qishu,a.price,a.q_end_time,a.q_external_time,a.q_user_code,b.g_title,b.g_thumb FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `g_status`='1' and `g_type`='3' ";
        $cloud_goodssql .= " and `q_uid` is not null  and `q_showtime` = 'N' and `area_id` = {$area_id} order by `q_end_time` desc";

        if ($limitnum) {
            $cloud_goodssql .= " " . $limitnum;
        }

        $cloud_goods = $this->{$Gettype}($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_goodsed($limitnum = "")
    {
        $cloud_goodssql = "SELECT id,q_uid,qishu,g_title,g_thumb,g_title,price,a.q_user_code,zongrenshu,canyurenshu,shenyurenshu FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `g_status`='1' and `g_type`='3' ";
        $cloud_goodssql .= " and `q_uid` is not null and `q_showtime` = 'N' order by `q_end_time` desc";

        if ($limitnum) {
            $cloud_goodssql .= " limit  " . $limitnum;
        }

        $cloud_goods = $this->GetList($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_goodsprevious($gid = "", $qishu)
    {
        $cqpsql = "SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where a.`gid`=$gid and a.`qishu`=$qishu and a.`q_showtime`='N'";
        return $this->GetOne($cqpsql);
    }

    public function cloud_goodsdetail($id = "")
    {
        $cloud_goodssql = "SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid left join `@#_goods_info` as c on a.gid=c.gid  where `g_status`='1' and `g_type`='3' and a.id='" . $id . "'";
        $cloud_goods = $this->GetOne($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_goodsdetaila($id = "")
    {
        $cloud_goodssql = "SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid left join `@#_goods_info` as c on a.gid=c.gid  where `g_status`='1' and `g_type`='3' and a.id='" . $id . "' and `q_showtime`='N'";
        $cloud_goods = $this->GetOne($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_goodslastone($gid = "")
    {
        $cloud_goodssql = "SELECT * FROM `@#_cloud_goods`  where `gid`='$gid' order by qishu desc";
        $cloud_goods = $this->GetOne($cloud_goodssql);
        return $cloud_goods;
    }

    public function cloud_qishu($gid)
    {
        $itemlist = $this->GetList("select id,qishu,q_uid from `@#_cloud_goods` where `gid`='$gid' order by `qishu` DESC");
        return $itemlist;
    }

    public function cloud_gid($id)
    {
        $gid = "SELECT gid FROM `@#_cloud_goods`  where  id='" . $id . "'";
        $gid = $this->GetOne($gid);
        return $gid["gid"];
    }

    public function cloud_code($q_user_code)
    {
        $q_user_code_len = strlen($q_user_code);
        $q_user_code_arr = array();

        for ($q_i = 0; $q_i < $q_user_code_len; $q_i++) {
            $q_user_code_arr[$q_i] = substr($q_user_code, $q_i, 1);
        }

        return $q_user_code_arr;
    }

    public function cloud_cpgoodslist($c_cateid = "", $c_brandid = "", $sorts = "", $limitnum = "")
    {
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $retkey = "`g_title`,`g_thumb`,`g_money`,a.`gid`,a.`id`,`price`,`shenyurenshu`,`zongrenshu`,`canyurenshu`";
        $cpgoodslistsql = "SELECT $retkey FROM `@#_cloud_goods` AS a LEFT JOIN `@#_goods` AS b ON a.gid=b.gid WHERE `g_status`='1' AND `g_type`='3' AND `shenyurenshu`>'0' AND `q_uid` IS NULL AND `area_id` = {$area_id} ";
        if ( $c_cateid && $c_brandid ) {
            $cpgoodslistsql .= "AND `g_brandid`='$c_brandid' AND `g_cateid` IN ($c_cateid) ";
        }
        else {
            if ( $c_cateid && ! $c_brandid ) {
                $cpgoodslistsql .= "AND `g_cateid` IN ($c_cateid)";
            }

            if ( ! $c_cateid && $c_brandid ) {
                $cpgoodslistsql .= "AND `g_brandid` = '$c_brandid'";
            }
            else {
                $cpgoodslistsql .= "";
            }
        }

        if ( $sorts ) {
            if ( strstr( $sorts, "order by") ) {
                $cpgoodslistsql .= $sorts . ",`g_add_time` desc";
            }
            else {
                $cpgoodslistsql .= $sorts;
                $cpgoodslistsql .= " order by `g_add_time` desc ";
            }
        }
        else {
            $cpgoodslistsql .= " order by `g_add_time` desc ";
        }

        $cpgoodslistsql .= " " . $limitnum;

        if ( $cpgoodslistsql ) {
            $cpgoodslist = $this->GetList( $cpgoodslistsql );
        }
        else {
            $cpgoodslist = 0;
        }

        return $cpgoodslist;
    }

    /**
     * 推荐商品列表  `g_style`='2'
     */
    public function cloud_recomgoodslist($c_cateid = "", $c_brandid = "", $sorts = "", $limitnum = "")
    {
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $retkey = "`g_title`,`g_thumb`,`g_money`,a.`gid`,a.`id`,`price`,`shenyurenshu`,`zongrenshu`,`canyurenshu`";
        $cpgoodslistsql = "SELECT $retkey FROM `@#_cloud_goods` AS a LEFT JOIN `@#_goods` AS b ON a.gid=b.gid WHERE `g_status`='1' AND `g_style` BETWEEN '2' AND '3' AND `g_type`='3' AND `shenyurenshu`>'0' AND `q_uid` IS NULL AND `area_id` = {$area_id} ";
        if ( $c_cateid && $c_brandid ) {
            $cpgoodslistsql .= "AND `g_brandid`='$c_brandid' AND `g_cateid` IN ($c_cateid) ";
        }
        else {
            if ( $c_cateid && ! $c_brandid ) {
                $cpgoodslistsql .= "AND `g_cateid` IN ($c_cateid)";
            }

            if ( ! $c_cateid && $c_brandid ) {
                $cpgoodslistsql .= "AND `g_brandid` = '$c_brandid'";
            }
            else {
                $cpgoodslistsql .= "";
            }
        }

        if ( $sorts ) {
            if ( strstr( $sorts, "order by") ) {
                $cpgoodslistsql .= $sorts . ",`g_add_time` desc";
            }
            else {
                $cpgoodslistsql .= $sorts;
                $cpgoodslistsql .= " order by `g_add_time` desc ";
            }
        }
        else {
            $cpgoodslistsql .= " order by `g_add_time` desc ";
        }

        $cpgoodslistsql .= " " . $limitnum;

        if ( $cpgoodslistsql ) {
            $cpgoodslist = $this->GetList( $cpgoodslistsql );
        }
        else {
            $cpgoodslist = 0;
        }

        return $cpgoodslist;
    }

    public function cloud_goodsm($shopids)
    {
        $shoplistsql = "SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `g_status`='1' and `g_type`='3' and a.id in (" . $shopids . ")";
        $shoplist = $this->GetList($shoplistsql, array("key" => "id"));
        return $shoplist;
    }

    public function cloud_cpgoodstotal($c_cateid = "", $c_brandid = "")
    {
        $totalsql = "SELECT a.gid FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid  where `g_status`='1' and `g_type`='3' and  `q_uid` is null  ";
        if ($c_cateid && $c_brandid) {
            $totalsql .= "and `g_brandid`='$c_brandid'  and `g_cateid` in ($c_cateid) ";
        }
        else {
            if ($c_cateid && !$c_brandid) {
                $totalsql .= "and `g_cateid` in ($c_cateid)";
            }

            if (!$c_cateid && $c_brandid) {
                $totalsql .= "and `g_brandid`='$c_brandid'";
            }
            else {
                $totalsql .= "";
            }
        }

        if ($totalsql) {
            $total = $this->GetCount($totalsql);
        }
        else {
            $total = 0;
        }

        return $total;
    }

    public function cloud_brand($c_cateid = "")
    {
        if (!$c_cateid) {
            $c_cateid = $this->GetList("select id,cateid,name from `@#_brand` where 1 order by `sort` DESC");
        }
        else {
            $brandsql = "select id,cateid,name from `@#_brand` where  ";
            $arraylen = count($c_cateid);

            foreach ($c_cateid as $key => $v ) {
                if ($key == $arraylen - 1) {
                    $brandsql .= "CONCAT(',',`cateid`,',') LIKE '%,{$v["cateid"]},%' ";
                }
                else {
                    $brandsql .= "CONCAT(',',`cateid`,',') LIKE '%,{$v["cateid"]},%' or ";
                }
            }

            $brandsql .= "order by `sort` DESC";
            $c_cateid = $this->GetList($brandsql);
        }

        return $c_cateid;
    }

    public function cloud_brandpcid($cid = "")
    {
        $pcid = $this->GetList("select * from `@#_cate` where `cateid` = '$cid' or `parentid`='$cid'");

        foreach ($pcid as $key => $v ) {
            $brandpcid[$key]["cateid"] = $v["cateid"];
        }

        return $brandpcid;
    }

    public function cloud_brandinfo($brandid = "")
    {
        $brandinfo = $this->GetOne("select * from `@#_brand` where `id` = '$brandid' limit 1 ");
        return $brandinfo;
    }

    public function cloud_cate1($c_cateid = "")
    {
        $daohang = $this->GetOne("select cateid,name,parentid,info from `@#_cate` where `cateid` = '$c_cateid' limit 1 ");
        return $daohang;
    }

    public function cloud_cate2($c_cateid = "")
    {
        $daohang = $this->GetList("select cateid,name,parentid,info from `@#_cate` where `parentid` = '$c_cateid'");
        return $daohang;
    }

    public function cloud_parentid( $parentid = "" )
    {
        if ( $parentid ) {
            $sun_cate = $this->GetList("SELECT cateid,parentid,name from `@#_cate` where `parentid` = '$parentid'");
            $two_clild = '';
            foreach ( $sun_cate as $k => $v )
            {
                $two_clild[] = $v['cateid'];
            }
            $clild_str = implode( "', '", $two_clild );
            if ( $clild_str )
            {
                $sql = "SELECT cateid,parentid,name from `@#_cate` where `parentid` IN ( '$clild_str' )";
                $clild_cate = $this->GetList( $sql );
                if ( $clild_cate )
                {
                    $sun_cate = array_merge( $sun_cate, $clild_cate );
                }
            }
        }
        else {
            $sun_cate = $this->GetList("select cateid,parentid,name from `@#_cate` where `model` = '1' and `parentid` = '0' order by `sort` DESC");
        }

        return $sun_cate;
    }

    public function select_send_log($wherewords = "", $Gettype = "GetOne", $selectwords = "*")
    {
        $sendlog = "SELECT $selectwords FROM `@#_send_log` where $wherewords";
        $send_log = $this->{$Gettype}($sendlog);
        return $send_log;
    }

    public function insert_send_log($data = "")
    {
        $ret = $this->sql_insert($data);
        $sendlog = "INSERT INTO `@#_send_log` ({$ret["keys"]}) VALUES ({$ret["vals"]})";
        $sendlog = $this->Query($sendlog);
        return $send_log;
    }

    public function update_send_log($setwords = "", $wherewords = "")
    {
        $sendlog = "UPDATE `@#_send_log` SET $setwords  WHERE $wherewords";
        $send_log = $this->Query($sendlog);
        return $send_log;
    }

    public function cloud_user_record($wherewords = "")
    {
        $order_db = System::load_app_model("order", "common");
        return $user_record = $order_db->ready_order($wherewords, 1);
    }

    public function cloud_user_recordID($uid = "", $shopid = "", $num = "100")
    {
        $order_db = System::load_app_model("order", "common");
        $wherewords = "`ouid`='$uid' and `ogid`='$shopid' ORDER BY oid DESC LIMIT " . $num;
        return $user_record = $order_db->ready_order($wherewords, 1);
    }

    public function cloud_user_recordhuode($num = "")
    {
        $order_db = System::load_app_model("order", "common");
        $selectwords = "`owin` is not null ORDER BY otime DESC LIMIT " . $num;
        return $user_record = $order_db->ready_order($selectwords, 1);
    }

    public function cloud_ogocode($oid = "")
    {
        $ogsql = "select * from `@#_cloud_select_i` where `oid`='$oid'";
        return $this->Getone($ogsql);
    }

    /**
     * 获取列表
     */
    public function get_list( $table, $where, $field, $order = '', $num = '' )
    {
        return $this->data_list( $table, $where, $field, $order, $num );
    }
}

System::load_sys_class("model", "sys", "no");

