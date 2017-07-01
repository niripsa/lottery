<?php 

include dirname(__FILE__).DIRECTORY_SEPARATOR."hcpay_submit.class.php";
class hcpay {
    
    private $config;
    private $url;
    //主入口
    
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }    
    public function config($config=null){                   
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1' and `pay_id`='$config[pay_id]'");
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
        $config['pay_ReturnUrl']= G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        $config['pay_NotifyUrl']=G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;        
        
        $this->config = $config;                
        if($config['pay_type'] == 1){
            $this->config_jsdz();
        }
        if($config['pay_type'] == 2){
            $this->config_dbjy();
        }
        
    }
    
    //汇潮支付每天11点到账
    private function config_jsdz(){
        $config = $this->config;
        $payment_type = "1";
        $MerNo = $config['pay_account']; //商户号
        $MD5key = $config['pay_key'];       //MD5私钥
        $BillNo = $config['pay_code'];//[必填]订单号
        $Amount = $config['pay_money'];//订单金额
        //$Amount ="0.01";
        $ReturnURL = $config['pay_ReturnUrl']; //[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过
        $AdviceURL = $config['pay_NotifyUrl']; //[必填]支付完成后，后台接收支付结果，可用来更新数据库值
        $orderTime = time(); //[必填]交易时间
        $defaultBankNumber = ""; //[选填]银行代码,没有指定银行 请保持为空
        $md5src = $MerNo."&".$BillNo."&".$Amount."&".$ReturnURL."&".$MD5key;        //校验源字符串
        $SignInfo = strtoupper(md5($md5src));       //MD5检验结果
        $Remark = ""; //[选填]升级
        $products = $config['pay_title']; //物品信息        
    
        $hcpay_config_id = $config['pay_account'];                                      //合作身份者id，以2088开头的16位纯数字
        $hcpay_config_key = $config['pay_key'];                                 //安全检验码，以数字和字母组成的32位字符
        $hcpay_config_input_charset = strtolower('utf-8');
        
        //构造要请求的参数数组，无需改动
        $parameter = array(
                    "MerNo" => $MerNo,//商户号
                    "BillNo" => $BillNo,//[必填]订单号
                    "Amount"    => $Amount,//订单金额
                    "ReturnURL" => $ReturnURL,//[必填]返回数据给商户的地址(商户自己填写):::注意请在测试前将该地址告诉我方人员;否则测试通不过
                    "AdviceURL" => $AdviceURL,//[必填]支付完成后，后台接收支付结果，可用来更新数据库值
                    "orderTime" => $orderTime,//[必填]交易时间
                    "defaultBankNumber" => $defaultBankNumber,//[选填]银行代码
                    "SignInfo"  => $SignInfo,//MD5检验结果
                    "Remark"    => $Remark,//[选填]升级
                    "products"  => $products,//物品信息
        );
        
        //签名方式 不需修改
        $hcpay_config_sign_type = strtoupper('MD5');            
        $hcpay_config_transport   = 'http';
        
        $hcpay_config=array(
            "partner"      =>$hcpay_config_id,
            "key"          =>$hcpay_config_key,
            "sign_type"    =>$hcpay_config_sign_type,
            "input_charset"=>$hcpay_config_input_charset,
            "transport"    =>$hcpay_config_transport
        );
        
        $hcpaySubmit = new HcpaySubmit($hcpay_config);
        $this->url = $hcpaySubmit->buildRequestForm($parameter,'POST','submit');
        
    }
        //发送
    public function send_pay(){
         echo  $this->url;
         exit;
        //header("Location: $url"); 
    }
}

?>
