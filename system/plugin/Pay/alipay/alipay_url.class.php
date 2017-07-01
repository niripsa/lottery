<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
class alipay_url {
    private $error = null;  
    public function __construct()
    {
        $this->db = System::load_sys_class( 'model' );
    }   
    
    public function qiantai()
    {
        sleep( 2 );
        // 支付分流
        switch ( trim($_GET['extra_common_param']) )
        {
            // 显示一元夺宝订单支付结果
            case 'cloud_order';
                $this->show_cloud_order();
            break;
        }
        $out_trade_no = $_GET['out_trade_no'];  //商户订单号
        $dingdaninfo = $this->db->GetOne("select `ostatus` from `@#_orders` where `ocode` = '$out_trade_no'");
        if ( $dingdaninfo )
        {
            if ( $dingdaninfo['ostatus'] == 2 )
            {
                _message( '充值成功！', WEB_PATH );
            }
            else
            {
                _message( '充值失败！', WEB_PATH );
            }
        }
        else
        {
            $recorddingdan = $this->db->GetOne("select `ostatus` from `@#_user_money_record` where `code` = '$out_trade_no'");
            if ( $recorddingdan )
            {
                if ( $recorddingdan['ostatus'] == 2 )
                {
                    _message( '支付成功！', WEB_PATH );
                }
                else
                {
                    _message( '支付失败！', WEB_PATH );
                }
                _message( '充值成功', WEB_PATH );
            }
            else
            {
                _message( '没有这个订单！', WEB_PATH );
            }
        }
    }

    /**
     * 前台显示充值的结果
     * @author Yusure  http://yusure.cn
     * @date   2015-10-29
     * @param  [param]
     * @return [type]     [description]
     */
    public function show_cloud_order()
    {
        $out_trade_no = $_GET['out_trade_no'];  //商户订单号
        $third_order_info = $this->db->GetOne("select `ocode` from `@#_third_order` where `pay_sn` = '$out_trade_no'");

        if ( $third_order_info )
        {
            $cloud_order_info = $this->db->GetOne("select `ostatus` from `@#_cloud_order` where `ocode` = '$third_order_info[ocode]'");
            if($cloud_order_info['ostatus']==2){
                _setcookie("Cartlist", NULL);
                _message('购买成功！',WEB_PATH);
            }else{
                _message('购买失败！',WEB_PATH);
            }
        }
        else
        {
            _message('购买失败！',WEB_PATH);
        }
    }
    
    public function houtai()
    {
        include dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_notify.class.php";    
        $pay_type = $this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'alipay' and `pay_start` = '1'");
        $key     =  $pay_type['pay_key'];       //支付KEY
        $partner =  $pay_type['pay_uid'];       //支付商号ID
        $userdb       = System::load_app_model("user", "common");
        $member_model = System::load_app_model("member", "common");
        $order_model  = System::load_app_model("order", "common");
        
        $alipay_config_sign_type = strtoupper('MD5');       //签名方式 不需修改
        $alipay_config_input_charset = strtolower('utf-8'); //字符编码格式      
        $alipay_config_cacert =  dirname(__FILE__).DIRECTORY_SEPARATOR."lib/cacert.pem";    //ca证书路径地址
        $alipay_config_transport   = 'http';
        
        $alipay_config = array(
            "partner"      => $partner,
            "key"          => $key,
            "sign_type"    => $alipay_config_sign_type,
            "input_charset"=> $alipay_config_input_charset,
            "cacert"       => $alipay_config_cacert,
            "transport"    => $alipay_config_transport
        );
        $alipayNotify = new AlipayNotify( $alipay_config );
        $verify_result = $alipayNotify->verifyNotify();
        if ( ! $verify_result ) { $this->error = false; exit; } //验证失败
        
        $out_trade_no = $_POST['out_trade_no']; //商户订单号
        $trade_no     = $_POST['trade_no'];         //支付宝交易号
        $trade_status = $_POST['trade_status']; //交易状态

        // 支付分流
        switch ( trim($_POST['extra_common_param']) )
        {
            // 处理一元夺宝订单
            case 'cloud_order';
                $order_db = System::load_app_model("order", "common");   
                $cloud_order_res = $order_db->update_duobao_order( $out_trade_no );
                if ( $cloud_order_res )
                {
                    echo 'success';die;
                }
                else
                {
                    echo 'fail';die;
                }
            break;
        }
        
        //开始处理及时到账和担保交易订单
        if ( $trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS' || $trade_status == 'WAIT_SELLER_SEND_GOODS' ) {
            $this->db->sql_begin();
            //查询充值订单
            $dingdaninfo = $this->db->GetOne("select * from `@#_orders` where `ocode` = '$out_trade_no' and `ostatus` = '1' for update");
            $time = time(); 
            if ( ! $dingdaninfo )
            {
                $recorddingdan = $this->db->GetOne("select * from `@#_user_money_record` where `code` = '$out_trade_no' and `status` = '1' for update");                
            } 
            if ( $dingdaninfo || $recorddingdan )
            {
                $c_money = intval($dingdaninfo['omoney']);
                $uid = $dingdaninfo['ouid'];
                if ( $dingdaninfo )
                {
                    $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '支付宝', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
                    /* 充值按比例返现 */
                    dump( '支付宝充值' );
                    $rebate = _app_cfg( 'money', 'rebate' );
                    /* 光100的整数才算 */
                    $back_money = intval($dingdaninfo["omoney"] / 100) * 100;
                    if ( $rebate > 0 && $back_money > 0  )
                    {
                        $back_money = $back_money / 100 * $rebate;
                        $acc_arr["uid"]     = $uid;
                        $acc_arr["type"]    = 1;
                        $acc_arr["pay"]     = "账户";
                        $acc_arr["content"] = "充值返现";
                        $acc_arr["money"]   = $back_money;
                        $acc_arr["time"]    = time();
                        $text = "充值返现:" . $back_money;
                        $order_model->user_add_chongzhi( $uid, $back_money, $text );
                        $member_model->user_account_add( $acc_arr );
                        $where = "`uid` = '{$uid}'";
                        $user_data = "`money` = `money` + {$back_money}";
                        $userdb->UpdateUser( $user_data, $where );
                    }
                    if ( $up_q1 && $up_q2 && $up_q3 )
                    {
                        $this->db->sql_commit();
                        $this->error = true;exit;
                    }
                    else
                    {
                        $this->error = false;exit;                            
                    }                  
                }
                else
                {
                     if ( $recorddingdan )
                     {
                        $c_money = intval($recorddingdan['money']);
                        $uid = $recorddingdan['uid'];                        
                        $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                        $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
                        $pay = System::load_app_class('UserPay', 'common'); 
                        $scookies = unserialize($recorddingdan['scookies']);        
                        $pay->scookie = $scookies;          
                        $ok = $pay->init($uid,$pay_type['pay_id'],'go_record'); //夺宝商品  
                        if ( $ok != 'ok' )
                        {
                            $this->error = false;exit;
                        }           
                        $check = $pay->go_pay(1); 
                        if ( $check && $up_q2 && $up_q3 )
                        {
                            $this->db->sql_commit();
                            $recorddel = $this->db->Query("DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')");  
                            if ( $recorddel ) 
                            {
                                _setcookie("Cartlist", null); 
                                $this->error = true;exit;                           
                            }
                            $this->error = false;exit;
                        }
                        else
                        {
                            $this->error = false;exit;     
                        }                                                              
                    }
                    else
                    {
                        $this->error = false;exit;                       
                    }
                }
            } else {
                $this->error = false;exit;
            } 
        }//开始处理订单结束
    }//function end
}