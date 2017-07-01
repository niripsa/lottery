<?php 
System::load_sys_class( "model", "sys", "no" );
/**夺宝计算方式插件Copyright_LvDeng_20150323**/

$action = isset( $_GET['action'] ) ? $_GET['action'] : false;

switch ( $action )
{
    case "setway":setway(); break;  //后台设置 
    case "optway":optway(); break;    
    case "postway":postway(); break; //前台普通购买  
    case "autoway":autoway(); break; //前台显示揭晓购买  
    case "openway":openway(); break; //前台显示揭晓购买  
 } 
 
 

//夺宝计算方式后台设置     
function setway()
{
    $db = System::load_sys_class("model");
    $ments = "设置夺宝程序开奖方式";     
    $loconfig = System::load_sys_config("lotteryway");    
    $sql="select * from `@#_config` WHERE `name`='lottery_type'";
    $default= $db->GetOne($sql);
    /**与数据库校验**/
    if($default['value']!=$loconfig['lotteryway']['opennow']){ 
     //更新配置  
        $loconfig['lotteryway']['opennow']=$default['value'];
        foreach($loconfig['lotterylist'] as $v=>$key){
        $loconfig['lotterylist'][$v]['state']=0;    
        }
        $loconfig['lotterylist'][$default['value']]['state']='1';           
        if(!is_writable(G_CONFIG.'lotteryway.inc.php')) _message('Please chmod  lotteryway.inc.php  to 0777 !');
        $html  = var_export($loconfig,true);
        $html  = "<?php \n return ".$html."; \n?>";
        $ok=file_put_contents(G_CONFIG.'lotteryway.inc.php',$html);  
        if(!$ok){
            _message("校验失败！");
        }       
    }
    /**与数据库校验**/
    include "setway.tpl.php";   
}

//ajax
function postway() { 
    
    if(isset($_GET['openway'])){
         $openway=$_GET['openway'];
         $loconfig = System::load_sys_config("lotteryway");    
         $openlottery=$loconfig['lotterylist'][$openway];        
        echo json_encode(array("lotterystate"=>$openlottery['state'],"lotterydir"=>$openlottery['dir'],"lotteryapiclass"=>$openlottery['apiclass'],"lotteryapifun"=>$openlottery['apifun'],"lotterycomment"=>$openlottery['comment']));
        return;exit;
    }else{
        echo json_encode(array("error"=>'error'));
        return;exit;                   
    }   

}
 
// 夺宝计算方式前台      
function optway()
{
    $post_arr   = isset( $_POST ) ? $_POST : false;
    $shop       = isset( $_POST['shop'] ) ? $_POST['shop'] : false;
    $lotteryway = isset( $_POST['lotteryway'] ) ? $_POST['lotteryway'] : false;
    $shop       = json_decode( base64_decode( $shop ) );
    // 夺宝计算方式配置文件
    $loconfig = System::load_sys_config("lotteryway");
    $lotterynowway = array();
    if ( $loconfig['lotterylist'][$lotteryway] ) $lotterynowway = $loconfig['lotterylist'][$lotteryway];  
    // 加载当前开奖方式的入口文件
    include dirname(__FILE__)."/".$lotterynowway['dir']."/./".$lotterynowway['apiclass'].".class.php";
    // 新建入口类
    $apiclass = new $lotterynowway['apiclass']();
    $apifun   = $lotterynowway['apifun'];
    if ( is_object( $shop ) ) {
        $shop = (array)$shop;
    }
    // 调用入口文件函数
    $apiclass->$apifun( $shop, 'add' );
}
 
// 限时揭晓 
function autoway()
{
    $db = System::load_sys_class( "model" );
    if ( ! isset( $_POST['shopid'] ) )
    {
        echo '-1';exit;
    }       
    $id = intval( $_POST['shopid'] );     
    $shop_info = $db->GetOne("SELECT * FROM `@#_cloud_goods` WHERE `id` = '$id' FOR UPDATE");
    if ( ! $shop_info ) {
        echo '-1';exit;
    }           
    if ( $shop_info['xsjx_time'] > time() ) {
        echo "-4";exit;
    }   
    if ( $shop_info['canyurenshu'] == '0' ) {
        echo '-3';exit;
    }           
    if ( ! empty( $shop_info['q_user_code'] ) && ( $shop_info['q_showtime'] == 'Y' ) ) {
        echo '-6';exit;
    }           
    if ( ! empty($shop_info['q_user_code']) && ($shop_info['q_showtime'] == 'N') ) {
        echo $shop_info['q_user_code'];exit;
    }
    // 夺宝计算方式配置文件
    $loconfig      = System::load_sys_config("lotteryway");  
    $opennow       = $loconfig['lotteryway']['opennow']; // 当前计算方式
    $lotterynowway = $loconfig['lotterylist'][$opennow];  

    include dirname(__FILE__) . "/".$lotterynowway['dir'] ."/". $lotterynowway['autoclass'].".class.php";
    // 新建入口类
    $apiclass = new $lotterynowway['autoclass']();

    // 调用入口文件函数   
    $autoinfo = $apiclass->autolottery_ret_install( $shop_info );
    echo $autoinfo;exit;
}

// 开奖计算方法设置
function openway()
{
    $data     = isset($_GET['data']) ? $_GET['data'] : false;
    $db       = System::load_sys_class("model");    
    $loconfig = System::load_sys_config("lotteryway"); 
    $loconfig['lotteryway']['opennow']=$data;
    foreach($loconfig['lotterylist'] as $v=>$key){
    $loconfig['lotterylist'][$v]['state']=0;    
    }
    $loconfig['lotterylist'][$data]['state']='1';
    if(!is_writable(G_CONFIG.'lotteryway.inc.php')) _message('Please chmod  lotteryway.inc.php  to 0777 !');
    $html = var_export($loconfig,true);
    $html = "<?php \n return ".$html."; \n?>";
    $ok   = file_put_contents( G_CONFIG . 'lotteryway.inc.php', $html );
       
    $sql = "UPDATE `@#_config` SET `value` = '$data' WHERE `name` = 'lottery_type'";
    $updatesql = $db->Query($sql);       
    if ( $ok && $updatesql )
    {
        if ( $data == 'cqssc' )
        {
            $html = "<?php return array('open'=>'','code'=>'','phase'=>''); ?>";         
            file_put_contents( G_CONFIG . 'sscdolottery.inc.php', $html );
        }
        _message( "开启成功！", WEB_PATH . "plugin=true&api=CloudWay&action=setway" );
    }
}