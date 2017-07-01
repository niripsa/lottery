<?php
    header ( 'Content-type:text/html;charset=utf-8' );
    include_once $_SERVER ['DOCUMENT_ROOT'] . '/system/modules/pay/lib/cupay/func/common.php';
    include_once $_SERVER ['DOCUMENT_ROOT'] . '/system/modules/pay/lib/cupay/func/SDKConfig.php';
    include_once $_SERVER ['DOCUMENT_ROOT'] . '/system/modules/pay/lib/cupay/func/secureUtil.php';
    include_once $_SERVER ['DOCUMENT_ROOT'] . '/system/modules/pay/lib/cupay/func/log.class.php';

/*
    @版本 ： 1.0
    @时间 :  2015-01-26
    @名称 :  中国银联
*/
class munionpay {
    
    private $config;
    private $url;
    
    //主入口
    public function config($config=null){

        $this->config = $config;
        $this->config_jsdz();
    }

    //即时到账
    public function config_jsdz(){  
        //初始化日志
        $log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
        $log->LogInfo ( "============处理前台请求开始===============" );
        // 初始化日志
        $params = array(
                'version' => '5.0.0',                       //版本号
                'encoding' => 'UTF-8',                      //编码方式
                'certId' => getSignCertId (),               //证书ID
                'txnType' => '01',                              //交易类型  
                'txnSubType' => '01',                           //交易子类
                'bizType' => '000000',                          //业务类型
                'frontUrl' =>  SDK_FRONT_NOTIFY_URL,                //前台通知地址
                'backUrl' => SDK_BACK_NOTIFY_URL,               //后台通知地址  
                'signMethod' => '01',       //签名方法
                'channelType' => '07',                  //渠道类型
                'accessType' => '0',                            //接入类型
                'merId' => '301442048990007',                   //商户代码
                //'orderId' => date('YmdHis'),                  //商户订单号      
                'orderId' => $this->config['code'],                 //商户订单号 
                'txnTime' => date('YmdHis'),                //订单发送时间
                'txnAmt' => $this->config['money'] * 100,                               //交易金额 单位分
                'currencyCode' => '156',                        //交易币种
                //'defaultPayType' => '0001',                       //默认支付方式  
                );
            

        // 签名
        
        //echo "<pre>";
        //echo print_r($params);exit;
        sign ( $params );


        // 前台请求地址
        $front_uri = SDK_FRONT_TRANS_URL;
        $log->LogInfo ( "前台请求地址为>" . $front_uri );
        // 构造 自动提交的表单
        $this->url = create_html ( $params, $front_uri );

        $log->LogInfo ( "-------前台交易自动提交表单>--begin----" );
        $log->LogInfo ( $this->url );
        $log->LogInfo ( "-------前台交易自动提交表单>--end-------" );
        $log->LogInfo ( "============处理前台请求 结束===========" );
        
        
    }

    //发送
    public function send_pay(){
         echo  $this->url;
         exit;
        //header("Location: $url"); 
    }
    
}