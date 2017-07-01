<?php

/*
 *  系统本地账户支付类
 *  <战线> booobusy@gmail.com
 *  2015年1月14日22:23:56
 */

$UserCheckDB = System::load_app_class("UserCheck","user");
$orderDB     = System::load_app_model("orders","orders");
$SyAction    = System::load_sys_class("SystemAction");

//监测的登录
$UserCheckDB -> GetUserCheckToBool() or exit("未登录!");

//获取订单
$oid = $_POST['oid'] ? intval($_POST['oid']) : $SyAction->SendStatus(404);
$Order   = $orderDB -> GetOrderOneInfo($oid) or $SyAction->SendStatus(404);

//监测余额
$UserCheckDB->UnserInfo['u_money'] < $Order['omoney'] ? exit("账户余额不够") : true;


//开始支付 余额支付
$ret = $orderDB->SetOrderAccountSuccess($UserCheckDB->UnserInfo,$Order);

if($ret){
    $url = WEB_PATH."/order-success-show.html";
}else{
    $url = WEB_PATH."/order-failure-show.html";
}
?>
<meta http-equiv="Refresh" content="0; url=<?php echo $url; ?>" /> 
<script>
    window.location.href = "<?php echo $url; ?>"
</script>
