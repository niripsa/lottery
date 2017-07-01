<?php

/*
    @支付宝即时到账接口
    @版本 ： 1.0
    @时间 :  2014-02-11
    @开发 :  TaoLong network
*/

class uqdalipay {
    
    private $config;
    private $url;
    public function __construct(){          
        $this->db=System::load_sys_class('model');      
    }   
    //主入口
    public function config($config=null){    
        $payreturn1=array();
        $payreturn2=array();

                    
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = '$config[pay_class]' and `pay_start` = '1' and `pay_id`='$config[pay_id]'");
        $config['pay_account']=$pay_type['pay_account'];
        $config['pay_key']=$pay_type['pay_key'];
        $config['pay_type']=$pay_type['pay_type'];
        
        $payreturn1['pay_class']=$pay_type['pay_class'];
        $payreturn1['pay_fun']="qiantai";       
        $payreturn1=json_encode($payreturn1);
        $payreturn1=base64_encode($payreturn1); 
                                
        $payreturn2['pay_class']=$pay_type['pay_class'];
        $payreturn2['pay_fun']="houtai";    
        $payreturn2=json_encode($payreturn2);
        $payreturn2=base64_encode($payreturn2);        
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
        $conf = array(
            'pid' => $config['pay_account'],  
            'pkey' => $config['pay_key'],
            'out_trade_no' => $config['pay_code'],  
            'return_url' => $config['pay_ReturnUrl'],
            'notify_url' => $config['pay_NotifyUrl'],
            'title' => $config['pay_title'],
            'total_fee' => $config['pay_money'],
        );
                  
        $this->url = $this->buildRequestForm($conf,"POST",'');
    }
    
    /*
        POST 构造参数
    */
    private function buildRequestForm($conf, $method, $button_name='') {
        //待请求参数数组

        $sHtml = "<h3>正在跳转到支付宝....</h3>";
        $sHtml .= "<form id='alipaysubmit' name='alipaysubmit' action='http://pay.diankaa.cn/payjk.aspx' method='".$method."'>";

        $sHtml.= "<input type='hidden' name='pid' value='".$conf['pid']."'/>";                      //PID
        $sHtml.= "<input type='hidden' name='pkey' value='".$conf['pkey']."'/>";                    //PKEY
        $sHtml.= "<input type='hidden' name='porderno' value='".$conf['out_trade_no']."'/>";        //订单编号
        $sHtml.= "<input type='hidden' name='pbiaoti' value='".$conf['title']."'/>";                //商品名称
        $sHtml.= "<input type='hidden' name='pmoney' value='".$conf['total_fee']."'/>";             //总价格
        $sHtml.= "<input type='hidden' name='preturnurl' value='".$conf['return_url']."'/>";        //回调地址
        $sHtml.= "<input type='hidden' name='pnotifyurl' value='".$conf['notify_url']."'/>";        //异步通知地址
        
        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";      
        $sHtml = $sHtml."<script>document.forms['alipaysubmit'].submit();</script>";        
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