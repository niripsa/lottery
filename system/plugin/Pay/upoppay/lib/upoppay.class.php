<?php
require_once(dirname(__FILE__) . '/quickpay_service.php');  
/*
    @版本 ： 1.0
    @时间 :  2015-03-27
    @名称 :  yungoucms--upop
*/
class upoppay {
    
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
        $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;        
        
        $this->config = $config;   
        $this->config_jsdz();
    }

    //即时到账
    public function config_jsdz(){  

        //下面这行用于测试，以生成随机且唯一的订单号
        mt_srand(quickpay_service::make_seed());

        $param['transType']             = quickpay_conf::CONSUME;  //交易类型，CONSUME or PRE_AUTH

        
        $param['orderAmount']           = $this->config['pay_money'] * 100;        //交易金额
        $param['orderNumber']           = $this->config['pay_code']; //订单号，必须唯一
        $param['orderTime']             = date('YmdHis');   //交易时间, YYYYmmhhddHHMMSS
        $param['orderCurrency']         = quickpay_conf::CURRENCY_CNY;  //交易币种，CURRENCY_CNY=>人民币

        $param['customerIp']            = $_SERVER['REMOTE_ADDR'];  //用户IP
        $param['frontEndUrl']           = $this->config['pay_ReturnUrl'];    //前台回调URLNotifyUrl
        $param['backEndUrl']            = $this->config['pay_NotifyUrl'];    //后台回调URL

/*
$param['frontEndUrl']           = "http://www.example.com/sdk/utf8/front_notify.php";    //前台回调URL
$param['backEndUrl']            = "http://www.example.com/sdk/utf8/back_notify.php";   
*/
        /* 可填空字段
           $param['commodityUrl']          = "http://www.example.com/product?name=商品";  //商品URL
           $param['commodityName']         = '商品名称';   //商品名称
           $param['commodityUnitPrice']    = 11000;        //商品单价
           $param['commodityQuantity']     = 1;            //商品数量
        //*/

        //其余可填空的参数可以不填写
           
         //echo "<pre>";
         //print_r($param);exit;
        $pay_service = new quickpay_service($param, quickpay_conf::FRONT_PAY);
        $this->url = $pay_service->create_html();

        
        
    }

    //发送
    public function send_pay(){
         header("Content-Type: text/html; charset=" . quickpay_conf::$pay_params['charset']);
         echo  $this->url;
         exit;
        //header("Location: $url"); 
    }
    
}