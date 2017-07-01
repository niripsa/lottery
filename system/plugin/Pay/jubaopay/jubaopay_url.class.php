<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
class jubaopay_url {
    private $error = null;  
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   

    
        
    public function qiantai(){  
        sleep(2);
        $out_trade_no = $_GET['payid']; //商户订单号
        $ua = strtolower($_SERVER['HTTP_USER_AGENT']);
        $uachar = "/(nokia|sony|ericsson|mot|samsung|sgh|lg|philips|panasonic|alcatel|lenovo|cldc|midp|mobile)/i";
        // 手机端
        if(($ua == '' || preg_match($uachar, $ua))&& !strpos(strtolower($_SERVER['REQUEST_URI']),'wap')){
            $dingdaninfo = $this->db->GetOne("select `ostatus` from `@#_orders` where `ocode` = '$out_trade_no'");
            if($dingdaninfo){
                if($dingdaninfo['ostatus']==2){
                    _message('充值成功！',WEB_PATH);
                }else{
                    _message('充值失败！',WEB_PATH);
                }
            }else{
                $recorddingdan = $this->db->GetOne("select `status` from `@#_user_money_record` where `code` = '$out_trade_no'");
                if($recorddingdan){
                    if($recorddingdan['status']==2){
                        _message('支付成功！',WEB_PATH);
                    }else{
                        _message('支付失败！',WEB_PATH);
                    }
                    _message('充值成功',WEB_PATH);
                }else{
                    _message('没有这个订单！',WEB_PATH);
                }                
             } 
      
        }else{
            $dingdaninfo = $this->db->GetOne("select `ostatus` from `@#_orders` where `ocode` = '$out_trade_no'");
            if($dingdaninfo){
                if($dingdaninfo['ostatus']==2){
                    _message('充值成功！',WEB_PATH);
                }else{
                    _message('充值失败！',WEB_PATH);
                }
            }else{
                $recorddingdan = $this->db->GetOne("select `status` from `@#_user_money_record` where `code` = '$out_trade_no'");
                if($recorddingdan){
                    if($recorddingdan['status']==2){
                        _message('支付成功！',WEB_PATH);
                    }else{
                        _message('支付失败！',WEB_PATH);
                    }
                    _message('充值成功',WEB_PATH);
                }else{
                    _message('没有这个订单！',WEB_PATH);
                }                
             } 
        }
    }
    
    public function houtai(){

        $message        =trim($_REQUEST["message"]);
        $signature      =trim($_REQUEST["signature"]);
        
        include dirname(__FILE__).'\lib\jubaopay\jubaopay_enc.php';
        $jubaopay_enc=new jubaopay_enc(dirname(__FILE__).'\lib\jubaopay\jubaopay.ini');
        $jubaopay_enc->decrypt($message);
        // 校验签名，然后进行业务处理
        $result=$jubaopay_enc->verify($signature);
                        
        $out_trade_no = $jubaopay_enc->getEncrypt("payid");
        $this->out_trade_no = $out_trade_no;    
        if(!$out_trade_no){
            echo "返回参数错误";exit;   
        }
        //校验码正确.
        if($result == 1){
            $state = $jubaopay_enc->getEncrypt("state");
            $orderNo = $jubaopay_enc->getEncrypt("orderNo");
            $amount = intval($jubaopay_enc->getEncrypt("amount"));
            if($state=="2"){                

                //如果需要应答机制则必须回写流,以success开头,大小写不敏感.  
                $ret = $this->jubaopay_chuli(intval($amount));
                if( $ret == '充值完成' || $ret == '商品购买成功'){
                    echo 'success';exit;
                }
                if($ret == '充值失败' || $ret == '商品购买失败'){
                    echo $ret;exit;
                }
            }
                        
        }else{
            echo "交易信息被篡改";
        }
    
        

    }
    
    private function jubaopay_chuli($amount){
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'jubaopay' and `pay_start` = '1'");
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
                $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '聚宝支付', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                if($up_q1&&$up_q2&&$up_q3){
                    $this->db->sql_commit();
                    return  '充值成功！';                   
                 }else{
                    return '充值失败！';                      
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
                    $ok = $pay->init($uid,$pay_type['pay_id'],'go_record'); //云购商品  
                    if($ok != 'ok'){
                        return  '商品购买失败！';
                    }           
                    $check = $pay->go_pay(1); 
                    if($check&&$up_q2&&$up_q3){
                        $recorddel = $this->db->Query("UPDATE `@#_user_money_record` SET `status` ='2' WHERE (`id`='{$recorddingdan[id]}')");  
                        $this->db->sql_commit();                       
                       if($recorddel){
                        _setcookie("Cartlist", null); 
                        return  '商品购买成功！';                       
                       }
                        return  '商品购买失败！';
                     }else{
                        return  '商品购买失败！'; 
                     }                                                              
                 }else{
                        return  '商品购买失败！';                  
                 }                   
            }                       
                
         }else{
                    return  '商品购买失败！';                                    
         }          

    }
    
}

?>