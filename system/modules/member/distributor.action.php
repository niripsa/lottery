<?php
System::load_app_class( 'UserAction', 'common', 'no' );

class distributor extends UserAction
{

    /**
     * 我要申请分销商
     * @author Yusure  http://yusure.cn
     * @date   2017-03-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function apply()
    {
        seo( 'title', '申请分销' );
        $member = $this->UserInfo;
        $apply_model = System::load_app_model( 'distributor_apply', 'common' );
        if ( $_GET['apply'] )
        {
            /* 检查是否分销商 */
            if ( is_distributor( $member['uid'] ) )
            {
                _message( '您已成为分销商！' );
            }
            /* 检查是否审核中或者未通过 */
            $where = 'uid = ' . $member['uid'];
            $apply_status = $apply_model->get_field( $where, 'apply_status' );
            if ( in_array( $apply_status, array( '0', '1', '2' ), true ) )
            {
                /* 根据状态返回提示语 */
                switch ( $apply_status )
                {
                    case '0':
                        _message( '审核中，请耐心等待！' );
                    break;
                    case '1':
                        _message( '您已成为分销商！' );
                    break;
                    case '2':
                        _message( '申请被拒绝！' );
                    break;
                }
            }
            else
            {
                /* 申请提交成功 */
                $data = array();
                $data['uid']          = $member['uid'];
                $data['username']     = $member['username'];
                $data['apply_status'] = $member['apply_status'];
                $data['apply_time']   = time();
                $res = $apply_model->add( $data );
                if ( $res )
                {
                    _message( '申请提交成功！' );
                }
                else
                {
                    _message( '申请提交失败！' );
                }
            }
        }
        else
        {
            $this->view->data( 'member', $member );
            $this->view->show( 'distributor.apply' );
        }        
    }

    /**
     * 分销中心
     * @author Yusure  http://yusure.cn
     * @date   2017-03-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function distributor_info()
    {
        $member = $this->UserInfo;
        $info = distributor_info( $member['uid'] );
        $num = chlid_num( $member['uid'] );
        
        seo( 'title', '分销信息查看' );
        $this->view->data( 'num', $num );
        $this->view->data( 'info', $info );
        $this->view->data( 'member', $member );
        $this->view->show( 'distributor.info' );
    }

    /**
     * 我的下级
     * @author Yusure  http://yusure.cn
     * @date   2017-03-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function my_child()
    {
        seo( 'title', '我的下级' );
        $distributor_model = System::load_app_model( 'distributor', 'common' );
        $member = $this->UserInfo;
        $num = chlid_num( $member['uid'] );

        $Rconfig = System::load_sys_config( 'param' );
        /* 子级查询 */
        $parent_id = _get( 'parent_id' );
        if ( $parent_id )
        {
            $list_where .= "`parent_id` = '" . $parent_id . "'";
        }
        else
        {
            $list_where = '`parent_id` = ' . $member['uid'];
        }
        $total = $distributor_model->get_count( $list_where );
        $page  = System::load_sys_class( 'page' );
        $page->config( $total, 10 );
        $field = 'dis_id, uid, username, dis_money, add_time';
        $list = $distributor_model->get_list( $list_where, $field, "`add_time` DESC", $page->setlimit(1) );
        $this->view->data( 'total', $total);
        $this->view->data( 'list', $list );
        $this->view->data( 'page', $page );
        $this->view->data( 'num', $num );
        $this->view->data( 'member', $member );
        $this->view->show( 'distributor.my_child' );
    }

    /**
     * 佣金记录
     * @author Yusure  http://yusure.cn
     * @date   2017-03-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function money_log()
    {
        seo( 'title', '佣金记录' );
        $log_model = System::load_app_model( 'dis_money_log', 'common' );
        $member = $this->UserInfo;

        $list_where = 'uid = ' . $member['uid'];
        $Rconfig = System::load_sys_config( 'param' );
        $total   = $log_model->get_count( $list_where );
        $page    = System::load_sys_class( 'page' );
        $page->config( $total, 10 );
        $field = '*';
        $list = $log_model->get_list( $list_where, $field, "`add_time` DESC", $page->setlimit(1) );
        $this->view->data( 'total', $total);
        $this->view->data( 'list', $list );
        $this->view->data( 'page', $page );
        $this->view->data( 'member', $member );
        $this->view->show( 'distributor.money_log' );
    }

    /**
     * 申请提现
     * @author Yusure  http://yusure.cn
     * @date   2017-03-06
     * @param  [param]
     * @return [type]     [description]
     */
    public function withdrawals_apply()
    {
        seo( 'title', '申请提现' );
        $distributor_model = System::load_app_model( 'distributor', 'common' );
        $withdrawals_model = System::load_app_model( 'withdrawals', 'common' );
        $member = $this->UserInfo;
        $info = distributor_info( $member['uid'] );
        if ( $_POST['submit'] )
        {
            $money    = _post( 'money' ) or _message( '请输入金额' );
            if ( $money <= 0 )
            {
                _message( '金额必须大于0' );
            }
            $card_num = _post( 'card_num' ) or _message( '请输入微信号' );
            $money    = format_price( $money );
            /* 检查金额是否充足 */
            if ( $money > $info['dis_money'] )
            {
                _message( '资金不足！' );
            }
            /* TODO 开启事务 减钱 + 加申请记录 */
            try
            {
                $distributor_model->sql_begin();
                $data = array();
                $data['dis_money'] = $info['dis_money'] - $money;
                $where = 'uid = ' . $member['uid'];
                $res = $distributor_model->save( $data, $where );
                if ( ! $res )
                {
                    throw new Exception( '更新distributor失败' );
                }
                $data = array();
                $data['uid']        = $member['uid'];
                $data['username']   = $member['username'];
                $data['with_money'] = $money;
                $data['method']     = 1;
                $data['card_num']   = $card_num;
                $data['add_time']   = time();
                $res = $withdrawals_model->add( $data );
                if ( ! $res )
                {
                    throw new Exception( 'withdrawals 写入失败' );
                }
                $distributor_model->sql_commit();
                _message( '申请成功！' );
            }
            catch ( Exception $e )
            {
                $distributor_model->sql_rollback();
                _message( $e->getMessage() );
            } 
        }
        else
        {
            $this->view->data( 'info', $info );
            $this->view->data( 'member', $member );
            $this->view->show( 'withdrawals.apply' );
        }        
    }

    /**
     * 提现记录
     * @author Yusure  http://yusure.cn
     * @date   2017-03-07
     * @param  [param]
     * @return [type]     [description]
     */
    public function withdrawals_log()
    {
        seo( 'title', '提现记录' );
        $withdrawals_model = System::load_app_model( 'withdrawals', 'common' );
        $member = $this->UserInfo;
        $list_where = 'uid = ' . $member['uid'];
        $total   = $withdrawals_model->get_count( $list_where );
        $page    = System::load_sys_class( 'page' );
        $page->config( $total, 10 );
        $field = '*';
        $list = $withdrawals_model->get_list( $list_where, $field, "`add_time` DESC", $page->setlimit(1) );
        $with_status = array( 0 => '<font color=red>审核中</font>', 1 => '<font color=green>已完成</font>' );
        $this->view->data( 'total', $total);
        $this->view->data( 'list', $list );
        $this->view->data( 'page', $page );
        $this->view->data( 'with_status', $with_status );
        $this->view->data( 'member', $member );
        $this->view->show( 'withdrawals.log' );
    }

    /**
     * 邀请好友
     * @author Yusure  http://yusure.cn
     * @date   2017-03-07
     * @param  [param]
     * @return [type]     [description]
     */
    public function invitefriends()
    {
        seo( 'title', '邀请好友加入分销' );
        $member = $this->UserInfo;
        $url = WEB_PATH . '/distributor/' . $member['uid'];
        $this->view->data( 'uid', $member['uid'] );
        $this->view->data( 'member', $member );
        $this->view->data( 'url', $url );
        $this->view->show( 'dis.invitefriends' );
    }



}