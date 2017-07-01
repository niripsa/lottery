<?php
System::load_app_class("UserAction", "common", "no");
class shop extends UserAction
{
    /**
     * 一元夺宝记录
     */
    public function userbuylist()
    {
        $order_db      = System::load_app_model("order", "common");
        $member        = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $page  = System::load_sys_class("page");
        $uid   = $member["uid"];
        $total = $order_db->ready_order_num($uid, 2);
        $num   = 10;
        $page->config($total, $num);
        $selectwords = "`ouid`='{$member["uid"]}'  order by  `otime` DESC";
        $record      = $order_db->ready_order($selectwords, 1, "", "", $page->setlimit(1));
        $record      = empty($record) ? array() : $record;
        foreach ( $record as $key => $v ) {
            $record[$key]["g_title"]       = useri_title($v["og_title"], "g_title");
            $record[$key]["g_thumb"]       = useri_title($v["og_title"], "g_thumb");
            $wininfo                       = get_shop_if_jiexiao($v["ogid"]);
            $record[$key]["q_end_time"]    = $wininfo["q_end_time"];
            $record[$key]["getowin_uid"]   = $wininfo["q_uid"];
            $winuser                       = $wininfo["q_user"];
            $record[$key]["getowin_uname"] = $winuser["username"];
        }

        $this->view->data( 'member', $this->UserInfo );
        $this->view->data("total", $total)->data("num", $num)->data("record", $record)->data("page", $page);
        $this->view->show("user.buylist");
    }

    /**
     * 一级管理商 订单
     */
    public function dis1_buylist()
    {
        $order_db      = System::load_app_model("order", "common");
        $member        = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $page  = System::load_sys_class("page");

        $where = '1 = 1';
        $ou_name      = $this->segment( 4 );
        $start_otime  = $this->segment( 5 );
        $end_otime    = $this->segment( 6 );
        $order_source = $this->segment( 7 );
        $manage_id    = $this->segment( 8 );
        if ( $ou_name && ! preg_match( "/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $ou_name ) )
        {
            $where .= " AND `ou_name` = '{$ou_name}'";
            $search['ou_name'] = $ou_name;
        }        
        if ( $start_otime || $end_otime )
        {
            $start_otime = str_replace( '_', ' ', $start_otime );
            $search['start_otime'] = $start_otime;
            $start_otime = strtotime( $start_otime ) ? : 0;
            $end_otime = str_replace( '_', ' ', $end_otime );
            $search['end_otime'] = $end_otime;
            $end_otime = strtotime( $end_otime ) ? : time();
            $where .= " AND `otime` BETWEEN {$start_otime} AND {$end_otime}";
        }
        /* 订单来源搜索 */
        if ( $order_source )
        {
            switch ( $order_source )
            {
                case 1:
                    $where .= "";
                break;
                case 2:
                    $where .= " AND `manage2_id` > '0' AND `manage3_id` = '0'";
                break;
                case 3:
                    $where .= " AND `manage3_id` > '0'";
                break;
            }
            $search['order_source'] = $order_source;
        }
        /* 管理商ID 搜索 */
        if ( $manage_id > 0 )
        {
            $where .= " AND (`manage2_id` = '{$manage_id}' OR `manage3_id` = '{$manage_id}')";
            $search['manage_id'] = $manage_id;
        }
        
        $uid   = $member["uid"];
        $where .= " AND `area_id` = '{$member["area_id"]}'";
        $order_amount = $order_db->get_sum( 'cloud_order', $where, 'omoney' );
        $total = $order_db->ready_order_num( $where, 4 );
        $num   = 10;
        $page->config($total, $num);
        $selectwords = $where . " order by  `otime` DESC";

        $record      = $order_db->ready_order($selectwords, 1, "", "", $page->setlimit(1));
        $record      = empty($record) ? array() : $record;
        foreach ( $record as $key => $v ) {
            $record[$key]["g_title"]       = useri_title($v["og_title"], "g_title");
            $record[$key]["g_thumb"]       = useri_title($v["og_title"], "g_thumb");
            $wininfo                       = get_shop_if_jiexiao($v["ogid"]);
            $record[$key]["q_end_time"]    = $wininfo["q_end_time"];
            $record[$key]["getowin_uid"]   = $wininfo["q_uid"];
            $winuser                       = $wininfo["q_user"];
            $record[$key]["getowin_uname"] = $winuser["username"];
        }

        $this->view->data( 'search', $search );
        $this->view->data( "search_url", G_MODULE_PATH . "/" . ROUTE_C . "/dis1_buylist" );
        $this->view->data( 'member', $this->UserInfo );        
        $this->view->data( 'order_amount', $order_amount );
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("record", $record);
        $this->view->data("page", $page);
        $this->view->show("dis1.buylist");
    }

    /**
     * 二级管理商 订单
     */
    public function dis2_buylist()
    {
        $order_db      = System::load_app_model("order", "common");
        $member        = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $page  = System::load_sys_class("page");

        $where = '1 = 1';
        $ou_name      = $this->segment( 4 );
        $start_otime  = $this->segment( 5 );
        $end_otime    = $this->segment( 6 );
        $order_source = $this->segment( 7 );
        $manage_id    = $this->segment( 8 );
        if ( $ou_name && ! preg_match( "/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $ou_name ) )
        {
            $where .= " AND `ou_name` = '{$ou_name}'";
            $search['ou_name'] = $ou_name;
        }        
        if ( $start_otime || $end_otime )
        {
            $start_otime = str_replace( '_', ' ', $start_otime );
            $search['start_otime'] = $start_otime;
            $start_otime = strtotime( $start_otime ) ? : 0;
            $end_otime = str_replace( '_', ' ', $end_otime );
            $search['end_otime'] = $end_otime;
            $end_otime = strtotime( $end_otime ) ? : time();
            $where .= " AND `otime` BETWEEN {$start_otime} AND {$end_otime}";
        }
        /* 订单来源搜索 */
        if ( $order_source )
        {
            switch ( $order_source )
            {
                case 2:
                    // $where .= " AND `manage3_id` = '0'";
                break;
                case 3:
                    $where .= " AND `manage3_id` > '0'";
                break;
            }
            $search['order_source'] = $order_source;
        }
        /* 管理商ID 搜索 */
        if ( $manage_id > 0 )
        {
            $where .= " AND (`manage3_id` = '{$manage_id}' OR `ouid` = '{$manage_id}')";
            $search['manage_id'] = $manage_id;
        }
        
        $uid   = $member["uid"];
        $where .= " AND (`manage2_id` = '{$member["uid"]}' OR `manage3_id` = '{$member["uid"]}')";
        $order_amount = $order_db->get_sum( 'cloud_order', $where, 'omoney' );
        $total = $order_db->ready_order_num( $where, 4 );
        $num   = 10;
        $page->config( $total, $num );
        $selectwords = $where . " order by `otime` DESC";

        $record      = $order_db->ready_order($selectwords, 1, "", "", $page->setlimit(1));
        $record      = empty($record) ? array() : $record;
        foreach ( $record as $key => $v ) {
            $record[$key]["g_title"]       = useri_title($v["og_title"], "g_title");
            $record[$key]["g_thumb"]       = useri_title($v["og_title"], "g_thumb");
            $wininfo                       = get_shop_if_jiexiao($v["ogid"]);
            $record[$key]["q_end_time"]    = $wininfo["q_end_time"];
            $record[$key]["getowin_uid"]   = $wininfo["q_uid"];
            $winuser                       = $wininfo["q_user"];
            $record[$key]["getowin_uname"] = $winuser["username"];
        }

        $this->view->data( 'search', $search );
        $this->view->data( "search_url", G_MODULE_PATH . "/" . ROUTE_C . "/dis2_buylist" );
        $this->view->data( 'member', $this->UserInfo );
        $this->view->data( 'order_amount', $order_amount );
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("record", $record);
        $this->view->data("page", $page);
        $this->view->show("dis2.buylist");
    }

    /**
     * 三级管理商 订单
     */
    public function dis3_buylist()
    {
        $order_db      = System::load_app_model("order", "common");
        $member        = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.userbuylist") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $page  = System::load_sys_class("page");

        $where = '1 = 1';
        $ou_name      = $this->segment( 4 );
        $start_otime  = $this->segment( 5 );
        $end_otime    = $this->segment( 6 );
        if ( $ou_name && ! preg_match( "/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $ou_name ) )
        {
            $where .= " AND `ou_name` = '{$ou_name}'";
            $search['ou_name'] = $ou_name;
        }        
        if ( $start_otime || $end_otime )
        {
            $start_otime = str_replace( '_', ' ', $start_otime );
            $search['start_otime'] = $start_otime;
            $start_otime = strtotime( $start_otime ) ? : 0;
            $end_otime = str_replace( '_', ' ', $end_otime );
            $search['end_otime'] = $end_otime;
            $end_otime = strtotime( $end_otime ) ? : time();
            $where .= " AND `otime` BETWEEN {$start_otime} AND {$end_otime}";
        }
        
        $uid   = $member["uid"];
        $where .= " AND `manage3_id` = '{$member["uid"]}'";
        $order_amount = $order_db->get_sum( 'cloud_order', $where, 'omoney' );
        $total = $order_db->ready_order_num( $where, 4 );
        $num   = 10;
        $page->config( $total, $num );
        $selectwords = $where . " order by  `otime` DESC";

        $record      = $order_db->ready_order( $selectwords, 1, "", "", $page->setlimit(1) );
        $record      = empty( $record ) ? array() : $record;
        foreach ( $record as $key => $v ) {
            $record[$key]["g_title"]       = useri_title($v["og_title"], "g_title");
            $record[$key]["g_thumb"]       = useri_title($v["og_title"], "g_thumb");
            $wininfo                       = get_shop_if_jiexiao($v["ogid"]);
            $record[$key]["q_end_time"]    = $wininfo["q_end_time"];
            $record[$key]["getowin_uid"]   = $wininfo["q_uid"];
            $winuser                       = $wininfo["q_user"];
            $record[$key]["getowin_uname"] = $winuser["username"];
        }

        $this->view->data( 'search', $search );
        $this->view->data( "search_url", G_MODULE_PATH . "/" . ROUTE_C . "/dis3_buylist" );
        $this->view->data( 'member', $this->UserInfo );
        $this->view->data( 'order_amount', $order_amount );
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("record", $record);
        $this->view->data("page", $page);
        $this->view->show("dis3.buylist");
    }

    public function userbuydetail()
    {
        $member = $this->UserInfo;
        $user_record_db = System::load_app_model("cloud_goods", "common");
        $order_db = System::load_app_model("order", "common");
        $title = "夺宝详情";
        $oid = intval($this->segment(4));
        $selectwords = "`oid`='$oid'";
        $user_record = $order_db->ready_order( $selectwords, 1 );
        $user_record = $user_record[0];
        $ogocode = $user_record_db->cloud_ogocode( $oid );
        if ( ! $user_record || ! $ogocode ) {
            _message(l("html.err"), WEB_PATH . "/member/shop/userbuylist", 3);
        }

        $shopinfo = $user_record_db->cloud_goodsdetail( $user_record["ogid"] );
        $user_record["g_thumb"]    = useri_title( $user_record["og_title"], "g_thumb" );
        $user_record["g_title"]    = useri_title( $user_record["og_title"], "g_title" );
        $user_record["q_showtime"] = $shopinfo["q_showtime"];
        $user_record["ogocode"]    = $ogocode["ogocode"];

        $this->view->data( 'member', $this->UserInfo );
        $this->view->data( 'user_record', $user_record );
        $this->view->show("user.buydetail");
    }

    public function orderlist()
    {
        $order_db = System::load_app_model("order", "common");
        $page     = System::load_sys_class("page");
        $member   = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.orderlist") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.orderlist") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.orderlist") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $uid         = $member["uid"];
        $selectwords = "`ouid`='$uid' and `owin` is  not null order by otime desc";
        $total       = $order_db->ready_order_num($selectwords, 1);
        $num = 10;
        $page->config( $total, $num );
        $record = $order_db->ready_order( $selectwords, 1, "", "", $page->setlimit(1) );
        $record = empty( $record ) ? array() : $record;
        foreach ( $record as $ckey => $cord ) {
            $jiexiao = get_shop_if_jiexiao( $cord["ogid"] );
            if ( ! $jiexiao ) {
                unset( $recordlist[$ckey] );
            }
        }

        $recordlist = $order_db->ready_order( $selectwords, 1, "", "" );
        $recordlist = empty( $recordlist ) ? array() : $recordlist;
        foreach ( $recordlist as $ckey => $cord ) {
            $jiexiao = get_shop_if_jiexiao( $cord["ogid"] );
            if ( ! $jiexiao ) {
                unset( $recordlist[$ckey] );
            }
        }

        if ( $recordlist )
        {
            $total = count( $recordlist );
        }
        else
        {
            $total = 0;
        }

        $this->view->data( 'member', $this->UserInfo );
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("page", $page);
        $this->view->data("record", $record);
        $this->view->data("uid", $uid);
        $this->view->data("total1", $total1);
        $this->view->show("user.orderlist");
    }

    public function plaingoods()
    {
        $userdb = System::load_app_model("user", "common");
        $page   = System::load_sys_class("page");
        $member = $this->UserInfo;
        $home_title    = $member["username"] . "__" . l("user.plaingoods") . "_" . _cfg("web_name");
        $home_keywords = $member["username"] . "__" . l("user.plaingoods") . "_" . _cfg("web_name");
        $home_desc     = $member["username"] . "__" . l("user.plaingoods") . "_" . _cfg("web_name");
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $uid   = $member["uid"];
        $total = $userdb->plaingoodsnum( $uid );
        $num   = 10;
        $page->config( $total, $num );
        $record = $userdb->plaingoodslist( $uid, $page->setlimit() );

        $this->view->data( 'member', $this->UserInfo );
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("page", $page);
        $this->view->data("record", $record);
        $this->view->data("uid", $uid);
        $this->view->show("user.plaingoods");
    }
}