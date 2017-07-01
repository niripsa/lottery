<?php
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'ConfigUtil.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'SignUtil.php';
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'TDESUtil.php';

/* *
 * 类名：Jdpaysubmit
 * 功能：京东支付接口请求提交类
 * 详细：构造京东支付HTML表单，提交至远程接口
 */
class Jdpaysubmit
{
    private $jdpay_config;
    private $jdpay_gateway_new;

    /**
     *网银在线网关地址
     */
    public function __construct( $jdpay_config )
    {
        $this->jdpay_config      = $jdpay_config;
        $this->jdpay_gateway_new = ConfigUtil::get_val_by_key( 'serverPayUrl' );
    }

    /**
     * 对各项参数执行加密
     */
    public function execute( $param )
    {
        $unSignKeyList = array("sign");
        $desKey = ConfigUtil::get_val_by_key("desKey");
        $sign   = SignUtil::signWithoutToHex($param, $unSignKeyList);
        $param["sign"] = $sign;
        $keys = base64_decode($desKey);
        
        if ( $param["device"] != null && $param["device"] != "" )
        {
            $param["device"] = TDESUtil::encrypt2HexStr( $keys, $param["device"] );
        }
        $param["tradeNum"] = TDESUtil::encrypt2HexStr( $keys, $param["tradeNum"] );
        if ( $param["tradeName"] != null && $param["tradeName"] != "" )
        {
            $param["tradeName"] = TDESUtil::encrypt2HexStr( $keys, $param["tradeName"] );
        }
        if ( $param["tradeDesc"] != null && $param["tradeDesc"] != "" )
        {
            $param["tradeDesc"] = TDESUtil::encrypt2HexStr( $keys, $param["tradeDesc"] );
        }
        
        $param["tradeTime"]   = TDESUtil::encrypt2HexStr( $keys, $param["tradeTime"] );
        $param["amount"]      = TDESUtil::encrypt2HexStr( $keys, $param["amount"] );
        $param["currency"]    = TDESUtil::encrypt2HexStr( $keys, $param["currency"] );
        $param["callbackUrl"] = TDESUtil::encrypt2HexStr( $keys, $param["callbackUrl"] );
        $param["notifyUrl"]   = TDESUtil::encrypt2HexStr( $keys, $param["notifyUrl"] );
        $param["ip"]          = TDESUtil::encrypt2HexStr( $keys, $param["ip"] );
        
        if ( $param["note"] != null && $param["note"] != "" )
        {
            $param["note"] = TDESUtil::encrypt2HexStr( $keys, $param["note"] );
        }
        if ( $param["userType"] != null && $param["userType"] != "" )
        {
            $param["userType"] = TDESUtil::encrypt2HexStr( $keys, $param["userType"] );
        }
        if ( $param["userId"] != null && $param["userId"] != "" )
        {
            $param["userId"] = TDESUtil::encrypt2HexStr( $keys, $param["userId"] );
        }
        if ( $param["expireTime"] != null && $param["expireTime"] != "" )
        {
            $param["expireTime"] = TDESUtil::encrypt2HexStr( $keys, $param["expireTime"] );
        }
        if ( $param["orderType"] != null && $param["orderType"] != "" )
        {
            $param["orderType"] = TDESUtil::encrypt2HexStr( $keys, $param["orderType"] );
        }
        if ( $param["industryCategoryCode"] != null && $param["industryCategoryCode"] != "" )
        {
            $param["industryCategoryCode"] = TDESUtil::encrypt2HexStr( $keys, $param["industryCategoryCode"] );
        }
        if ( $param["specCardNo"] != null && $param["specCardNo"] != "" )
        {
            $param["specCardNo"] = TDESUtil::encrypt2HexStr( $keys, $param["specCardNo"] );
        }
        if ( $param["specId"] != null && $param["specId"] != "" )
        {
            $param["specId"] = TDESUtil::encrypt2HexStr( $keys, $param["specId"] );
        }
        if ( $param["specName"] != null && $param["specName"] != "" )
        {
            $param["specName"] = TDESUtil::encrypt2HexStr( $keys, $param["specName"] );
        }
        return $param;
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    public function buildRequestForm( $para_temp, $method, $button_name )
    {
        /* 执行加密 */
        $para_temp = $this->execute( $para_temp );
        $sHtml .= "<h3>正在跳转到京东支付....</h3>";
        $sHtml .= "<form id='jdpaysubmit' name='jdpaysubmit' action='".$this->jdpay_gateway_new."' method='".$method."'>";
        while( list( $key, $val ) = each( $para_temp ) )
        {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/><br />";
        }
        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";      
        $sHtml = $sHtml."<script>document.forms['jdpaysubmit'].submit();</script>";
        return $sHtml;
    }
}