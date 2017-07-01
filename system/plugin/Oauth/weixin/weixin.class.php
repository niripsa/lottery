<?php 

/**
 * 微信登陆API接口开发
 *2015-08-31-lvdeng
 **/

include "config.php";


class oauth_weixin  {

        

    /*退出微博登陆*/
    public function out(){

    }
    
    /*取得登陆接口地址*/
    public function login(){
        
        _session_destroy();
        $web_url=G_WEB_PATH."/?plugin=1&api=Oauth&action=callback&data=weixin"; 
        $redirect_uri=urlencode($web_url);
        $code_url="https://open.weixin.qq.com/connect/qrconnect?appid=".WX_APPID."&redirect_uri=".$redirect_uri."&response_type=code&scope=snsapi_login&state=".WX_APPSECRET."#wechat_redirect";

        exit(header("location:".$code_url));
    }
    
    /*回调的处理*/
    public function callback(){
        
        if(isset($_GET['code'])){
            $code=$_GET['code'];
            $state=$_GET['state'];      
            $return_url="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".WX_APPID."&secret=".WX_APPSECRET."&code=".$code."&grant_type=authorization_code";
            $getdata=file_get_contents($return_url);
            $url_content = (Array)json_decode(trim($getdata));  
            // var_dump($url_content);exit;
            $go_url="https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".WX_APPID."&grant_type=refresh_token&refresh_token=".$url_content['refresh_token'];
            $godata=file_get_contents($go_url);
            $godata=(Array)json_decode(trim($godata));
            $userdb=System::load_app_model("user","common");
            if(!$godata['openid']){
                exit("微信绑定出错！");         
            }else{
                $uid=$this->getuserband('weixin',$godata['openid']);            
                oauth_userband('weixin',$godata['openid']);     
                $info=$userdb->BondWeixin($godata['openid'],$uid);                  
            }           
        }
    
    }
    public function getuserband($type='',$openid=''){   
        $userdb=System::load_app_model("user","common");
        $where="`b_type`='$type' and `b_code`='$openid'";
        $usri=$userdb->UserBand($where,'b_uid');
        return $uid=$usri['b_uid'];
    }   
    /*配置文件处理*/
    public function postconfig(){       
        //-------读取配置文件   
        $file = fopen(__DIR__."/config.php","w+");      
        fwrite($file,"<?php ".PHP_EOL);
        fwrite($file,"define(\"WX_APPID\" , '".$_POST['id']."' );".PHP_EOL);
        fwrite($file,"define(\"WX_APPSECRET\" , '".$_POST['key']."' );".PHP_EOL);
        fwrite($file,"define(\"WX_CALLBACK_URL\" , '".$_POST['callback']."' );".PHP_EOL);
        fclose($file);      
        _message("微信配置 ok.");
    }
    
    
    /*获取配置数组*/
    public function getdata(){
        return array(
            "id"=>WX_APPID,
            "key"=>WX_APPSECRET,
            "callback"=>WX_CALLBACK_URL,
        );
    }
}