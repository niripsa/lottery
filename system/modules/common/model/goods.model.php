<?php

class goods_model extends model
{
    public function goodslist($g_status = "1", $g_num = "", $g_sort = "DESC", $add_condition = "")
    {
        $goodssql = "SELECT * FROM `@#_goods`  where `g_status`='" . $g_status . "' and `g_type`='1' order by `g_add_time`" . $g_sort;

        if ($g_num) {
            $goodssql .= " LIMIT 0," . $g_num;
        }

        $goods = $this->GetList($goodssql);
        return $goods;
    }

    public function cpgoodstotal($c_cateid = "", $c_brandid = "")
    {
        $totalsql = "SELECT * FROM  `@#_goods`   where `g_status`='1' and `g_type`='1' ";
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

    public function cpgoodslist($c_cateid = "", $c_brandid = "", $sorts = "", $limitnum = "")
    {
        $cpgoodslistsql = "SELECT * FROM `@#_goods`  where `g_status`='1' and `g_type`='1'  ";
        if ($c_cateid && $c_brandid) {
            $cpgoodslistsql .= "and `g_brandid`='$c_brandid'  and `g_cateid` in ($c_cateid) ";
        }
        else {
            if ($c_cateid && !$c_brandid) {
                $cpgoodslistsql .= "and `g_cateid` in ($c_cateid)";
            }

            if (!$c_cateid && $c_brandid) {
                $cpgoodslistsql .= "and `g_brandid`='$c_brandid'";
            }
            else {
                $cpgoodslistsql .= "";
            }
        }

        if ($sorts) {
            $cpgoodslistsql .= $sorts;
        }
        else {
            $cpgoodslistsql .= " order by `g_add_time` desc ";
        }

        $cpgoodslistsql .= " " . $limitnum;

        if ($cpgoodslistsql) {
            $cpgoodslist = $this->GetList($cpgoodslistsql);
        }
        else {
            $cpgoodslist = 0;
        }

        return $cpgoodslist;
    }

    public function goodsdetail($g_status = "1", $g_num = "", $g_sort = "DESC", $add_condition = "")
    {
        $goodssql = "SELECT a.*,b.g_picarr,b.g_content FROM `@#_goods` as a left join `@#_goods_info` as b on a.gid=b.gid  where a.`g_status`='" . $g_status . "' and a.`g_type`='1'";

        if ($add_condition) {
            $goodssql .= " and " . $add_condition;
        }

        $goodssql .= " order by a.`g_add_time`" . $g_sort;

        if ($g_num) {
            $goodssql .= " LIMIT 0," . $g_num;
        }

        $goods = $this->GetList($goodssql);
        return $goods;
    }

    public function get_goods_one($where)
    {
        $sql = "SELECT a.*,b.g_picarr,b.g_content FROM `@#_goods` as a left join `@#_goods_info` as b on a.gid=b.gid  where ";

        if (!empty($where)) {
            $sql .= $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_goods_any($where)
    {
        $sql = "SELECT a.*,b.g_picarr,b.g_content FROM `@#_goods` as a left join `@#_goods_info` as b on a.gid=b.gid  where  ";

        if (!empty($where)) {
            $sql .= " " . $where;
        }

        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function goods_add($data)
    {
        $data_info["g_picarr"] = $data["g_picarr"];
        $data_info["g_content"] = $data["g_content"];
        unset($data["g_picarr"]);
        unset($data["g_content"]);
        $this->sql_begin();
        $gid = $this->Insert("goods", $data);
        $data_info["gid"] = $gid;
        $res = $this->Insert("goods_info", $data_info);
        if ($res && $gid) {
            $this->sql_commit();
            return $gid;
        }
        else {
            $this->sql_rollback();
            return false;
        }
    }

    public function goods_save($data, $where)
    {
        $data_info["g_picarr"] = $data["g_picarr"];
        $data_info["g_content"] = $data["g_content"];
        unset($data["g_picarr"]);
        unset($data["g_content"]);
        $this->sql_begin();
        $gid = $this->Update("goods", $data, $where);
        $res = $this->Update("goods_info", $data_info, $where);
        if (($res !== false) && ($gid !== false)) {
            $this->sql_commit();
            return true;
        }
        else {
            $this->sql_rollback();
            return false;
        }
    }

    public function get_goods($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_goods`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function goods_sort($data)
    {
        $i = false;

        foreach ($data as $k => $v ) {
            $res = $this->Update("goods", array("g_sort" => $v), "gid=" . $k);

            if ($res) {
                $i = true;
            }
        }

        return $i;
    }

    public function goods_del($id)
    {
        $this->sql_begin();
        $sql = "DELETE FROM `@#_goods` WHERE gid=" . $id;
        $r1 = $this->Delete($sql);
        $sql = "DELETE FROM `@#_goods_info` WHERE gid=" . $id;
        $r3 = $this->Delete($sql);
        if (($r1 !== false) && ($r3 !== false)) {
            $this->sql_commit();
            return true;
        }
        else {
            $this->sql_rollback();
            return false;
        }
    }

    public function get_goods_num($where)
    {
        $sql = "select count(*) as num from `@#_goods`";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function cloud_goods_num($where)
    {
        $sql = "select count(*) as num from `@#_goods` a left join `@#_cloud_goods` b on a.gid=b.gid ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function cloud_goods_num1($where)
    {
        $sql = "select count(*) as num from (select gid as g_id,max(id) as m_id from `@#_cloud_goods` group by gid ORDER BY id desc) c LEFT JOIN `@#_cloud_goods` b on c.m_id=b.id left join `@#_goods` as a on c.g_id=a.gid ";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function cloud_goods_list($where = "", $order = "", $num = "")
    {
        $sql = "SELECT a.*,b.id,b.q_uid,b.q_user,b.q_end_time,b.maxqishu,b.price,b.zongrenshu,b.canyurenshu,b.shenyurenshu,b.qishu FROM `@#_goods` as a left join `@#_cloud_goods` as b on a.gid=b.gid ";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function cloud_goods($where = "", $order = "", $num = "")
    {
        $sql = "select a.*,b.*,c.* from (select gid as g_id,max(id) as m_id from `@#_cloud_goods` group by gid ORDER BY id desc) c LEFT JOIN `@#_cloud_goods` b on c.m_id=b.id left join `@#_goods` as a on c.g_id=a.gid ";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function cloud_goods_one($where)
    {
        $sql = "SELECT a.*,b.g_picarr,b.g_content FROM `@#_goods` as a left join `@#_goods_info` as b on a.gid=b.gid  where a.`g_type`='3'";

        if (!empty($where)) {
            $sql .= " AND " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        $sql = "SELECT * FROM `@#_cloud_goods` where `gid`='" . $res["gid"] . "' order by id desc";
        $r = $this->GetOne($sql);

        if (is_array($r)) {
            unset($r["gid"]);
            $res = array_merge($res, $r);
        }

        return $res;
    }

    public function cloud_qishu_one($where, $field = "*")
    {
        $sql = "SELECT " . $field . " FROM `@#_cloud_goods` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function del_cloud_one($id)
    {
        $info = $this->cloud_goods_one("a.`gid` = '" . $id . "'");
        $codes_table = $info["codes_table"];
        $this->sql_begin();

        if (!empty($codes_table)) {
            $sql = "DELETE FROM `@#_" . $codes_table . "` WHERE cg_id=" . $id;
            $res = $this->Delete($sql);
        }
        else {
            $res = true;
        }

        $sql = "DELETE FROM `@#_goods` WHERE gid=" . $id;
        $r1 = $this->Delete($sql);
        $sql = "DELETE FROM `@#_cloud_goods` WHERE gid=" . $id;
        $r2 = $this->Delete($sql);
        $sql = "DELETE FROM `@#_goods_info` WHERE gid=" . $id;
        $r3 = $this->Delete($sql);
        if (($res !== false) && ($r1 !== false) && ($r2 !== false) && ($r3 !== false)) {
            $this->sql_commit();
            return true;
        }
        else {
            $this->sql_rollback();
            return false;
        }
    }

    public function del_cloud_qishu($id)
    {
        $sql = "DELETE FROM `@#_cloud_goods` WHERE id=" . $id;
        return $this->Delete($sql);
    }

    public function del_cloud_list($idstr)
    {
        $id_arr = explode(",", $idstr);
        $num = 0;
        if (is_array($id_arr) && (0 < count($id_arr))) {
            foreach ($id_arr as $v ) {
                if ($this->del_cloud_one($v)) {
                    $num++;
                }
            }

            if ($num == count($id_arr)) {
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return $this->del_cloud_one($idstr);
        }
    }

    /**
     * 云购商品添加
     */
    public function cloud_goods_add( $data )
    {
        $data_info["g_picarr"]   = $data["g_picarr"];
        $data_info["g_content"]  = $data["g_content"];
        $cloud["price"]          = $data["yunjiage"];
        $cloud["maxqishu"]       = $data["maxqishu"];
        $cloud["xsjx_time"]      = $data["xsjx_time"];
        $cloud["xsjx_diff_time"] = $data["xsjx_diff_time"];
        $cloud["zongrenshu"]     = $data["zongrenshu"];
        $cloud["shenyurenshu"]   = $data["shenyurenshu"];
        $cloud["is_virtual"]     = $data["is_virtual"];
        $cloud["shop_id"]        = $data["shop_id"];
        
        unset($data["g_picarr"]);
        unset($data["g_content"]);
        unset($data["yunjiage"]);
        unset($data["maxqishu"]);
        unset($data["xsjx_time"]);
        unset($data["xsjx_diff_time"]);
        unset($data["zongrenshu"]);
        unset($data["shenyurenshu"]);
        $this->sql_begin();
        $gid = $this->Insert("goods", $data);
        $data_info["gid"] = $gid;
        $res = $this->Insert("goods_info", $data_info);
        $cloud["gid"] = $gid;
        $num = ceil( $cloud["zongrenshu"] / 3000 );
        $table = $this->get_codes_table( $num, 1 );
        $cloud["codes_table"] = $table;
        $cloud["time"]        = time();
        $rx = $this->Insert( "cloud_goods", $cloud );
        $rr = $this->create_cloud_codes( $cloud["zongrenshu"], 3000, $rx );
        if ( $res && $rx && $gid && $rr ) {
            $this->sql_commit();
            return $rx;
        }
        else {
            $this->sql_rollback();
            return false;
        }
    }

    /**
     * 生成下一期云购商品
     */
    public function cloud_goods_next( $data )
    {
        $this->sql_begin();
        $num                       = ceil( $data["zongrenshu"] / 3000 );
        $table                     = $this->get_codes_table( $num, 1 );
        $data["codes_table"]       = $table;
        $newdata                   = array();
        $newdata["gid"]            = $data["gid"];
        $newdata["price"]          = $data["price"];
        $newdata["zongrenshu"]     = $data["zongrenshu"];
        $newdata["canyurenshu"]    = "0";
        $newdata["shenyurenshu"]   = $data["zongrenshu"];
        $newdata["qishu"]          = intval($data["qishu"]);
        $newdata["qishu"]++;
        $newdata["maxqishu"]       = $data["maxqishu"];
        $newdata["codes_table"]    = $table;
        $newdata["record_table"]   = $data["record_table"];
        $newdata["xsjx_time"]      = $data["xsjx_time"] + ($data["xsjx_diff_time"] * 60);
        $newdata["xsjx_diff_time"] = $data["xsjx_diff_time"];
        $newdata["is_virtual"]     = $data["is_virtual"];
        $newdata["shop_id"]        = $data["shop_id"];
        $newdata["time"]           = time();
        $newdata["q_showtime"]     = "N";
        $rx = $this->Insert( "cloud_goods", $newdata );
        $rr = $this->create_cloud_codes( $data["zongrenshu"], 3000, $rx );
        if ( $rx && $rr )
        {
            $this->sql_commit();
            return $rx;
        }
        else
        {
            $this->sql_rollback();
            return false;
        }
    }

    /**
     * 云购商品修改
     * goods  goods_info  cloud_goods  操作3个表
     */
    public function cloud_goods_save( $data, $where )
    {
        $data_info["g_picarr"]   = $data["g_picarr"];
        $data_info["g_content"]  = $data["g_content"];
        $cloud["price"]          = $data["yunjiage"];
        $cloud["maxqishu"]       = $data["maxqishu"];
        $cloud["xsjx_time"]      = $data["xsjx_time"];
        $cloud["xsjx_diff_time"] = $data["xsjx_diff_time"];
        $cloud["is_virtual"]     = $data["is_virtual"];
        $cloud["shop_id"]        = $data["shop_id"];
        unset( $data["g_picarr"] );
        unset( $data["g_content"] );
        unset( $data["yunjiage"] );
        unset( $data["maxqishu"] );
        unset( $data["xsjx_time"] );
        unset( $data["xsjx_diff_time"] );
        unset( $data["zongrenshu"] );
        $this->sql_begin();
        $gid  = $this->Update( "goods", $data, $where );
        $res  = $this->Update( "goods_info", $data_info, $where );
        $resx = $this->Update( "cloud_goods", $cloud, $where );
        if ( ($res !== false) && ($gid !== false) && ($resx !== false) )
        {
            $this->sql_commit();
            return true;
        }
        else
        {
            $this->sql_rollback();
            return false;
        }
    }

    public function reset_money( $data, $gid )
    {
        unset($data["dosubmit"]);
        $cloud["price"]        = $data["price"];
        $cloud["zongrenshu"]   = $data["zongrenshu"];
        $cloud["shenyurenshu"] = $data["shenyurenshu"];
        unset($data["price"]);
        unset($data["zongrenshu"]);
        unset($data["shenyurenshu"]);
        $this->sql_begin();
        $where1 = " gid='" . $gid . "'";
        $where2 = "SELECT MAX(qishu) as qishu,id FROM `@#_cloud_goods` WHERE `gid`='$gid'";
        $tmp = $this->GetOne( $where2 );
        $reset_shopid = $tmp["qishu"];
        $where = "SELECT id FROM `@#_cloud_goods` WHERE gid='" . $gid . "' AND `qishu`='" . $reset_shopid . "'";
        $tmpid = $this->GetOne( $where );
        $where3 = " gid='" . $gid . "' AND `qishu`='" . $reset_shopid . "'";
        $r = $this->Update( "goods", $data, $where1 );
        /* 删除夺宝码 + 重新生成夺宝码 */
        $res = $this->del_cloud_codes( $tmpid["id"] );
        $rs = $this->create_cloud_codes( $cloud["zongrenshu"], 3000, $tmpid["id"] );
        $pre = _db_cfg("tablepre");
        $cloud["codes_table"] = str_replace($pre, "", $rs);
        $resx = $this->Update( "cloud_goods", $cloud, $where3 );
        if ( ($res !== false) && ($rs !== false) && ($resx !== false) && ($r !== false) )
        {
            $this->sql_commit();
            return true;
        }
        else
        {
            $this->sql_rollback();
            return false;
        }
    }

    public function del_cloud_goods()
    {
    }

    public function del_cloud_record()
    {
    }

    /* 删除夺宝码 */
    public function del_cloud_codes( $gid )
    {
        dump( '删除夺宝码' );
        dump( $gid );
        $sql = "DELETE FROM `@#_cloud_codes_1` where `cg_id`='" . $gid . "'";
        return $this->Delete( $sql );
    }

    public function get_cloud_codes($gid)
    {
    }

    /**
     * 生成云购码
     */
    public function create_cloud_codes($CountNum = NULL, $len = 3000, $sid = NULL)
    {
        $num = ceil( $CountNum / $len );
        $code_i = $CountNum;
        $table  = $this->get_codes_table( $num );
        $sql    = "INSERT INTO " . $table . " (`cg_id`, `cg_cid`, `cg_len`, `cg_codes`,`cg_codes_tmp`) VALUES";
        $fl     = 0;

        for ( $i = 1; $i <= $num; $i++ )
        {
            $val_str = "";

            for ( $j = 1; $j <= $len; $j++ ) {
                $tmp[$code_i] = $code_i;
                $code_i--;

                if ( $code_i <= 0 ) {
                    break;
                }
            }

            shuffle( $tmp );
            $codes     = serialize( $tmp );
            $count_num = count( $tmp );
            $val_str = "('" . $sid . "', '" . $i . "','" . $count_num . "','" . $codes . "','" . $codes . "')";
            unset( $tmp );
            $query = $this->Query( $sql . $val_str );

            if ( $query )
            {
                $fl++;
            }
        }

        if ( $fl == $num ) {
            return $table;
        }

        return false;
    }

    public function show_table()
    {
        echo $this->get_codes_table();
    }

    /**
     * 云购码 存放表名
     */
    public function get_codes_table($num = 0, $type = 0)
    {
        $table = _db_cfg("database");
        $pre   = _db_cfg("tablepre");
        $field = "Tables_in_" . $table;
        $arr = $this->GetList("SHOW TABLES FROM " . $table . " WHERE " . $field . " LIKE '" . $pre . "cloud_codes_%';");
        sort( $arr );
        $last_table_arr = end( $arr );
        $last_table = $last_table_arr[$field];

        if ( 0 < $num ) {
            $sql = "SELECT count(*) as num FROM " . $last_table . "";
            $res = $this->GetOne( $sql );
            if ( (100000 - $res["num"]) < $num ) {
                $t_arr = explode( "_", $last_table );
                $t_arr[count($t_arr) - 1]++;
                $last_table = implode( "_", $t_arr );
            }
        }

        if ( $type == 1 ) {
            $last_table = str_replace( $pre, "", $last_table );
        }

        return $last_table;
    }

    public function brand_add( $data = array() )
    {
        return $this->Insert( "brand", $data );
    }

    public function brand_save( $where, $data = array() )
    {
        return $this->Update( "brand", $data, $where );
    }

    public function get_brand_num( $where = array() )
    {
        $sql = "SELECT count(id) as num FROM `@#_brand`";

        if ( ! empty( $where ) ) {
            $sql .= " WHERE " . $where;
        }

        $tmp = $this->GetOne( $sql );
        return $tmp["num"];
    }

    public function get_brand($where = "", $field = "*", $order = "", $num = "")
    {
        if ( empty( $field ) ) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_brand`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if ( ! empty( $num ) && (strpos( $num, "," ) <= 0 )) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList( $sql );

        if ( ! $res ) {
            return false;
        }

        return $res;
    }

    public function get_brand_one($where, $field = "*")
    {
        $sql = "SELECT " . $field . " FROM `@#_brand` ";

        if ( ! empty( $where ) ) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne( $sql );

        if ( ! $res ) {
            return false;
        }

        return $res;
    }

    public function brand_del( $where )
    {
        $sql = "DELETE FROM `@#_brand` WHERE " . $where;
        return $this->Delete( $sql );
    }

    public function brand_sort( $data )
    {
        $i = false;

        foreach ( $data as $k => $v ) {
            $res = $this->Update( "brand", array("sort" => $v), "id=" . $k );

            if ( $res ) {
                $i = true;
            }
        }

        return $i;
    }

    /**
     * 获取列表
     */
    public function get_list( $table, $where, $field, $order = '', $num = '' )
    {
        return $this->data_list( $table, $where, $field, $order, $num );
    }

    /**
     * 获取单条信息
     */
    public function get_one( $table, $where, $field = '' )
    {
        return $this->data_one( $table, $where, $field );
    }
}

System::load_sys_class("model", "sys", "no");

?>