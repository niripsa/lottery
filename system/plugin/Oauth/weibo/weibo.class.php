<?php 

/**
 * 新浪微博API接口开发
 * 基于新浪SAE云平台提供的接口, 官网：http://sae.sina.com.cn
 * 2014-12-24
 * 战线@booobusy@gmail.com
 * SAE版本 : 2.0
 * 版本    : 1.0
 *
 **/

include "config.php";
include "saetv2.ex.class.php";



/**
    退出登录 http://open.weibo.com/wiki/2/account/end_session
    2.00W7WRjBPkmTxC5987c179fdxak1uC
    2.00W7WRjBPkmTxC5987c179fdxak1uC
    
    授权页面有cookie时可以用end_session清除已验证用户的session，退出登录，
    并将cookie设为null。这个主要用于widget等web应用场合。
    或者在授权链接上添加focelogin参数，将focelogin参数设置为true。
    
    
    2  Intel(R) Pentium(R) D CPU 3.00GHz
 * 
 * array(4) { ["access_token"]=> string(32) "2.00W7WRjBPkmTxC0e3611c06798PpxD" 
 * ["remind_in"]=> string(6) "125089" 
 * ["expires_in"]=> int(125089)
 *  ["uid"]=> string(10) "1585244180" }

    1585244180
**/


class oauth_weibo  {
    
    private $o;
    
    public function __construct(){
        $this->o = new SaeTOAuthV2(WB_AKEY,WB_SKEY);
    }
        

    /*退出微博登陆*/
    public function out(){
        $c = new SaeTClientV2( WB_AKEY , WB_SKEY , "2.00W7WRjBPkmTxC5987c179fdxak1uC");
        $parameters=array('access_token'=>"2.00W7WRjBPkmTxC5987c179fdxak1uC");
        $msg = $c->oauth->get("https://api.weibo.com/2/account/end_session.json",$parameters);

    }
    
    /*取得登陆接口地址*/
    public function login(){
        
        _session_destroy();
        $code_url = $this->o->getAuthorizeURL(WB_CALLBACK_URL);
        if(isset($_GET['return']) && $_GET['return'] == '1'){
            exit($code_url);
        }
        exit(header("location:".$code_url."&focelogin=1"));
    }
    
    /*回调的处理*/
    public function callback(){
        if (isset($_GET['code'])) {
            $keys = array();
            $keys['code'] = $_GET['code'];
            $keys['redirect_uri'] = WB_CALLBACK_URL;
            try {
                $token = $this->o->getAccessToken('code',$keys);
            } catch (OAuthException $e) {
                exit("微博授权出错");
            }
        }
                
        //根据ID获取用户等基本信息
        //$c = new SaeTClientV2( WB_AKEY,WB_SKEY,$token['access_token']);
        //$user_message = $c->show_user_by_id($weibo_uid['uid']);
        //$weibo_uid = $c->get_uid();
    
        oauth_userband('weibo',$token['uid']);  
        //_setcookie( 'weibojs_'.$this->o->client_id, http_build_query($token));
    
    }
    
    /*配置文件处理*/
    public function postconfig(){       
        //-------读取配置文件               
        $file = fopen(__DIR__."/config.php","w+");      
        fwrite($file,"<?php ".PHP_EOL);
        fwrite($file,"define(\"WB_AKEY\" , '".$_POST['id']."' );".PHP_EOL);
        fwrite($file,"define(\"WB_SKEY\" , '".$_POST['key']."' );".PHP_EOL);
        fwrite($file,"define(\"WB_CALLBACK_URL\" , '".$_POST['callback']."' );".PHP_EOL);
        fclose($file);      
        _message("weibo save ok.");
    }
    
    
    /*获取配置数组*/
    public function getdata(){
        return array(
            "id"=>WB_AKEY,
            "key"=>WB_SKEY,
            "callback"=>WB_CALLBACK_URL,
        );
    }
}