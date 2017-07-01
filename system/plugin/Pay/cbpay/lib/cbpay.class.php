<?php
include dirname(__FILE__).DIRECTORY_SEPARATOR."cbpay_submit.class.php";
class cbpay{
    private $config;
    private $url;
    
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }      
    //主入口
    public function config($config=null){
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1' and `pay_id`='$config[pay_id]'");
        $config['pay_uid']=$pay_type['pay_uid'];
        $config['pay_key']=$pay_type['pay_key'];
        $config['pay_type']=$pay_type['pay_type'];
        $payreturn1=array();$payreturn2=array();        
        $payreturn1['pay_class']=$pay_type['pay_class'];
        $payreturn1['pay_fun']="qiantai";       
        $payreturn1=base64_encode(json_encode($payreturn1));  
                             
        $payreturn2['pay_class']=$pay_type['pay_class'];
        $payreturn2['pay_fun']="houtai";    
        $payreturn2=base64_encode(json_encode($payreturn2));          
        $config['pay_ReturnUrl'] = G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        $config['pay_NotifyUrl'] = G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;        
        
        $this->config = $config;    
        $this->config_jsdz();
    }

    //即时到账
    private function config_jsdz(){
        $cbpay_config = $this->config;
        $cbpay_config1          = array();
        $cbpay_config1['v_mid'] = trim($cbpay_config['pay_uid']);
        $cbpay_config2['key']   = trim($cbpay_config['pay_key']);
        $cbpay_config1['v_oid'] = trim($cbpay_config['pay_code']);
        $cbpay_config1['v_amount'] = trim($cbpay_config['pay_money']);
        // $cbpay_config1['v_amount'] = 0.01;
        $cbpay_config1['v_moneytype'] = "CNY";
        $cbpay_config1['v_url'] = trim($cbpay_config['pay_ReturnUrl']);
        if( $cbpay_config['pay_bank'] == 'DEFAULT' ){
            $cbpay_config['pay_bank'] = "";
        }else{
            $cbpay_config['pay_bank'] = str_replace('cbpay','',$cbpay_config['pay_bank']);      
        }       
        $cbpay_config1['pmode_id']  = trim($cbpay_config['pay_bank']);
        $cbpay_config1['remark1']   = $cbpay_config['order_type'];
        $cbpay_config1['remark2']   = "[url:=".$cbpay_config['pay_NotifyUrl']."]";
        $cbpay_config1['v_md5info'] = strtoupper(md5($cbpay_config1['v_amount'].$cbpay_config1['v_moneytype'].$cbpay_config1['v_oid'].$cbpay_config1['v_mid'].$cbpay_config1['v_url'].$cbpay_config2['key']));      
        $cbpaySubmit = new CbpaySubmit($cbpay_config1);
            
        $this->url = $cbpaySubmit->buildRequestForm($cbpay_config1,'POST','submit');
    }

    //发送
    public function send_pay(){
         echo  $this->url;
         exit;
    }



}