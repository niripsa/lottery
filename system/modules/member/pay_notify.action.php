<?php
/*支付回调信息
{"bank_type":"DEBIT_CARD","buyer_logon_id":"176****9800","buyer_user_id":"2088422715397228","charset":"UTF-8","fee_type":"CNY","fund_bill_list":"[{\"amount\":\"0.01\",\"fundChannel\":\"BANKCARD\",\"fundType\":\"DEBIT_CARD\"}]","gmt_create":"20170606103735","mch_id":"102511496019","nonce_str":"1496716660249","openid":"2088422715397228","out_trade_no":"C14967166505244449","out_transaction_id":"2017060621001004220271905060","pay_result":"0","result_code":"0","sign":"7B8BE45D135337502E6AED45A6D274A1","sign_type":"MD5","status":"0","time_end":"20170606103739","total_fee":"1","trade_type":"pay.alipay.native","transaction_id":"102511496019201706066141485667","version":"2.0"}
*/
class pay_notify extends SystemAction{
    var $pay_class;

    public function __construct(){
        include G_PLUGIN . "Pay/unipay/request.php";
        $this->pay_class = new Request();
    }

    public function alipay_notify(){
        $aCallBackInfo = $this->pay_class->callback();
        if(empty($aCallBackInfo) || !empty($aCallBackInfo['result_code'])){
            exit("failure!");
        }

        $userpaydb = System::load_app_class('UserPay', 'common'); 
        $sOrderId = strval($aCallBackInfo['out_trade_no']);
        $userpaydb->callback_info = $aCallBackInfo;
        $bIsSuccess = $userpaydb->pay_success_recharge_order($sOrderId);
        
        if($bIsSuccess){
            exit('success');
        }

        file_put_contents("/home/dev/pay_notify_" . date('Ymd') . ".log", json_encode($aCallBackInfo).PHP_EOL, FILE_APPEND);
        exit('failure');
    }

    public function wxpay_notify(){
        $aCallBackInfo = $this->pay_class->callback();
        if(empty($aCallBackInfo) || !empty($aCallBackInfo['result_code'])){
            exit("failure!");
        }

        $userpaydb = System::load_app_class('UserPay', 'common'); 
        $sOrderId = strval($aCallBackInfo['out_trade_no']);
        $userpaydb->callback_info = $aCallBackInfo;
        $bIsSuccess = $userpaydb->pay_success_recharge_order($sOrderId);
        
        if($bIsSuccess){
            exit('success');
        }

        file_put_contents("/home/dev/pay_notify_" . date('Ymd') . ".log", json_encode($aCallBackInfo).PHP_EOL, FILE_APPEND);
        exit('failure');
    }
}


