<?php
defined("G_IN_SYSTEM") || exit("no");
System::load_app_class("admin", G_ADMIN_DIR, "no");
class member extends admin
{
    public $member_count_num = 0;
    public $member_new_num = 0;
    private $db;
    private $model;
    private $ments;

    public function __construct()
    {
        parent::__construct();
        $this->model = System::load_app_model("member", "common");
        $this->order = System::load_app_model("order", "common");
        /* 地区 Model */
        $this->area_model = System::load_app_model( 'area', 'common' );
        $this->ments = array(
            array("lists", "会员列表", ROUTE_M . "/" . ROUTE_C . "/lists"),
            array("lists", "查找会员", ROUTE_M . "/" . ROUTE_C . "/select"),
            array("insert", "添加会员", ROUTE_M . "/" . ROUTE_C . "/insert"),
            array("insert", "会员配置", ROUTE_M . "/" . ROUTE_C . "/config"),
            // array("insert", "<b>会员福利配置</b>", ROUTE_M . "/" . ROUTE_C . "/member_fufen"),
            array("insert", "充值记录", ROUTE_M . "/" . ROUTE_C . "/recharge")
        );
        $this->member_count_num = $this->model->get_count();
        $time = strtotime(date("Y-m-d"));
        $this->member_new_num = $this->model->get_count(" `time` > '" . $time . "'");
        /* 管理商等级 0(普通会员) 1 2 3 */
        $this->manage_rank_arr = array( '0' => '普通会员', '1' => '一级', '2' => '二级', '3' => '三级' );
    }

    public function lists()
    {
        $user_type   = ( ! $this->segment(4) ? "def" : $this->segment(4));
        $user_ziduan = ( ! $this->segment(5) ? "uid" : $this->segment(5));
        $user_order  = ( ! $this->segment(6) ? "desc" : $this->segment(6));
        $user_type_arr = array(
            "def"      => "默认会员", 
            "del"      => "删除会员", 
            "noreg"    => "未认证会员", 
            "day_new"  => "今日新增", 
            "day_shop" => "今日消费", 
            "rank1"    => "一级", 
            "rank2"    => "二级", 
            "rank3"    => "三级", 
            "b_qq"     => "QQ绑定会员", 
            "b_weibo"  => "微博绑定会员", 
            "b_taobao" => "淘宝绑定会员"
        );

        if ( ! isset( $user_type_arr[$user_type] ) )
        {
            $user_type = "def";
        }

        $user_ziduan_arr = array(
            "uid"        => "会员ID", 
            "money"      => "账户金额", 
            "score"      => "账户福分", 
            "jingyan"    => "会员经验", 
            "time"       => "注册时间", 
            "login_time" => "登陆时间"
        );

        if ( ! isset( $user_ziduan_arr[$user_ziduan] ) )
        {
            $user_ziduan = "uid";
        }

        if ( ($user_order != "desc") && ($user_order != "asc") )
        {
            $user_order    = "desc";
            $user_order_cn = "倒序显示";
        }
        else {
            $user_order_cn = "正序显示";
        }

        $sql_where = "";

        switch ( $user_type )
        {
            case "noreg":
                $sql_where = "`emailcode` <> '1' and `mobilecode` <> '1'";
            break;

            case "day_new":
                $day_time = strtotime( date("Y-m-d") . " 00:00:00" );
                $sql_where = "`time` > '$day_time'";
            break;

            case "day_shop":
            $day_time = strtotime( date("Y-m-d") ) . ".000";
            $uids = "";
            $conutc = $this->model->get_record( "ur_time` > '" . $day_time . "'", "ur_uid" );

            foreach ( $conutc as $c ) {
                $uids .= "'" . $c["ur_uid"] . "',";
            }

            $uids = trim( $uids, "," );

            if ( ! empty( $uids ) )
            {
                $sql_where = "`uid` in ( $uids )";
            }
            else {
                $sql_where = "`uid` in ( '0' )";
            }
            break;

            case "del":
                $sql_where = "`status` = -1";
            break;
            /* 一级管理商 */
            case 'rank1':
                $sql_where = "`manage_rank` = 1";
            break;
            /* 二级管理商 */
            case 'rank2':
                $sql_where = "`manage_rank` = 2";
            break;
            /* 三级管理商 */
            case 'rank3':
                $sql_where = "`manage_rank` = 3";
            break;
        }

        $this_path    = WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/" . ROUTE_A;
        $select_where = "当前查看$user_type_arr[$user_type] - 使用$user_ziduan_arr[$user_ziduan] - $user_order_cn";
        $num   = 10;
        $total = $this->model->get_count( $sql_where );
        $page  = System::load_sys_class( "page" );

        if ( isset( $_GET["p"] ) ) {
            $pagenum = $_GET["p"];
        }
        else {
            $pagenum = 1;
        }

        $page->config( $total, $num );
        $members = $this->model->get_user_list( $sql_where, "*", "`$user_ziduan` $user_order", $page->setlimit(1) );
        foreach ( (array)$members as $k => $v )
        {
            if ( $v['area_id'] > 0 )
            {
                $members[ $k ]['area_name'] = $this->area_model->get_area_name( 'area_id=' . $v['area_id'] );
            }
            else
            {
                $members[ $k ]['area_name'] = '无地区';
            }
        }
        
        $this->view->data( 'user_ziduan', $user_ziduan );
        $this->view->data( 'user_ziduan_arr', $user_ziduan_arr );
        $this->view->data( 'manage_rank_arr', $this->manage_rank_arr );
        $this->view->data("member_count_num", $this->member_count_num);
        $this->view->data("member_new_num", $this->member_new_num);
        $this->view->data("ments", $this->ments);
        $this->view->data("total", $total);
        $this->view->data("this_path", $this_path);
        $this->view->data("select_where", $select_where);
        $this->view->data("page", $page->show("two"));
        $this->view->data("members", $members);
        $this->view->tpl("member.lists");
    }

    /**
     * 添加会员
     */
    public function insert()
    {
        $member_allgroup = $this->model->get_group("", "groupid,`name`");

        if ( isset( $_POST["submit"] ) ) {
            $data = _post();
            unset( $data["submit"] );

            if ( empty( $data["username"] ) ) {
                _message("用户名不能为空");
                exit();
            }

            if ( empty( $data["password"] ) ) {
                _message("密码不能为空");
                exit();
            }
            else {
                $data["password"] = md5(md5($data["password"]) . md5($data["password"]));
            }

            $data["time"] = time();

            if ( ! empty( $data["email"] ) ) {
                $info = $this->model->get_user_one("`email` = '" . $data["email"] . "'");
            }
            else {
                $info = $this->model->get_user_one("`mobile` = '" . $data["mobile"] . ".");
            }

            if ( $info ) {
                _message("该会员已经存在！");
            }

            if ( $data['manage_rank'] > 1 )
            {
                _message("管理员只能开通一级管理商！");
            }

            $res = $this->model->user_add( $data );

            if ( $res ) {
                _message( "增加成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/lists" );
            }
            else {
                _message("增加失败");
            }
        }
        /* 省份 Start */
        $area_list = $this->area_model->get_area_list( 'area_deep = 1', 'area_id, area_name', 'area_sort' );
        $this->view->data( 'area_list', $area_list );
        /* 省份 End */
        
        $this->view->data( 'manage_rank_arr', $this->manage_rank_arr );
        $this->view->data("member_allgroup", $member_allgroup);
        $this->view->data("ments", $this->ments);
        $this->view->tpl("member.insert");
    }

    /**
     * 会员修改
     */
    public function modify()
    {
        $uid = intval($this->segment(4));
        $member = $this->model->get_user_one("`uid`='" . $uid . "'");
        if ( $member["groupid"] )
        {
            $member_group = $this->model->get_group_one("`groupid`=" . $member["groupid"] . "");
        }
        $member_allgroup = $this->model->get_group();

        if ( isset( $_POST["submit"] ) )
        {
            $data = _post();
            if ( ! $data['area_id'] )
            {
                unset( $data['area_id'] );
            }
            unset($data["submit"]);

            if (empty($data["password"])) {
                unset($data["password"]);
            }
            else {
                $data["password"] = md5(md5($data["password"]) . md5($data["password"]));
            }

            if ( $data["money"] != $member["money"] )
            {
                if ( $member["money"] < $data["money"] ) {
                    $content_money = $data["money"] - $member["money"];
                    $content_num = "1";
                }
                else {
                    $content_money = $member["money"] - $data["money"];
                    $content_num = "-1";
                }

                $acc_arr["uid"]     = $uid;
                $acc_arr["type"]    = $content_num;
                $acc_arr["pay"]     = "账户";
                $acc_arr["content"] = "管理员修改金额";
                $acc_arr["money"]   = $content_money;
                $acc_arr["time"]    = time();
                $text = ($content_num == "1" ? "管理员增加了:" . $content_money : "管理员减少了:" . $content_money);
                $this->order->user_add_chongzhi($uid, $content_money, $text);
                $rx = $this->model->user_account_add($acc_arr);
            }

            $res = $this->model->user_save("`uid`='" . $uid . "'", $data);

            if ( $res !== false ) {
                _message("修改成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/lists");
            }
            else {
                _message("修改失败");
            }
        }
        /* 省份 Start */
        $area_list = $this->area_model->get_area_list( 'area_deep = 1', 'area_id, area_name', 'area_sort' );
        $this->view->data( 'area_list', $area_list );
        /* 省份 End */

        $area_info = $this->area_model->get_area_one( "area_id = {$member['area_id']}", 'area_name' );
        $member['area_name'] = $area_info['area_name'];

        $this->view->data( 'manage_rank_arr', $this->manage_rank_arr );
        $this->view->data("member_group", $member_group);
        $this->view->data("member_allgroup", $member_allgroup);
        $this->view->data("ments", $this->ments);
        $this->view->data("member", $member);

        $this->view->tpl("member.insert");
    }

    public function huifu()
    {
        $uid = intval($this->segment(4));
        $res = $this->model->user_restore($uid);

        if ($res) {
            _message("恢复成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/lists");
        }
        else {
            _message("恢复失败");
        }
    }

    public function del()
    {
        $uid = intval($this->segment(4));
        $res = $this->model->user_del($uid);

        if ($res) {
            _message("删除成功", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/lists");
        }
        else {
            _message("删除失败");
        }
    }

    public function del_true()
    {
        $uid = intval($this->segment(4));
        $res = $this->model->user_del_true($uid);

        if ($res) {
            _message("删除成功");
        }
        else {
            _message("删除失败");
        }
    }

    public function select()
    {
        if (isset($_POST["submit"])) {
            $data = _post();
            if (empty($data["sousuo"]) || empty($data["content"])) {
                _message("参数错误");
            }

            $members = array();

            if ($data["sousuo"] == "id") {
                $where = "`uid` = '" . $data["content"] . "'";
            }

            if ($data["sousuo"] == "nickname") {
                $where = "`username` LIKE '%" . $data["content"] . "%'";
            }

            if ($data["sousuo"] == "email") {
                $where = "`email` LIKE '%" . $data["content"] . "%'";
            }

            if ($data["sousuo"] == "mobile") {
                $where = "`mobile` LIKE '%" . $data["content"] . "%'";
                $where .= " or `phone` LIKE '%" . $data["content"] . "%'";
            }

            $members = $this->model->get_user_list($where);
        }

        $this->view->data("ments", $this->ments);
        $this->view->data("data", $data);
        $this->view->data("members", $members);
        $this->view->tpl("member.select");
    }

    public function member_group()
    {
        $ments = array(
            array("member_group", "会员组", ROUTE_M . "/" . ROUTE_C . "/member_group"),
            array("member_add_group", "添加会员组", ROUTE_M . "/" . ROUTE_C . "/add_group")
            );
        $member_group = $this->model->get_group();
        $this->view->data("member_group", $member_group);
        $this->view->tpl("member.member_group")->data("ments", $ments);
    }

    public function group_modify()
    {
        $id = intval($this->segment(4));
        $member_group = $this->model->get_group_one("`groupid`='" . $id . "'");

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            if (empty($data["name"]) || !isset($data["jingyan_start"]) || empty($data["jingyan_end"])) {
                _message("会员组或者经验值不能为空");
            }
            else if ($data["jingyan_end"] <= $data["jingyan_start"]) {
                _message("开始经验不能大于结束经验");
            }

            $res = $this->model->group_save("`groupid`='" . $id . "'", $data);

            if ($res) {
                _message("修改成功");
            }
            else {
                _message("修改失败");
            }
        }

        $this->view->tpl("member.add_group")->data("member_group", $member_group);
    }

    public function group_del()
    {
        $id = intval($this->segment(4));
        $sql = "DELETE FROM `@#_member_group` WHERE `groupid`='$id'";
        $this->db->Query($sql);

        if ($this->db->affected_rows()) {
            _message("删除成功");
        }
        else {
            _message("删除失败");
        }
    }

    public function add_group()
    {
        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);
            if (empty($data["name"]) || !isset($data["jingyan_start"]) || empty($data["jingyan_end"])) {
                _message("会员组或者经验值不能为空");
            }

            $res = $this->model->group_add($data);

            if ($res) {
                _message("增加成功");
            }
            else {
                _message("增加失败");
            }
        }

        $this->view->tpl("member.add_group");
    }

    public function config()
    {
        $user_setting = system::load_app_model("setting", "common");
        $user_config = $user_setting->ready_setting("reg");

        if (isset($_POST["submit"])) {
            $data = _post();
            $data["nickname"] = htmlspecialchars($data["nickname"]);
            $data["nickname"] = trim($data["nickname"], ",");
            $data["nickname"] = str_ireplace(" ", "", $data["nickname"]);
            $data["reg_email"] = (isset($data["reg_email"]) ? 1 : 0);
            $data["reg_mobile"] = (isset($data["reg_mobile"]) ? 1 : 0);
            $data["reg_num"] = (isset($data["reg_num"]) ? $_POST["reg_num"] : 0);
            $user_setting->write_setting("reg", $data);
            _message("操作成功");
        }

        $this->view->data("user_config", $user_config);
        $this->view->tpl("member.config")->data("ments", $this->ments);
    }

    public function member_fufen()
    {
        $user_setting = system::load_app_model("setting", "common");

        if (isset($_POST["submit"])) {
            $data = _post();
            unset($data["submit"]);

            if ($data["fufen_yuan"] <= 0) {
                _message("福分输入有错误");
            }

            $jieguo = $data["fufen_yuan"] % 10;

            if ($jieguo != 0) {
                _message("福分输入有错误");
            }

            $res = $user_setting->write_setting("fufen", $data);

            if ($res) {
                $user_setting->cfgPut("fufen", "user_fufen");
                _message("修改成功!", WEB_PATH . "/" . ROUTE_M . "/" . ROUTE_C . "/member_fufen");
            }
            else {
                _message("修改失败!");
            }
        }

        $config = $user_setting->ready_setting("fufen");
        $this->view->data("ments", $this->ments);
        $this->view->tpl("member.fufen")->data("config", $config);
    }

    /**
     * 充值记录
     */
    public function recharge()
    {
        $m_order = System::load_app_model("order", "common");
        $Rconfig = System::load_sys_config("param");

        $search = array();        
        if ( ! preg_match( "/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['start_otime'] = $this->segment( 4 );
        }
        $search['end_otime'] = $this->segment( 5 );
        $search['source']    = $this->segment( 6 );
        $search['user_type'] = $this->segment( 7 );
        $search['type_val']  = $this->segment( 8 );

        $wheres = '';
        /* 充值时间搜索 */
        if ( $search['start_otime'] || $search['end_otime'] )
        {
            $search['start_otime'] = str_replace( '_', ' ', $search['start_otime'] );
            $search['end_otime']   = str_replace( '_', ' ', $search['end_otime'] );            

            $start_otime = strtotime( $search['start_otime'] ) ? : 0;
            $end_otime   = strtotime( $search['end_otime'] ) ? : time();
            $wheres .= " AND otime BETWEEN ". $start_otime ." AND " . $end_otime;
        }
        /* 充值来源 */
        if ( $search['source'] > 0 )
        {
            $wheres .= " AND `oremark` = '充值'";
        }
        /* 用户类型 + 类型值 */
        if ( $search['user_type'] && $search['type_val'] )
        {
            $user_uid = $this->model->get_user_one("`$search[user_type]` = '" . $search['type_val'] . "'");
            if ( ! $user_uid ) 
            {
                _message( $search['type_val'] . "不存在！" );
            }
            $wheres .= " AND `ouid`='" . $user_uid["uid"] . "'";
        }

        $wheres   = "(otype=1 or otype=4) and ostatus=2" . $wheres;
        $num      = 20;
        $total    = $m_order->get_order_num( $wheres );
        $summoeny = $m_order->get_order_sum( $wheres, "omoney" );
        $page     = System::load_sys_class( "page" );

        if ( preg_match("/\/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", G_PARAM_URL, $matches) ) {
            $pagenum = $matches[1];
        }
        else {
            $pagenum = 1;
        }

        $page->config( $total, $num, $pagenum, "0" );
        $start_record = ($pagenum - 1) * $num;
        $recharge     = $m_order->get_order( $wheres, "*", "`otime` desc", $start_record . "," . $num );
        $members      = array();

        for ( $i = 0; $i < count($recharge); $i++ )
        {
            $uid         = $recharge[$i]["ouid"];
            $member      = $this->model->get_user_one("`uid`='" . $uid . "'");
            $members[$i] = $member["username"];

            if ( empty( $member["username"] ) ) {
                if ( ! empty($member["email"]) ) {
                    $members[$i] = $member["email"];
                }

                if ( ! empty( $member["mobile"] ) ) {
                    $members[$i] = $member["mobile"];
                }
            }
        }

        $this->view->data("search", $search);
        $this->view->data( "url", G_ADMIN_PATH . "/" . ROUTE_C . "/" . ROUTE_A );
        $this->view->data("recharge", $recharge);
        $this->view->data("members", $members);
        $this->view->data("total", $total);
        $this->view->data("summoeny", $summoeny);
        $this->view->data("page", $page->show("two"));
        $this->view->data("ment", $this->ments);
        $this->view->tpl("member.recharge");
    }

    /**
     * 将查询到的数据导出到excel
     */
    public function explode_excel()
    {
        $m_order = System::load_app_model("order", "common");
        $Rconfig = System::load_sys_config("param");

        $search = array();        
        if ( ! preg_match( "/" . $Rconfig["page_p"] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['start_otime'] = $this->segment( 4 );
        }
        $search['end_otime'] = $this->segment( 5 );
        $search['source']    = $this->segment( 6 );
        $search['user_type'] = $this->segment( 7 );
        $search['type_val']  = $this->segment( 8 );

        $wheres = '';
        /* 充值时间搜索 */
        if ( $search['start_otime'] || $search['end_otime'] )
        {
            $search['start_otime'] = str_replace( '_', ' ', $search['start_otime'] );
            $search['end_otime']   = str_replace( '_', ' ', $search['end_otime'] );            

            $start_otime = strtotime( $search['start_otime'] ) ? : 0;
            $end_otime   = strtotime( $search['end_otime'] ) ? : time();
            $wheres .= " AND otime BETWEEN ". $start_otime ." AND " . $end_otime;
        }
        /* 充值来源 */
        if ( $search['source'] > 0 )
        {
            $wheres .= " AND `oremark` = '充值'";
        }
        /* 用户类型 + 类型值 */
        if ( $search['user_type'] && $search['type_val'] )
        {
            $user_uid = $this->model->get_user_one("`$search[user_type]` = '" . $search['type_val'] . "'");
            if ( ! $user_uid ) 
            {
                _message( $search['type_val'] . "不存在！" );
            }
            $wheres .= " AND `ouid`='" . $user_uid["uid"] . "'";
        }

        $wheres   = "(otype=1 or otype=4) and ostatus=2" . $wheres;
        $total    = $m_order->get_order_num( $wheres );
        $summoeny = $m_order->get_order_sum( $wheres, "omoney" );
        $recharge = $m_order->get_order( $wheres, "*", "`otime` desc" );
        $members  = array();
        $recharge_num = count( $recharge );
        for ( $i = 0; $i < $recharge_num; $i++ )
        {
            $uid         = $recharge[$i]["ouid"];
            $member      = $this->model->get_user_one("`uid`='" . $uid . "'");
            $members[$i] = $member["username"];

            if ( empty( $member["username"] ) ) {
                if ( ! empty($member["email"]) ) {
                    $members[$i] = $member["email"];
                }

                if ( ! empty( $member["mobile"] ) ) {
                    $members[$i] = $member["mobile"];
                }
            }
        }
        foreach ($recharge as $key => $value) {
            $recharge[ $key ]['ueser_name'] = $members[ $key ];
        }
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type:application/vnd.ms-execl");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header('Content-type: text/html; charset=utf-8');
        header("Content-type:application/vnd.ms-excel;charset=UTF-8"); 
        header("Content-Disposition:filename=chongzhijilu.xls"); //输出的表格名称
        header("Content-Transfer-Encoding:binary");

        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . "PHPExcel".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR ."PHPExcel/IOFactory".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR ."PHPExcel/Writer/Excel2007".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR."PHPExcel/Writer/Excel5".".php";
        require_once G_SYSTEM . "libs" . DIRECTORY_SEPARATOR."PHPExcel/Worksheet/Drawing".".php";

        $obj = new PHPExcel();
        $obj->getProperties()->setCreator('一元夺宝');
        $obj->getProperties()->setLastModifiedBy("James zheng");
        $obj->getProperties()->setTitle("充值记录");
        $obj->getProperties()->setSubject("充值记录");
        $obj->getProperties()->setDescription("充值记录导出");
        $obj->setActiveSheetIndex(0);
        $obj->getDefaultStyle()->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $obj->getDefaultStyle()->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);

        $obj->getActiveSheet()->getStyle('A1:R1')->getFont()->setSize(12);
        $obj->getActiveSheet()->getStyle('A1:R1')->getFont()->setBold(true);
        $obj->getActiveSheet()->getStyle('A1:R1')->getFont()->getColor()->setARGB(FFCC66CC);
        
        $obj->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $obj->getActiveSheet()->getColumnDimension('B')->setWidth(15);
        $obj->getActiveSheet()->getColumnDimension('C')->setWidth(15);
        $obj->getActiveSheet()->getColumnDimension('D')->setWidth(15);
        
        $obj->getActiveSheet()->SetCellValue('A1', '用户名');
        $obj->getActiveSheet()->SetCellValue('B1', '充值金额');
        $obj->getActiveSheet()->SetCellValue('C1', '充值来源');
        $obj->getActiveSheet()->SetCellValue('D1', '时间');

        $explode_result = array();
        foreach($recharge as $k => $v){
            $explode_result[$k]['name'] = $v['ueser_name'];
            $explode_result[$k]['omoney'] = $v['omoney'];
            $explode_result[$k]['oremark'] = $v['oremark'];
            $explode_result[$k]['otime'] = date("Y-m-d H:i:s", $v["otime"]);
        }
        $obj->setActiveSheetIndex(0);
        $i=2;
        $obj->getActiveSheet()->getColumnDimension("L".$i)->setWidth(15);
        $obj->getActiveSheet()->getRowDimension($i)->setRowHeight(100);
        foreach($explode_result as $kk => $vv) {
            $obj->getActiveSheet()->SetCellValue('A'.$i , $vv['name']);
            $obj->getActiveSheet()->SetCellValue('B'.$i , $vv['omoney']);
            $obj->getActiveSheet()->SetCellValue('C'.$i , $vv['oremark']);
            $obj->getActiveSheet()->SetCellValue('D'.$i , $vv['otime']);
            $i++;
        }

        $objWriter = PHPExcel_IOFactory::createWriter($obj, 'Excel5');
        $objWriter->save('php://output');
        exit;
    }

    public function pay_list()
    {
        if (isset($_POST["sososubmit"])) {
            $data = _post();
            if (!empty($data["posttime1"]) && !empty($data["posttime2"])) {
                $data["posttime1"] = strtotime($data["posttime1"]);
                $data["posttime2"] = strtotime($data["posttime2"]);

                if ($data["posttime2"] < $data["posttime1"]) {
                    _message("前一个时间不能大于后一个时间");
                }

                $times = " `otime`>='" . $data["posttime1"] . "' AND `otime`<='" . $data["posttime2"] . "'";
            }

            if ($data["yonghu"] == "用户id") {
                if (!empty($data["yonghuzhi"])) {
                    $uid = " `ouid`='" . $data["yonghuzhi"] . "'";
                }
            }

            if ($data["yonghu"] == "用户名称") {
                if (!empty($data["yonghuzhi"])) {
                    $user_uid = $this->model->get_user_one("`username`='" . $data["yonghuzhi"] . "'");

                    if ($user_uid) {
                        $uid = " `ouid`='{$user_uid["uid"]}'";
                    }
                    else {
                        _message($data["yonghuzhi"] . "用户不存在！");
                    }
                }
            }

            if ($data["yonghu"] == "用户邮箱") {
                if (!empty($data["yonghuzhi"])) {
                    $user_uid = $this->model->get_user_one("`email`='" . $data["yonghuzhi"] . "'");

                    if ($user_uid) {
                        $uid = " `ouid`='{$user_uid["uid"]}'";
                    }
                    else {
                        _message($data["yonghuzhi"] . "用户不存在！");
                    }
                }
            }

            if ($data["yonghu"] == "用户手机") {
                if (!empty($data["yonghuzhi"])) {
                    $user_uid = $this->model->get_user_one("`mobile`='" . $data["yonghuzhi"] . "'");

                    if ($user_uid) {
                        $uid = " `ouid`='{$user_uid["uid"]}'";
                    }
                    else {
                        _message($data["yonghuzhi"] . "用户不存在！");
                    }
                }
            }

            $wheres = $times . $uid;
        }

        $num = 20;
        $page = System::load_sys_class("page");

        if (empty($wheres)) {
            $selectwords = " 1 order by  `otime` DESC";
            $total = $this->order->ready_order_num($selectwords, 1);
            $page->config($total, $num);
            $pay_list = $this->order->ready_order($selectwords . $page->setlimit(), 1);
            $pay_list = empty($pay_list)?array():$pay_list;
            foreach ($pay_list as $v ) {
                $summoeny += $v["omoney"];
            }
        }
        else {
            $selectwords = $wheres . " order by  `otime` DESC";
            $total = $this->order->ready_order_num($selectwords, 1);
            $page->config($total, $num);
            $pay_list = $this->order->ready_order($selectwords, 1, "", "", $page->setlimit(1));

            foreach ($pay_list as $v ) {
                $summoeny += $v["omoney"];
            }
        }

        $members = array();

        for ($i = 0; $i < count($pay_list); $i++) {
            $uid = $pay_list[$i]["ouid"];
            $member = $this->model->get_user_one("`uid`='" . $uid . "'");
            $members[$i] = $member["username"];

            if (empty($member["username"])) {
                if (!empty($member["email"])) {
                    $members[$i] = $member["email"];
                }

                if (!empty($member["mobile"])) {
                    $members[$i] = $member["mobile"];
                }
            }
        }

        $this->view->data("summoeny", $summoeny);
        $this->view->data("total", $total);
        $this->view->data("page", $page->show("two"));
        $this->view->data("ment", $this->ments);
        $this->view->data("pay_list", $pay_list);
        $this->view->tpl("member.pay_list")->data("members", $members);
    }
}
