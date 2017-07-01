<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
include dirname(__FILE__).DIRECTORY_SEPARATOR."lib/quickpay_service.php";
class upoppay_url {
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    
    
    public function qiantai($out_trade_no){ 
/*  
        sleep(2);
        $out_trade_no = $_REQUEST['orderNumber'];
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$out_trade_no'");
        if(!$dingdaninfo || $dingdaninfo['status'] == '未付款'){
            _message("支付失败");           
        }else{
            if(empty($dingdaninfo['scookies'])){
                _message("充值成功!",WEB_PATH."/member/home/userbalance");
            }else{
                if($dingdaninfo['scookies'] == '1'){
                    _message("支付成功!",WEB_PATH."/member/cart/paysuccess");
                }else{
                    _message("商品还未购买,请重新购买商品!",WEB_PATH."/member/cart/cartlist");
                }
                    
            }
        }
*/
    }
    
    
    public function houtai(){
        $v_oid = $_POST['orderNumber'];
        try {
        $response = new quickpay_service($_POST, quickpay_conf::RESPONSE);
        if ($response->get('respCode') != quickpay_service::RESP_SUCCESS) {
            $err = sprintf("Error: %d => %s", $response->get('respCode'), $response->get('respMsg'));
            throw new Exception($err);
        }

        $arr_ret = $response->get_args();
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
                    $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '银联支付', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                    if($up_q1&&$up_q2&&$up_q3){
                        $this->db->sql_commit();
                        echo "success";exit;  
                     }else{
                       echo "fail";exit;      
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
                            _setcookie("Cartlist", null);
                            echo "fail";exit;   //商品购买失败          
                        }           
                        $check = $pay->go_pay(1); 
                        if($check&&$up_q2&&$up_q3){
                            $this->db->sql_commit();
                           $recorddel = $this->db->Query("DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')");  
                           if($recorddel){
                            _setcookie("Cartlist", null);
                             echo "success";exit;                                                         
                           }
                           _setcookie("Cartlist", null);
                               echo "success";exit;
                         }else{
                            echo "fail";exit;      
                         }                                                              
                     }else{
                      echo "fail";exit;                       
                     }                   
                }                       
                    
             }else{
                echo "fail";exit;                                            
             } 
        }
        catch(Exception $exp) {
            //后台通知出错
            file_put_contents('notify.txt', var_export($exp, true));
        }
                    
    }//function end
    
}//

?>