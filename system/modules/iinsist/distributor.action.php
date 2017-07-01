<?php
defined( 'G_IN_SYSTEM' ) || exit( 'no' );
System::load_app_class( 'admin', G_ADMIN_DIR, 'no' );
class distributor extends admin
{
    private $db;
    private $model;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class( 'model' );
        $this->distributor_model = System::load_app_model( 'distributor', 'common' );
        $this->ment = array(
            array("lists", "分销商管理", ROUTE_M . "/" . ROUTE_C . "/user_list"),
        );
    }

    /**
     * 分销商列表
     */
    public function distributor_list()
    {
        $Rconfig = System::load_sys_config( 'param' );
        $list_where = '1 = 1';
        /* 条件查询 Start */
        $search = array();
        if ( ! preg_match( "/" . $Rconfig['page_p'] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['start_time'] = $this->segment( 4 );
        }
        $search['end_time'] = trim( $this->segment( 5 ) );
        $search['username'] = trim( $this->segment( 6 ) );

        if ( $search['start_time'] || $search['end_time'] )
        {
            $search['start_time'] = str_replace( '_', ' ', $search['start_time'] );
            $search['end_time']   = str_replace( '_', ' ', $search['end_time'] );            

            $start_time = strtotime( $search['start_time'] ) ? : 0;
            $end_time   = strtotime( $search['end_time'] ) ? : time();
            $list_where .= " AND add_time BETWEEN ". $start_time ." AND " . $end_time;
        }
        if ( $search['username'] )
        {
            $list_where .= " AND `username` LIKE '%" . $search['username'] . "%'";
        }
        /* 子级查询 */
        $parent_id = _get( 'parent_id' );
        if ( $parent_id )
        {
            $list_where .= " AND `parent_id` = '" . $parent_id . "'";
        }
        /* 条件查询 End */

        $num   = 10;
        $total = $this->distributor_model->get_count( $list_where );
        $page  = System::load_sys_class( 'page' );

        $page->config( $total, $num );
        $field = 'dis_id, uid, username, dis_money, add_time';
        $distributor_list = $this->distributor_model->get_list( $list_where, $field, "`add_time` DESC", $page->setlimit(1) );
        $this->view->data( 'total', $total);
        $this->view->data( 'search', $search );
        $this->view->data( 'distributor_list', $distributor_list );
        $this->view->data( 'page', $page->show( 'one', true ));
        $this->view->data( 'ments', $this->ment );
        $this->view->data( 'url', G_ADMIN_PATH . "/" . ROUTE_C . "/" . ROUTE_A );
        $this->view->tpl( 'distributor.list' );
    }

    /**
     * 分销商详细信息
     */
    public function distributor_info()
    {
        $dis_id = $this->segment( 4 );
        $where = 'dis_id = ' . $dis_id;
        $info = $this->distributor_model->get_info( $where, '*' );
        $parent_info = $this->parent_info( $info );
        
        $this->view->data( 'info', $info );
        $this->view->data( 'parent_info', $parent_info );        
        $this->view->tpl( 'distributor.info' );
    }

    /**
     * 获取三层上级信息
     */
    public function parent_info( $info )
    {
        $parent_info = array();
        /* 一层信息 */
        $parent_info[1] = array( 'uid' => $info['parent_id'], 'username' => $info['parent_username'] );
        
        /* 二层信息 */
        if ( $info['parent_id'] )
        {
            $where = 'uid = ' . $info['parent_id'];
            $res = $this->distributor_model->get_info( $where, 'uid, username, parent_id, parent_username' );
            $parent_info[2] = array( 'uid' => $res['parent_id'], 'username' => $res['parent_username'] );
        }

        /* 三层信息 */
        if ( $res['parent_id'] )
        {
            $where = 'uid = ' . $res['parent_id'];
            $res = $this->distributor_model->get_info( $where, 'parent_id, parent_username' );
            $parent_info[3] = array( 'uid' => $res['parent_id'], 'username' => $res['parent_username'] );
        }

        return $parent_info;
    }

    /**
     * 分销佣金记录
     */
    public function money_log()
    {
        $money_log_model = System::load_app_model( 'dis_money_log', 'common' );

        $Rconfig = System::load_sys_config( 'param' );
        $list_where = '1 = 1';
        /* 条件查询 Start */
        $search = array();
        if ( ! preg_match( "/" . $Rconfig['page_p'] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['start_time'] = $this->segment( 4 );
        }
        $search['end_time'] = trim( $this->segment( 5 ) );
        $search['username'] = trim( $this->segment( 6 ) );

        if ( $search['start_time'] || $search['end_time'] )
        {
            $search['start_time'] = str_replace( '_', ' ', $search['start_time'] );
            $search['end_time']   = str_replace( '_', ' ', $search['end_time'] );            

            $start_time = strtotime( $search['start_time'] ) ? : 0;
            $end_time   = strtotime( $search['end_time'] ) ? : time();
            $list_where .= " AND add_time BETWEEN ". $start_time ." AND " . $end_time;
        }
        if ( $search['username'] )
        {
            $list_where .= " AND `username` = '" . $search['username'] . "'";
        }
        /* 条件查询 End */

        $num   = 10;
        $total = $money_log_model->get_count( $list_where );
        $page  = System::load_sys_class( 'page' );

        $page->config( $total, $num );
        $field = '*';
        $log_list = $money_log_model->get_list( $list_where, $field, "`add_time` DESC", $page->setlimit(1) );
        $this->view->data( 'total', $total);
        $this->view->data( 'search', $search );
        $this->view->data( 'log_list', $log_list );
        $this->view->data( 'page', $page->show( 'one', true ));
        $ments = array(
            array("lists", "佣金明细", ROUTE_M . "/" . ROUTE_C . "/money_log"),
        );
        $this->view->data( 'ments', $ments );
        $this->view->data( 'url', G_ADMIN_PATH . "/" . ROUTE_C . "/" . ROUTE_A );

        $this->view->tpl( 'distributor.money_log' );
    }

    /**
     * 申请分销商
     */
    public function distributor_apply()
    {
        $apply_model = System::load_app_model( 'distributor_apply', 'common' );
        $Rconfig = System::load_sys_config( 'param' );
        $ments = array(
            array("lists", "申请分销", ROUTE_M . "/" . ROUTE_C . "/money_log"),
        );
        /* 申请状态 0未处理  1已通过  2拒绝 */
        $status_arr[0] = "<span style='color: #642100'>未处理</span>";
        $status_arr[1] = "<span style='color: #006000'>已通过</span>";
        $status_arr[2] = "<span style='color: #FF0000'>拒绝</span>";

        $list_where = '1 = 1';
        /* 条件查询 Start */
        $search = array();
        if ( ! preg_match( "/" . $Rconfig['page_p'] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['start_time'] = $this->segment( 4 );
        }
        $search['end_time'] = trim( $this->segment( 5 ) );
        $search['username'] = trim( $this->segment( 6 ) );

        if ( $search['start_time'] || $search['end_time'] )
        {
            $search['start_time'] = str_replace( '_', ' ', $search['start_time'] );
            $search['end_time']   = str_replace( '_', ' ', $search['end_time'] );            

            $start_time = strtotime( $search['start_time'] ) ? : 0;
            $end_time   = strtotime( $search['end_time'] ) ? : time();
            $list_where .= " AND apply_time BETWEEN ". $start_time ." AND " . $end_time;
        }
        if ( $search['username'] )
        {
            $list_where .= " AND `username` = '" . $search['username'] . "'";
        }
        /* 条件查询 End */

        $num   = 10;
        $total = $apply_model->get_count( $list_where );
        $page  = System::load_sys_class( 'page' );

        $page->config( $total, $num );
        $field = '*';
        $apply_list = $apply_model->get_list( $list_where, $field, "`apply_time` DESC", $page->setlimit(1) );

        $this->view->data( 'total', $total);
        $this->view->data( 'search', $search );
        $this->view->data( 'apply_list', $apply_list );
        $this->view->data( 'page', $page->show( 'one', true ));
        $this->view->data( 'status_arr', $status_arr );
        $this->view->data( 'ments', $ments );
        $this->view->data( 'url', G_ADMIN_PATH . "/" . ROUTE_C . "/" . ROUTE_A );

        $this->view->tpl( 'distributor.apply' );
    }

    /**
     * 处理分销商操作
     * @author Yusure  http://yusure.cn
     * @date   2017-03-03
     * @param  [param]
     * @return [type]     [description]
     */
    public function distributor_handle()
    {
        $status   = intval( $this->segment( 4 ) );
        $apply_id = intval( $this->segment( 5 ) );
        $apply_model = System::load_app_model( 'distributor_apply', 'common' );
        $user_model  = System::load_app_model( 'user', 'common' );
        
        $where = 'apply_id = ' . $apply_id;
        $apply_info = $apply_model->get_info( $where, '*' );
        $username = $user_model->SelectUserUid( $apply_info['uid'], 'username' );
        try
        {
            $this->distributor_model->sql_begin();
            if ( 1 === $status )
            {
                /* 插入distributor记录 */
                $data = array();
                $data['uid']      = $apply_info['uid'];
                $data['username'] = $username['username'];
                $data['add_time'] = time();
                $result = $this->distributor_model->add( $data );
                if ( ! $result )
                {
                    throw new Exception( '写入distributor失败' );
                }
            }
            
            $save_data = array();
            $save_data['apply_status'] = $status;
            $result = $apply_model->save( $save_data, $where );
            if ( ! $result )
            {
                throw new Exception( '更新apply失败' );
            }
            $this->distributor_model->sql_commit();
            _message( '操作成功！' );
        }
        catch ( Exception $e  )
        {
            $this->distributor_model->sql_rollback();
            _message( $e->getMessage() );
        }        
    }

    /**
     * 分销提现操作
     */
    public function withdrawals()
    {
        $withdrawals_model = System::load_app_model( 'withdrawals', 'common' );
        $Rconfig = System::load_sys_config( 'param' );
        $ments = array(
            array("lists", "申请提现", ROUTE_M . "/" . ROUTE_C . "/money_log"),
        );
        /* 状态   0审核中  1已完成 */
        $status_arr[0] = "<span style='color: #642100'>审核中</span>";
        $status_arr[1] = "<span style='color: #006000'>已完成</span>";

        $list_where = '1 = 1';
        /* 条件查询 Start */
        $search = array();
        if ( ! preg_match( "/" . $Rconfig['page_p'] . "([0-9]{1,10})/i", $this->segment( 4 ) ) )
        {
            $search['start_time'] = $this->segment( 4 );
        }
        $search['end_time'] = trim( $this->segment( 5 ) );
        $search['username'] = trim( $this->segment( 6 ) );

        if ( $search['start_time'] || $search['end_time'] )
        {
            $search['start_time'] = str_replace( '_', ' ', $search['start_time'] );
            $search['end_time']   = str_replace( '_', ' ', $search['end_time'] );            

            $start_time = strtotime( $search['start_time'] ) ? : 0;
            $end_time   = strtotime( $search['end_time'] ) ? : time();
            $list_where .= " AND add_time BETWEEN ". $start_time ." AND " . $end_time;
        }
        if ( $search['username'] )
        {
            $list_where .= " AND `username` = '" . $search['username'] . "'";
        }
        /* 条件查询 End */

        $num   = 10;
        $total = $withdrawals_model->get_count( $list_where );
        $page  = System::load_sys_class( 'page' );

        $page->config( $total, $num );
        $field = '*';
        $with_list = $withdrawals_model->get_list( $list_where, $field, "`add_time` DESC", $page->setlimit(1) );

        $this->view->data( 'total', $total);
        $this->view->data( 'search', $search );
        $this->view->data( 'with_list', $with_list );
        $this->view->data( 'page', $page->show( 'one', true ));
        $this->view->data( 'status_arr', $status_arr );
        $this->view->data( 'ments', $ments );
        $this->view->data( 'url', G_ADMIN_PATH . "/" . ROUTE_C . "/" . ROUTE_A );

        $this->view->tpl( 'distributor.withdrawals' );
    }

    /**
     * 提现操作
     */
    public function withdrawals_handle()
    {
        $withdrawals_model = System::load_app_model( 'withdrawals', 'common' );
        $with_id = intval( $this->segment( 4 ) );
        $where = 'with_id = ' . $with_id;
        $data = array();
        $data['status']   = '1';
        $data['finish_time'] = time();
        $result = $withdrawals_model->save( $data, $where );
        if ( $result !== false )
        {
            _message( '操作成功！' );
        }
        else
        {
            _message( '操作失败！' );
        }
    }

}