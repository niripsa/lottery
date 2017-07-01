<?php
include_once dirname(__FILE__)."/unionpay/common.php";
include_once dirname(__FILE__)."/unionpay/SDKConfig.php";
include_once dirname(__FILE__)."/unionpay/secureUtil.php";
include_once dirname(__FILE__)."/unionpay/log.class.php";
/*
    
    @版本 ： 1.0
    @时间 :  2014-06-16
    @名称 :  中国银联
*/

class unionpay {
    
    private $config;
    private $url;
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    } 
        
    //主入口
    public function config($config=null){            
        $this->config = $config;
        $payreturn1=array();
        $payreturn2=array();
        $payreturn1['pay_class']=$pay_type['pay_class'];
        $payreturn1['pay_fun']="qiantai";       
        $payreturn1=json_encode($payreturn1);
        $payreturn1=base64_encode($payreturn1); 
                                
        $payreturn2['pay_class']=$pay_type['pay_class'];
        $payreturn2['pay_fun']="houtai";    
        $payreturn2=json_encode($payreturn2);
        $payreturn2=base64_encode($payreturn2);
                    
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1' and `pay_id`='$config[pay_id]'");
        $config['pay_account']=$pay_type['pay_account'];
        $config['pay_key']=$pay_type['pay_key'];
        $config['pay_type']=$pay_type['pay_type'];
        $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;         
                
        $this->config_jsdz();
    }
    
    //即时到账
    private function config_jsdz(){ 
    $config =$this->config ;
    $log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
    // 初始化日志
    $params = array(
            'version' => '5.0.0',                       //版本号
            'encoding' => 'UTF-8',                      //编码方式
            'certId' => getSignCertId (),               //证书ID
            'txnType' => '01',                              //交易类型  
            'txnSubType' => '01',                           //交易子类
            'bizType' => '000000',                          //业务类型
            'frontUrl' =>SDK_FRONT_NOTIFY_URL,                  //前台通知地址
            'backUrl' => SDK_FRONT_NOTIFY_URL,              //后台通知地址  
            'signMethod' => '01',       //签名方法
            'channelType' => '07',                  //渠道类型
            'accessType' => '0',                            //接入类型
            'merId' => $config['id'],                   //商户代码
            'orderId' => $config['code'],                   //商户订单号
            'txnTime' => date('YmdHis'),                //订单发送时间
            'txnAmt' => $config['money']*100,                               //交易金额
            'currencyCode' => '156',                        //交易币种
            'defaultPayType' => '0001',                     //默认支付方式  
            );
        // 签名
        sign ( $params );
        // 前台请求地址
        $front_uri = SDK_FRONT_NOTIFY_URL;
        $html_form = create_html ($params,$front_uri);
        $html_form=$log->LogInfo($html_form );
        if(!$html_form){
        exit;
        }
            
        }

}