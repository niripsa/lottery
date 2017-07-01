<?php 

class malipay {
    
    private $config;
    private $url;
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }       
    
    //主入口
    public function config($config=null){
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

        $config['pay_ReturnUrl'] = $config['pay_ReturnUrl'] ? $config['pay_ReturnUrl'] : G_WEB_PATH.'/index.php/plugin-Pay-return-alipayReturnUrl?';
        $config['pay_NotifyUrl'] = $config['pay_NotifyUrl'] ? $config['pay_NotifyUrl'] : G_WEB_PATH.'/index.php/plugin-Pay-return-alipayNotifyUrl?';            
        // $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        // $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;           
        $this->config = $config;        
        $this->send_maliapy();
    }

    //即时到账
    private function send_maliapy(){
        $config = $this->config;
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR."alipay_submit.class.php";           
        
        $alipay_config = array();
        //合作身份者id，以2088开头的16位纯数字
        $alipay_config['partner']       = $config['pay_uid'];

        //安全检验码，以数字和字母组成的32位字符
        //如果签名方式设置为“MD5”时，请设置该参数
        $alipay_config['key']           = $config['pay_key'];

        //商户的私钥（后缀是.pen）文件相对路径
        //如果签名方式设置为“0001”时，请设置该参数
        $alipay_config['private_key_path']  = dirname(__FILE__).'/rsa_private_key.pem';

        //支付宝公钥（后缀是.pen）文件相对路径
        //如果签名方式设置为“0001”时，请设置该参数
        $alipay_config['ali_public_key_path']= dirname(__FILE__).'/alipay_public_key.pem';


        //↑↑↑↑↑↑↑↑↑↑请在这里配置您的基本信息↑↑↑↑↑↑↑↑↑↑↑↑↑↑↑


        //签名方式 不需修改
        $alipay_config['sign_type']    = 'RSA';
        $alipay_config['sign_type']    = '0001';
        $alipay_config['sign_type']    = 'MD5';
        //字符编码格式 目前支持 gbk 或 utf-8
        $alipay_config['input_charset']= 'utf-8';

        //ca证书路径地址，用于curl中ssl校验
        //请保证cacert.pem文件在当前文件夹目录中
        $alipay_config['cacert']    = dirname(__FILE__).'/cacert.pem';

        //访问模式,根据自己的服务器是否支持ssl访问，若支持请选择https；若不支持请选择http
        $alipay_config['transport']    = 'http';
    
        /**************************调用授权接口alipay.wap.trade.create.direct获取授权码token**************************/
            
        //返回格式
        $format = "xml";
        //必填，不需要修改

        //返回格式
        $v = "2.0";
        //必填，不需要修改

        //请求号
        $req_id = date('Ymdhis').rand(10000,99999);
        //必填，须保证每次请求都是唯一

        //http://wappaygw.alipay.com/service/rest.htm

        //**req_data详细信息**

        //服务器异步通知页面路径
        $notify_url = $config['pay_NotifyUrl'];
        //需http://格式的完整路径，不允许加?id=123这类自定义参数

        //页面跳转同步通知页面路径
        $call_back_url = $config['pay_ReturnUrl'];      
        //需http://格式的完整路径，不允许加?id=123这类自定义参数
        
        //操作中断返回地址
        $merchant_url = "http://127.0.0.1:8800/WS_WAP_PAYWAP-PHP-UTF-8/xxxx.php";
        //用户付款中途退出返回商户的地址。需http://格式的完整路径，不允许加?id=123这类自定义参数

        //卖家支付宝帐户    
        $seller_email =  $config['pay_account'];
        //必填

        //商户订单号
        $out_trade_no = $config['pay_code'];
        //商户网站订单系统中唯一订单号，必填

        //订单名称
        $subject = $config['pay_title'];
        //必填

        //付款金额
        $total_fee = $config['pay_money'];
        //$total_fee = 0.01;
        //必填

        //请求业务参数详细
        $req_data = '<direct_trade_create_req><notify_url>' . $notify_url . '</notify_url><call_back_url>' . $call_back_url . '</call_back_url><seller_account_name>' . $seller_email . '</seller_account_name><out_trade_no>' . $out_trade_no . '</out_trade_no><subject>' . $subject . '</subject><total_fee>' . $total_fee . '</total_fee></direct_trade_create_req>';
        //必填
        /**********************************************************

        ***********************************************************/

        //构造要请求的参数数组，无需改动
        $para_token = array(
                "service" => "alipay.wap.trade.create.direct",
                "partner" => trim($alipay_config['partner']),
                "sec_id" => trim($alipay_config['sign_type']),
                "format"    => $format,
                "v" => $v,
                "req_id"    => $req_id,
                "req_data"  => $req_data,
                "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $html_text = $alipaySubmit->buildRequestHttp($para_token);

        //URLDECODE返回的信息
        $html_text = urldecode($html_text);

        //解析远程模拟提交后返回的信息
        $para_html_text = $alipaySubmit->parseResponse($html_text);

        //获取request_token
        $request_token = $para_html_text['request_token'];


        /**************************根据授权码token调用交易接口alipay.wap.auth.authAndExecute**************************/

        //业务详细
        $req_data = '<auth_and_execute_req><request_token>' . $request_token . '</request_token></auth_and_execute_req>';
        //必填

        //构造要请求的参数数组，无需改动
        $parameter = array(
                "service" => "alipay.wap.auth.authAndExecute",
                "partner" => trim($alipay_config['partner']),
                "sec_id" => trim($alipay_config['sign_type']),
                "format"    => $format,
                "v" => $v,
                "req_id"    => $req_id,
                "req_data"  => $req_data,
                "_input_charset"    => trim(strtolower($alipay_config['input_charset']))
        );

        //建立请求
        $alipaySubmit = new AlipaySubmit($alipay_config);
        $this->url = $alipaySubmit->buildRequestForm($parameter, 'GET', 'submit');      
    }

    //发送
    public function send_pay(){
         exit($this->url); 
        //header("Location: $url"); 
    }
}

?>
