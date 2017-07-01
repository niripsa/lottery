<?php 

 /**
  * 功能：异步通知页面
  * 版本：1.0
  * 日期：2012-10-11
  * 作者：中国银联UPMP团队
  * 版权：中国银联
  * 说明：以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己的需要，按照技术文档编写,并非一定要使用该代码。该代码仅供参考。
  * */
  
defined('G_IN_SYSTEM')or exit('No permission resources.');
//ini_set("display_errors","OFF");


include_once dirname(__FILE__)."/lib/unionpay/upmp_config.php";
include_once dirname(__FILE__)."/lib/unionpay/upmp_core.php";
include_once dirname(__FILE__)."/lib/unionpay/upmp_service.php";



class unionpay_url  {
    private $out_trade_no;
    public function __construct(){          
        $this->db=System::load_sys_class('model');
        $config = $this->db->GetOne("SELECT * FROM `@#_payment` WHERE `pay_class` = 'unionpay' LIMIT 1");       
        if(!$config){
            $this->return_meg("未开通该支付接口!");
        }
        $config['pay_key'] = unserialize($config['pay_key']);       
        
        $config['id'] = $config['pay_key']['id']['val'];        //支付合作ID
        $config['key'] = $config['pay_key']['key']['val'];      //支付KEY
        upmp_config::$mer_id            = $config['id'];
        upmp_config::$security_key      = $config['key'];
        upmp_config::$mer_back_end_url  = $config['NotifyUrl']; 
        upmp_config::$mer_front_end_url = $config['ReturnUrl'];
        
    
    }
    
    
    function demo(){
    
        /*
            $base64_url = "tn=".$resp['tn'].",resultURL=".urlencode($config['ReturnUrl'].$code."/").",usetestmode=false";
            $this->base64_url = urlencode(base64_encode($base64_url));  
                */
        
        
        echo $url = "tn=201406271347090012582,resultURL=http%3A%2F%2Fm.cp518.com%2Findex.php%2Fpay%2Funionpay_url%2Fqiantai%2FC14038480293015989%2F,usetestmode=false";
        echo "<br>";
        echo base64_encode($url);
        echo "<br>";
        echo urlencode(base64_encode($url));
    
    }
    
    /*返回*/
    private function return_meg($content='',$title='',$type=""){
        if(_is_mobile()){           
            _messagemobile($content,$title,$type);      
        }else{
            _message($content);
        }   
        
    }

    /*
    *   前台GET返回
    */
    public function qiantai(){
    
        if(!isset($_GET['header'])){
            $url = trim(get_web_url(),"/");
            $url = $url."/?header=1";
            header("Location: $url");exit;
        }
        sleep(2);
        $this->db->Autocommit_start();
        $this->out_trade_no = $out_trade_no = safe_replace($this->segment(4));
        $dingdaninfo = $this->db->GetOne("select * from `@#_orders` where `ocode` = '$out_trade_no' for update");
        $this->db->Autocommit_rollback();       
        if(!$dingdaninfo || $dingdaninfo['ostatus'] == '1'){
            $url = WEB_PATH."/mobile/home";
            header("Location: $url");exit;
            $this->return_meg("支付失败","支付失败","no");      
        }else{
            if(empty($dingdaninfo['scookies'])){
                $this->return_meg("充值成功","充值成功","yes");
            }else{
                if($dingdaninfo['scookies'] == '1'){
                    $this->return_meg("支付成功","支付成功","yes");
                }else{
                    $this->return_meg("商品还未购买,请重新购买商品","商品还未购买,请重新购买商品","no");
                }                   
            }
        }
    }
    /*前台返回函数完毕*/
    
    
    
    function query(){
        
        $bbb  = System::load_app_class("unionpay","pay");
        $config=array();
        $config['id'] = upmp_config::$mer_id;
        $config['key'] = upmp_config::$security_key;
        $config['dingdancode'] = $_POST['orderNumber']; 
        $config['orderTime'] = $_POST['orderTime']; 
        $config['mer_back_end_url'] = upmp_config::$mer_back_end_url;
        $config['mer_front_end_url'] = upmp_config::$mer_front_end_url;
        
        $bbb->unionpay_query($config);
    
    }
    
    /*
    *   后台异步返回
    */
    
    public function houtai(){   
        
        
        /*
            异步通知报文
            $str = UpmpService::buildReq($_POST);       
        */
        if (UpmpService::verifySignature($_POST)){// 服务器签名验证成功
            $this->query();
            //请在这里加上商户的业务逻辑程序代码
            //获取通知返回参数，可参考接口文档中通知参数列表(以下仅供参考)
            $transStatus = $_POST['transStatus'];// 交易状态
            if (""!=$transStatus && "00"==$transStatus){
                // 交易处理成功
                $this->out_trade_no = $_POST['orderNumber'];                
                $ret = $this->unionpay_chuli();         
                if($ret == '已付款' || $ret == '充值完成' || $ret == '商品购买成功'){
                    echo 'success';exit;
                }
                if($ret == '充值失败' || $ret == '商品购买失败'){
                    echo $ret;exit;
                }               
            }           
        }else {
            echo "fail";
        }           
    }
    
    
    /*支付与充值处理*/
    private function unionpay_chuli(){
        $pay_type =$this->db->GetOne("SELECT * from `@#_payment` where `pay_class` = 'unionpay' and `pay_start` = '1'");
        $out_trade_no = $this->out_trade_no;
        $this->db->Autocommit_start();
        $dingdaninfo = $this->db->GetOne("select * from `@#_orders` where `ocode` = '$out_trade_no' for update");
        if(!$dingdaninfo){ return false;}   //没有该订单,失败
        if($dingdaninfo['ostatus'] == '2'){
            return '已付款';
        }
        $c_money = intval($dingdaninfo['omoney']);
        $uid = $dingdaninfo['ouid'];
        $time = time();     
        
        $up_q1 = $this->db->Query("UPDATE `@#_orders` SET `otype` = '手机银联', `ostatus` = '已付款' where `oid` = '$dingdaninfo[id]' and `ocode` = '$dingdaninfo[code]'");
        $up_q2 = $this->db->Query("UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')");          
        $up_q3 = $this->db->Query("INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '充值', '$c_money', '$time')");
        
        if($up_q1 && $up_q2 && $up_q3){         
            $this->db->Autocommit_commit();
        }else{
            $this->db->Autocommit_rollback();
            return '充值失败';
        }           
        if(empty($dingdaninfo['scookies'])){                    
            return "充值完成";  //充值完成  
        }
        
        $scookies = unserialize($dingdaninfo['scookies']);          
        $pay = System::load_app_class('pay','pay');     
        $pay->scookie = $scookies;
        $ok = $pay->init($uid,$pay_type['pay_id'],'go_record'); //夺宝商品  
        if($ok != 'ok'){
            $_COOKIE['Cartlist'] = '';_setcookie('Cartlist',NULL);          
            return '商品购买失败';  //商品购买失败          
        }       

        $check = $pay->go_pay(1);
        if($check){
            $this->db->Query("UPDATE `@#_orders` SET `scookies` = '1' where `ocode` = '$out_trade_no' and `ostatus` = '已付款'");
            $_COOKIE['Cartlist'] = '';_setcookie('Cartlist',NULL);
            return "商品购买成功";
        }else{
            return '商品购买失败';
        }           

    }
}