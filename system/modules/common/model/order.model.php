<?php
class order_model extends model
{
    public function get_order($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("orders", $where, $field, $order, $num);
        return $res;
    }

    public function get_order_by_goods($where = "", $order = "", $num = "")
    {
        $sql = "SELECT a.*,b.* FROM go_user_record a LEFT JOIN go_orders b ON a.oid=b.oid";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if ( ! empty( $num ) && (strpos( $num, "," ) <= 0) )
        {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList( $sql );
        if ( ! $res )
        {
            return false;
        }
        return $res;
        $res = $this->data_list( "orders", $where, $field, $order, $num );
        return $res;
    }

    public function save_order( $where, $data )
    {
        return $res = $this->Update( "orders", $data, $where);
    }

    public function get_order_num($where)
    {
        $res = $this->data_num("orders", $where);
        return $res;
    }

    public function get_order_one($where, $field = "*")
    {
        $res = $this->data_one("orders", $where, $field);
        return $res;
    }

    public function get_order_info($where)
    {
        $res = $this->data_one("orders_info", $where, "otext");
        return $res;
    }

    public function get_order_sum($where, $field)
    {
        $res = $this->data_sum("orders", $where, $field);
        return $res;
    }

    /**
     * 获取总和
     */
    public function get_sum( $table, $where, $field )
    {
        $res = $this->data_sum( $table, $where, $field );
        return $res;
    }

    /**
     * 设置发货
     */
    public function send_goods( $data )
    {
        $this->sql_begin();
        $res = $this->Update("cloud_select", array("ofstatus" => $data["ofstatus"]), "`oid`='" . $data["oid"] . "'");
        if ( $res !== false && ($data["ofstatus"] == 2) )
        {
            unset( $data["ofstatus"] );
            $ship = $this->get_ship_one("`oid`='" . $data["oid"] . "' and `etype`='" . $data["etype"] . "'");
            if ( $ship )
            {
                $oid   = $data["oid"];
                $etype = $data["etype"];
                unset( $data["oid"] );
                unset( $data["etype"] );
                $rs = $this->Update("ship", $data, "`oid`='" . $oid . "' and `etype`='" . $etype . "'");
            }
            else
            {
                $rs = $this->Insert( "ship", $data );
            }

            if ( $rs !== false )
            {
                $this->sql_commit();
            }
            else
            {
                $this->sql_rollback();
            }

            return $rs;
        }
        else
        {
            if ( $res )
            {
                $this->sql_commit();
            }
            else
            {
                $this->sql_rollback();
            }

            return $res;
        }
    }

    public function get_ems($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("ems", $where, $field, $order, $num);
        return $res;
    }

    public function get_ems_one($where, $field = "*")
    {
        $res = $this->data_one("ems", $where, $field);
        return $res;
    }

    public function get_ship_one($where, $field = "*")
    {
        $res = $this->data_one("ship", $where, $field);
        return $res;
    }

    public function get_AI_ID()
    {
        return $this->Insert("cloud_id", array("id" => ""));
    }

    private function create_record_table()
    {
        $pre        = _db_cfg("tablepre");
        $curr_table = $this->get_record_table();
        $info       = $this->GetOne("show table status like '" . $pre . "cloud_id'");
        $aid        = $info["Auto_increment"];
        $m_id       = floor($aid / 100000);

        if ( 0 < $m_id )
        {
            $bool = $this->db->table_exists($pre . "cloud_order_" . $m_id);

            if ( ! $bool )
            {
                $res = $this->create_index_table("order", $pre . "cloud_order_" . $m_id);
                if ( $res )
                {
                    $this->write_table_record($curr_table, $pre . "cloud_order_" . $m_id, $aid);
                }

                return $res;
            }
        }
    }

    private function get_engine_table($t = 0, $is_pre = 1)
    {
        $pre = _db_cfg("tablepre");
        $data = $this->db->list_tables("cloud_order");
        sort( $data );
        $table = "";

        foreach ( $data as $v )
        {
            if ( $is_pre == 0 )
            {
                $v = str_replace( $pre, "", $v );
            }

            $v = "`" . $v . "`";
            $table .= ($table == "" ? $v : "," . $v);
        }

        $t_table = "cloud_select";

        if ( $is_pre == 1 )
        {
            $t_table = $pre . "cloud_select";
        }

        if ( $t == 1 )
        {
            return $t_table;
        }

        $table = $pre . $table;
        return array("t" => $t_table, "list" => $table);
    }

    private function get_engine_table_i($t = 0, $is_pre = 1)
    {
        $pre = _db_cfg("tablepre");
        $data = $this->db->list_tables("cloud_info");
        sort( $data );
        $table = "";

        foreach ( $data as $v )
        {
            if ( $is_pre == 0 )
            {
                $v = str_replace( $pre, "", $v );
            }

            $v = "`" . $v . "`";
            $table .= ($table == "" ? $v : "," . $v);
        }

        $t_table = "cloud_select_i";

        if ( $is_pre == 1 )
        {
            $t_table = $pre . "cloud_select_i";
        }

        if ( $t == 1 )
        {
            return $t_table;
        }

        $table = $pre . $table;
        return array("t" => $t_table, "list" => $table);
    }

    private function write_table_record($curr_table, $next_table, $m_id)
    {
        if ( $curr_table == "cloud_order" )
        {
            $res = $this->Insert("order_table", array("table_name" => $curr_table, "start_time" => 0, "end_time" => time(), "start_id" => 1, "end_id" => $m_id - 1));
            $rs = $this->Insert("order_table", array("table_name" => $next_table, "start_time" => time(), "start_id" => $m_id));
        }
        else
        {
            $res = $this->Update("order_table", array("end_time" => time(), "end_id" => $m_id - 1), "`table_name`='" . $curr_table . "'");
            $rs = $this->Insert("order_table", array("table_name" => $next_table, "start_time" => time(), "start_id" => $m_id));
        }

        return $res && $rs;
    }

    private function create_index_table( $type, $table_name )
    {
        if ( $type == "order" )
        {
            $table_name_info = str_replace( "order", "info", $table_name );
        }

        $sql_order = "CREATE TABLE `" . $table_name . "` (\r\n              `oid` int(10) unsigned NOT NULL,\r\n\t\t\t  `ogid` int(11) DEFAULT NULL COMMENT '订单类型(1充值订单,2商品订单,3夺宝订单,其他)',\r\n\t\t\t  `og_title` varchar(200) DEFAULT NULL,\r\n\t\t\t  `oqishu` smallint(6) DEFAULT NULL,\r\n\t\t\t  `ouid` int(10) unsigned NOT NULL COMMENT '会员id',\r\n\t\t\t  `ou_name` varchar(50) DEFAULT NULL,\r\n\t\t\t  `ocode` char(20) NOT NULL COMMENT '订单号',\r\n\t\t\t  `omoney` decimal(10,2) unsigned NOT NULL COMMENT '订单金额',\r\n\t\t\t  `ofstatus` tinyint(4) DEFAULT '0' COMMENT '发货状态(1未发货,2已发货,3已收货)',\r\n\t\t\t  `ostatus` tinyint(4) DEFAULT NULL COMMENT '付款状态(1未付款,2已付款,3退款中.4退款成功)',\r\n\t\t\t  `opay` char(10) NOT NULL COMMENT '支付方式',\r\n\t\t\t  `owin` varchar(50) DEFAULT NULL,\r\n\t\t\t  `onum` int(5) DEFAULT NULL COMMENT '购买次数',\r\n\t\t\t  `oip` varchar(20) DEFAULT NULL COMMENT '订单IP',\r\n\t\t\t  `otime` decimal(13,3) DEFAULT NULL,\r\n\t\t\t  PRIMARY KEY (`oid`),\r\n\t\t\t  KEY `ogid` (`ogid`),\r\n\t\t\t  KEY `ouid` (`ouid`)\r\n            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
        $sql_order_info = "CREATE TABLE `" . $table_name_info . "` (\r\n              `oid` int(11) unsigned NOT NULL,\r\n\t\t\t  `ogid` int(11) DEFAULT NULL,\r\n\t\t\t  `ogocode` longtext,\r\n\t\t\t  `oremark` varchar(500) DEFAULT NULL,\r\n\t\t\t  `otext` text COMMENT '订单附加信息',\r\n\t\t\t  KEY `oid` (`oid`) USING BTREE\r\n            ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单附表';";
        $sql_cloud_u = "CREATE TABLE `" . $table_name . "` (\r\n              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,\r\n              `uid` int(11) DEFAULT NULL,\r\n              `gid` int(11) DEFAULT NULL,\r\n              `oid` int(11) DEFAULT NULL,\r\n              PRIMARY KEY (`id`)\r\n            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";
        $sql_cloud_g = "CREATE TABLE `" . $table_name . "` (\r\n              `id` int(11) unsigned NOT NULL AUTO_INCREMENT,\r\n              `uid` int(11) DEFAULT NULL,\r\n              `gid` int(11) DEFAULT NULL,\r\n              `oid` int(11) DEFAULT NULL,\r\n              PRIMARY KEY (`id`)\r\n            ) ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;";

        if ( $type == "order" )
        {
            $rs = $this->query( $sql_order ) && $this->query( $sql_order_info );
            $tmp = $this->get_engine_table( 0, 1 );
            $sql_engine = "alter table `" . $tmp["t"] . "` engine=mrg_myisam  insert_method=last union=(" . $tmp["list"] . ");";
            $tmp_i = $this->get_engine_table_i( 0, 1 );
            $sql_engine_i = "alter table `" . $tmp_i["t"] . "` engine=mrg_myisam  insert_method=last  union=(" . $tmp_i["list"] . ");";
            $res = $this->query( $sql_engine ) && $this->query( $sql_engine_i );
            $res = $res && $rs;
        }
        else if ( $type == "goods" )
        {
            $res = $this->query( $sql_cloud_g );
        }
        else if ( $type == "user" )
        {
            $res = $this->query( $sql_cloud_u );
        }

        return $res;
    }

    private function get_record_table()
    {
        $table = "";
        $data = $this->db->list_tables("cloud_order");

        if ( count( $data ) == 1 )
        {
            $table = $data[0];
        }
        else if ( 1 < count( $data ) )
        {
            sort( $data );
            $table = $data[count($data) - 1];
        }

        return ltrim($table, _db_cfg("tablepre"));
    }

    private function get_order_table($type, $id)
    {
        $table = "";

        if ( $type == "user" )
        {
            $table = "cloud_u";
            $sql   = "SELECT record_table FROM `@#_user` WHERE `uid`='" . $id . "'";
        }
        else
        {
            $table = "cloud_g";
            $sql   = "SELECT record_table FROM `@#_cloud_goods` WHERE `id`='" . $id . "'";
        }

        $info = $this->GetOne( $sql );
        if ( $info && ! empty( $info["record_table"] ) )
        {
            return trim( $info["record_table"], _db_cfg("tablepre") );
        }
        else
        {
            return $table;
        }
    }

    /**
     * 计算商品表名
     */
    public function goods_table( $id, $num = "" )
    {
        $pre = _db_cfg("tablepre");

        if ( empty( $num ) )
        {
            $sql = "SELECT zongrenshu FROM " . $pre . "goods WHERE id='" . $id . "' ";
            $arr = $this->GetOne( $sql );
            $num = $arr["zongrenshu"];
        }

        if ( 10000 < $num )
        {
            $info = $this->create_index_table( "goods", $pre . "cloud_g_" . $id );
            if ( $info )
            {
                $sql = "UPDATE " . $pre . "cloud_goods SET `record_table`='cloud_g_" . $id . "' WHERE id='" . $id . "'";
                $res = $this->query( $sql );
                return "cloud_g_" . $id;
            }
            else
            {
                return "cloud_g";
            }
        }
        else
        {
            return "cloud_g";
        }
    }

    private function user_table( $uid )
    {
        $pre = _db_cfg("tablepre");
        $sql = "SELECT COUNT(id) AS num FROM " . $pre . "cloud_u WHERE `uid`='" . $uid . "'";
        $info = $this->GetOne( $sql );
        if ( 10000 <= $info["num"] )
        {
            $res = $this->create_index_table( "user", $pre . "cloud_u_" . $uid );
            if ( $res )
            {
                $sql = "INSERT INTO " . $pre . "cloud_u_" . $uid . "(uid,gid,oid) SELECT uid,gid,oid FROM " . $pre . "cloud_u WHERE uid='" . $uid . "'";
                $r = $this->Query( $sql );
                $rr = $this->data_del( "cloud_u", "`uid`='" . $uid . "'" );
                $rs = $this->Update( "user", array("record_table" => "cloud_u_" . $uid), "uid='" . $uid . "'");
                return $pre . "cloud_u_" . $uid;
            }
            else
            {
                return "cloud_u";
            }
        }
    }

    /**
     * 生成订单
     */
    public function write_order( $data )
    {
        $oid = $this->get_AI_ID();
        $rr  = $this->create_record_table();
        $record_table = $this->get_record_table();
        $user_table   = $this->get_order_table("user", $data["ouid"]);
        $goods_table  = $this->get_order_table("goods", $data["ogid"]);
        $data["oid"]     = $oid;
        $info["ogid"]    = $data["ogid"];
        $info["ogocode"] = $data["ogocode"];
        $info["oremark"] = $data["oremark"];
        $info["otext"]   = $data["otext"];
        unset( $data["ogocode"] );
        unset( $data["oremark"] );
        unset( $data["otext"] );
        $select_res = $this->Insert( "cloud_select", $data );
        $info["oid"] = $oid;
        if ( $select_res )
        {
            $select_i_res  = $this->Insert( "cloud_select_i", $info );
        }
        $res = $this->Insert( $user_table, array("uid" => $data["ouid"], "gid" => $data["ogid"], "oid" => $oid) );
        $rs  = $this->Insert( $goods_table, array("uid" => $data["ouid"], "gid" => $data["ogid"], "oid" => $oid) );
        $this->create_record_table();
        $this->goods_table( $data["ogid"] );
        $this->user_table( $data["ouid"] );
        if ( $select_res && $select_i_res && $res && $rs )
        {
            return $oid;
        }
        else
        {
            return false;
        }
    }

    /**
     * 第三方支付订单写入
     * @author Yusure  http://yusure.cn
     * @date   2015-10-28
     * @param  [param]
     * @return [type]     [description]
     */
    public function write_third_order( $data )
    {
        $this->Insert( "third_order", $data, 'third' );
    }

    /**
     * 付款成功通知，更新订单状态
     * @author Yusure  http://yusure.cn
     * @date   2015-11-05
     * @param  [param]
     * @return [type]     [description]
     */
    public function update_duobao_order( $pay_sn )
    {
        if ( ! $pay_sn )
        {
            return false;
        }
        
        $userpay_clouddb = System::load_app_model("UserPay_cloud", "common");
        $fufen = System::load_sys_config("user_fufen");
        $member_model = System::load_app_model("member", "common");
        $userpaydb = System::load_app_model("UserPay", "common");
        
        $third_order_list = $this->GetList("SELECT * from `@#_third_order` where `pay_sn` = '$pay_sn'");

        if ( $third_order_list[0]['ostatus'] == 2 )
        {
            return true;
        }
        $uid = $third_order_list[0]['ouid'];
        $user_where = "uid = $uid";
        $this->members = $member_model->get_user_one( $user_where, 'uid, jingyan, score' );

        /* 商品总数量初始化 */
        $goods_count_num = 0;
        foreach ( $third_order_list as $key => $shop )
        {
            $goods_count_num += $shop['onum'];
            $shop['id'] = $shop['ogid'];
            $ret_data = array();
            pay_get_shop_codes($shop["onum"], $shop, $ret_data);
            
            $orderinfo = array();
            $orderinfo["ostatus"]  = "2";
            $orderinfo["opay"]     = $shop['opay'];
            $orderinfo["otime"]    = $shop['otime'];
            $orderinfo["ouid"]     = $shop['ouid'];
            $orderinfo["ou_name"]  = $shop['ou_name'];
            $orderinfo["ogid"]     = $shop['ogid'];
            $orderinfo["og_title"] = $shop['og_title'];
            $orderinfo["oqishu"]   = $shop["oqishu"];
            $orderinfo["oip"]      = $shop['oip'];
            $orderinfo["ocode"]    = $shop['ocode'];
            $orderinfo["onum"]     = $shop['onum'];
            $orderinfo["ogocode"]  = $ret_data["user_code"];
            $orderinfo["omoney"]   = $shop['omoney'];           

            $InsertOrders = $this->write_order( $orderinfo );

            $scgsql = "SELECT
                        zongrenshu,
                        canyurenshu,
                        shenyurenshu,
                        qishu,
                        maxqishu,
                        xsjx_time,
                        gid
                    FROM
                        `@#_cloud_goods`
                    WHERE
                        `id` = $shop[ogid]
                    ORDER BY
                        `qishu` DESC
                    LIMIT 1";
            $clouddb_res = $this->GetOne($scgsql);

            /* 修改临时表状态 */
            $this->Query( "UPDATE `@#_third_order` SET `ostatus` = 2 where (`pay_sn` = '" . $pay_sn . "')" );

            /* 参与，剩余 人数处理 */
            if (($clouddb_res["zongrenshu"] <= $clouddb_res["canyurenshu"]) && ($clouddb_res["qishu"] <= $clouddb_res["maxqishu"])) {
                $clouddb_res['canyurenshu'] = $clouddb_res["zongrenshu"];
                $update_cgoods = "`canyurenshu`=`zongrenshu`,`shenyurenshu` = '0' where `id` = '{$shop["id"]}'";
                $userpay_clouddb->UpdateCgoods($update_cgoods);
            }
            else {
                $clouddb_res["canyurenshu"] = $clouddb_res["canyurenshu"] + $shop["onum"];
                $shenyurenshu = $clouddb_res["zongrenshu"] - $clouddb_res["canyurenshu"];
                $update_cgoods = "`canyurenshu` = '{$clouddb_res["canyurenshu"]}',`shenyurenshu` = '$shenyurenshu' WHERE `id`='{$shop["id"]}'";
                $query = $userpay_clouddb->UpdateCgoods($update_cgoods);
            }

            $shop['xsjx_time']   = $clouddb_res['xsjx_time'];
            $shop['canyurenshu'] = $clouddb_res['canyurenshu'];
            $shop['qishu']       = $clouddb_res['qishu'];
            $shop['maxqishu']    = $clouddb_res['maxqishu'];
            $shop['gid']         = $clouddb_res['gid'];

            /* 开奖处理 */
            if ( ($clouddb_res["zongrenshu"] <= $clouddb_res["canyurenshu"]) && ($clouddb_res["qishu"] <= $clouddb_res["maxqishu"]) )
            {
                $this->db->sql_begin();
                $loconfig = System::load_sys_config("lotteryway");
                $userpaydb->UpdateUserInfo( $uid, $setwords );
                $json_shop = json_encode($shop);
                $json_shop = base64_encode($json_shop);
                $post_arr = array( "shop" => $json_shop, "lotteryway" => $loconfig["lotteryway"]["opennow"] );
                _g_triggerRequest( WEB_PATH . "/plugin-CloudWay-optway", false, $post_arr );
                if ( ! $query_insert )
                {
                    $this->db->sql_rollback();
                }
                else {
                    $this->db->sql_commit();
                }
            }
        }

        /* 福分 */
        $mygoscore = $fufen["f_shoppay"] * $goods_count_num;
        $myscore = $this->members["score"] + $mygoscore;
        $setwords = "`score`='$myscore'";
        $query_fufen = $userpaydb->UpdateUserInfo($uid, $setwords);

        /* 经验 */
        $jingyan = $this->members["jingyan"] + ($fufen["z_shoppay"] * $goods_count_num);
        $setwords = "`jingyan`='$jingyan'";
        $query_jingyan = $userpaydb->UpdateUserInfo($uid, $setwords);
        
        if ( $InsertOrders )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    public function ready_order( $where, $type = 1, $field = "", $order = "", $num = 0 )
    {
        if ( $type == 1 )
        {
            $table = $this->get_engine_table(1, 0);
            $data  = $this->data_list($table, $where, $field, $order, $num);
        }
        else if ( $type == 2 )
        {
            $table = $this->get_order_table("user", $where);
            $data  = $this->data_list($table, "`uid`='" . $where . "'", "", "", $num);
        }
        else if ( $type == 3 )
        {
            $table = $this->get_order_table( "goods", $where );
            $data  = $this->data_list( $table, "`gid`='" . $where . "'", "", "", $num );
        }

        if ( $type != 1 )
        {
            foreach ( $data as &$row )
            {
                $field = "ouid,ogid,og_title,oqishu,ou_name,ocode,omoney,ofstatus,ostatus,owin,onum,oip,otime";
                $info = $this->get_one_order( $row["oid"], $field );
                $row["ouid"]     = $info["ouid"];
                $row["ogid"]     = $info["ogid"];
                $row["og_title"] = $info["og_title"];
                $row["oqishu"]   = $info["oqishu"];
                $row["ou_name"]  = $info["ou_name"];
                $row["ocode"]    = $info["ocode"];
                $row["omoney"]   = $info["omoney"];
                $row["ofstatus"] = $info["ofstatus"];
                $row["ostatus"]  = $info["ostatus"];
                $row["owin"]     = $info["owin"];
                $row["onum"]     = $info["onum"];
                $row["oip"]      = $info["oip"];
                $row["otime"]    = $info["otime"];
            }
        }

        return $data;
    }

    public function update_order( $where, $data )
    {
        return $this->Update( "cloud_select", $data, $where );
    }

    public function ready_order_num( $where, $type = 1 )
    {
        if ( $type == 1 )
        {
            $table = $this->get_engine_table( 1, 0 );
            $data  = $this->data_num( $table, $where );
        }
        else if ( $type == 2 )
        {
            $table = $this->get_order_table( "user", $where );
            $data  = $this->data_num( $table, "`uid`='" . $where . "'" );
        }
        else if ( $type == 3 )
        {
            $table = $this->get_order_table( "goods", $where );
            $data  = $this->data_num( $table, "`gid`='" . $where . "'" );
        }
        else if ( $type == 4 )
        {
            $table = "cloud_order";
            $data  = $this->data_num( $table, $where );
        }

        return $data;
    }

    private function get_table($in)
    {
        if ( is_numeric( $in ) )
        {
            $sql = "SELECT table_name FROM `@#_order_table` WHERE `start_id` < " . $in . " AND `end_id` > " . $in;
        }
        else
        {
            $time = substr($in, 1, strlen($in) - 8);
            $sql = "SELECT table_name FROM `@#_order_table` WHERE `start_time` < " . $time . " AND `end_time` > " . $time;
        }

        $info = $this->GetOne( $sql );
        if ( $info && (0 < count($info)) )
        {

        }

        return "cloud_order";
    }

    public function get_one_order( $id, $field_a, $field_b = "" )
    {
        $data_a = $this->data_one( "cloud_select", "`oid`='" . $id . "'", $field_a );

        if ( ! empty( $field_b ) )
        {
            $data_b = $this->data_one("cloud_select_i", "`oid`='" . $id . "'", $field_b);

            if ( $data_b )
            {
                return array_merge( $data_a, $data_b );
            }
            else
            {
                return $data_a;
            }
        }
        else
        {
            return $data_a;
        }
    }

    /**
     * 管理员充值金额
     */
    public function user_add_chongzhi( $uid, $money, $text )
    {
        $time = time();
        $sql = "INSERT INTO `@#_orders` (`otype`, `ouid`, `ocode`, `omoney`, `ofstatus`, `ostatus`, `opay`, `oremark`, `otime`) VALUES ('4', '$uid', '0', '$money', '3', '2', '管理员修改', '$text', '$time')";
        return $this->Query( $sql );
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
 
    /**
     * 添加数据
     */
    public function insert_data( $table, $data )
    {
        return $this->Insert( $table, $data );
    }
}