<?php
include dirname(__FILE__).DIRECTORY_SEPARATOR."HttpClient.class.php";
/*
    @支付宝即时到账接口
    @版本 ： 1.0
    @时间 :  2014-02-11
    @开发 :  TaoLong network
*/

class gopay {
    
    private $config;
    private $url;
    
    //主入口
    
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }     
    public function config($config=null){                
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1' and `pay_id`='$config[pay_id]'");
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
                
        $config['pay_account']=$pay_type['pay_account'];
        $config['pay_uid']=$pay_type['pay_uid'];
        $config['pay_key']=$pay_type['pay_key'];
        $config['pay_type']=$pay_type['pay_type'];
        $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2; 
         
        $this->config = $config;        
        if($config['pay_type'] == 1){
            $this->config_jsdz();
        }
    }
    
    //即时到账
    private function config_jsdz(){
        $config = $this->config;
        $version='2.1';
        $language='1';
        $tranCode='8888';
        $merchantID=$config['pay_uid'];
        $merOrderNum=$config['pay_code'];
        $tranAmt=$config['pay_money'];
        $feeAmt='';
        $tranDateTime=date('YmdHis',time());
        $frontMerUrl=$config['pay_ReturnUrl'];
        $backgroundMerUrl=$config['pay_NotifyUrl'];
        $tranIP= $_SERVER["REMOTE_ADDR"];
        // $gopayServerTime=HttpClient::getGopayServerTime();
        $gopayServerTime='';
        $VerficationCode=$config['pay_key'];
        $virCardNoIn=$config['pay_account'];
        
        $signStr='version=['.$version.']tranCode=['.$tranCode.']merchantID=['.$merchantID.']merOrderNum=['.$merOrderNum.']tranAmt=['.$tranAmt.']feeAmt=['.$feeAmt.']tranDateTime=['.$tranDateTime.']frontMerUrl=['.$frontMerUrl.']backgroundMerUrl=['.$backgroundMerUrl.']orderId=[]gopayOutOrderId=[]tranIP=['.$tranIP.']respCode=[]gopayServerTime=['.$gopayServerTime.']VerficationCode=['.$VerficationCode.']';
        //VerficationCode是商户识别码为用户重要信息请妥善保存
        //注意调试生产环境时需要修改这个值为生产参数
        $signValue = md5($signStr); 
        $conf = array(
            'version' => '2.1',  //版本号  
            'language' => '1',//语言种类  1 GBK 2 UTF-8 默认为 1 
            'charset' => '1',//1 GBK 2 UTF-8 默认为 1
            'signType' => '1',//1 MD5 2 SHA 默认为 1
            'tranCode' => '8888',   //交易代码     本域指明了交易的类型，支付网关 接口必须为8888
            'merchantID' => $merchantID,  //商户代码 签约国付宝商户唯一用户ID
            'merOrderNum' => $merOrderNum,//订单号 
            'tranAmt' =>$tranAmt, //交易金额
            'feeAmt' =>'', //商户提取 佣金金额
            'currencyType' => '156', //币种  多币种预留字段，暂只能为 156，代 表人民币 
            'frontMerUrl' => $frontMerUrl, //前台通知地址 
            'backgroundMerUrl' => $backgroundMerUrl, //后台通知地址
            'tranDateTime' => $tranDateTime, //交易时间  订单发起的交易时间 
            'virCardNoIn' => $virCardNoIn,  //转入账户  卖家在国付宝平台开设的国付宝账户号
            'tranIP' => $tranIP,  //用户IP 
            'isRepeatSubmit' =>'0',  //0不允许重复 1 允许重复 默认  
            'bankCode' =>'',//直连银行交 易必填 ICBC 
            'userType' => '',//直连银行交易(必填) 1
            'gopayServerTime' => $gopayServerTime,//开启时间戳 防钓鱼机制 必填 20111202115229
            'goodsName' => '',//商品名称
            'goodsDetail' => '',
            'buyerName' => '',
            'buyerContact' => '',
            'merRemark1' => '',
            'merRemark2' => '',
            'signValue' => $signValue //VerficationCode是商户识别码为用户重要信息请妥善保存 
        );
        $this->url = $this->buildRequestForm($conf,"POST",'');
    }
    
    /*
        POST 构造参数
    */
    private function buildRequestForm($conf, $method, $button_name='') {
        //待请求参数数组        
        $sHtml = "<h3>正在跳转到国付宝....</h3>";
        $sHtml.= "<form id='returnfunc' name='returnfunc' action='https://gateway.gopay.com.cn/Trans/WebClientAction.do' method='".$method."'>";
        $sHtml.= "<input type='hidden' id='version' name='version' value='".$conf['version']."'/>";                         
        $sHtml.= "<input type='hidden' id='charset' name='charset' value='".$conf['charset']."'/>";                         
        $sHtml.= "<input type='hidden' id='language' name='language' value='".$conf['language']."'/>";                          
        $sHtml.= "<input type='hidden' id='signType' name='signType' value='".$conf['signType']."'/>";                          
        $sHtml.= "<input type='hidden' id='tranCode' name='tranCode' value='".$conf['tranCode']."'/>";                          
        $sHtml.= "<input type='hidden' id='merchantID' name='merchantID' value='".$conf['merchantID']."'/>";                            
        $sHtml.= "<input type='hidden' id='merOrderNum' name='merOrderNum' value='".$conf['merOrderNum']."'/>";                         
        $sHtml.= "<input type='hidden' id='tranAmt' name='tranAmt' value='".$conf['tranAmt']."'/>";                         
        $sHtml.= "<input type='hidden' id='feeAmt' name='feeAmt' value='".$conf['feeAmt']."'/>";                            
        $sHtml.= "<input type='hidden' id='currencyType' name='currencyType' value='".$conf['currencyType']."'/>";                          
        $sHtml.= "<input type='hidden' id='frontMerUrl' name='frontMerUrl' value='".$conf['frontMerUrl']."'/>";                         
        $sHtml.= "<input type='hidden' id='backgroundMerUrl' name='backgroundMerUrl' value='".$conf['backgroundMerUrl']."'/>";                          
        $sHtml.= "<input type='hidden' id='tranDateTime' name='tranDateTime' value='".$conf['tranDateTime']."'/>";                          
        $sHtml.= "<input type='hidden' id='virCardNoIn' name='virCardNoIn' value='".$conf['virCardNoIn']."'/>";                         
        $sHtml.= "<input type='hidden' id='tranIP' name='tranIP' value='".$conf['tranIP']."'/>";                                                    
        $sHtml.= "<input type='hidden' id='isRepeatSubmit' name='isRepeatSubmit' value='".$conf['isRepeatSubmit']."'/>";                                                    
        $sHtml.= "<input type='hidden' id='bankCode' name='bankCode' value='".$conf['bankCode']."'/>";                          
        $sHtml.= "<input type='hidden' id='userType' name='userType' value='".$conf['userType']."'/>";                          
        $sHtml.= "<input type='hidden' id='gopayServerTime' name='gopayServerTime' value='".$conf['gopayServerTime']."'/>"; 
        $sHtml.= "<input type='hidden' id='goodsName' name='goodsName' value='".$conf['goodsName']."'/>";   
        $sHtml.= "<input type='hidden' id='goodsDetail' name='goodsDetail' value='".$conf['goodsDetail']."'/>"; 
        $sHtml.= "<input type='hidden' id='buyerName' name='buyerName' value='".$conf['buyerName']."'/>";   
        $sHtml.= "<input type='hidden' id='buyerContact' name='buyerContact' value='".$conf['buyerContact']."'/>";  
        $sHtml.= "<input type='hidden' id='merRemark1' name='merRemark1' value='".$conf['merRemark1']."'/>";    
        $sHtml.= "<input type='hidden' id='merRemark2' name='merRemark2' value='".$conf['merRemark2']."'/>";    
        $sHtml.= "<input type='hidden' id='signValue' name='signValue' value='".$conf['signValue']."'/>";   
        $sHtml.= "<script>document.forms['returnfunc'].submit();</script>";     
        return $sHtml;
    }
    
    //发送
    public function send_pay(){
            iconv_set_encoding("internal_encoding", "UTF-8");
            iconv_set_encoding("output_encoding", "GBK");
            // 开始缓存
            ob_start("ob_iconv_handler");   
            echo   $this->url  ;
            ob_end_flush();
            exit;
            //header("Location: $url"); 
    }
}