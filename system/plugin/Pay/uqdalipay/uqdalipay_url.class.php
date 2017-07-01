<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

class uqdalipay_url  {

    private $error = null;
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    
    public function qiantai(){  
        $this->out_trade_no = $_GET['out_trade_no'];
        $this->qiantai_one($getinfo);
        $this->qiantai_two();
    
    }
    public function qiantai_two(){
        _message(L('pay.suc'),WEB_PATH);       
    }
    
    private function qiantai_one(){     
        if(!isset($_GET['notify_type']) && $_GET['notify_type'] != 'trade_status_sync'){        
            $this->error = false;return;            
        }       
        if(!isset($_GET['seller_email'])){
            $this->error = false;return;            
        }
        
        $out_trade_no = $_GET['out_trade_no'];  //商户订单号
        $trade_no = $_GET['trade_no'];          //支付宝交易号
        $trade_status = $_GET['trade_status'];  //交易状态
    
        //开始处理即时到账和担保交易订单
        if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS' || $trade_status == 'WAIT_SELLER_SEND_GOODS') {                        
            $this->db->sql_begin();
            //---以下为充值处理--
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
                    $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '分润支付宝', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                    if($up_q1&&$up_q2&&$up_q3){
                        $this->error = true;return;  
                     }else{
                       $this->error = false;return;       
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
                            _setcookie('Cartlist',NULL);
                            $this->error = false;return;    //商品购买失败          
                        }           
                        $check = $pay->go_pay(1); 
                        if($check&&$up_q2&&$up_q3){
                           $recorddel = $this->db->Query("DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')");  
                           if($recorddel){
                            _setcookie('Cartlist',NULL);
                             $this->error = true;return;                                                           
                           }
                           _setcookie('Cartlist',NULL);
                            $this->error = true;return;  
                         }else{
                                $this->error = false;return;      
                         }                                                              
                     }else{
                        $this->error = false;return;                       
                     }                   
                }                       
                    
             }else{
                $this->error = false;return;                                             
             }           
        
        }//开始处理订单结束
                

    }//function end
    
    

    public function houtai(){
        if(!isset($_GET['notify_type']) && $_GET['notify_type'] != 'trade_status_sync'){        
            $this->error = false;return;            
        }       
        if(!isset($_GET['seller_email'])){
            $this->error = false;return;            
        }
        
        $out_trade_no = $_GET['out_trade_no'];  //商户订单号
        $trade_no = $_GET['trade_no'];          //支付宝交易号
        $trade_status = $_GET['trade_status'];  //交易状态
    
        //开始处理即时到账和担保交易订单
        if($trade_status == 'TRADE_FINISHED' || $trade_status == 'TRADE_SUCCESS' || $trade_status == 'WAIT_SELLER_SEND_GOODS') {                        
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
                    $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '分润支付宝', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                    if($up_q1&&$up_q2&&$up_q3){
                        $this->db->sql_commit();                    
                        $this->error = true;return;  
                     }else{
                       $this->error = false;return;       
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
                            _setcookie('Cartlist',NULL);
                            $this->error = false;return;    //商品购买失败          
                        }           
                        $check = $pay->go_pay(1); 
                        if($check&&$up_q2&&$up_q3){
                           $this->db->sql_commit();                     
                           $this->error = true;return;    
                         }else{
                                $this->error = false;return;      
                         }                                                              
                     }else{
                        $this->error = false;return;                       
                     }                   
                }                       
                    
             }else{
                $this->error = false;return;                                             
             }           
        
        }
        //开始处理订单结束
  } 
  
  
  
}//