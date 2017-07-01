<?php

class index_model extends model
{
    public function init()
    {
    }

    public function search_goods( $search = "" )
    {
        $area_id = _getcookie( 'area_id' ) ? : _cfg( 'default_area_id' );
        $area_id = intval( $area_id );
        $search_sql = "SELECT gid FROM `@#_goods` WHERE (`g_title` LIKE '%" . $search . "%' OR `g_keyword` LIKE '%" . $search . "%') AND `area_id`= {$area_id}";
        $shoplist = $this->GetList( $search_sql );
        $shopids = "";

        foreach ( $shoplist as $key => $v ) {
            $shopids .= $v["gid"] . ",";
        }

        $shopids = trim( $shopids, "," );
        $sshoplist = $this->GetList("SELECT * FROM `@#_cloud_goods` as a left join `@#_goods` as b on a.gid=b.gid where a.gid in (" . $shopids . ") AND `shenyurenshu` > '0' AND `q_uid` is null");
        return $sshoplist;
    }

    public function get_admin_group($gid)
    {
        if (empty($gid)) {
            return false;
        }

        return $this->GetOne("select * from `@#_admin_group` WHERE `gid`='" . $gid . "'");
    }

    public function get_admin_menu($ids)
    {
        if (empty($ids)) {
            return false;
        }

        $sql = "select * from `@#_ments` WHERE `type`=1 and `id` in(" . $ids . ")";

        if ($ids == "all") {
            $sql = "select * from `@#_ments`  WHERE `type`=1";
        }

        return $this->GetList($sql);
    }

    public function sel_notice()
    {
        $sql = "select id,cateid,title,posttime from @#_article WHERE cateid=23 ORDER  by posttime desc limit 4";
        return $this->GetList($sql);
    }

    public function findcate($wherewords = "")
    {
        $fsql = "SELECT * FROM `@#_cate` where  $wherewords LIMIT 1";
        return $this->GetOne($fsql);
    }

    public function goods_sales_top()
    {
        $m_start = strtotime(date("Y-m") . "-01 00:00:00");
        $sql = "select q_uid,gid,sum(canyurenshu) as a_sum,count(gid) as b_num from `@#_cloud_goods`  where `q_uid` is not NULL and `q_end_time`>=" . $m_start . " group by gid order by a_sum desc  limit 10";
        $data = $this->GetList($sql);
        if (is_array($data) && (0 < count($data))) {
            foreach ($data as &$row ) {
                $tmp = $this->GetOne("select g_title from `@#_goods` where `gid`='" . $row["gid"] . "'");
                $row["title"] = $tmp["g_title"];
            }
        }

        return $data;
    }

    public function web_acc()
    {
        $web_acc["cate"]["name"] = "栏目";
        $data = $this->GetOne("select count(cateid) as num from `@#_cate` where `model`='1'");
        $web_acc["cate"]["val"] = $data["num"];
        $web_acc["brand"]["name"] = "品牌";
        $data = $this->GetOne("select count(id) as num from `@#_brand` ");
        $web_acc["brand"]["val"] = $data["num"];
        $web_acc["article"]["name"] = "文章";
        $data = $this->GetOne("select count(cateid) as num from `@#_article`");
        $web_acc["article"]["val"] = $data["num"];
        $web_acc["goods"]["name"] = "商品数量";
        $data = $this->GetOne("select count(gid) as num from `@#_goods`");
        $web_acc["goods"]["val"] = $data["num"];
        $web_acc["time"]["name"] = "限时揭晓";
        $data = $this->GetOne("select count(id) as num from `@#_cloud_goods` where q_uid is null and xsjx_time=0");
        $web_acc["time"]["val"] = $data["num"];
        $web_acc["member"]["name"] = "会员人数";
        $data = $this->GetOne("select count(uid) as num from `@#_user`");
        $web_acc["member"]["val"] = $data["num"];
        $today_time = strtotime(date("Y-m-d") . " 00:00:00");
        $web_acc["today_member"]["name"] = "今日新增会员";
        $data = $this->GetOne("select count(uid) as num from `@#_user` where `time`>=" . $today_time . "");
        $web_acc["today_member"]["val"] = $data["num"];
        $web_acc["today_goods"]["name"] = "今日新增商品";
        $data = $this->GetOne("select count(gid) as num from `@#_goods` where `g_add_time`>=" . $today_time . "");
        $web_acc["today_goods"]["val"] = $data["num"];
        $web_acc["today_money"]["name"] = "今日新增收入";
        $data = $this->GetOne("select sum(omoney) as num from `@#_orders` where `otime`>=" . $today_time . " and `otype`=3 and ostatus=2");
        $web_acc["today_money"]["val"] = (!empty($data["num"]) ? $data["num"] : 0);
        return $web_acc;
    }

    public function sales_list()
    {
        $m_start = strtotime(date("Y-m") . "-01 00:00:00");
        $sql = "select  FROM_UNIXTIME(otime,'%d') as d,sum(omoney) as a_sum,count(oid) as b_num from go_orders where `otime` is not null and `otime`>" . $m_start . " GROUP BY d";
        $data = $this->GetList($sql);
        $acc["x"] = "";
        $acc["sales"] = "";
        $acc["order"] = "";
        if (is_array($data) && (0 < count($data))) {
            foreach ($data as $row ) {
                $acc["x"] .= ($acc["x"] == "" ? "'" . $row["d"] . "'" : ",'" . $row["d"] . "'");
                $acc["sales"] .= ($acc["sales"] == "" ? $row["a_sum"] : "," . $row["a_sum"]);
                $acc["order"] .= ($acc["order"] == "" ? $row["b_num"] : "," . $row["b_num"]);
            }
        }

        return $acc;
    }

    public function link_list($type = "1")
    {
        $link = $this->GetList("select * from `@#_link` where `type`='$type'");
        return $link;
    }
}

System::load_sys_class("model", "sys", "no");