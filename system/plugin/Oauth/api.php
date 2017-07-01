<?php

/**
 *  当前文件可自定义内容
 *  @author 战线 <booobusy@gmail.com>
 *  time 2014-12-24
 *  版本    : 1.0
 */
 
 
 
 
defined("G_EXECMODE") or die ("I'm sorry, you don't have the access");


/*
 *      
class Oauth_plugin extends SystemAction {
    
    function __construct(){
                
    }   
} 


$oauth = new Oauth_plugin();
if ($oauth instanceof SystemAction) {
     echo 'Yes, $oauth is a SystemAction';
}
        参见 get_class()，get_parent_class() 和 is_subclass_of()。 
 * 
 * */

 
 require "user_plugin.model.php";
 
 

/**
 *  判断用户是否绑定,如果登录了就绑定当前用户
 *  @code 用户绑定的唯一标识
 *  @outh 用户绑定类型
 */
 function oauth_userband($outh,$code=''){

    $obj = user_plugin_model::GetObject();
    $info = $obj -> get_outh_user($outh,$code); 
    $userdb = System::load_app_class("UserCheck",'common');
    
    if($info){
        //已绑定
        //指定用户登录  
        $userdb->UserLoginUID($info['b_uid']);
        $url = G_WEB_PATH;
        $str = "<script>
            if(window.opener){                  
                    window.opener.location.href = '{$url}';
                    window.close();
            }else{                  
                    window.location.href = '{$url}';
            }       
        </script>"; 
        echo $str;
    }else{  
        //未绑定
        if($userdb->UserInfo){
            //插入用户 oauth 表
            $ret = $obj -> set_outh_user($userdb->UserInfo['uid'],$outh,$code);
            $url = G_WEB_PATH;
        }else{      
            $url = G_WEB_PATH."/?plugin=true&api=Oauth&action=show&token="._encrypt($code."__".$outh,"ENCODE","",1800); 
        }
        exit(header("location:".$url));
    }
 }
  
 //绑定界面显示
 function oauth_view_show(){
    
    $token = isset($_GET['token']) ? $_GET['token'] : '';
    $bandurl = G_WEB_PATH."/?plugin=true&api=Oauth&action=band&token=".$token;
    include "TPL/login.tpl.php";
 }
 
 //开始绑定账户 AJAX
 function oauth_view_band(){
    
    $token = isset($_GET['token']) ? _encrypt($_GET['token'],'DECODE') : '';

    if(!$token){
        _SendMsgJson("status",-1);
        _SendMsgJson("msg",'token验证不通过',1);
    }
    
    $token = explode("__", $token);
    if(!isset($token[1])){
        _SendMsgJson("status",-1);
        _SendMsgJson("msg",'token验证不通过',1);
    }
    
    $code = $token[0];
    $outh = $token[1];  
    
    $obj = user_plugin_model::GetObject();  
    
    //是否注册
    if($_POST['type'] == "register"){
        
            //注册用户
            $userCheck = System::load_app_class("UserCheck",'common');  
            $return = $userCheck->UserRegister($_POST['user'],$_POST['pass']);                  
            
            //插入用户 oauth 表
            $obj -> set_outh_user($return['uid'],$outh,$code);
        
            //指定用户登录
            $userCheck->UserLoginUID($return['uid']);
    
            _SendMsgJson("msg",$return['msg']);
            _SendMsgJson("status",1,1);         
    }
    
    //是否登录并绑定（LvDeng 如果并没有已经注册的账户注册）
    if($_POST['type'] == "login"){
                    
        //判断用户
        $userCheck = System::load_app_class("UserCheck",'common');  
        $return = $userCheck->UserLogin($_POST['user'],$_POST['pass']); 
        if(!$return['status']){
            _SendMsgJson("msg","绑定失败");
            _SendMsgJson("status",-1,1);            
        }

        //指定用户登录
        $checkuser=$userCheck->UserLoginUID($return['uid']);
        //插入用户 oauth 表 
        $obj -> set_outh_user($return['uid'],$outh,$code);
        
        _SendMsgJson("msg","绑定成功");
        _SendMsgJson("status",1,1);
        
    
    }
 }


 function oauth_view_list(){ 
    $obj = user_plugin_model::GetObject(); 
    $userdb = System::load_app_class("UserCheck",'common'); 
    if($userdb->UserInfo['uid']){
        $userband=$obj ->get_outh_userband($userdb->UserInfo['uid']);   
    }
    include "tpl/list.tpl.php";     
 }
 
 
 function oauth_view_config(){
     _PluginCheckAdmin();   
    $dir = include "config.php";
    $config = array();
    foreach($dir as $v){
        include dirname(__FILE__)."/".$v."/./".$v.".class.php";
        $class = "oauth_".$v;
        $outh = new $class();
        $config[$v] = $outh->getdata();
    }
            
    include "tpl/config.tpl.php";
 }


 
//插件操作
$action       = isset($_GET['action']) ? basename($_GET['action']) : _SendStatus(404);   //选择的 action

//需要指定某一类Oauth的控制器
$actions = array("login","callback","postconfig");
if(in_array($action, $actions)){
    
    if($action == "postconfig"){
         _PluginCheckAdmin();
    }   
    $outh = isset($_GET['data']) ? basename($_GET['data']) : _SendStatus(404);
    //分发控制器
    if(!is_dir(dirname(__FILE__)."/".$outh)){_SendStatus(404);}
    include dirname(__FILE__)."/".$outh."/./".$outh.".class.php";
    $class = "oauth_".$outh;
    $outh = new $class(); /*实例化选择的 outh api*/

    if(method_exists($outh,$action)){       
        call_user_func(array($outh,$action));   
    } else {        
        _SendStatus(404);
    }
    return;
}

//开始绑定 界面操作 
$bandaction = 'oauth_view_'.$action;
if(function_exists($bandaction)){
    $bandaction();
}else{
    _SendStatus(404);
}