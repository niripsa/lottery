<?php 
defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");
include_once $_SERVER ['DOCUMENT_ROOT'] . '/system/modules/pay/lib/upoppay/quickpay_service.php';
class upoppay_url extends SystemAction {
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    
    
    public function qiantai($out_trade_no){ 
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

        //更新数据库，将交易状态设置为已付款
        $this->db->Autocommit_start();
        $dingdaninfo = $this->db->GetOne("select * from `@#_member_addmoney_record` where `code` = '$v_oid' and `status` = '未付款' for update");
        if(!$dingdaninfo){  $this->qiantai($v_oid);}    //没有该订单,失败
        $c_money = intval($dingdaninfo['money']);           
        $uid = $dingdaninfo['uid'];
        $time = time();         
        $up_q1 = $this->db->Query("UPDATE `@#_member_addmoney_record` SET `pay_type` = '手机银联', `status` = '已付款' where `id` = '$dingdaninfo[id]' and `code` = '$dingdaninfo[code]'");
        $up_q2 = $this->db->Query("UPDATE `@#_member` SET `money` = `money` + $c_money where (`uid` = '$uid')");                
        $up_q3 = $this->db->Query("INSERT INTO `@#_member_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
            
        if($up_q1 && $up_q2 && $up_q3){
            $this->db->Autocommit_commit();         
        }else{
            $this->db->Autocommit_rollback();
            $this->qiantai($v_oid);
        }           
        if(empty($dingdaninfo['scookies'])){                    
                $this->qiantai($v_oid); //充值完成          
        }           
        $scookies = unserialize($dingdaninfo['scookies']);          
        $pay = System::load_app_class('pay','pay');         
        $pay->scookie = $scookies;  

        $ok = $pay->init($uid,$pay_type['pay_id'],'go_record'); //夺宝商品  
        if($ok != 'ok'){
            _setcookie('Cartlist',NULL);
            $this->qiantai($v_oid); //商品购买失败          
        }           
        $check = $pay->go_pay(1);
        if($check){
            $this->db->Query("UPDATE `@#_member_addmoney_record` SET `scookies` = '1' where `code` = '$v_oid' and `status` = '已付款'");
            _setcookie('Cartlist',NULL);
            $this->qiantai($v_oid);         
        }else{
            $this->qiantai($v_oid);
        }


        //以下仅用于测试
        // file_put_contents('notify.txt', var_export($arr_ret, true));

        }
        catch(Exception $exp) {
            //后台通知出错
            file_put_contents('notify.txt', var_export($exp, true));
        }
                    
    }//function end
    
}//

?>