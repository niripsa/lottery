<?php 

class jubaopay {
 
    private $config;
    /**
    *   支付入口
    **/
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }       
    public function config($config=null){
            $this->config = $config;
            
    }
    
    public function send_pay(){
        $config = $this->config;
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1'");
        $config['pay_uid']=$pay_type['pay_uid'];
        $config['pay_account']=$pay_type['pay_account'];
        $config['pay_key']=$pay_type['pay_key'];
        $config['pay_type']=$pay_type['pay_type'];
        $payreturn1=array();$payreturn2=array();        
        $payreturn1['pay_class']=$pay_type['pay_class'];
        $payreturn1['pay_fun']="qiantai";       
        $payreturn1=base64_encode(json_encode($payreturn1));  
                             
        $payreturn2['pay_class']=$pay_type['pay_class'];
        $payreturn2['pay_fun']="houtai";    
        $payreturn2=base64_encode(json_encode($payreturn2));        
        $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/plugin-Pay-return-jubaopayReturnUrl';
        $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/plugin-Pay-return-jubaopayNotifyUrl?';   
        
    //  $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
    //  $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;      
        $payid=$config['pay_code'];
        // $partnerid="15110636281796995070";
        $partnerid=$config['pay_uid'];
        $amount=$config['pay_money'];
        $payerName=$config['pay_useruid'] ;
        $remark=htmlspecialchars(trim($config['pay_title']));
        $returnURL=$config['pay_ReturnUrl'];    // 可在商户后台设置
        $callBackURL=$config['pay_NotifyUrl'];  // 可在商户后台设置
        $payMethod="ALL";


        //////////////////////////////////////////////////////////////////////////////////////////////////
         //商户利用支付订单（payid）和商户号（partnerid）进行对账查询
        include dirname(__FILE__).'/jubaopay/jubaopay_enc.php';
        $jubaopay_enc=new jubaopay_enc(dirname(__FILE__).'/jubaopay/jubaopay.ini');
        $jubaopay_enc->setEncrypt("payid", $payid);
        $jubaopay_enc->setEncrypt("partnerid", $partnerid);
        $jubaopay_enc->setEncrypt("amount", $amount);
        $jubaopay_enc->setEncrypt("payerName", $payerName);
        $jubaopay_enc->setEncrypt("remark", $remark);
        $jubaopay_enc->setEncrypt("returnURL", $returnURL);
        $jubaopay_enc->setEncrypt("callBackURL", $callBackURL);

        //对交易进行加密=$message并签名=$signature
        $jubaopay_enc->interpret();
        $message=$jubaopay_enc->message;
        $signature=$jubaopay_enc->signature;
        /**官方测试账号**/
        // $message = "FKnfqL3P0ZbZSELgmzgZyzqnCV0AmChhdGldvwmd3BqWsvunH4Gcr8zPVUs0qpqNQ66fXRYU/us3Lcfb7fv7zWKwLQlbkedMHcjhCMauqh71Sxdpo9sceY3Tgh5k6nWzRf8gIQEkXStLgp/9i0OtPygOSFgzwxpDFoQOgrgBPZ8=SP+HaU//Yo7L5DeGNWNJ/njNtdhIUli1Ki3yQx3h43PNAtSi8tEzyYJIRz9pBX6hG8/ehH9criMcmw4WfKsP9J41NXsIhyOjVlramcW6Ea6V4QT0sBJHoMnPNkEp6pK1a2S6eKVJ4hboOAMbe/VzkOGTUfhl/WdeEOvY2UL3pMo=az0cj3qg7vYMLzJ8aXfs0bIr1nKIW2z3ib5T6zJf8vAYcWqkvIzE/qcXMdx0Tr9doMqb7M8zUcqimgb/9CrsnCP6nm7e+/MEKgVdKbT4x+38/lAFpOM8NJJlzC59wjnFGfz9rM2ErOKKuIutGcMYIOG1shuEBaj7To/ZzEsfAH3bTutgRhDp6scdLW+gZFu1kphnqLb25p5jBev7mpvfE9skC5HFzsHL+Wa+/fbZkVXsFpJBp8Q2/9pIzAWnF5QiZA7mR7OHPkxLVKPtQOMlmlVQhIfYod/gD6u6geYBL+uiBS3pzP61fyO+Jf8dtF03GkxoHEWxSHwwMWsMAMFSZ0PVF6DwqU/ri7a5CiYqki+6IqRZPQMi3gEvNEZUprC0uoQ4PTc7olOGw4YXGzZFJZRr5FVbt1t2lJgsg5+c5eTPnzET03yGVB+x9wuJI5iF";
        // $signature = "KZ6HCdUWAeteaRNkSuwhrS+qOOyb/fGM1HZK+ycMlHB9M00mhQrkRubHA1cUm+03VQJ0KSAE1gZb0kgztjdPaAO8sdNW9Z9kCZeRV4hSQzfydkhQLw4Zp/rMoOq4pd4UdjyhQkSj5Pcr4F068VDwwBfIVUaiO9cIGIrTFV5W4YE=";
        
        if(G_IS_MOBILE)
        include dirname(__FILE__).'\jubaopay\jubaopay_wap_send.tpl.php';
        else
        include dirname(__FILE__).'\jubaopay\jubaopay_send.tpl.php';
        ob_end_flush();
        exit;
    
    }

 }

?>