<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');

ini_set("display_errors","OFF");

class malipay_url{
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    

    
    /* 同步通知 */
    public function qiantai(){
        sleep(2);
        $out_trade_no = $_GET['out_trade_no'];  //商户订单号
        $dingdaninfo = $this->db->GetOne("select `ostatus` from `@#_orders` where `ocode` = '$out_trade_no'");
        if($dingdaninfo){
            if($dingdaninfo['ostatus']==2){
                _message( '充值成功！', WEB_PATH, '', '', true );
            }else{
                _message('充值失败！',WEB_PATH);
            }
        }else{
            $recorddingdan = $this->db->GetOne("select `ostatus` from `@#_user_money_record` where `code` = '$out_trade_no'");
            if($recorddingdan){
                if($recorddingdan['ostatus']==2){
                    _message('支付成功！', WEB_PATH, '', '', true );
                }else{
                    _message('支付失败！',WEB_PATH);
                }
                _message( '充值成功', WEB_PATH, '', '', true );
            }else{
                _message('没有这个订单！',WEB_PATH);
            }                
         }          
    }/* function end */

    /**
     * WAP 同步回调
     * @author Yusure  http://yusure.cn
     * @date   2015-10-31
     * @param  [param]
     * @return [type]     [description]
     */
    public function mobile_clound_return()
    {
        $out_trade_no = $_GET['out_trade_no'];  //商户订单号
        $third_order_info = $this->db->GetOne("select `ocode` from `@#_third_order` where `pay_sn` = '$out_trade_no'");

        if($third_order_info)
        {
            $cloud_order_info = $this->db->GetOne("select `ostatus` from `@#_cloud_order` where `ocode` = '$third_order_info[ocode]'");
            if($cloud_order_info['ostatus']==2){
                _setcookie("Cartlist", NULL);
                _message( '购买成功！', WEB_PATH, '', '', true );
            }else{
                _message('购买失败！',WEB_PATH);
            }
        }
        else
        {
            _message('购买失败！',WEB_PATH);
        }
    }
    
    
    /* 异步通知 */
    public function houtai(){                   
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_notify.class.php";
            //计算得出通知验证结果
            $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'malipay' and `pay_start` = '1'");
            $key =  $pay_type['pay_key'];       //支付KEY
            $partner =  $pay_type['pay_uid'];       //支付商号ID
            $alipay_config=array(
                "partner"      => $partner,
                "key"          => $key,
                "private_key_path"=>dirname(__FILE__).'/lib/rsa_private_key.pem',
                "ali_public_key_path"=>dirname(__FILE__).'/lib/alipay_public_key.pem',
                "sign_type"    =>'MD5',
                "input_charset"=>'utf-8',
                "cacert"       =>dirname(__FILE__).'/lib/cacert.pem',
                "transport"    =>'http'
            );          
            $alipayNotify = new AlipayNotify($alipay_config);
            $verify_result = $alipayNotify->verifyNotify();

            if($verify_result) {
                //解析notify_data
                //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
                $doc = new DOMDocument();   
                if ($alipay_config['sign_type'] == 'MD5') {
                    $doc->loadXML($_POST['notify_data']);
                }
                
                if ($alipay_config['sign_type'] == '0001') {
                    $doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
                }
                
                if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {
                    //商户订单号
                    $this->out_trade_no = $out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
                    //支付宝交易号
                    $trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
                    //交易状态
                    $trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
                    
                    if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                        //TRADE_SUCCESS     高级即时到账，买家付款成功后出现
                        //TRADE_FINISHED    普通和高级(退款)会出现
                        
                        $ret = $this->updata_order();                       
                        if($ret == 'success'){
                            echo 'success';exit;
                        }
                        if($ret == 'fail'){
                            echo 'success';exit;
                        }
                        echo "success";     //请不要修改或删除
                    }                   
                }
            } else {
                echo "fail";
            }   
    }
    
    
    /*支付与充值处理*/
    private function updata_order(){
        $out_trade_no = $this->out_trade_no;
            $this->db->sql_begin();
            //查询充值订单
            $dingdaninfo = $this->db->GetOne("select * from `@#_orders` where `ocode` = '$out_trade_no' and `ostatus` = '1' for update");
            $time = time(); 
            if(!$dingdaninfo){
                $recorddingdan = $this->db->GetOne("select * from `@#_user_money_record` where `code` = '$out_trade_no' and `status` = '1' for update");                
             } 
             if($dingdaninfo||$recorddingdan){
                $c_money = intval($dingdaninfo['omoney']);
                $uid = $dingdaninfo['ouid'];
                if($dingdaninfo){
                    $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '支付宝', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                    if($up_q1&&$up_q2&&$up_q3){
                        $this->db->sql_commit();
                        return 'success';                       
                     }else{
                        return 'fail';                        
                     }                  
                }else{
                     if($recorddingdan){
                        $c_money = intval($recorddingdan['money']);
                        $uid = $recorddingdan['uid'];                        
                        $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                        $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                      
                        $pay = System::load_app_class('UserPay', 'common'); 
                        $scookies = unserialize($recorddingdan['scookies']);        
                        $pay->scookie = $scookies;          
                        $ok = $pay->init($uid,$pay_type['pay_id'],'go_record'); //夺宝商品  
                        if($ok != 'ok'){
                            return 'fail';  
                        }           
                        $check = $pay->go_pay(1); 
                        if($check&&$up_q2&&$up_q3){
                            $this->db->sql_commit();
                           $recorddel = $this->db->Query("DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')");  
                           if($recorddel){
                            _setcookie("Cartlist", null); 
                            return 'success';                           
                           }
                            return 'fail';  
                         }else{
                            return 'fail';       
                         }                                                              
                     }else{
                        return 'fail';                           
                     }                   
                }                       
                    
             }else{
                return 'fail';                                            
             }



        
    }/* function end */



    /* WAP 一元购异步通知 Yusure */
    public function mobile_clound_notify(){                 
            include_once dirname(__FILE__).DIRECTORY_SEPARATOR."lib/alipay_notify.class.php";
            //计算得出通知验证结果
            $pay_type = $this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'malipay' and `pay_start` = '1'");
            $key =  $pay_type['pay_key'];       //支付KEY
            $partner =  $pay_type['pay_uid'];       //支付商号ID

            $alipay_config = array(
                "partner"      => $partner,
                "key"          => $key,
                "private_key_path" => dirname(__FILE__).'/lib/rsa_private_key.pem',
                "ali_public_key_path" => dirname(__FILE__).'/lib/alipay_public_key.pem',
                "sign_type"    =>'MD5',
                "input_charset"=>'utf-8',
                "cacert"       => dirname(__FILE__).'/lib/cacert.pem',
                "transport"    =>'http'
            );          
            $alipayNotify = new AlipayNotify($alipay_config);
            $verify_result = $alipayNotify->verifyNotify();

            if($verify_result) {
                //解析notify_data
                //注意：该功能PHP5环境及以上支持，需开通curl、SSL等PHP配置环境。建议本地调试时使用PHP开发软件
                $doc = new DOMDocument();   
                if ($alipay_config['sign_type'] == 'MD5') {
                    $doc->loadXML($_POST['notify_data']);
                }
                
                if ($alipay_config['sign_type'] == '0001') {
                    $doc->loadXML($alipayNotify->decrypt($_POST['notify_data']));
                }
                
                if( ! empty($doc->getElementsByTagName( "notify" )->item(0)->nodeValue) ) {

                    //商户订单号
                    $this->out_trade_no = $out_trade_no = $doc->getElementsByTagName( "out_trade_no" )->item(0)->nodeValue;
                    //支付宝交易号
                    $trade_no = $doc->getElementsByTagName( "trade_no" )->item(0)->nodeValue;
                    //交易状态
                    $trade_status = $doc->getElementsByTagName( "trade_status" )->item(0)->nodeValue;
                    
                    if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS') {
                        //TRADE_SUCCESS     高级即时到账，买家付款成功后出现
                        //TRADE_FINISHED    普通和高级(退款)会出现
                        
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
                    }                   
                }
            } else {
                echo "fail";die;
            }   
    }

    
}