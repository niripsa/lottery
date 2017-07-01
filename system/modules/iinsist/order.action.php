<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class order extends admin
{
    private $model;
    private $ment;
    private $m_share;
    private $share_ment;
    private $m_user;

    public function __construct()
    {
        parent::__construct();
        $this->model   = System::load_app_model("order", "common");
        $this->m_share = System::load_app_model("share", "common");
        $this->m_user  = System::load_app_model("member", "common");
        $this->goods   = System::load_app_model("goods", "common");
        $this->ment = array(
            array("lists", "夺宝订单列表", ROUTE_M . "/" . ROUTE_C . "/lists"),
            array("lists", "中奖订单", ROUTE_M . "/" . ROUTE_C . "/lists/win"),
            array("lists", "已发货", ROUTE_M . "/" . ROUTE_C . "/lists/sendok"),
            array("lists", "未发货", ROUTE_M . "/" . ROUTE_C . "/lists/notsend"),
            array("insert", "已完成", ROUTE_M . "/" . ROUTE_C . "/lists/ok"),
            array("insert", "已作废", ROUTE_M . "/" . ROUTE_C . "/lists/del"),
            array("insert", "待收货", ROUTE_M . "/" . ROUTE_C . "/lists/shouhuo"),
            array("genzhong", "快递跟踪", ROUTE_M . "/" . ROUTE_C . "/genzhong")
        );
        $this->nment = array(
            array("lists", "普通订单列表", ROUTE_M . "/" . ROUTE_C . "/nlists"),
            array("lists", "已发货", ROUTE_M . "/" . ROUTE_C . "/nlists/sendok"),
            array("lists", "未发货", ROUTE_M . "/" . ROUTE_C . "/nlists/notsend"),
            array("insert", "已完成", ROUTE_M . "/" . ROUTE_C . "/nlists/ok"),
            array("insert", "已作废", ROUTE_M . "/" . ROUTE_C . "/nlists/del"),
            array("insert", "待收货", ROUTE_M . "/" . ROUTE_C . "/nlists/shouhuo"),
            array("genzhong", "快递跟踪", ROUTE_M . "/" . ROUTE_C . "/genzhong")
        );
        $this->share_ment = array(
            array("lists", "晒单管理", ROUTE_M . "/" . ROUTE_C . "/share"),
            array("addcate", "晒单回复管理", ROUTE_M . "/" . ROUTE_C . "/share_msg")
        );
    }

    public function genzhong()
    {
        $this->view->tpl("order.genzhong")->data("ment", $this->ment);
    }

    /**
     * 夺宝订单列表
     */
    public function lists()
    {
        $status_arr = array( 1 => "未付款", 2 => "已付款", 3 => "退款中", 4 => "已退款" );
        $fstatus_arr = array(
            -1 => "<span style='color:red;'>已作废</span>", 
            1  => "未发货", 
            2  => "已发货", 
            3  => "已收货"
        );
        $where = $this->segment(4);
        switch ( $where )
        {
            case 'win':
                $list_where = "`ofstatus` > 0";
            break;
            case 'sendok':
                $list_where = "`ofstatus` = 2 ";
            break;
            case 'notsend':
                $list_where = "`ofstatus` = 1";
            break;
            case 'ok':
                $list_where = "`ofstatus` = 3";
            break;
            case 'del':
                $list_where = "`ofstatus` = -1";
            break;
            default:
                $list_where = "`ofstatus` >= 1";
            break;
        }

        $order = "";
        if ( isset( $_POST["paixu_submit"] ) )
        {
            $paixu = $_POST["paixu"];
            switch ( $paixu )
            {
                case 'time1':
                    $order .= " `otime` DESC";
                break;
                case 'time2':
                    $order .= " `otime` ASC";
                break;
                case 'money1':
                    $order .= " `omoney` DESC";
                break;
                case 'money2':
                    $order .= " `omoney` ASC";
                break;
            }
        }
        else
        {
            $order .= " `otime` DESC";
            $paixu = "time1";
        }

        $num   = 15;
        $total = $this->model->ready_order_num( $list_where );
        $page  = System::load_sys_class("page");
        $page->config( $total, $num );
        $recordlist = $this->model->ready_order( $list_where, 1, "*", $order, $page->setlimit(1) );
        foreach ( $recordlist as &$row )
        {
            $row["status_txt"]   = $status_arr[$row["ostatus"]];
            $row["ofstatus_txt"] = $fstatus_arr[$row["ofstatus"]];
        }
        /* 导出操作 */
        if ( 'export' == $this->segment(5) )
        {
            $this->export_excel( $recordlist );
        }

        $this->view->data( "where", $where );
        $this->view->data( "url", G_ADMIN_PATH . "/" . ROUTE_C . "/" . ROUTE_A );
        $this->view->data( "recordlist", $recordlist );
        $this->view->data( "paixu", $paixu );
        $this->view->data( "page", $page->show( "li", true ) );
        $this->view->data( "ment", $this->ment );
        $this->view->tpl( "order.list" );
    }

    /**
     * 将查询到的订单导出到excel
     */
    public function export_excel( $data )
    {
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-type: text/html; charset=utf-8');
        header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
        $excel_name = '订单列表' . date( 'Y-m-d', time() );
        header("Content-Disposition:filename=". $excel_name .".xls"); // 输出的表格名称
        header("Content-Transfer-Encoding:binary");

        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . "PHPExcel".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . "PHPExcel/IOFactory".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . "PHPExcel/Writer/Excel2007".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . "PHPExcel/Writer/Excel5".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . "PHPExcel/Worksheet/Drawing".".php";

        $obj = new PHPExcel();
        $obj->getProperties()->setCreator('一元夺宝');
        $obj->getProperties()->setLastModifiedBy("James zheng");
        $obj->getProperties()->setTitle("订单列表");
        $obj->getProperties()->setSubject("订单列表");
        $obj->getProperties()->setDescription("订单列表导出");
        $obj->setActiveSheetIndex(0);
        $obj->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $obj->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $obj->getActiveSheet()->getStyle('A1:R1')->getFont()->setSize(12);
        $obj->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
        $obj->getActiveSheet()->getStyle('A1:R1')->getFont()->getColor()->setARGB(FFCC66CC);
        
        $obj->getActiveSheet()->SetCellValue( 'A1', '订单号' );
        $obj->getActiveSheet()->SetCellValue( 'B1', '商品标题' );
        $obj->getActiveSheet()->SetCellValue( 'C1', '购买用户' );
        $obj->getActiveSheet()->SetCellValue( 'D1', '购买总价' );
        $obj->getActiveSheet()->SetCellValue( 'E1', '购买日期' );
        $obj->getActiveSheet()->SetCellValue( 'F1', '中奖' );

        $obj->setActiveSheetIndex(0);
        $i = 2;
        foreach ( $data as $k => $info )
        {
            /* 订单号 */
            $obj->getActiveSheet()->SetCellValue( 'A'.$i , $info['ocode'] );
            $obj->getActiveSheet()->getColumnDimension( 'A' )->setWidth( 20 );
            /* 商品标题 */
            $obj->getActiveSheet()->SetCellValue( 'B'.$i , _unser( $info['og_title'], "g_title" ) );
            $obj->getActiveSheet()->getColumnDimension( 'B' )->setWidth( 50 );
            /* 购买用户 */
            $obj->getActiveSheet()->SetCellValue( 'C'.$i , $info['ou_name'] );
            $obj->getActiveSheet()->getColumnDimension( 'C' )->setWidth( 20 );
            /* 购买总价 */
            $obj->getActiveSheet()->SetCellValue( 'D'.$i , $info['omoney'] );
            $obj->getActiveSheet()->getColumnDimension( 'D' )->setWidth( 10 );
            /* 购买日期 */
            $obj->getActiveSheet()->SetCellValue( 'E'.$i , date( "Y-m-d H:i:s", $info['otime'] ) );
            $obj->getActiveSheet()->getColumnDimension( 'E' )->setWidth( 20 );
            /* 中奖 */
            $obj->getActiveSheet()->SetCellValue( 'F'.$i , $info['ofstatus_txt'] );
            $i++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter( $obj, 'Excel5' );
        $objWriter->save( 'php://output' );
        exit;
    }

    public function nlists()
    {
        $status_arr = array(1 => "未付款", 2 => "已付款", 3 => "退款中", 4 => "已退款");
        $fstatus_arr = array(-1 => "<span style='color:red;'>已作废</span>", 1 => "未发货", 2 => "已发货", 3 => "已收货");
        $where = $this->segment(4);

        if ($where == "win") {
            $list_where = "`ofstatus` > 0";
        }
        else if ($where == "sendok") {
            $list_where = "`ofstatus` = 2 ";
        }
        else if ($where == "notsend") {
            $list_where = "`ofstatus` = 1 ";
        }
        else if ($where == "ok") {
            $list_where = " `ofstatus` = 3";
        }
        else if ($where == "del") {
            $list_where = "`ofstatus` = -1";
        }
        else {
            $list_where = "`ostatus` >= 1";
        }

        $order = "";

        if (isset($_POST["paixu_submit"])) {
            $paixu = $_POST["paixu"];

            if ($paixu == "time1") {
                $order .= " `otime` DESC";
            }

            if ($paixu == "time2") {
                $order .= " `otime` ASC";
            }

            if ($paixu == "money1") {
                $order .= " `omoney` DESC";
            }

            if ($paixu == "money2") {
                $order .= " `omoney` ASC";
            }
        }
        else {
            $order .= "  `otime` DESC";
            $paixu = "time1";
        }

        $list_where = (empty($list_where) ? "`otype=2`" : $list_where . " AND `otype`=2 ");
        $num = 15;
        $total = $this->model->get_order_num($list_where);
        $page = System::load_sys_class("page");
        $page->config($total, $num);
        $recordlist = $this->model->get_order($list_where, "*", $order, $page->setlimit(1));

        foreach ($recordlist as &$row ) {
            $info = $this->model->get_order_info("`oid`=" . $row["oid"]);
            $row["g_title"] = _unser($info["otext"], "g_title");
            $row["status_txt"] = $status_arr[$row["ostatus"]];
            $row["ofstatus_txt"] = $fstatus_arr[$row["ofstatus"]];
        }

        $this->view->data("recordlist", $recordlist);
        $this->view->data("paixu", $paixu);
        $this->view->data("page", $page->show("li", true));
        $this->view->tpl("order.nlist")->data("ment", $this->nment);
    }

    /**
     * 订单详情
     */
    public function detail()
    {
        $status_arr = array(1 => "未付款", 2 => "已付款", 3 => "退款中", 4 => "已退款");
        $fstatus_arr = array(-1 => "已作废", 1 => "未发货", 2 => "已发货", 3 => "已收货");
        $code                 = abs(intval($this->segment(4)));
        $record               = $this->model->get_one_order( $code, "*", "*" );
        $record["status_txt"] = $status_arr[$record["ostatus"]];
        $user                 = $this->m_user->get_user_one("`uid`='" . $record["ouid"] . "'");
        $user_add             = $this->m_user->get_user_addr_list("`uid`='" . $record["ouid"] . "'");
        $user_record          = $this->m_user->get_record_one("`oid`='" . $record["oid"] . "'");
        $cloud_goods          = $this->goods->cloud_qishu_one("`id`='" . $record["ogid"] . "'");
        $goods                = $this->goods->cloud_goods_one("a.`gid`='" . $cloud_goods["gid"] . "'");

        if ( ! $record ) {
            _message("参数不正确!");
        }

        if ( isset( $_POST["submit"] ) )
        {
            $data = _post();
            unset($data["submit"]);
            $data["etype"]  = 3;
            $data["etime"]  = time();
            $data["emoney"] = floatval($data["emoney"]);

            if ( ! $data["emoney"] )
            {
                $data["emoney"] = "0.01";
            }
            else
            {
                $data["emoney"] = sprintf( "%.2f", $data["emoney"] );
            }
            $rs = $this->model->send_goods( $data );
            if ( $rs )
            {
                _message("更新成功", G_ADMIN_PATH . "/" . ROUTE_C . "/detail/" . $code);
            }
            else
            {
                _message("更新失败");
            }
        }

        $ems = $this->model->get_ems();
        $this->view->data("ems", $ems);
        $ship = $this->model->get_ship_one( "`oid`='" . $record["oid"] . "' and `etype` = 3" );
        $this->view->data("ship", $ship);
        $this->view->data("record", $record);
        $this->view->data("user", $user);
        $this->view->data("user_add", $user_add);
        $this->view->data("cloud_goods", $cloud_goods);
        $this->view->data("goods", $goods);
        $this->view->data("user_record", $user_record);
        $this->view->data("ment", $this->ment);
        $this->view->tpl("order.detail");
    }

    public function ndetail()
    {
        $status_arr = array(1 => "未付款", 2 => "已付款", 3 => "退款中", 4 => "已退款");
        $fstatus_arr = array(-1 => "已作废", 1 => "未发货", 2 => "已发货", 3 => "已收货");
        $code = abs(intval($this->segment(4)));
        $record = $this->model->get_order_one("`oid`='" . $code . "'");
        $record["status_txt"] = $status_arr[$record["ostatus"]];
        $user = $this->m_user->get_user_one("`uid`='" . $record["ouid"] . "'");
        $user_add = $this->m_user->get_user_addr_list("`uid`='" . $record["ouid"] . "'");
        $user_record = $this->m_user->get_record_one("`oid`='" . $record["oid"] . "'");
        $info = $this->model->get_order_info("`oid`=" . $record["oid"]);
        $goods = unserialize($info["otext"]);

        if (!$record) {
            _message("参数不正确!");
        }

        if (isset($_POST["submit"])) {
            $data = _post();
            $order["ofstatus"] = $data["ofstatus"];
            $rs = $this->model->save_order("`oid`='" . $data["oid"] . "' ", $order);
            unset($data["submit"]);

            if ($data["ofstatus"] == 2) {
                $data["emoney"] = floatval($data["emoney"]);

                if (!$data["emoney"]) {
                    $data["emoney"] = "0.01";
                }
                else {
                    $data["emoney"] = sprintf("%.2f", $data["emoney"]);
                }

                $data["etime"] = time();
                $data["etype"] = 1;
                unset($data["ofstatus"]);
                $res = $this->model->send_goods($data);
                if ($rs && $res) {
                    _message("更新成功", G_ADMIN_PATH . "/" . ROUTE_C . "/ndetail/" . $code);
                }
                else {
                    _message("更新失败");
                }
            }
            else if ($rs) {
                _message("更新成功", G_ADMIN_PATH . "/" . ROUTE_C . "/ndetail/" . $code);
            }
            else {
                _message("更新失败");
            }
        }

        $ems = $this->model->get_ems();
        $this->view->data("ems", $ems);
        $ship = $this->model->get_ship_one("`oid`='" . $record["oid"] . "' and `etype` =1");
        $this->view->data("ship", $ship);
        $this->view->data("record", $record);
        $this->view->data("user", $user);
        $this->view->data("user_add", $user_add);
        $this->view->data("cloud_goods", $cloud_goods);
        $this->view->data("goods", $goods);
        $this->view->data("user_record", $user_record);
        $this->view->tpl("order.ndetail")->data("ment", $this->ment);
    }

    /**
     * 订单搜索
     */
    public function select()
    {
        $Rconfig    = System::load_sys_config("param");
        $page       = System::load_sys_class("page");
        $num        = 10;
        $status_arr = array( "未知", "未付款", "已付款", "退款中", "已退款" );
        $record     = "";
        
        $search = array();        
        if ( ! preg_match( "/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['ocode'] = $this->segment( 4 );
        }
        $search['user']        = $this->segment( 5 );
        $search['user_val']    = $this->segment( 6 );
        $search['goods']       = $this->segment( 7 );
        $search['ogid']        = $this->segment( 8 );
        $search['start_otime'] = $this->segment( 9 );
        $search['end_otime']   = $this->segment( 10 );

        $where = '1 = 1';
        /* 订单号查询 */
        if ( $search['ocode'] )
        {
            $where .= " AND `ocode` = '" . $search['ocode'] . "'";
        }
        /* 用户UID, 用户名称, 管理商UID 查询 */
        if ( $search['user_val'] && $search['user'] )
        {
            $where .= " AND `". $search['user_val'] ."` = '" . $search['user'] . "'";
        }
        /* 商品ID 查询 */
        if ( $search['goods'] && $search['ogid'] )
        {
            $where .= " AND `". $search['goods'] ."` = '" . $search['ogid'] . "'";
        }
        /* 添加时间 查询 */
        if ( $search['start_otime'] || $search['end_otime'] )
        {
            $search['start_otime'] = str_replace( '_', ' ', $search['start_otime'] );
            $search['end_otime']   = str_replace( '_', ' ', $search['end_otime'] );            
            $start_otime = strtotime( $search['start_otime'] ) ? : 0;
            $end_otime   = strtotime( $search['end_otime'] ) ? : time();
            $where .= " AND otime BETWEEN ". $start_otime ." AND " . $end_otime;
        }

        /* 结算金额 = 总金额 * 0.95 */
        $settle_money = $this->model->get_sum( 'cloud_select', $where, 'omoney' );
        $settle_money = $settle_money . ' * 0.95 = ' . $settle_money * 0.95;
        $this->view->data( 'settle_money', $settle_money );

        $total  = $this->model->ready_order_num( $where, 1 );
        $page->config( $total, $num, "/ocode/" . $str_v);
        $where .= ' ORDER BY otime DESC';
        $record = $this->model->ready_order( $where, 1, "", "", $page->setlimit(1) );

        foreach ( $record as &$row ) {
            $row["status_txt"] = $status_arr[$row["ostatus"]];
        }

        $this->view->data("search", $search);
        $this->view->data("str", $str);
        $this->view->data("str_v", $str_v);
        $this->view->data("data", $data);
        $this->view->data("record", $record);
        $this->view->data("page", $page->show("li", true));
        $this->view->data("url", G_ADMIN_PATH . "/" . ROUTE_C . "/select");
        $this->view->data("ment", $this->ment);
        $this->view->tpl( "order.select" );
    }

    public function share()
    {
        $num = 20;
        $total = $this->m_share->get_share_num("");
        $page = System::load_sys_class("page");
        $page->config($total, $num);
        $shaidan = $this->m_share->get_share("", "", "sd_id desc", $page->setlimit(1));
        $this->view->data("shaidan", $shaidan);
        $this->view->data("total", $total);
        $this->view->data("page", $page->show("one", true));
        $this->view->tpl("order.share")->data("ment", $this->share_ment);
    }

    public function share_del()
    {
        $id = intval($this->segment(4));
        $res = $this->m_share->share_del("`sd_id`='" . $id . "'");

        if ($res) {
            _message("删除成功", G_ADMIN_PATH . "/" . ROUTE_C . "/share");
        }
        else {
            _message("参数错误");
        }
    }

    public function share_msg()
    {
        $num = 20;
        $total = $this->m_share->get_share_msg_num("");
        $page = System::load_sys_class("page");

        if (isset($_GET["p"])) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config($total, $num, $pagenum, "0");

        if ($page->page < $pagenum) {
            $pagenum = $page->page;
        }

        $start_record = ($pagenum - 1) * $num;
        $shaidan = $this->m_share->get_share_msg("", "", "id desc", $start_record . "," . $num);

        foreach ($shaidan as &$row ) {
            $member = $this->m_user->get_user_one("`uid`='" . $row["sdhf_userid"] . "'");

            if ($member["username"] != NULL) {
                $row["member"] = $member["username"];
            }
            else if ($member["mobile"] != NULL) {
                $row["member"] = $member["mobile"];
            }
            else if ($member["email"] != NULL) {
                $row["member"] = $member["email"];
            }
        }

        $this->view->data("shaidan", $shaidan);
        $this->view->data("total", $total);
        $this->view->data("page", $page->show("one", "li"));
        $this->view->tpl("order.share_msg")->data("ment", $this->share_ment);
    }

    public function share_msg_del()
    {
        $id = intval($this->segment(4));
        $res = $this->m_share->share_msg_del("`id`='" . $id . "'");

        if ($res) {
            _message("删除成功", G_ADMIN_PATH . "/" . ROUTE_C . "/share_msg");
        }
        else {
            _message("参数错误");
        }
    }

    public function search()
    {
        $status_arr = array(1 => "未付款", 2 => "已付款", 3 => "退款中", 4 => "已退款");
        $keyword = _post("s");
        $user_where = "username like '%" . $keyword . "%' OR email like '%" . $keyword . "%' OR mobile like '%" . $keyword . "%'";
        $order_where = "`ostatus` >= 1 AND `otype`=3 AND ocode LIKE '%" . $keyword . "%'";
        $goods_where = "`g_type`=1 AND g_title LIKE '%" . $keyword . "%'";
        $cgoods_where = "a.g_type=3  AND a.g_title LIKE '%" . $keyword . "%'";

        if (is_numeric($keyword)) {
            $user_where .= " OR uid='" . $keyword . "'";
            $goods_where = "`g_type`=1 AND (g_title LIKE '%" . $keyword . "%' OR gid='" . $keyword . "')";
            $cgoods_where = "a.g_type=3  AND (a.g_title LIKE '%" . $keyword . "%' OR a.gid='" . $keyword . "')";
        }

        $members = $this->m_user->get_user_list($user_where);
        $order = $this->model->get_order($order_where);

        foreach ($order as &$row ) {
            $info = $this->model->get_order_info("`oid`='" . $row["oid"] . "'");
            $row["info"] = unserialize($info["otext"]);
            $row["status_txt"] = $status_arr[$row["ostatus"]];
        }

        $m_cate = System::load_app_model("cate", "common");
        $goods = $this->goods->get_goods($goods_where);

        foreach ($goods as &$row ) {
            $row["cate_name"] = $m_cate->get_cate_name("cateid=" . $row["g_cateid"]);
        }

        $cgoods = $this->goods->cloud_goods($cgoods_where);

        foreach ($cgoods as &$row ) {
            $row["cate_name"] = $m_cate->get_cate_name("cateid=" . $row["g_cateid"]);
        }

        $this->view->data("search_user", $members);
        $this->view->data("search_order", $order);
        $this->view->data("search_goods", $goods);
        $this->view->data("search_cgoods", $cgoods);
        $this->view->data("s", $keyword);
        $this->view->tpl("order.search");
    }
}
?>
