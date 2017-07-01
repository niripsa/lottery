<?php
/* *
 * 功能：支付宝服务器异步通知页面
 * 版本：3.3
 * 日期：2012-07-23
 * 说明：
 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。


 *************************页面功能说明*************************
 * 创建该页面文件时，请留心该页面文件中无任何HTML代码及空格。
 * 该页面不能在本机电脑测试，请到服务器上做测试。请确保外部可以访问该页面。
 * 该页面调试工具请使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyNotify
 * 如果没有收到该页面返回的 success 信息，支付宝会在24小时内按一定的时间策略重发通知
 */

 
//记录回传参数
/*
if(isset($_POST['trade_no'])){
    file_put_contents("alipay_callback_data", var_export($_POST,true),FILE_APPEND);
}
*/
 
require("alipay.config.php");
require("lib/alipay_notify.class.php");

//计算得出通知验证结果
$alipayNotify = new AlipayNotify($alipay_config);
$verify_result = $alipayNotify->verifyNotify();

//验证失败
if ( ! $verify_result )
{
    exit("fail");
}

//支付失败
if ( $_POST['trade_status'] != 'TRADE_FINISHED' && $_POST['trade_status'] != 'TRADE_SUCCESS' )
{
    exit("fail");
}

    
//订单号
$out_trade_no = $_POST['out_trade_no'];

//支付宝交易号
$trade_no     = $_POST['trade_no'];

//交易状态
$trade_status = $_POST['trade_status'];

//支付金额
$total_fee    =  $_POST['total_fee'];

/**********   处理网站的订单        *************/
    
$OrderDB = System::load_app_model( "orders", "orders" );
$ret     = $OrderDB->SetOrderPaySuccess( 'alipay', $out_trade_no, $_POST ); 
if ( $ret )
{
    echo "success";
}
else
{
    echo "fail";
}
    
/*********   处理网站的订单结束     *********/