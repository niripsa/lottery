<?php

/**
 * 是否分销商
 */
function is_distributor( $uid )
{
    $distributor_model = System::load_app_model( 'distributor', 'common' );
    $where = 'uid = ' . $uid;
    $res = $distributor_model->get_field( $where, 'dis_id' );
    if ( $res )
    {
        return true;
    }
    else
    {
        return false;
    }
}

/**
 * 分销商信息
 */
function distributor_info( $uid, $field = '*' )
{
    $distributor_model = System::load_app_model( 'distributor', 'common' );
    $where = 'uid = ' . $uid;
    $res = $distributor_model->get_info( $where, $field );
    if ( $res )
    {
        return $res;
    }
    else
    {
        return false;
    }
}

/**
 * 下级分销商数量
 * @author Yusure  http://yusure.cn
 * @date   2017-03-06
 * @param  [param]
 * @return [type]     [description]
 */
function chlid_num( $uid )
{
    $distributor_model = System::load_app_model( 'distributor', 'common' );
    $where = 'parent_id = ' . $uid;
    $res = $distributor_model->get_list( $where, 'uid' );
    if ( ! $res ) { return 0; }
    $child_id_arr  = array();
    foreach ( (array)$res as $k => $v )
    {
        $child_id_arr[] = $v['uid'];
    }
    $child_id = implode( ',', $child_id_arr );
    $where = "parent_id IN ( $child_id )";
    $num = $distributor_model->get_count( $where );
    return count( $res ) + intval( $num );
}

/**
 * 三层上级分销商
 * @author Yusure  http://yusure.cn
 * @date   2017-03-07
 * @param  [param]
 * @return [type]     [description]
 */
function distributor_parent( $uid )
{
    $parent_arr = array();
    $field = 'uid, username, parent_id, dis_money';

    /* 自身信息 */
    $self = distributor_info( $uid, $field );
    if ( ! $self['parent_id'] ) { return false; }
    
    /* 第一层信息 */
    $one = distributor_info( $self['parent_id'], $field );
    $parent_arr[1] = $one;
    if ( ! $one['parent_id'] ) { return $parent_arr; }

    /* 第二层信息 */
    $two = distributor_info( $one['parent_id'], $field );
    $parent_arr[2] = $two;
    if ( ! $two['parent_id'] ) { return $parent_arr; }
    
    /* 第三层信息 */
    $three = distributor_info( $two['parent_id'], $field );
    $parent_arr[3] = $three;
    
    return $parent_arr;
}

/**
 * 佣金分发
 * @author Yusure  http://yusure.cn
 * @date   2017-03-07
 * @param  [param]
 * @param  [type]     $uid   [充值人UID]
 * @param  [type]     $money [充值金额]
 * @return [type]            [description]
 */
function distribute_money( $uid, $money, $type )
{
    dump( $type . '--异步支付调用佣金分发--'.date( 'Y-m-d H:i:s' ) );
    $distributor_model = System::load_app_model( 'distributor', 'common' );
    $money_log = System::load_app_model( 'dis_money_log', 'common' );
    /* 获取所有佣金配置 */
    $commission = System::load_sys_config( 'commission' );
    /* 检查佣金金额是否大于0 */
    if ( $commission['commission_amount'] <= 0 )
    {
        return;
    }

    /* 根据uid查出三层上级 */
    $parent_arr = distributor_parent( $uid );
    if ( ! $parent_arr ) { return; }

    /* 根据佣金配置比例计算出 上级 => 佣金 */
    $money = $money * $commission['commission_amount'];
    $money_1 = $money * $commission['commission_1'] / 100;
    $money_2 = $money * $commission['commission_2'] / 100;
    $money_3 = $money * $commission['commission_3'] / 100;
    foreach ( $parent_arr as $k => $v )
    {
        if ( $v['uid'] )
        {
            $name = 'money_' . $k;
            $parent_arr[$k]['money'] = format_price( $$name );
        }
    }

    /* 开启事务，开始分佣 更新佣金 + 写佣金记录 */
    try
    {
        $distributor_model->sql_begin();
        foreach ( (array)$parent_arr as $k => $v )
        {
            $sql = "UPDATE `@#_distributor` SET `dis_money` = `dis_money` + {$v['money']} WHERE `uid` = {$v['uid']}";
            $res = $distributor_model->Query( $sql );
            if ( ! $res )
            {
                throw new Exception( 'distributor更新出错' );
            }
            $log_data = array();
            $log_data['uid']           = $v['uid'];
            $log_data['username']      = $v['username'];
            $log_data['change_money']  = $v['money'];
            $log_data['surplus_money'] = $v['money'] + $v['dis_money'];
            $log_data['add_time']      = time();
            $res = $money_log->add( $log_data );
            if ( ! $res )
            {
                throw new Exception( '写入money_log出错' );
            }
        }
        /* 提交事务 */
        $distributor_model->sql_commit();
    }
    catch ( Exception $e )
    {
        dump( $e->getMessage() );
        $distributor_model->sql_rollback();
    }    
}

/**
 * 加入分销
 * @author Yusure  http://yusure.cn
 * @date   2017-03-10
 * @param  [param]
 * @param  [Array]    $user      [用户个人信息]
 * @param  [type]     $parent_id [上级ID]
 * @return [type]                [description]
 */
function join_distribution( $user, $parent_id )
{
    $user_model = System::load_app_model( 'user', 'common' );
    $distributor_model = System::load_app_model( 'distributor', 'common' );

    /* 检查上级ID是否有效 */
    $parent_info = $user_model->SelectUserUid( $parent_id );
    if ( ! $parent_info )
    {
        _message( '获取上级失败' );
    }

    /* 加入分销 */
    $data = array();
    $data['uid']             = $user['uid'];
    $data['username']        = $user['username'];
    $data['parent_id']       = $parent_info['uid'];
    $data['parent_username'] = $parent_info['username'];
    $data['dis_money']       = '0';
    $data['add_time']        = time();
    $res = $distributor_model->add( $data );
    if ( $res )
    {
        return true;
    }
    else
    {
        return false;
    }
}