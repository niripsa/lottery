<?php
    include_once $_SERVER ['DOCUMENT_ROOT'] . '/system/modules/pay/lib/upoppay/quickpay_service.php';
    
/*
    @版本 ： 1.0
    @时间 :  2015-03-27
    @名称 :  yungoucms--upop
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

        //下面这行用于测试，以生成随机且唯一的订单号
        mt_srand(quickpay_service::make_seed());

        $param['transType']             = quickpay_conf::CONSUME;  //交易类型，CONSUME or PRE_AUTH

        
        $param['orderAmount']           = $this->config['money'] * 100;        //交易金额
        $param['orderNumber']           = $this->config['code']; //订单号，必须唯一
        $param['orderTime']             = date('YmdHis');   //交易时间, YYYYmmhhddHHMMSS
        $param['orderCurrency']         = quickpay_conf::CURRENCY_CNY;  //交易币种，CURRENCY_CNY=>人民币

        $param['customerIp']            = $_SERVER['REMOTE_ADDR'];  //用户IP
        $param['frontEndUrl']           = $this->config['ReturnUrl'];    //前台回调URLNotifyUrl
         $param['backEndUrl']            = $this->config['NotifyUrl'];    //后台回调URL

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