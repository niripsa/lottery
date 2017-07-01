<?php 

class wxpay {
    
    private $config;
    private $url;

    // 主入口
    public function __construct()
    {
        $this->db = System::load_sys_class( 'model' );
    }

    public function config( $config = null )
    {
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR."WxPayPubHelper.php";//引入文件需求
        $this->config = $config;
        $pay_type = $this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1' and `pay_id`='$config[pay_id]'");
        if ( ! $pay_type ) {}
        $config['pay_uid']     = $pay_type['pay_uid'];
        $config['pay_account'] = $pay_type['pay_account'];
        $config['pay_key']     = $pay_type['pay_key'];
        $config['pay_type']    = $pay_type['pay_type'];
        $payreturn1              = array();
        $payreturn2              = array();        
        $payreturn1['pay_class'] = $pay_type['pay_class'];
        $payreturn1['pay_fun']   = "qiantai";       
        $payreturn1              = base64_encode(json_encode($payreturn1));
        $payreturn2['pay_class'] = $pay_type['pay_class'];
        $payreturn2['pay_fun']   = "houtai";
        $payreturn2              = base64_encode(json_encode($payreturn2));
        $config['pay_ReturnUrl'] = G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        //$config['pay_NotifyUrl']=G_WEB_PATH.'/index.php?plugin=true&api=Pay&action=return&wx='.$payreturn2;     
        $config['pay_NotifyUrl'] = G_WEB_PATH.'/i.php?plugin=true&api=Pay&action=return&wx='.$payreturn2;
        WxPayConf_pub::$APPID      = $config['pay_uid'];
        WxPayConf_pub::$MCHID      = $config['pay_account'];
        WxPayConf_pub::$KEY        = $config['pay_key'];
        WxPayConf_pub::$NOTIFY_URL = $config['pay_NotifyUrl'];
        $this->config = $config;
        
        if ( $config['pay_type'] == 1 )
        {
            if ( is_weixin() )
            {
                $this->config_mobile_v3();
            }
            else
            {
                $this->config_jsdz();
            }
        }
        if ( $config['pay_type'] == 2 )
        {
            $this->config_dbjy();
        }
    }

    /**
     * V3 JSAPI 微信支付
     */
    private function config_mobile_v3()
    {
        $config       = $this->config;
        $out_trade_no = $config['pay_code'];
        $total_fee    = $config['pay_money'] * 100;
        $url = "index.php/member/account/account_pay?ocode=".$out_trade_no;
        header( "location:".$url ); die;
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR."WxPay.Api.php";
        require_once dirname(__FILE__).DIRECTORY_SEPARATOR."WxPay.JsApiPay.php";
        //①、获取用户openid
        $tools = new JsApiPay();
        $openId = $tools->GetOpenid();

        //②、统一下单
        $input = new WxPayUnifiedOrder();
        $input->SetBody("购买/充值");
        $input->SetAttach("购买/充值");
        $input->SetOut_trade_no( $out_trade_no );
        $input->SetTotal_fee( $total_fee );
        $input->SetTime_start(date("YmdHis"));
        $input->SetTime_expire(date("YmdHis", time() + 600));
        $input->SetGoods_tag("购买/充值");
        $input->SetNotify_url( $config['pay_NotifyUrl'] );
        $input->SetTrade_type("JSAPI");
        $input->SetOpenid($openId);
        $order = WxPayApi::unifiedOrder($input);
        echo '<font color="#f00"><b>统一下单支付单信息</b></font><br/>';
        $this->printf_info($order);
        $jsApiParameters = $tools->GetJsApiParameters($order);

        //获取共享收货地址js函数参数
        $editAddress = $tools->GetEditAddressParameters();

echo <<<EOT
<html>
<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/> 
    <title>微信支付样例-支付</title>
    <script type="text/javascript">
    //调用微信JS api 支付
    function jsApiCall()
    {
        WeixinJSBridge.invoke(
            'getBrandWCPayRequest',
            <?php echo $jsApiParameters; ?>,
            function(res){
                WeixinJSBridge.log(res.err_msg);
                alert(res.err_code+res.err_desc+res.err_msg);
            }
        );
    }

    function callpay()
    {
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
                document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
            }
        }else{
            jsApiCall();
        }
    }
    </script>
    <script type="text/javascript">
    //获取共享地址
    function editAddress()
    {
        WeixinJSBridge.invoke(
            'editAddress',
            <?php echo $editAddress; ?>,
            function(res){
                var value1 = res.proviceFirstStageName;
                var value2 = res.addressCitySecondStageName;
                var value3 = res.addressCountiesThirdStageName;
                var value4 = res.addressDetailInfo;
                var tel = res.telNumber;
                
                alert(value1 + value2 + value3 + value4 + ":" + tel);
            }
        );
    }
    
    window.onload = function(){
        if (typeof WeixinJSBridge == "undefined"){
            if( document.addEventListener ){
                document.addEventListener('WeixinJSBridgeReady', editAddress, false);
            }else if (document.attachEvent){
                document.attachEvent('WeixinJSBridgeReady', editAddress); 
                document.attachEvent('onWeixinJSBridgeReady', editAddress);
            }
        }else{
            editAddress();
        }
    };
    
    </script>
</head>
<body>
    <br/>
    <font color="#9ACD32"><b>该笔订单支付金额为<span style="color:#f00;font-size:50px">1分</span>钱</b></font><br/><br/>
    <div align="center">
        <button style="width:210px; height:50px; border-radius: 15px;background-color:#FE6714; border:0px #FE6714 solid; cursor: pointer;  color:white;  font-size:16px;" type="button" onclick="callpay()" >立即支付</button>
    </div>
</body>
</html>
EOT;
    }

    //打印输出数组信息
    public function printf_info($data)
    {
        foreach($data as $key=>$value){
            echo "<font color='#00ff55;'>$key</font> : $value <br/>";
        }
    }

    private function config_mobile()
    {
        $config = $this->config;
        $openid = $_COOKIE['openid'];

        $unifiedOrder = new UnifiedOrder_pub();
        $jsApi = new JsApi_pub();

        //设置统一支付接口参数
        //设置必填参数
        //appid已填,商户无需重复填写
        //mch_id已填,商户无需重复填写
        //noncestr已填,商户无需重复填写
        //spbill_create_ip已填,商户无需重复填写
        //sign已填,商户无需重复填写
        $unifiedOrder->setParameter("openid",$openid);//商品描述
        $unifiedOrder->setParameter("body","购买/充值");//商品描述
        //自定义订单号，此处仅作举例
        //$timeStamp = time();
        //$total_fee=$config['money'];
        $total_fee = $config['pay_money'] * 100;
        //$total_fee=1;
        $out_trade_no = $config['pay_code'];
        $flag = strtolower(substr($out_trade_no,0,1));

        //$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
        $unifiedOrder->setParameter("out_trade_no",$out_trade_no);//商户订单号
        $unifiedOrder->setParameter("total_fee",$total_fee);//总金额
        $unifiedOrder->setParameter("notify_url",$config['pay_NotifyUrl']);//通知地址
        $unifiedOrder->setParameter("trade_type","JSAPI");//交易类型

        //非必填参数，商户可根据实际情况选填
        //$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号
        //$unifiedOrder->setParameter("device_info","XXXX");//设备号
        //$unifiedOrder->setParameter("attach","XXXX");//附加数据
        //$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
        //$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间
        //$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记
        //$unifiedOrder->setParameter("openid","XXXX");//用户标识
        //$unifiedOrder->setParameter("product_id","XXXX");//商品ID

        $prepay_id = $unifiedOrder->getPrepayId();

        //=========步骤3：使用jsapi调起支付============
        $jsApi->setPrepayId($prepay_id);
        $jsApiParameters = $jsApi->getParameters();

        unset($unifiedOrder);
        //echo "location:/wechat/pay.php?s=".$jsApiParameters;
        
        header("location:/wechat/pay.php?f=".$flag."&s=".urlencode($jsApiParameters));
        exit;
    }

    // 及时到账
    private function config_jsdz()
    {
        $config = $this->config;    
        $unifiedOrder = new UnifiedOrder_pub();
            //设置统一支付接口参数
            //设置必填参数
            //appid已填,商户无需重复填写
            //mch_id已填,商户无需重复填写
            //noncestr已填,商户无需重复填写
            //spbill_create_ip已填,商户无需重复填写
            //sign已填,商户无需重复填写
            $unifiedOrder->setParameter("body","夺宝商品");//商品描述
            //自定义订单号，此处仅作举例
            $total_fee=$config['pay_money']*100;
            $out_trade_no=$config['pay_code'];
            //$total_fee=1;
            $unifiedOrder->setParameter("out_trade_no","$out_trade_no");//商户订单号 
            $unifiedOrder->setParameter("total_fee",$total_fee);//总金额
            $unifiedOrder->setParameter("notify_url",WxPayConf_pub::$NOTIFY_URL);//通知地址 
            $unifiedOrder->setParameter("trade_type","NATIVE");//交易类型
            //非必填参数，商户可根据实际情况选填

            
            //获取统一支付接口结果
            $unifiedOrderResult = $unifiedOrder->getResult();
            //商户根据实际情况设置相应的处理流程
            if ($unifiedOrderResult["return_code"] == "FAIL") 
            {
                //商户自行增加处理流程
                echo "通信出错：".$unifiedOrderResult['return_msg']."<br>";
            }
            elseif($unifiedOrderResult["result_code"] == "FAIL")
            {
                //商户自行增加处理流程
                echo "错误代码：".$unifiedOrderResult['err_code']."<br>";
                echo "错误代码描述：".$unifiedOrderResult['err_code_des']."<br>";
            }
            elseif($unifiedOrderResult["code_url"] != NULL)
            {
            
                //从统一支付接口获取到code_url
                 $code_url = $unifiedOrderResult["code_url"];
                 include('native_dynamic_qrcode.php');  
                //商户自行增加处理流程
                //......
            }
            
    }
    
    //担保交易
    private function config_dbjy(){             
    }
    
    //发送
    public function send_pay(){
         echo  $this->url;
         exit;

    }
}

