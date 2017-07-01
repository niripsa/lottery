<?php

/**
 *  框架插件系统管理器不能删除,
 *  复制统管全局插件的 插拔.
 *  附录： 那个叫 LAWSON 的便利店很贵的。我们不要去
 *  附录： 昨天还看到了一个叫 小野田宽郎 的日本人，很碉堡啊。
 *  <战线> booobusy@gmail.com,526541010@qq.com
 */

 
 defined("G_EXECMODE") or die ("I'm sorry, you don't have the access");

 //指派动作
 $action = isset($_GET['action']) ?  $_GET['action'] : false;

 //可操作动作，返回 JSON
 $actions = array(
    "add",          //
    "showview",     //显示主界面
    "install",      //安装插件
    "uninstall",    //卸载插件
    "listinfo",     //调用本地安装列表
    "weblistinfo",//调用公网上的插件列表
    "template",     //调用本地安装模板列表
    "webtemplate",//调用公网上的模板列表
    "oneinfo"       //获取单个插件
 );

 //判断权限
 if(!_PluginCheckAdmin(1)){
    _SendMsgJson("status",-1);
    _SendMsgJson("msg","User don't have the access",1);
 }


 //判断动作
 if(!in_array($action, $actions)){
    _SendMsgJson("status",-1);
    _SendMsgJson("msg","not action.",1);
 }

 /*
 //判断插件是否存在  卸载用
 if(!isset($package[$pname])){
    _SendMsgJson("status",-1);
    _SendMsgJson("msg","not plugin.",1);
 }
 */


 $action = ("Plugin_Manager_".$action);
 $action(); return;

 /*********************/


 //界面显示
 function Plugin_Manager_showview(){
    include "cloudapp.list.tpl.php";exit;
 }

 //安装
 function Plugin_Manager_install(){
        $package = &_PluginGetAll();
        $pname   = basename($_GET['data']);
        if(isset($package[$pname])){
           _message("插件已经存在.");
        }
        $install_path = G_PLUGIN.$pname."/install.php";
        if(file_exists($install_path)){
            include $install_path;
        }else{
            _message("插件安装文件错误.");
        }      
        return;
 }

 //卸载
 function Plugin_Manager_uninstall(){

 }

 //添加
 function Plugin_Manager_add(){
 
     $name = isset($_GET['name']) ?  $_GET['name'] : false;
     //POST 获取购买数据，
     //成功后添加进本地插件包
     //前台显示可安装插件

 }

 //获取本地全部
 function Plugin_Manager_listinfo(){

    
    _SendMsgJson("status",1);
    _SendMsgJson("msg",_PluginGetAll(),1);
 }

  //获取网络全部
 function Plugin_Manager_weblistinfo(){

    $package = &_PluginGetAll();
    $ctx = stream_context_create(array('http' => array('timeout' => 3)));
    $url = "http://www.yungoucms.com/plugin/plugin.php?action=weblistinfo";
    $i = 3;
    while($i--){
        $result = @file_get_contents($url,false,$ctx);
        if($result)break;
    }
    if($i==0){
        _SendMsgJson("status",1);
        _SendMsgJson("msg","{}",1);
    }
    echo '{"status":1,"msg":'.$result.'}';
    exit;
 }

//本地模板
function Plugin_Manager_template(){

    $templates=system::load_sys_config("view","templates");
    $path = G_WEB_PATH.'/'.G_STATICS_DIR.'/templates/';
    foreach($templates as $k=>$v){
        if(!is_dir(G_TEMPLATES.$v['dir'])){
            unset($templates[$k]);
        }else{
        clearstatcache(G_TEMPLATES.$v['dir']);
        $templates[$k]['photo'] = $path.$v['dir']."/images/".$v['dir'].".jpg";
        }
    }

    _SendMsgJson("status",1);
    _SendMsgJson("msg",$templates,1);
}

//服务器模板
function Plugin_Manager_webtemplate(){
    $ctx = stream_context_create(array('http' => array('timeout' => 3)));
    $url = "http://www.yungoucms.com/plugin/plugin.php?action=webtemplate";
    $i = 3;
    while($i--){
        $result = @file_get_contents($url,false,$ctx);
        if($result)break;
    }
    $webtemplate=json_decode($result,true);
    $temp_config=system::load_sys_config("view");
    $templates=$temp_config['templates'];
    if(empty($webtemplate)) {
        $webtemplate=array();
    }
    foreach($webtemplate as $k=>$v){
        foreach($templates as $val){
            if($val['kid']==$v['id'] and $v['status']=1){
               unset($webtemplate[$k]);
            }
        }
    }
    $webtemplates=json_encode($webtemplate);
    if($i==0){
        _SendMsgJson("status",1);
        _SendMsgJson("msg","{}",1);
    }
    echo '{"status":1,"msg":'.$webtemplates.'}';
    exit;
}





 //获取一个
 function Plugin_Manager_oneinfo(){
    $package = &_PluginGetAll();
    _SendMsgJson("status",1);
    _SendMsgJson("msg",Plugin_Manager_ifplugin($package),1);
 }


 //指派插件判断
 function Plugin_Manager_ifplugin(){
    $package = &_PluginGetAll();
    $pname  = isset($_GET['name']) ?  $_GET['name'] : false;
    if(!isset($package[$pname])){
        _SendMsgJson("status",-1);
        _SendMsgJson("msg","not plugin.",1);
    }
    return $package[$pname];
 }
