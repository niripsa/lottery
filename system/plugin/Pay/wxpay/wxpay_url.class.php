<?php 
defined( 'G_IN_SYSTEM' ) or exit( 'No permission resources.' );
ini_set( 'display_errors', 'OFF' );
class wxpay_url extends SystemAction
{
    public function __construct()
    {          
        $this->db = System::load_sys_class( 'model' );
    }

    public function checkpay()
    {
        $userpaydb     = System::load_app_model("UserPay","common");
        $out_trade_no  = $_POST["out_trade_no"];
        $dingdaninfo   = $userpaydb->get_order($out_trade_no,'n');
        $recorddingdan = $userpaydb->get_money_record($out_trade_no);
        $pay['status'] = -1;
        if ( $dingdaninfo )
        {
            $pay['status'] = $dingdaninfo['ostatus'];
        }
        if ( $recorddingdan )
        {
            $pay['status'] = $recorddingdan['status'];
            if ( $pay['status'] == 2 )
            {          
                $sql = "DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')";
                $recorddel = $this->db->Query( $sql );
            }           
        }
        
        if ( $pay['status'] == 2 )
        {  
            _setcookie( "Cartlist", null );
            echo json_encode( array('code'=>4,'msg'=>"支付成功!") );exit;
        }
        else if ( $pay['status'] == 1 )
        {
            echo json_encode( array('code'=>999,'msg'=>"等待!") );exit;
        }
        else
        {
            echo json_encode( array('code'=>0,'msg'=>"支付失败!") );exit;
        }
    }

    public function houtai()
    {
        $this->db = System::load_sys_class('model');              
        $sql = "SELECT * FROM `@#_payment` WHERE `pay_class` = 'wxpay' AND `pay_start` = '1'";
        $pay_type = $this->db->GetOne( $sql );
        include_once dirname(__FILE__).DIRECTORY_SEPARATOR."lib/WxPayPubHelper.php"; // 引入文件需求
        $notify = new Notify_pub();

        WxPayConf_pub::$APPID = $pay_type['pay_uid'];
        WxPayConf_pub::$MCHID = $pay_type['pay_account'];
        WxPayConf_pub::$KEY   = $pay_type['pay_key'];
        //存储微信的回调
        $xml = $GLOBALS['HTTP_RAW_POST_DATA'];
        $notify->saveData($xml);
        $notify->unarr();
        $arr          = $notify->getData();
        $out_trade_no = $arr['out_trade_no'];
        $total_fee_t  = $arr['total_fee'] / 100;
        
        //验证签名，并回应微信。
        //对后台通知交互时，如果微信收到商户的应答不是成功或超时，微信认为通知失败，
        //微信会通过一定的策略（如30分钟共8次）定期重新发起通知，
        //尽可能提高通知的成功率，但微信不保证通知最终能成功。
        if ( $notify->checkSign() == FALSE )
        {
            $notify->setReturnParameter("return_code","FAIL");//返回状态码
            $notify->setReturnParameter("return_msg","签名失败");//返回信息
        }
        else
        {
            $notify->setReturnParameter("return_code","SUCCESS");//设置返回码
        }
        if ( is_array( $arr ) )
        {
            $returnXml = $notify->returnXml();
            echo $returnXml;
        }

        if ( $notify->checkSign() == TRUE )
        {
            if ( $notify->data["return_code"] == "FAIL" )
            {
                //此处应该更新一下订单状态，商户自行增删操作
               // $log_->log_result($log_name,"【通信出错】:\n".$xml."\n");
            }
            else if ( $notify->data["result_code"] == "FAIL" )
            {
                //此处应该更新一下订单状态，商户自行增删操作
                //$log_->log_result($log_name,"【业务出错】:\n".$xml."\n");
            }
            else
            {
                $pay = System::load_app_class('UserPay', 'common');             
                $pay->pay_success_order($out_trade_no,$pay_type['pay_id'],"微信支付");
                //此处应该更新一下订单状态，商户自行增删操作
                //$log_->log_result($log_name,"【支付成功】:\n".$xml."\n");
            }

            //商户自行增加处理流程,
            //例如：更新订单状态
            //例如：数据库操作
            //例如：推送支付完成信息
            exit;
        }
        if ( ! isset( $_POST["out_trade_no"] ) )
        {
            $out_trade_no = " ";
        }
        else
        {
            $out_trade_no = $_POST["out_trade_no"];
            //使用订单查询接口
            $orderQuery = new OrderQuery_pub();
            //设置必填参数
            //appid已填,商户无需重复填写
            //mch_id已填,商户无需重复填写
            //noncestr已填,商户无需重复填写
            //sign已填,商户无需重复填写
            $orderQuery->setParameter("out_trade_no","$out_trade_no");//商户订单号
            $time = time();

            //获取订单查询结果
            $orderQueryResult = $orderQuery->getResult();
            //商户根据实际情况设置相应的处理流程,此处仅作举例
            if ($orderQueryResult["return_code"] == "FAIL") {
                $wxstatus = array("status"=>"fail","code"=>"","msg"=>"通信出错：".$orderQueryResult['return_msg']);
                echo $wxstatus=json_encode($wxstatus); exit;
            }
            elseif($orderQueryResult["result_code"] == "FAIL"){
                $wxstatus = array("status"=>"fail","code"=>"","msg"=>"错误代码：".$orderQueryResult['err_code_des']);
                echo $wxstatus=json_encode($wxstatus); exit;
            }
            else
            {
                if ( $orderQueryResult["result_code"]=='SUCCESS' && $orderQueryResult["trade_state"]=='SUCCESS' )
                {
                    $pay = System::load_app_class('UserPay', 'common');
                    $pay->pay_success_order($orderQueryResult['out_trade_no'],$pay_type['pay_id'],"微信支付");
                }
                else
                {
                    $wxstatus = array("status"=>"fail","code"=>"","msg"=>"fail6");
                    echo $wxstatus=json_encode($wxstatus); exit;
                }
            }
        }
    }
}