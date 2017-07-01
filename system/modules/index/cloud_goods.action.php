<?php
class cloud_goods extends SystemAction
{
    public function __construct()
    {
        $this->model = System::load_app_model("cloud_goods", "common");
        $this->order = System::load_app_model("order", "common");
    }

    public function init()
    {
    }

    /**
     * 商品列表
     */
    public function cglist()
    {
        $model_title = L("cgoods.list") . "<br>";
        $page = System::load_sys_class("page");
        $select = ($this->segment(4) ? $this->segment(4) : "0_0_0_0");
        $select = explode("_", $select);
        $select[] = "0";
        $select[] = "0";
        $cid = abs(intval($select[0]));
        $bid = abs(intval($select[1]));

        if ( $bid ) {
            $cateid = $this->model->cloud_brandinfo( $bid );
            $cateid = explode( ",", $cateid["cateid"] );

            if ( 1 < count( $cateid ) ) {
                $cateid = $cateid;
            }
            else {
                $cateid = false;
            }
        }

        $sort = abs(intval($select[2]));
        $sorts = "";

        switch ( $sort ) {
        case "1":
            $sorts = "order by `shenyurenshu` ASC";
            break;

        case "2":
            $sorts = "and `g_style` in ( 1, 3 ) order by `g_sort`";
            break;

        case "3":
            $sorts = "order by `shenyurenshu` ASC";
            break;

        case "4":
            $sorts = "order by `time` DESC";
            break;

        case "5":
            $sorts = "order by `g_money` DESC";
            break;

        case "6":
            $sorts = "order by `g_money` ASC";
            break;
        case "7":
            $sorts = "order by `zongrenshu` DESC";
            break;
        case "8":
            $sorts = "order by `canyurenshu` DESC";
            break;
        default:
            $sorts = "order by `g_sort`, `shenyurenshu` ASC";
        }

        if ( ! $cid ) {
            $brand = $this->model->cloud_brand();
            $daohang_title = L("goods.classify");
            $one_cate_list = $this->model->cloud_parentid();
        }
        else {
            $brandpcid       = $this->model->cloud_brandpcid($cid);
            $brand           = $this->model->cloud_brand($brandpcid);
            $daohang         = $this->model->cloud_cate1($cid);
            $daohang["info"] = unserialize($daohang["info"]);
            $daohang_title   = (empty($daohang["name"]) ? L("goods.classify") : $daohang["name"]);
            $daohang_seo     = (empty($daohang["info"]["meta_title"]) ? $daohang["name"] : $daohang["info"]["meta_title"]);
            $one_cate_list = $this->model->cloud_parentid($cid);
            if ( ! $one_cate_list && $daohang["parentid"] ) {
                $one_cate_list = $this->model->cloud_parentid($daohang["parentid"]);
            }
        }

        $model_title = $daohang_title . "_" . L("cgoods.list") . "_" . _cfg("web_name");
        $num = 20;
        if ( $cid && $bid ) {
            $sun_id_str = "'" . $cid . "'";
            $sun_cate = $this->model->cloud_cate2( $daohang["cateid"] );

            foreach ( $sun_cate as $v ) {
                $sun_id_str .= ",'" . $v["cateid"] . "'";
            }

            $total = $this->model->cloud_cpgoodstotal($sun_id_str, $bid);
        }
        else if ( $bid ) {
            $total = $this->model->cloud_cpgoodstotal("", $bid);
        }
        else if ( $cid ) {
            $sun_id_str = "'" . $cid . "'";
            $sun_cate   = $this->model->cloud_parentid($daohang["cateid"]);

            foreach ( $sun_cate as $v ) {
                $sun_id_str .= ",'" . $v["cateid"] . "'";
            }

            $total = $this->model->cloud_cpgoodstotal($sun_id_str, "");
        }
        else {
            $total = $this->model->cloud_cpgoodstotal("", "");
        }

        if ( $one_cate_list ) {
            $one_cate_listtag = "N";

            foreach ( $one_cate_list as $v ) {
                if ( $cid == $v["cateid"] ) {
                    $one_cate_listtag = "Y";
                }
            }
        }

        $page->config( $total, $num );
        $cpgoodslist = $this->model->cloud_cpgoodslist( $sun_id_str, $bid, $sorts, $page->setlimit() );
        $home_title    = findconfig("seo", "goods_title");
        $home_keywords = findconfig("seo", "goods_keywords");
        $home_desc     = findconfig("seo", "goods_desc");
        $seoinfo = array();
        $seoinfo["title"]       = $daohang["info"]["meta_title"];
        $seoinfo["keyword"]     = $daohang["info"]["meta_keyword"];
        $seoinfo["description"] = $daohang["info"]["meta_description"];
        $seoinfo["brand"]       = $daohang_title;

        if ( ! $home_title ) {
            $home_title    .= _cfg("web_name") . "_" . L("cgoods.list");
            $home_keywords .= L("cgoods.list");
            $home_desc     .= L("cgoods.list");
        }

        seo("title", $home_title, "glist", $seoinfo);
        seo("keywords", $home_keywords, "glist", $seoinfo);
        seo("description", $home_desc, "glist", $seoinfo);
        if ( abs(intval($select[3])) == 1 ) {
            $this->view->data("total", $total);
            $this->view->data("cpgoodslist", $cpgoodslist);
            $this->view->data("page", $page);
            $this->view->data("daohang_title", $daohang_title);
            $this->view->data("brand", $brand);
            $this->view->data("cid", $cid);
            $this->view->data("one_cate_list", $one_cate_list);
            $this->view->data("daohang", $daohang);
            $this->view->data("num", $num);
            $this->view->data("bid", $bid);
            $this->view->data("sort", $sort);
            $this->view->data("one_cate_listtag", $one_cate_listtag);
            $this->view->data("cateid", $cateid);
            $this->view->show("index.index_163");
        } else {
            $this->view->data("total", $total);
            $this->view->data("cpgoodslist", $cpgoodslist);
            $this->view->data("page", $page);
            $this->view->data("daohang_title", $daohang_title);
            $this->view->data("brand", $brand);
            $this->view->data("cid", $cid);
            $this->view->data("one_cate_list", $one_cate_list);
            $this->view->data("daohang", $daohang);
            $this->view->data("num", $num);
            $this->view->data("bid", $bid);
            $this->view->data("sort", $sort);
            $this->view->data("one_cate_listtag", $one_cate_listtag);
            $this->view->data("cateid", $cateid);
            $this->view->show("index.cglist");
        }
    }
    /*搜索页面*/
    public function search_view(){
        $this->view->show("user.search");
    }
    /**
     * 搜索产品
     */
    public function search_goods(){
        $page = System::load_sys_class("page");
        $search_con = $_REQUEST['search_con'];
        $sorts = " AND g_title like '%".$search_con."%' order by `g_sort`, `shenyurenshu` ASC";
        $brand = $this->model->cloud_brand();
        $daohang_title = L("goods.classify");
        $one_cate_list = $this->model->cloud_parentid();
        $model_title = $daohang_title . "_" . L("cgoods.list") . "_" . _cfg("web_name");
        $num = 20;
        $total = $this->model->cloud_cpgoodstotal("", "");
          if ( $one_cate_list ) {
            $one_cate_listtag = "N";

            foreach ( $one_cate_list as $v ) {
                if ( $cid == $v["cateid"] ) {
                    $one_cate_listtag = "Y";
                }
            }
        }
        $page->config( $total, $num );
        $cpgoodslist = $this->model->cloud_cpgoodslist( '', '', $sorts, '' );
        $home_title    = findconfig("seo", "goods_title");
        $home_keywords = findconfig("seo", "goods_keywords");
        $home_desc     = findconfig("seo", "goods_desc");
        $seoinfo = array();
        $seoinfo["title"]       = $daohang["info"]["meta_title"];
        $seoinfo["keyword"]     = $daohang["info"]["meta_keyword"];
        $seoinfo["description"] = $daohang["info"]["meta_description"];
        $seoinfo["brand"]       = $daohang_title;

        if ( ! $home_title ) {
            $home_title    .= _cfg("web_name") . "_" . L("cgoods.list");
            $home_keywords .= L("cgoods.list");
            $home_desc     .= L("cgoods.list");
        }

        seo("title", $home_title, "glist", $seoinfo);
        seo("keywords", $home_keywords, "glist", $seoinfo);
        seo("description", $home_desc, "glist", $seoinfo);
        $this->view->data("total", $total);
        $this->view->data("cpgoodslist", $cpgoodslist);
        $this->view->data("page", $page);
        $this->view->data("daohang_title", $daohang_title);
        $this->view->data("brand", $brand);
        $this->view->data("cid", $cid);
        $this->view->data("one_cate_list", $one_cate_list);
        $this->view->data("daohang", $daohang);
        $this->view->data("num", $num);
        $this->view->data("bid", $bid);
        $this->view->data("sort", $sort);
        $this->view->data("one_cate_listtag", $one_cate_listtag);
        $this->view->data("cateid", $cateid);
        $this->view->data("search_con", $search_con);
        $this->view->show("user.search");
    }
    public function ajax_cloud_goods_l(){
        $page = $_GET['page'] ? $_GET['page'] : 1;
        $res = $this->model->ajax_cloud_goodslist( '6', '2', $page );
        foreach ($res as $key => $value) {
            if ( $value['canyurenshu'] == '0' ) {
               $res[ $key ]['canyu_rate'] = '0';
            }else{
                $res[ $key ]['canyu_rate'] = intval( width( $value['canyurenshu'], $value['zongrenshu'], 100 ) );
            }
        }
        echo json_encode( $res );die;
    }
    /**
     * 推荐商品列表
     */
    public function recomglist()
    {
        $model_title = L("cgoods.list") . "<br>";
        $page = System::load_sys_class("page");
        $select = ($this->segment(4) ? $this->segment(4) : "0_0_0_0");
        $select = explode("_", $select);
        $select[] = "0";
        $select[] = "0";
        $cid = abs(intval($select[0]));
        $bid = abs(intval($select[1]));

        if ( $bid ) {
            $cateid = $this->model->cloud_brandinfo($bid);
            $cateid = explode(",", $cateid["cateid"]);

            if ( 1 < count( $cateid ) ) {
                $cateid = $cateid;
            }
            else {
                $cateid = false;
            }
        }

        $sort = abs(intval($select[2]));
        $sorts = "";

        switch ( $sort ) {
        case "1":
            $sorts = "order by `shenyurenshu` ASC";
            break;

        case "2":
            $sorts = "and `g_style` = '1'";
            break;

        case "3":
            $sorts = "order by `shenyurenshu` ASC";
            break;

        case "4":
            $sorts = "order by `time` DESC";
            break;

        case "5":
            $sorts = "order by `g_money` DESC";
            break;

        case "6":
            $sorts = "order by `g_money` ASC";
            break;
        case "7":
            $sorts = "order by `zongrenshu` DESC";
            break;
        case "8":
            $sorts = "order by `canyurenshu` DESC";
            break;
        default:
            $sorts = "order by `shenyurenshu` ASC";
        }

        if ( ! $cid ) {
            $brand = $this->model->cloud_brand();
            $daohang_title = L("goods.classify");
            $one_cate_list = $this->model->cloud_parentid();
        }
        else {
            $brandpcid       = $this->model->cloud_brandpcid($cid);
            $brand           = $this->model->cloud_brand($brandpcid);
            $daohang         = $this->model->cloud_cate1($cid);
            $daohang["info"] = unserialize($daohang["info"]);
            $daohang_title   = (empty($daohang["name"]) ? L("goods.classify") : $daohang["name"]);
            $daohang_seo     = (empty($daohang["info"]["meta_title"]) ? $daohang["name"] : $daohang["info"]["meta_title"]);
            $one_cate_list = $this->model->cloud_parentid($cid);
            if ( ! $one_cate_list && $daohang["parentid"] ) {
                $one_cate_list = $this->model->cloud_parentid($daohang["parentid"]);
            }
        }

        $model_title = $daohang_title . "_" . L("cgoods.list") . "_" . _cfg("web_name");
        $num = 20;
        if ( $cid && $bid ) {
            $sun_id_str = "'" . $cid . "'";
            $sun_cate = $this->model->cloud_cate2($daohang["cateid"]);

            foreach ( $sun_cate as $v ) {
                $sun_id_str .= ",'" . $v["cateid"] . "'";
            }

            $total = $this->model->cloud_cpgoodstotal($sun_id_str, $bid);
        }
        else if ( $bid ) {
            $total = $this->model->cloud_cpgoodstotal("", $bid);
        }
        else if ( $cid ) {
            $sun_id_str = "'" . $cid . "'";
            $sun_cate   = $this->model->cloud_parentid($daohang["cateid"]);

            foreach ( $sun_cate as $v ) {
                $sun_id_str .= ",'" . $v["cateid"] . "'";
            }

            $total = $this->model->cloud_cpgoodstotal($sun_id_str, "");
        }
        else {
            $total = $this->model->cloud_cpgoodstotal("", "");
        }

        if ($one_cate_list) {
            $one_cate_listtag = "N";

            foreach ( $one_cate_list as $v ) {
                if ( $cid == $v["cateid"] ) {
                    $one_cate_listtag = "Y";
                }
            }
        }

        $page->config( $total, $num );
        $cpgoodslist = $this->model->cloud_recomgoodslist( $sun_id_str, $bid, $sorts, $page->setlimit() );
        $home_title    = findconfig("seo", "goods_title");
        $home_keywords = findconfig("seo", "goods_keywords");
        $home_desc     = findconfig("seo", "goods_desc");
        $seoinfo = array();
        $seoinfo["title"]       = $daohang["info"]["meta_title"];
        $seoinfo["keyword"]     = $daohang["info"]["meta_keyword"];
        $seoinfo["description"] = $daohang["info"]["meta_description"];
        $seoinfo["brand"]       = $daohang_title;

        if ( ! $home_title ) {
            $home_title    .= _cfg("web_name") . "_" . L("cgoods.list");
            $home_keywords .= L("cgoods.list");
            $home_desc     .= L("cgoods.list");
        }

        seo("title", $home_title, "glist", $seoinfo);
        seo("keywords", $home_keywords, "glist", $seoinfo);
        seo("description", $home_desc, "glist", $seoinfo);
        if ( abs(intval($select[3])) == 1 ) {
            $this->view->data("total", $total);
            $this->view->data("cpgoodslist", $cpgoodslist);
            $this->view->data("page", $page);
            $this->view->data("daohang_title", $daohang_title);
            $this->view->data("brand", $brand);
            $this->view->data("cid", $cid);
            $this->view->data("one_cate_list", $one_cate_list);
            $this->view->data("daohang", $daohang);
            $this->view->data("num", $num);
            $this->view->data("bid", $bid);
            $this->view->data("sort", $sort);
            $this->view->data("one_cate_listtag", $one_cate_listtag);
            $this->view->data("cateid", $cateid);
            $this->view->show("index.index_163");
        } else {
            $this->view->data("total", $total);
            $this->view->data("cpgoodslist", $cpgoodslist);
            $this->view->data("page", $page);
            $this->view->data("daohang_title", $daohang_title);
            $this->view->data("brand", $brand);
            $this->view->data("cid", $cid);
            $this->view->data("one_cate_list", $one_cate_list);
            $this->view->data("daohang", $daohang);
            $this->view->data("num", $num);
            $this->view->data("bid", $bid);
            $this->view->data("sort", $sort);
            $this->view->data("one_cate_listtag", $one_cate_listtag);
            $this->view->data("cateid", $cateid);
            $this->view->show("index.recomglist");
        }
    }

    /**
     * 商品详情
     */
    public function cgitem()
    {
        $id = abs(intval(safe_replace($this->segment(4))));

        if ( ! $id ) {
            _message(L("html.err"), WEB_PATH, 3);
        }

        $item = $this->model->cloud_goodsdetail($id);

        if ( ! $item ) {
            _message( L("goods.no"), WEB_PATH, 3 );
        }

        $q_showtime = (isset($item["q_showtime"]) && ($item["q_showtime"] == "N") ? "N" : "Y");
        if ( $item["q_end_time"] && ($q_showtime == "N") ) {
            header("location: " . WEB_PATH . "/cgdataserver/" . $item["id"]);
            exit();
        }

        $itemlist = $this->model->cloud_qishu($item["gid"]);

        if ( ! $itemlist[0]["q_uid"] )
        {
            if ( $itemlist[0]["id"] == $item["id"] )
            {
                $cgoods_url0 = WEB_PATH . "/cgoods/" . $itemlist[0]["id"];
                $style0      = "period_Ongoing period_ArrowCur bg_red";
            }
            else
            {
                $cgoods_url0 = WEB_PATH . "/cgoods/" . $itemlist[0]["id"];
                $style0      = "period_Ongoing";
            }
        }
        else if ( $itemlist[0]["id"] == $item["id"] )
        {
            $cgoods_url0 = WEB_PATH . "/cgoods/" . $itemlist[0]["id"];
            $style0      = "period_ArrowCur bg_red";
        }
        else
        {
            $cgoods_url0 = WEB_PATH . "/cgdataserver/" . $itemlist[0]["id"];
            $style0      = "";
        }

        $itemlist0 = $itemlist[0];
        unset( $itemlist[0] );

        foreach ( $itemlist as $key => $qitem )
        {
            $itemlist[$key]["key"] = $key;

            if ( $qitem["id"] == $item["id"] )
            {
                $itemlist[$key]["cgoods_url"] = "";
                $itemlist[$key]["style"]      = "period_ArrowCur  bg_red";
                $itemlist[$key]["stylea"]     = "";
            }
            else
            {
                $itemlist[$key]["cgoods_url"] = WEB_PATH . "/cgdataserver/" . $qitem["id"];
                $itemlist[$key]["style"]      = "";
                $itemlist[$key]["stylea"]     = "gray02";
            }
        }

        $model_title = array();
        $cateinfo = $this->model->cloud_cate1($item["g_cateid"]);
        $model_title["cate_name"] = $cateinfo["name"];
        $brandinfo = $this->model->cloud_brandinfo($item["g_brandid"]);
        $model_title["brand_name"] = $brandinfo["name"];
        $item["g_picarr"] = unserialize($item["g_picarr"]);
        $model_title["title"] = L("cgoods.detail") . "<br>";
        $previous_cgoods = $this->model->cloud_goodsprevious($item["gid"], $item["qishu"] - 1);
        $previous_user = unserialize($previous_cgoods["q_user"]);
        $wherewords = "`ouid`= '{$previous_cgoods["q_uid"]}' and `ogid` = '{$previous_cgoods["id"]}' and `oqishu` = '{$previous_cgoods["qishu"]}' and `owin` = '{$previous_cgoods["q_user_code"]}'";
        $preuser_shop_time = $this->order->ready_order($wherewords, 1);
        $preuser_shop_time = $preuser_shop_time[0]["otime"];
        $wherewords = "`ogid`='$id' AND `oqishu`='{$item["qishu"]}' ORDER BY oid DESC LIMIT 6";
        $cgoods_user_record = $this->order->ready_order($wherewords, 1);
        $home_title    = findconfig("seo", "goods_detail_title");
        $home_keywords = findconfig("seo", "goods_detail_keywords");
        $home_desc     = findconfig("seo", "goods_detail_desc");

        if ( ! $home_title ) {
            $home_title    = _cfg("web_name") . "_" . L("cgoods.detail");
            $home_keywords = _cfg("web_name") . "_" . L("cgoods.detail");
            $home_desc     = _cfg("web_name") . "_" . L("cgoods.detail");
        }

        $seoinfo = array();
        $seoinfo["g_title"]       = $item["g_title"];
        $seoinfo["g_keyword"]     = $item["g_keyword"];
        $seoinfo["g_description"] = $item["g_description"];
        $seoinfo["g_brand"]       = $model_title["brand_name"];

        seo("title", $home_title, "gitem", $seoinfo);
        seo("keywords", $home_keywords, "gitem", $seoinfo);
        seo("description", $home_desc, "gitem", $seoinfo);
        
        $this->view->data("id", $id);
        $this->view->data("model_title", $model_title);
        $this->view->data("itemlist0", $itemlist0);
        $this->view->data("cgoods_url0", $cgoods_url0);
        $this->view->data("style0", $style0);
        $this->view->data("itemlist", $itemlist);
        $this->view->data("item", $item);
        $this->view->data("style0", $style0);
        $this->view->data("cookieinfo", $cookieinfo);
        $this->view->data("previous_cgoods", $previous_cgoods);
        $this->view->data("previous_user", $previous_user);
        $this->view->data("preuser_shop_time", $preuser_shop_time);
        $this->view->data("cgoods_user_record", $cgoods_user_record);
        $this->view->show("index.cgitem");
    }

    public function cgdataserver()
    {
        $id = abs(intval(safe_replace($this->segment(4))));

        if (!$id) {
            _message(L("html.err"), WEB_PATH, 3);
        }

        $item = $this->model->cloud_goodsdetail($id);

        if (!$item) {
            _message(L("goods.no"), WEB_PATH, 3);
        }

        $update_pay = System::load_app_model("UserPay", "common");

        if (empty($item["q_user_code"])) {
            _message(L("goods.ing"), WEB_PATH . "/cgoods/" . $id);
        }

        if (isset($item["q_showtime"]) && ($item["q_showtime"] == "Y")) {
            header("location: " . WEB_PATH . "/cgoods/" . $item["id"]);
            exit();
        }

        $itemlist = $this->model->cloud_qishu($item["gid"]);
        if (!$itemlist[0]["q_uid"]) {
            if ($itemlist[0]["id"] == $item["id"]) {
                $cgoods_url0 = WEB_PATH . "/cgoods/" . $itemlist[0]["id"];
                $style0 = "period_Ongoing period_ArrowCur";
            }
            else {
                $cgoods_url0 = WEB_PATH . "/cgoods/" . $itemlist[0]["id"];
                $style0 = "period_Ongoing";
            }
        }
        else if ($itemlist[0]["id"] == $item["id"]) {
            $cgoods_url0 = WEB_PATH . "/cgdataserver/" . $itemlist[0]["id"];
            $style0 = "period_ArrowCur ";
        }
        else {
            $cgoods_url0 = WEB_PATH . "/cgoods/" . $itemlist[0]["id"];
            $style0 = "";
        }

        $itemlist0 = $itemlist[0];
        $wherewords = "`ouid`= '{$item["q_uid"]}' AND `ogid` = '$id' AND `oqishu` = '{$item["qishu"]}'";
        $count_record = $this->order->ready_order($wherewords, 1);
        foreach ( $count_record as $v )
        {
            $user_shop_number += $v["onum"];
            $ogocode = $this->model->cloud_ogocode($v["oid"]);
            $user_shop_codes .= $ogocode["ogocode"] . ",";
        }

        $user_shop_codes = rtrim($user_shop_codes, ",");
        $wherewords = "`ouid`= '{$item["q_uid"]}' AND `ogid` = '$id' AND `oqishu` = '{$item["qishu"]}' AND `owin`='{$item["q_user_code"]}'";
        $user_shop_time = $this->order->ready_order($wherewords, 1);
        $user_shop_time = $user_shop_time[0]["otime"];
        unset($itemlist[0]);

        foreach ( $itemlist as $key => $qitem ) {
            $itemlist[$key]["key"] = $key;

            if ( $qitem["id"] == $item["id"] ) {
                $itemlist[$key]["cgoods_url"] = "";
                $itemlist[$key]["style"]      = "period_ArrowCur  bg_red";
                $itemlist[$key]["stylea"]     = "";
            }
            else {
                $itemlist[$key]["cgoods_url"] = WEB_PATH . "/cgdataserver/" . $qitem["id"];
                $itemlist[$key]["style"]      = "";
                $itemlist[$key]["stylea"]     = "gray02";
            }
        }

        $q_user = unserialize($item["q_user"]);
        $q_userinfo["uid"]      = $q_user["uid"];
        $q_userinfo["username"] = $q_user["username"];
        $q_userinfo["user_ip"]  = $q_user["user_ip"];
        $q_userinfo["img"]      = $q_user["img"];
        $item["q_user"]   = $q_userinfo;
        $item["g_picarr"] = unserialize($item["g_picarr"]);
        $q_user_code_arr  = $this->model->cloud_code($item["q_user_code"]);
        $model_title = array();
        $cateinfo = $this->model->cloud_cate1($item["g_cateid"]);
        $model_title["cate_name"] = $cateinfo["name"];
        $brandinfo = $this->model->cloud_brandinfo($item["g_brandid"]);
        $model_title["brand_name"] = $brandinfo["name"];
        $h = abs(date("H", $item["q_end_time"]));
        $i = date("i", $item["q_end_time"]);
        $s = date("s", $item["q_end_time"]);
        $w = substr($item["q_end_time"], 11, 3);
        $user_shop_time_add = $h . $i . $s . $w;
        $shop_fadd = fmod(($user_shop_time_add * 100) + $item["q_external_code"], $item["canyurenshu"]);
        $item["shop_fmod"] = $shop_fadd;
        $item["q_external_content"] = unserialize($item["q_external_content"]);

        if ( $item["q_content"] ) {
            $item_q_content = unserialize($item["q_content"]);
            $keysvalue = $new_array = array();

            foreach ( $item_q_content as $k => $v ) {
                $keysvalue[$k] = $v["otime"];
                $h = date("H", $v["otime"]);
                $i = date("i", $v["otime"]);
                $s = date("s", $v["otime"]);
                list($timesss, $msss) = explode(".", $v["otime"]);
                $item_q_content[$k]["otime_add"] = $h . $i . $s . $msss;
            }

            arsort($keysvalue);
            reset($keysvalue);

            foreach ( $keysvalue as $k => $v ) {
                $new_array[$k] = $item_q_content[$k];
            }

            foreach ( $new_array as $key => $v ) {
                $record_time = explode(".", $v["otime"]);
                $new_array[$key]["otime"] = $record_time[0];
                $new_array[$key]["otime0"] = $record_time[1];
            }

            $item["q_content"] = $new_array;
            $item["shop_fmod"] = fmod($item["q_counttime"] + $item["q_external_code"], $item["canyurenshu"]);
        }
        else {
            $item["q_counttime"] = $user_shop_time_add;
        }

        $model_title["title"] = L("cgoods.detail") . "<br>";
        $home_title           = _cfg("web_name") . "_最新揭晓列表";
        $home_keywords        = _cfg("web_name") . "_最新揭晓列表";
        $home_desc            = _cfg("web_name") . "_最新揭晓列表";
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $loconfig = System::load_sys_config("lotteryway");

        if ( $loconfig["lotteryway"]["opennow"] == "default" ) {
            $this->view->show("index.cgdataserver");
        }
        else {
            $this->view->show("index.cgdataserver_ex");
        }

        $this->view->data("model_title", $model_title);
        $this->view->data("item", $item);
        $this->view->data("itemlist0", $itemlist0);
        $this->view->data("cgoods_url0", $cgoods_url0);
        $this->view->data("style0", $style0);
        $this->view->data("itemlist", $itemlist);
        $this->view->data("q_user_code_arr", $q_user_code_arr);
        $this->view->data("user_shop_codes", $user_shop_codes);
        $this->view->data("q_user", $q_user);
        $this->view->data("user_shop_number", $user_shop_number);
        $this->view->data("user_shop_time", $user_shop_time);
        $this->view->data("id", $id);
    }

    public function cglottery()
    {
        $home_title    = _cfg("web_name") . "_最新揭晓列表";
        $home_keywords = _cfg("web_name") . "_最新揭晓列表";
        $home_desc     = _cfg("web_name") . "_最新揭晓列表";
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $order_db = System::load_app_model("order", "common");
        $model_title = L("cgoods.lottery") . "<br>";
        $total = $this->model->cloud_goodslisted("", "GetCount");
        $num = 12;
        $page = System::load_sys_class("page");
        $page->config($total, $num);
        $cglotterylist = $this->model->cloud_goodslisted($page->setlimit(), "GetList");

        foreach ( $cglotterylist as $key => $v ) {
            $q_userinfo = unserialize($v["q_user"]);
            $q_user = array();
            $q_user["uid"]      = $q_userinfo["uid"];
            $q_user["username"] = $q_userinfo["username"];
            $q_user["user_ip"]  = $q_userinfo["user_ip"];
            $q_user["img"]      = $q_userinfo["img"];
            $cglotterylist[$key]["q_user"] = $q_user;
            $cglotterylist[$key]["onum"]   = go_count_record($v["id"], $v["q_uid"], "s");
            $cglotterylist[$key]["money"]  = sprintf("%.2f", $v["zongrenshu"] * $v["price"]);
        }

        $this->view->data("model_title", $model_title);
        $this->view->data("cglotterylist", $cglotterylist);
        $this->view->data("total", $total);
        $this->view->data("num", $num);
        $this->view->data("page", $page);
        $this->view->show("index.cglottery");
    }

    /**
     * 所有参与记录
     */
    public function go_record_iframe()
    {
        $gid = abs(intval(safe_replace($this->segment(4))));
        $len = abs(intval(safe_replace($this->segment(5))));

        if ( $len < 10 ) {
            $len = 10;
        }

        $wherewords = "`ogid` = '$gid' order by otime desc";
        $total = $this->order->ready_order_num($wherewords, 1);

        if ( isset( $_GET["p"] ) ) {
            $pagenum = (int) $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $num = $len;
        $go_record_list = $this->order->ready_order( $wherewords, 1 );
        $go_record_list = empty( $go_record_list ) ? array() : $go_record_list;
        foreach ( $go_record_list as $k => $user ) {
            $go_record_list[$k]["tag"] = 1;

            if ( $k == 0 )
            {
                $go_record_list[$k]["tag"] = 2;
            }

            if ((0 < $k) && (date("Ymd", $user["otime"]) < date("Ymd", $go_record_list[$k - 1]["otime"])))
            {
                $go_record_list[$k]["tag"] = 3;
            }

            $ogocode = $this->model->cloud_ogocode($user["oid"]);
            $go_record_list[$k]["ogocode"] = $ogocode["ogocode"];
        }

        $this->view->data("total", $total);
        $this->view->data("go_record_list", $go_record_list);
        $this->view->show("index.go_record_iframe");
    }

    /**
     * 限时揭晓列表
     */
    public function cloud_autolottery()
    {
        $home_title    = _cfg("web_name") . "_限时揭晓列表";
        $home_keywords = _cfg("web_name") . "_限时揭晓列表";
        $home_desc     = _cfg("web_name") . "_限时揭晓列表";
        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);
        $jinri_time   = abs(date("m")) . "月" . date("d") . "日";
        $minri_time   = abs(date("m", strtotime("+1 day"))) . "月" . date("d", strtotime("+1 day")) . "日";
        $w_jinri_time = strtotime(date("Y-m-d"));
        $w_minri_time = strtotime(date("Y-m-d", strtotime("+1 day")));
        $w_hinri_time = strtotime(date("Y-m-d", strtotime("+2 day")));
        $wherewords = "`xsjx_time` > '$w_jinri_time' and `xsjx_time` < '$w_minri_time' order by id DESC limit 0,3";
        $jinri_shoplist = $this->model->cloud_goodsauto( $wherewords );

        foreach ( $jinri_shoplist as $key => $v ) 
        {
            $jinri_shoplist[$key]["time_H"] = abs(date("H", $v["xsjx_time"]));
        }

        $wherewords = "`xsjx_time` > '$w_minri_time' and `xsjx_time` < '$w_hinri_time' order by id DESC limit 0,3";
        $minri_shoplist = $this->model->cloud_goodsauto( $wherewords );
        $wherewords = "`xsjx_time` != '0' and `q_uid` != '0' order by `xsjx_time` DESC limit 0,8";
        $endshoplist = $this->model->cloud_goodsauto( $wherewords );

        $this->view->data( "jinri_time", $jinri_time );
        $this->view->data( "minri_time", $minri_time );
        $this->view->data( "jinri_shoplist", $jinri_shoplist );
        $this->view->data( "minri_shoplist", $minri_shoplist );
        $this->view->data( "endshoplist", $endshoplist );
        $this->view->show( "index.cloud_autolottery" );
    }

    public function cgoodsdesc()
    {
        $id = abs(intval(safe_replace($this->segment(4))));

        if (!$id) {
            _message(L("html.err"), WEB_PATH, 3);
        }

        $item = $this->model->cloud_goodsdetail($id);

        if (!$item) {
            _message(L("goods.no"), WEB_PATH, 3);
        }

        $model_title = array();
        $cateinfo = $this->model->cloud_cate1($item["g_cateid"]);
        $model_title["cate_name"] = $cateinfo["name"];
        $brandinfo = $this->model->cloud_brandinfo($item["g_brandid"]);
        $model_title["brand_name"] = $brandinfo["name"];
        $item["g_picarr"] = unserialize($item["g_picarr"]);
        $model_title["title"] = L("cgoods.detail") . "<br>";
        $home_title    = findconfig("seo", "goods_detail_title");
        $home_keywords = findconfig("seo", "goods_detail_keywords");
        $home_desc     = findconfig("seo", "goods_detail_desc");

        if (!$home_title) {
            $home_title = _cfg("web_name") . "_" . L("cgoods.detail");
        }

        if (!$home_title) {
            $home_keywords = _cfg("web_name") . "_" . L("cgoods.detail");
        }

        if (!$home_title) {
            $home_desc = _cfg("web_name") . "_" . L("cgoods.detail");
        }

        $seoinfo = array();
        $seoinfo["g_title"]       = $item["g_title"];
        $seoinfo["g_keyword"]     = $item["g_keyword"];
        $seoinfo["g_description"] = $item["g_description"];
        $seoinfo["g_brand"]       = $model_title["brand_name"];
        seo("title", $home_title, "gitem", $seoinfo);
        seo("keywords", $home_keywords, "gitem", $seoinfo);
        seo("description", $home_desc, "gitem", $seoinfo);

        $this->view->data("id", $id);
        $this->view->data("model_title", $model_title);
        $this->view->data("item", $item);
        $this->view->show("index.cgoodsdesc");
    }

    public function CalResult()
    {
        $id = abs(intval(safe_replace($this->segment(4))));
        if ( ! $id ) {
            _message(L("html.err"), WEB_PATH, 3);
        }

        $item = $this->model->cloud_goodsdetail( $id );
        if ( ! $item ) {
            _message(L("goods.no"), WEB_PATH, 3);
        }

        $h = abs(date("H", $item["q_end_time"]));
        $i = date("i", $item["q_end_time"]);
        $s = date("s", $item["q_end_time"]);
        $w = substr($item["q_end_time"], 11, 3);
        $user_shop_time_add = $h . $i . $s . $w;
        $shop_fadd = fmod(($user_shop_time_add * 100) + $item["q_external_code"], $item["canyurenshu"]);
        $item["shop_fmod"] = $shop_fadd;
        $item["user_shop_time_add"] = $user_shop_time_add;
        $item["q_external_content"] = unserialize($item["q_external_content"]);

        if ( $item["q_content"] )
        {
            $item_q_content = unserialize( $item["q_content"] );
            $keysvalue = $new_array = array();

            foreach ( $item_q_content as $k => $v )
            {
                $keysvalue[$k] = $v["otime"];
                $h = date("H", $v["otime"]);
                $i = date("i", $v["otime"]);
                $s = date("s", $v["otime"]);
                list( $timesss, $msss ) = explode( ".", $v["otime"] );
                $item_q_content[$k]["timecode"] = $h . $i . $s . $msss;
            }

            arsort( $keysvalue );
            reset( $keysvalue );

            foreach ( $keysvalue as $k => $v ) {
                $new_array[$k] = $item_q_content[$k];
            }

            $item["q_content"] = $new_array;
            $item["shop_fmod"] = fmod($item["q_counttime"], $item["canyurenshu"]);
        }
        else {
            $item["q_counttime"] = $user_shop_time_add;
        }

        $loconfig = System::load_sys_config("lotteryway");

        if ( $loconfig["lotteryway"]["opennow"] == "default" ) {
            $this->view->show("index.calResult");
        }
        else {
            $this->view->show("index.calResult_ex");
        }

        $this->view->data("item", $item)->data("item", $item)->data("user_shop_time_add", $user_shop_time_add);
    }

    public function QishuToid()
    {
        $orangetemp = System::load_app_model("orangetemp", "common");
        if (!isset($_POST["gid"]) || !isset($_POST["qishu"])) {
            echo json_encode(array("error" => 0));
            return NULL;
            exit();
        }
        else {
            $qishu = ($_POST["qishu"] ? intval($_POST["qishu"]) : 1);
            $gid   = ($_POST["gid"] ? intval($_POST["gid"]) : 1);
            if ( ! $qishu || ! $gid ) {
                echo json_encode(array("error" => 0));
                return NULL;
                exit();
            }
            else {
                $goodsinfo = $orangetemp->CloudQishu($gid, $qishu);
                echo json_encode(array("error" => 1, "result" => $goodsinfo["id"]));
                return NULL;
                exit();
            }
        }
    }
}