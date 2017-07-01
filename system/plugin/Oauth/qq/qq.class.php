<?php 

/**
 * 登录QQ登录 API接口开发
 * 2015-01-03
 * <战线>@booobusy@gmail.com
 * qqconnect版本 : 2.1
 * 版本    : 1.0
 *
 **/
 
 /*
    =注意：
    获得access_token，在callback页面中使用$qc->qq_callback()返回access_token,
    $qc->get_openid()返回openid，之后可以将access_token和openid保存（三个月有效期），
    之后调用接口时不需要重新授权，但需要将access_token和Openid传入QC的参数中，如下：
    $qc = new QC($access_token, $openid)
*/
 
 _session_start();
 include "API/qqConnectAPI.php";
  
 class oauth_qq {
    
    private $qc;
    
    public function __construct(){
        $this->qc = new QC();
    }
        
 
    /*取得登陆接口地址*/
    public function login(){
        //_session_destroy();
        $this->qc = new QC();
        $this->qc->qq_login();      
    }
    
    /*回调的处理*/
    public function callback(){
            
    
        $qq_asc = $this->qc->qq_callback();
        $qq_id  = $this->qc->get_openid();
        
        //$this->qc = new QC($qq_asc,$qq_id);
        if(!$qq_id || !$qq_asc || !$this->qc){          
            exit("qq authorize error!");
        }
    
        //开始判断是否需要绑定
        oauth_userband('qq',$qq_id);
    }
    
    /*配置文件处理*/
    public function postconfig(){       
        //-------读取配置文件
        $incFileContents = file(ROOT."comm/inc.php");       
        $incFileContents = $incFileContents[1];
        $inc = json_decode($incFileContents);
                
        $inc->appid     = $_POST['id'];
        $inc->appkey    = $_POST['key'];
        $inc->callback  = $_POST['callback'];
        

        $file = fopen(ROOT."comm/inc.php","w");
        fwrite($file,"<?php die('forbidden'); ?>".PHP_EOL);
        fwrite($file,json_encode($inc));
        fclose($file);      
        _message("ok");
        
    }
    
    /*获取配置数组*/
    public function getdata(){
        $incFileContents = file(ROOT."comm/inc.php");       
        $incFileContents = $incFileContents[1];
        $inc = json_decode($incFileContents);   
        return array(
            "id"=>$inc->appid,
            "key"=>$inc->appkey,
            "callback"=>$inc->callback,
        );  
    }

 }
