<?php
include_once dirname(__FILE__).DIRECTORY_SEPARATOR.'ConfigUtil.php';
/* *
 * 类名：ChinaBankSubmit
 * 功能：网银+接口请求提交类
 * 详细：构造支付宝各接口表单HTML文本，获取远程HTTP数据
 */
class Jdpaysubmit{
    private $jdpay_config;
    private $jdpay_gateway_new;
    /**
     *网银在线网关地址
     */

    function __construct($jdpay_config){
        $this->jdpay_config = $jdpay_config;
        $this->jdpay_gateway_new = ConfigUtil::get_val_by_key('serverPayUrl');
        //file_put_contents('Jdpaysubmit.txt','payurl    '.$this->jdpay_gateway_new ."\n",FILE_APPEND);
    }

    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function buildRequestForm($para_temp, $method, $button_name) {
        $sHtml .= "<h3>正在跳转到京东支付....</h3>";
        $sHtml .= "<form id='jdpaysubmit' name='jdpaysubmit' action='".$this->jdpay_gateway_new."' method='".$method."'>";
        while (list ($key, $val) = each ($para_temp)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        //submit按钮控件请不要含有name属性
        //$sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";      
        $sHtml = $sHtml."<script>document.forms['jdpaysubmit'].submit();</script>";     
        return $sHtml;
    }



    
}
?>
