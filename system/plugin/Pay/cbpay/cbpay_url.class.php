<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
class cbpay_url  {
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    
    
    public function qiantai($v_oid=false) { 
        sleep(2);
        /* 通过判断remark1来分流各种方式 */
        $order_type = trim( $_POST['remark1'] );
        switch ( $order_type ) 
        {
            /* 一元夺宝商品 */
            case 'cloud_order':
                $this->show_cloud_order();
                break;
            
            /* 充值订单 */
            default:
                $this->show_recharge();
                break;
        }
        
        
    }

    /**
     *  前台充值结果显示
     */
    public function show_recharge()
    {
        $v_oid = trim( $_POST['v_oid'] );   //商户订单号
        //查询充值订单
        $recharge_info = $this->db->GetOne( "select * from `@#_orders` where `ocode` = '$v_oid' and `ostatus` = '2'" );

        if ( $recharge_info ) 
        {
            _message( "充值成功！", WEB_PATH );
        } 
        else 
        {
            _message( "充值失败！", WEB_PATH );
        }
    }

    /**
     * 一元夺宝商品网银支付显示结果
     * @author Yusure  http://yusure.cn
     * @date   2015-11-05
     * @param  [param]
     * @return [type]     [description]
     */
    public function show_cloud_order()
    {
        $pay_sn = trim( $_POST['v_oid'] );  //商户订单号paysn
        $third_order_info = $this->db->GetOne("select `ocode` from `@#_third_order` where `pay_sn` = '$pay_sn'");
        if ( $third_order_info )
        {
            $cloud_order_info = $this->db->GetOne("select `ostatus` from `@#_cloud_order` where `ocode` = '$third_order_info[ocode]'");
            if ( $cloud_order_info['ostatus'] == 2 )
            {
                _setcookie("Cartlist", NULL);
                _message('购买成功！',WEB_PATH);
            }
            else
            {
                _message('购买失败！',WEB_PATH);
            }
        }
        else
        {
            _message('购买失败！',WEB_PATH);
        }
    }
    
    
    public function houtai(){
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'cbpay' and `pay_start` = '1'");
        $key = $pay_type['pay_key'];        //支付KEY
        //登陆后在上面的导航栏里可能找到“资料管理”，在资料管理的二级导航栏里有“MD5密钥设置” 
        //建议您设置一个16位以上的密钥或更高，密钥最多64位，但设置16位已经足够了
        //****************************************
        $v_oid       = trim($_POST['v_oid']);       // 商户发送的v_oid订单编号   
        $v_pmode     = trim($_POST['v_pmode']);    // 支付方式（字符串）   
        $v_pstatus   = trim($_POST['v_pstatus']);   //  支付状态 ：20（支付成功）；30（支付失败）
        $v_pstring   = trim($_POST['v_pstring']);   // 支付结果信息 ： 支付完成（当v_pstatus=20时）；失败原因（当v_pstatus=30时,字符串）； 
        $v_amount    = trim($_POST['v_amount']);     // 订单实际支付金额
        $v_moneytype = trim($_POST['v_moneytype']); //订单实际支付币种    
        $remark1     = trim($_POST['remark1']);      //备注字段1
        $remark2     = trim($_POST['remark2']);     //备注字段2
        $v_md5str    = trim($_POST['v_md5str']);   //拼凑后的MD5校验值  

        /**
         * 重新计算md5的值
         */
                               
        $md5string=strtoupper(md5($v_oid.$v_pstatus.$v_amount.$v_moneytype.$key));

        /**
         * 判断返回信息，如果支付成功，并且支付结果可信，则做进一步的处理
         */


        if ( $v_md5str == $md5string ) {
            if ( $v_pstatus == "20" ) 
            {
                //支付成功，可进行逻辑处理！
                switch ( $remark1 ) {
                    /* 一元夺宝 */
                    case 'cloud_order':
                        $order_db = System::load_app_model("order", "common");   
                        $cloud_order_res = $order_db->update_duobao_order( $v_oid );
                        if ( $cloud_order_res )
                        {
                            echo "ok";die;
                        }
                        else
                        {
                            echo "error";die;
                        }
                    break;
                    
                    /* 金币充值 */
                    default:
                        $order_res = $this->recharge_notify( $v_oid );
                        if ( $order_res )
                        {
                            echo "ok";die;
                        }
                        else
                        {
                            echo "error";die;
                        }
                    break;
                }
            } 
            else 
            {
                echo 'error';die;
            }
        }
        
            
    }//function end

    /**
     * 网银充值异步
     * @author Yusure  http://yusure.cn
     * @date   2015-11-05
     * @param  [param]
     * @return [type]     [description]
     */
    public function recharge_notify( $v_oid )
    {
        $this->db->sql_begin();

        //查询充值订单
        $aa = "select * from `@#_orders` where `ocode` = '$v_oid' and `ostatus` = '1' for update";
        $dingdaninfo = $this->db->GetOne($aa);
        $time = time(); 
        if ( !$dingdaninfo ) {
            $recorddingdan = $this->db->GetOne("select * from `@#_user_money_record` where `code` = '$v_oid' and `status` = '1' for update");                
         } 
         if( $dingdaninfo || $recorddingdan ) {
            if( $dingdaninfo ) {                    
                $c_money = intval($dingdaninfo['omoney']);                  
                $uid = $dingdaninfo['ouid'];
                $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '网银在线', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                if( $up_q1 && $up_q2 && $up_q3 ) {
                    $this->db->sql_commit();
                    return true;
                 } else {
                   return false;
                 }                  
            } else {
                 if ( $recorddingdan ) {
                    $c_money = intval($recorddingdan['money']);
                    $uid = $recorddingdan['uid'];                        
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                      
                    $pay = System::load_app_class('UserPay', 'common'); 
                    $scookies = unserialize($recorddingdan['scookies']);        
                    $pay->scookie = $scookies;          
                    $ok = $pay->init($uid,$pay_type['pay_id'],'go_record'); //夺宝商品  
                    if ( $ok != 'ok' ) {
                        _setcookie("Cartlist", null);
                        return false;
                    }           
                    $check = $pay->go_pay(1); 
                    if( $check && $up_q2 && $up_q3 ) {
                        $this->db->sql_commit();
                       $recorddel = $this->db->Query("DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')");  
                       if ( $recorddel ) {
                            _setcookie("Cartlist", null);
                            return true;                                                                             
                       }
                       _setcookie("Cartlist", null);
                        return false;
                     } else {
                        return false;
                     }                                                              
                 } else {
                        return false;
                 }                   
            }                       
                
         } else {
            return false;
         } 
    }
    
}

?>