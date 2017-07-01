<?php


//插件操作
$action       = isset($_GET['action']) ? basename($_GET['action']) : _SendStatus(404);   //选择的 action
$action       = "Plugin_Fund_".$action;



function Plugin_Fund_show(){
    seo("title",_cfg("web_name")."_云基金");
    seo("keywords",_cfg("web_name")."_云基金");
    seo("description",_cfg("web_name")."_云基金");    
    include "fund_plugin.model.php";
    $fundobj =  new fund_plugin_model();
    $F = $fundobj->get_fund_data();
    if($_GET['ajax']){
        _SendMsgJson("status",1);
        _SendMsgJson("data",json_encode($F),1);
    }
    include "tpl/show.tpl.php";
}


function Plugin_Fund_ajax(){
    include "fund_plugin.model.php";
    $fundobj =  new fund_plugin_model();
    $F = $fundobj->get_fund_data();
    echo $F['fund_cmoney'];
}




function Plugin_Fund_config(){
    _PluginCheckAdmin();
    include "fund_plugin.model.php";
    $fundobj =  new fund_plugin_model();
    $F = $fundobj->get_fund_data();

    include "tpl/config.tpl.php";
}


function Plugin_Fund_postconfig(){

  _PluginCheckAdmin();
  include "fund_plugin.model.php";
  $fundobj =  new fund_plugin_model();
  $fundobj->set_fund_data($_POST);

  $off = isset($_POST['fund_off']) ? intval($_POST['fund_off']) : 1;
  $off = ($off != 1 && $off !=2) ? 1 : $off;

  _PluginUpdatePackage('Fund',array("Status"=>$off));

  _message("ok!");

}



if(!function_exists($action)){
    _SendStatus(404);
}

$action();
