<?php
class index extends SystemAction
{
    public function init()
    {
        /* 云购商品 Model */
        $cloud_goods_model = System::load_app_model("cloud_goods", "common");
        if ( $_GET['app_member_id'] ) 
        {
            $user = System::load_app_class( 'UserCheck', 'common' )->UserInfo;
        }
        $home_title    = findconfig( 'seo', 'home_title' );
        $home_keywords = findconfig( 'seo', 'home_keywords' );
        $home_desc     = findconfig( 'seo', 'home_desc' );

        if ( ! $home_title ) 
        {
            $home_title    = _cfg( 'web_name' );
            $home_keywords = _cfg( 'web_name' );
            $home_desc     = _cfg( 'web_name' );
        }

        seo("title", $home_title);
        seo("keywords", $home_keywords);
        seo("description", $home_desc);

        /* 限时揭晓 Start */
        $w_jinri_time = strtotime(date("Y-m-d"));
        $w_minri_time = strtotime(date("Y-m-d", strtotime("+1 day")));
        $now_time     = time();
        $wherewords = "`xsjx_time` > '$w_jinri_time' order by g_sort";
        $jinri_shoplist = $cloud_goods_model->cloud_goodsauto( $wherewords );
        $this->view->data( 'jinri_shoplist', $jinri_shoplist );
        /* 限时揭晓 End */

        $model  = System::load_app_model( 'index', 'common' );
        $notice = $model->sel_notice();
        $this->view->show("index.index_163")->data("isindex", "Y")->data("notice", $notice);
    }

    /**
     * 商品搜索
     */
    public function search()
    {
        $search = $this->segment_array();
        array_shift($search);
        array_shift($search);
        array_shift($search);
        $search = implode("/", $search);

        if ( ! $search ) {
            _message( L("search.emp") );
        }

        $model  = System::load_app_model("index", "common");
        $search = urldecode($search);
        $search = safe_replace($search);

        if ( ! _is_utf8( $search ) ) {
            $search = iconv("GBK", "UTF-8", $search);
        }

        $search = str_ireplace("union", "", $search);
        $search = str_ireplace("select", "", $search);
        $search = str_ireplace("delete", "", $search);
        $search = str_ireplace("update", "", $search);
        $search = str_ireplace("/**/", "", $search);
        seo("title", _cfg("web_name") . "_" . $search);
        seo("keywords", _cfg("web_name") . "_" . $search);
        seo("description", _cfg("web_name") . "_" . $search);
        $search_res = $model->search_goods( $search );
        $search_sum = count($search_res);

        $this->view->data("search", $search);
        $this->view->data("search_res", $search_res);
        $this->view->data("search_sum", $search_sum);
        $this->view->show("index.search");
    }

    public function cloud_gorecord()
    {
        seo("title", _cfg("web_name") . "_历史记录");
        seo("keywords", _cfg("web_name") . "_历史记录");
        seo("description", _cfg("web_name") . "_历史记录");
        $start_time = strtotime(date("Y-m-d", time()));
        $end_time = time();
        $start_time_i = $end_time_i = date("i", time());
        $start_time_h = date("H", time()) - 2;
        $end_time_h = date("H", time());
        $order_db = System::load_app_model("order", "common");
        $cloud_goodsdb = System::load_app_model("cloud_goods", "common");
        $selectwords = "1";
        $res = $order_db->ready_order($selectwords, 1);

        foreach ($res as $v ) {
            $SUM += $v["onum"];
        }

        $setkey = "`value`='$SUM'";
        $where = "`name`='goods_count_num'";
        $cloud_goodsdb->upgoods_count_num($setkey, $where);

        if (isset($_POST["dosubmit"])) {
            $start_time = $_POST["start_time_data"] . " " . $_POST["start_time_h"] . ":" . $_POST["start_time_i"] . ":00";
            $end_time = $_POST["end_time_data"] . " " . $_POST["end_time_h"] . ":" . $_POST["end_time_i"] . ":00";
            $start_time = strtotime($start_time);
            $end_time = strtotime($end_time);
            $start_time_i = date("i", $start_time);
            $end_time_i = date("i", $end_time);
            $start_time_h = date("H", $start_time);
            $end_time_h = date("H", $end_time);
            if ((strlen($start_time) != 10) && (strlen($end_time) != 10)) {
                _message(L("record.num.err"));
            }

            if ($end_time < $start_time) {
                _message(L("record.time.err"));
            }

            $start_time .= ".000";
            $end_time .= ".000";
            $selectwords = "`otime` > '$start_time' and `otime` < '$end_time' order by `otime` desc limit 0,20";
            $RecordList = $order_db->ready_order($selectwords, 1, "");
        }
        else {
            $time = time();
            $start_time = ($time - 7200) . ".000";
            $end_time = $time . ".000";
            $start_time_h = date("H", $start_time);
            $end_time_h = date("H", $end_time);
            $selectwords = "`otime` > '$start_time' and `otime` < '$end_time' order by `otime` desc limit 0,20";
            $RecordList = $order_db->ready_order($selectwords, 1, "");
        }

        $selecteds_shi_start = $selecteds_shi_end = array();
        $selecteds_shi = 23;

        for ($i = 0; $i < $selecteds_shi; $i++) {
            if ($i == $start_time_h) {
                $selecteds_shi_start[$i]["selected"] = " selected=\"selected\"";
            }
            else {
                $selecteds_shi_start[$i]["selected"] = "";
            }

            $selecteds_shi_start[$i]["num"] = $i;

            if ($i < 10) {
                $selecteds_shi_start[$i]["num"] = "0" . $i;
            }
        }

        for ($i = 0; $i < $selecteds_shi; $i++) {
            if ($i == $end_time_h) {
                $selecteds_shi_end[$i]["selected"] = " selected=\"selected\"";
            }
            else {
                $selecteds_shi_end[$i]["selected"] = "";
            }

            $selecteds_shi_end[$i]["num"] = $i;

            if ($i < 10) {
                $selecteds_shi_end[$i]["num"] = "0" . $i;
            }
        }

        $record_fen = 59;
        $selecteds_fen_start = $selecteds_fen_end = array();
        $selecteds_fen = array();

        for ($i = 0; $i < $record_fen; $i++) {
            if ($i == $start_time_i) {
                $selecteds_fen_start[$i]["selected"] = " selected=\"selected\"";
            }
            else {
                $selecteds_fen_start[$i]["selected"] = "";
            }

            $selecteds_fen_start[$i]["num"] = $i;

            if ($i < 10) {
                $selecteds_fen_start[$i]["num"] = "0" . $i;
            }
        }

        for ($i = 0; $i < $record_fen; $i++) {
            if ($i == $end_time_i) {
                $selecteds_fen_end[$i]["selected"] = " selected=\"selected\"";
            }
            else {
                $selecteds_fen_end[$i]["selected"] = "";
            }

            $selecteds_fen_end[$i]["num"] = $i;

            if ($i < 10) {
                $selecteds_fen_end[$i]["num"] = "0" . $i;
            }
        }

        $n = 1;
        $RecordList = empty($RecordList)?array():$RecordList;
        foreach ($RecordList as $key => $v ) {
            $RecordList[$key]["otime"] = explode(".", $v["otime"]);
            $n++;

            if (($n % 2) == 0) {
                $RecordList[$key]["class"] = "Record_content";
            }
            else {
                $RecordList[$key]["class"] = "Record_contents";
            }
        }

        $this->view->show("index.buyrecord")->data("RecordList", $RecordList)->data("start_time", $start_time)->data("end_time", $end_time)->data("selecteds_shi_start", $selecteds_shi_start)->data("selecteds_shi_end", $selecteds_shi_end)->data("selecteds_fen_start", $selecteds_fen_start)->data("selecteds_fen_end", $selecteds_fen_end);
    }

    public function buyrecordbai()
    {
        seo("title", _cfg("web_name") . "_最新100条历史记录");
        seo("keywords", _cfg("web_name") . "_最新100条历史记录");
        seo("description", _cfg("web_name") . "_最新100条历史记录");
        $cloud_goodsdb = System::load_app_model("cloud_goods", "common");
        $order_db = System::load_app_model("order", "common");
        $selectwords = "1";
        $res = $order_db->ready_order($selectwords, 1);

        foreach ($res as $v ) {
            $SUM += $v["onum"];
        }

        $setkey = "`value`='$SUM'";
        $where = "`name`='goods_count_num'";
        $cloud_goodsdb->upgoods_count_num($setkey, $where);
        $wherewords = "1 order by oid desc limit 0,100";
        $RecordList = $order_db->ready_order($wherewords, 1);
        $n = 1;

        foreach ($RecordList as $key => $v ) {
            $RecordList[$key]["otime"] = explode(".", $v["otime"]);
            $n++;

            if (($n % 2) == 0) {
                $RecordList[$key]["class"] = "Record_content";
            }
            else {
                $RecordList[$key]["class"] = "Record_contents";
            }
        }

        $this->view->show("index.buyrecordbai")->data("RecordList", $RecordList)->data("res", $res);
    }

    public function skinchange()
    {
        $ini = System::load_sys_config("view");
        $skin = $this->segment(4);
        if (empty($skin) || ($skin == "-this")) {
            _setcookie("skin", NULL, -1);
            $this->SendStatus("location", G_HTTP_REFERER ? G_HTTP_REFERER : G_WEB_PATH);
        }

        if (!isset($ini["templates"][$skin])) {
            $this->SendStatus(404);
        }

        _setcookie("skin", _encrypt($skin));
        $this->SendStatus("location", G_WEB_PATH);
    }

    /**
     * 获取主页三级联动地区
     */
    public function index_area()
    {
        /* 地区 Model */
        $area_model = System::load_app_model( 'area', 'common' );
        $area_list = $area_model->get_area_list( 'area_deep < 3', '*', 'area_sort' );
        $handle_area = array();
        foreach ( $area_list as $k => $v )
        {
            if ( $v['area_deep'] == 1 )
            {
                $handle_area['province'][ $v['area_id'] ] = $v['area_name'];
            }
            else if ( $v['area_deep'] == 2 )
            {
                $second_area = array( 'id' => $v['area_id'], 'name' => $v['area_name'] );
                $handle_area['city'][ $v['area_parent_id'] ][] = $second_area;
            }
        }
        echo json_encode( $handle_area );die;
    }

    /**
     * 获取子级地区     
     */
    public function get_clild_area()
    {
        /* 地区 Model */
        $area_model = System::load_app_model( 'area', 'common' );

        $area_id = $this->segment( 4 );
        $condition = 'area_parent_id =' . $area_id;
        $area_list = $area_model->get_area_list( $condition, 'area_id, area_name', 'area_sort' );
        exit( json_encode( $area_list ) );
    }

    /**
     * 获取地区名称
     */
    public function get_area_name()
    {
        /* 地区 Model */
        $area_model = System::load_app_model( 'area', 'common' );
        $area_id = intval( $this->segment( 4 ) );
        $area_name = $area_model->get_area_name( 'area_id = ' . $area_id );
        exit( $area_name );
    }

    /**
     * 经纬度获取城市
     */
    public function get_geocoder()
    {
        /* 地区 Model */
        $area_model = System::load_app_model( 'area', 'common' );
        $location   = trim( $_GET['location'] );
        $url = 'http://api.map.baidu.com/geocoder/v2/';
        $url .= '?ak=ecyp2qk78optvRY2Uq6ZYdNg4k8gVIh7&location='. $location .'&output=json';
        $result = file_get_contents( $url );
        $result = json_decode( $result, true );
        $area_name = $result['result']['addressComponent']['district'];
        $area_id = $area_model->get_area_name( "area_name = '$area_name'", 'area_id' );
        if ( $area_id )
        {
            _setcookie( 'area_id', $area_id, 86400 * 30 );
            exit( 'true' );
        }
    }

}