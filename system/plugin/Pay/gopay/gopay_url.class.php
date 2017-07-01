<?php 

defined('G_IN_SYSTEM')or exit('No permission resources.');
ini_set("display_errors","OFF");

class gopay_url  {

    private $error = false;
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    
    public function qiantai(){      
        if($this->error){   
            _message("支付成功！",WEB_PATH);
        }else{
            $this->houtai();
        }
    
    }
    private function houtai(){  
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'gopay' and `pay_start` = '1'");
        $VerficationCode=$pay_type['pay_key'];      
        $version = $_POST["version"];
        $charset = $_POST["charset"];
        $language = $_POST["language"];
        $signType = $_POST["signType"];
        $tranCode = $_POST["tranCode"];
        $merchantID = $_POST["merchantID"];
        $merOrderNum = $_POST["merOrderNum"];
        $tranAmt = $_POST["tranAmt"];
        $feeAmt = '';
        $frontMerUrl = $_POST["frontMerUrl"];
        $backgroundMerUrl = $_POST["backgroundMerUrl"];
        $tranDateTime = $_POST["tranDateTime"];
        $tranIP = $_POST["tranIP"];
        $respCode = $_POST["respCode"];
        $msgExt = $_POST["msgExt"];
        $orderId = '';
        $gopayOutOrderId ='';
        $bankCode = $_POST["bankCode"];
        $tranFinishTime = $_POST["tranFinishTime"];
        $merRemark1 = $_POST["merRemark1"];
        $merRemark2 = $_POST["merRemark2"];
        $signValue = $_POST["signValue"];
        

        //注意md5加密串需要重新拼装加密后，与获取到的密文串进行验签
        $signValue2='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=['.$orderId.']gopayOutOrderId=['.$gopayOutOrderId.']tranIP=['.$tranIP.']respCode=[]gopayServerTime=[]VerficationCode=['.$VerficationCode.']';
        //VerficationCode是商户识别码为用户重要信息请妥善保存
        //注意调试生产环境时需要修改这个值为生产参数
        
        $signValue2 = md5($signValue2);
    
        //开始处理即时到账和担保交易订单
        if($signValue==$signValue2&&$respCode=='0000') {
            //支付成功，可进行逻辑处理！
            $this->db->sql_begin();

            //查询充值订单
            $aa="select * from `@#_orders` where `ocode` = '$v_oid' and `ostatus` = '1' for update";
            $dingdaninfo = $this->db->GetOne($aa);
            $time = time(); 
            if(!$dingdaninfo){
                $recorddingdan = $this->db->GetOne("select * from `@#_user_money_record` where `code` = '$v_oid' and `status` = '1' for update");                
             } 
             if($dingdaninfo||$recorddingdan){
                if($dingdaninfo){                   
                    $c_money = intval($dingdaninfo['omoney']);                  
                    $uid = $dingdaninfo['ouid'];
                    $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `opay` = '网银在线', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'");
                    $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");              
                    $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");                 
                    if($up_q1&&$up_q2&&$up_q3){
                        $this->db->sql_commit();
                        $this->error = true;
                     }else{
                       $this->error = false;   
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
                            $this->error = false;       
                        }           
                        $check = $pay->go_pay(1); 
                        if($check&&$up_q2&&$up_q3){
                            $this->db->sql_commit();
                           $recorddel = $this->db->Query("DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')");  
                           if($recorddel){
                            _setcookie("Cartlist", null);
                            $this->error = true;                                                
                           }
                           _setcookie("Cartlist", null);
                            $this->error = false;                          
                         }else{
                            $this->error = false;      
                         }                                                              
                     }else{
                            $this->error = false;                      
                     }                   
                }                       
                    
             }else{
                $this->error = false;                                          
             }                      
                    
        }//开始处理订单结束
                

    }//function end

    
}//