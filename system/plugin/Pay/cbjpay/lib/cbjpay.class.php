<?php
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "SignUtil.php";
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "ConfigUtil.php";
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "cbjpay_submit.class.php";

class cbjpay
{
    private $config;
    private $url;

    public function __construct()
    {
        $this->db = System::load_sys_class( 'model' );
    }

    // 主入口
    public function config( $config = null )
    {
        $sql = "SELECT * FROM `@#_payment` WHERE `pay_class` = '$config[pay_class]' AND `pay_start` = '1'";
        $pay_type = $this->db->GetOne( $sql );
        $config['pay_uid']     = $pay_type['pay_uid'];
        $config['pay_account'] = $pay_type['pay_account'];
        $config['pay_key']     = $pay_type['pay_key'];
        $config['pay_type']    = $pay_type['pay_type'];
        $payreturn1 = array();
        $payreturn2 = array();
        $payreturn1['pay_class'] = $pay_type['pay_class'];
        $payreturn1['pay_fun']   = "qiantai";
        $payreturn1 = base64_encode( json_encode( $payreturn1 ) );
        $payreturn2['pay_class'] = $pay_type['pay_class'];
        $payreturn2['pay_fun']   = "houtai";
        $payreturn2 = base64_encode( json_encode( $payreturn2 ) );
        $config['pay_ReturnUrl'] = G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn1;
        $config['pay_NotifyUrl'] = G_WEB_PATH.'/index.php/?plugin=true&api=Pay&action=return&data='.$payreturn2;

        $this->config = $config;
        $this->config_jsdz();
    }

    // 即时到账
    private function config_jsdz()
    {
        $param = array(
            'version'     => 'V2.0',
            'merchant'    => ConfigUtil::get_val_by_key('merchantNum'),
            'tradeNum'    => $this->config['pay_code'],
            'tradeName'   => $this->config['pay_title'],
            'tradeDesc'   => $this->config['pay_title'],
            'tradeTime'   => date('YmdHis'),
            'amount'      => ($this->config['pay_money'] * 100) . '',
            // 'amount'      => '1', // 测试1分
            'currency'    => 'CNY',
            'callbackUrl' => $this->config['pay_ReturnUrl'],
            'notifyUrl'   => $this->config['pay_NotifyUrl'],
            'ip'          => _get_ip(),
            'orderType'   => '1',
            'userId'      => _getcookie( 'uid' ),
        );

        $cbjpaySubmit = new CbjpaySubmit( $param );
        $this->url   = $cbjpaySubmit->buildRequestForm( $param, 'POST', 'submit' );
    }

    // 发送
    public function send_pay()
    {
        exit( $this->url );
    }
}