<?php
/* *
 * 类名：HcpaySubmit
 * 功能：汇潮支付请求提交类
 * 详细：构造支付宝各接口表单HTML文本，获取远程HTTP数据
 */
class HcpaySubmit {

    var $hcpay_config;
    /**
     *支付宝网关地址（新）
     */
    var $hcpay_gateway_new = 'https://pay.ecpss.com/sslpayment';

    function __construct($hcpay_config){
        $this->hcpay_config = $hcpay_config;
    }
    function HcpaySubmit($hcpay_config) {
        $this->__construct($hcpay_config);
    }
    
    
    /**
     * 建立请求，以表单HTML形式构造（默认）
     * @param $para_temp 请求参数数组
     * @param $method 提交方式。两个值可选：post、get
     * @param $button_name 确认按钮显示文字
     * @return 提交表单HTML文本
     */
    function buildRequestForm($para_temp, $method, $button_name) {
        //待请求参数数组
        $sHtml = "<h3>正在跳转到汇潮支付....</h3>";
        $sHtml .= "<form   name='E_FORM' action='".$this->hcpay_gateway_new."' method='".$method."'>";  
        while (list ($key, $val) = each ($para_temp)) {
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }
        //submit按钮控件请不要含有name属性
       // $sHtml = $sHtml."<input type='submit' value='".$button_name."'></form>";      
        $sHtml = $sHtml."<script>document.forms['E_FORM'].submit();</script>";      
        return $sHtml;
    }
}
?>