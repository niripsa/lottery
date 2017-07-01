<?php 

class Pay_shopinsert {

/*
    揭晓与插入商品
    @shop   商品数据
*/

function pay_insert_shop_x( $shop = '', $type = '' )
{    
    $g_c_x = System::load_app_config( "get_code_x", '', "pay" );
    if ( is_array( $g_c_x ) && isset( $g_c_x['class'] ) )
    {
        $gcx_db = System::load_app_class( $g_c_x['class'], "pay" );
    }
    else
    {
        $g_c_x  = array("class"=>"tocode");
        $gcx_db = System::load_app_class( $g_c_x['class'], "pay" );
    }        
    $gcx_db->config( $shop, $type );
    $gcx_db->get_run_tocode();
    $ret_data = $gcx_db->returns();
}


/*
    揭晓与插入商品
    @shop   商品数据
*/

function pay_insert_shop( $shop = '', $type = '' )
{
    $time = sprintf( "%.3f", microtime( true ) );
    $update_cloud = System::load_app_model("UserPay_cloud","common");//加载夺宝购买商品model
    $update_pay   = System::load_app_model("UserPay","common");//加载购买商品model
    $order_db     = System::load_app_model("order","common");//加载购买商品model    
    if ( $shop['xsjx_time'] != '0' ) {
        $update_cgoods="`canyurenshu`=`zongrenshu`, `shenyurenshu` = '0' where `id` = '$shop[id]'";
        return $update_cloud->UpdateCgoods($update_cgoods);
    }
    include dirname(__FILE__)."/"."/CloudTocode".".class.php";
    $tocode = new CloudTocode();
    $tocode->shop = $shop;  
    $tocode->run_tocode($time,100,$shop['canyurenshu'],$shop);

    $code       = $tocode->go_code;
    $content    = addslashes($tocode->go_content);
    $counttime  = $tocode->count_time;
    $where      = "`ogid` = '$shop[id]' and `ogocode` LIKE  '%$code%'";    
    $oidinfo    = $update_pay->cloud_gocode($where); 
    $wherewords = "`oid`=$oidinfo[oid]"; 
    $u_go_info  = $order_db->ready_order($wherewords);  
    $u_go_info  = $u_go_info[0];
    $u_info     = $update_pay->SelectUserUid($u_go_info['ouid']);
    
    // 更新商品
    $query = true;
    if ( $u_info )
    {        
        $u_info['username'] = _htmtocode($u_info['username']);
        $q_user = serialize($u_info);
        $gtimes = (int)System::load_sys_config('system','goods_end_time');
        if ( $gtimes == 0 || $gtimes == 1 )
        {
            $q_showtime = 'N';
        }
        else
        {
            $q_showtime = 'Y';
        }
        $gtimes = $gtimes + $time;
        $update_cgoods = "`canyurenshu`=`zongrenshu`,
                        `shenyurenshu` = '0',
                        `q_uid` = '$u_info[uid]',
                        `q_user` = '$q_user',
                        `q_user_code` = '$code',
                        `q_content` = '$content',
                        `q_counttime` ='$counttime',
                        `q_end_time` = '$time',
                        `q_external_time` = '$gtimes',
                        `q_showtime` = '$q_showtime'
                         where `id` = '$shop[id]'";

        $q = $update_cloud->UpdateCgoods( $update_cgoods );
        if ( !$q ) $query = false;  
        if ( $q) {
        $wherewords = "`oid` = '$u_go_info[oid]' and `ouid` = '$u_go_info[ouid]' and `ogid` = '$shop[id]' and `oqishu` = '$shop[qishu]' and `ofstatus`='0'";
        $data = array();
        $data['owin'] = $code;
        $data['ofstatus'] = '1';
        $q = $order_db->update_order( $wherewords, $data );        
            if ( ! $q ) {
                $query = false;
            } else {
                $post_arr = array( "uid" => $u_info['uid'], "gid" => $shop['id'], "send" => 1 );
                _g_triggerRequest( WEB_PATH.'/index/send/send_shop_code', false, $post_arr );
            }
        } else {
            $query =  false;
        }
    } else {    
        $query =  false;
    }

    /* 新建 */
    if ( $query ) {
        if ( $shop['qishu'] < $shop['maxqishu'] ) { 
            $maxinfo = $update_cloud->SelectCgoods_gid( $shop['gid'] );
            if ( ! $maxinfo ) {
                $maxinfo = array( "qishu" => $shop['qishu'] );
            }
            $goods  = System::load_app_model( "goods", "common" ); // 加载夺宝购买商品model
            $intall = $goods->cloud_goods_next($maxinfo);
            /**
             * 预购买订单生成
             */
            if ( $intall ) {
                if ( file_exists( G_PLUGIN . "MulBuy/MulBuy.model.php" ) )
                {
                    include_once G_PLUGIN."MulBuy/MulBuy.model.php";
                    $MulBuydb = new MulBuy_model();
                    $InsertMulOrders = $MulBuydb->write_mul_order( $maxinfo, $intall );//购买多期插件使用(写入多期订单)
                }
            }
            if ( ! $intall ) return $query;
        }
    }
    return $query;
}


/*
    夺宝基金
    go_number @夺宝人次
*/
function pay_go_fund( $go_number = null )
{
    if ( ! $go_number ) return true;
    $db   = System::load_sys_class("model");
    $fund = $db->GetOne("select * from `@#_fund` where 1");
    if ( $fund && $fund['fund_off'] )
    {
        $money = $fund['fund_money'] * $go_number + $fund['fund_count_money'];
        return $db->Query("UPDATE `@#_fund` SET `fund_count_money` = '$money'");
    }
    else
    {
        return true;
    }
}


/*
    用户佣金
    uid         用户id
    dingdancode @订单号
*/
function pay_go_yongjin( $uid = null, $dingdancode = null )
{
    if ( ! $uid || ! $dingdancode ) return true;
    $db   = System::load_sys_class("model");
    $time = time();
    $yesyaoqing = $db->GetOne("SELECT `yaoqing` FROM `@#_member` WHERE `uid`='$uid'");
    if ( $yesyaoqing['yaoqing'] )
    {
        $yongjin = 0.06; //每一元返回的佣金               
    }
    else
    {
        return true;
    }   
    $yongjin = floatval(substr(sprintf("%.3f",$yongjin), 0, -1));
    $gorecode = $db->GetList("SELECT * FROM `@#_member_go_record` WHERE `code`='$dingdancode'");
    foreach ( $gorecode as $val )
    {
        $y_money = $val['moneycount'] * $yongjin;
        $content = "(第".$val['shopqishu']."期)".$val['shopname'];
        $db->Query("INSERT INTO `@#_member_recodes`(`uid`,`type`,`content`,`shopid`,`money`,`ygmoney`,`time`)VALUES('$uid','1','$content','$val[shopid]','$y_money','$val[moneycount]','$time' )");
    }    
}

}