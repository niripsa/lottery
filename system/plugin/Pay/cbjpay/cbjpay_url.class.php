<?php 
defined('G_IN_SYSTEM') or exit('No permission resources.');
ini_set( "display_errors", "OFF" );
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "lib/DesUtils.php";
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "lib/ConfigUtil.php";
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "lib/TDESUtil.php";
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "lib/SignUtil.php";
include dirname(__FILE__) . DIRECTORY_SEPARATOR . "lib/XMLUtil.php";

class cbjpay_url
{
    public function __construct()
    {
        $this->db = System::load_sys_class( 'model' );
    }
    
    /* 前台同步返回 */
    public function qiantai( $status = 'fail' )
    {
        sleep( 1 );
        $param = $this->callback_execute();
        if ( isset( $param['tradeNum'] ) )
        {
            $out_trade_no = $param['tradeNum'];
            $sql = "SELECT * FROM `@#_orders` WHERE `ocode` = '$out_trade_no' FOR UPDATE";
            $dingdaninfo = $this->db->GetOne( $sql );
            if ( $dingdaninfo )
            {
                if ( $dingdaninfo['ostatus'] == 2 )
                {
                    _message( "支付成功！", WEB_PATH . '/member/home/userindex' );
                }
                else
                {
                    _message( "支付失败！", WEB_PATH . '/member/home/userindex' );
                }
            }
            else
            {
                $sql = "SELECT * FROM `@#_cloud_order` WHERE `ocode` = '$out_trade_no' AND `ostatus` = '2' FOR UPDATE";
                $record = $this->db->GetOne( $sql );
                if ( $record )
                {
                    _message( "支付成功！", WEB_PATH . '/member/shop/userbuylist' ); 
                }
                else
                {
                    _message( "支付失败！", WEB_PATH . '/member/home/userindex' );
                }
            }
        }
        else
        {
            _message( "H5支付失败,没有这个订单！", WEB_PATH );
        }
    }

    /**
     * 同步验证签名
     */
    public function callback_execute()
    {
        $desKey = ConfigUtil::get_val_by_key( "desKey" );
        $keys   = base64_decode( $desKey );
        $param;
        if ( $_POST["tradeNum"] != null && $_POST["tradeNum"] != "" )
        {
            $param["tradeNum"] = TDESUtil::decrypt4HexStr( $keys, $_POST["tradeNum"] );
        }
        if ( $_POST["amount"] != null && $_POST["amount"] != "" )
        {
            $param["amount"] = TDESUtil::decrypt4HexStr( $keys, $_POST["amount"] );
        }
        if ( $_POST["currency"] != null && $_POST["currency"] != "" )
        {
            $param["currency"] = TDESUtil::decrypt4HexStr( $keys, $_POST["currency"] );
        }
        if ( $_POST["tradeTime"] != null && $_POST["tradeTime"] != "" )
        {
            $param["tradeTime"] = TDESUtil::decrypt4HexStr( $keys, $_POST["tradeTime"] );
        }
        if ( $_POST["note"] != null && $_POST["note"] != "" )
        {
            $param["note"] = TDESUtil::decrypt4HexStr( $keys, $_POST["note"] );
        }
        if ( $_POST["status"] != null && $_POST["status"] != "" )
        {
            $param["status"] = TDESUtil::decrypt4HexStr( $keys, $_POST["status"] );
        }
        
        $sign = $_POST["sign"];
        $strSourceData = SignUtil::signString( $param, array() );
        $decryptStr = RSAUtils::decryptByPublicKey( $sign );
        $sha256SourceSignString = hash ( "sha256", $strSourceData );
        if ( $decryptStr != $sha256SourceSignString )
        {
            dump( 'H5京东支付同步验证签名失败！' );
        }
        else
        {
            return $param;
        }
    }
    
    /**
     * 后台异步通知
     */
    public function houtai()
    {
        /* 异步验证 */
        $resdata = $this->notify_execute();
        $sql = "SELECT * FROM `@#_payment` WHERE `pay_class` = 'jdpay' AND `pay_start` = '1'";
        $pay_type = $this->db->GetOne( $sql );
  
        if ( $resdata['result']['desc'] == 'success' )
        {
            // 数据库操作
            $out_trade_no = $resdata['tradeNum'];
            $this->db->sql_begin();
            // 查询充值订单
            $sql = "SELECT * FROM `@#_orders` WHERE `ocode` = '$out_trade_no' AND `ostatus` = '1' FOR UPDATE";
            $dingdaninfo = $this->db->GetOne( $sql );
            $time = time();
            if ( ! $dingdaninfo )
            {
                $sql = "SELECT * FROM `@#_user_money_record` WHERE `code` = '$out_trade_no' AND `status` = '1' FOR UPDATE";
                $recorddingdan = $this->db->GetOne( $sql );
            }
            if ( $dingdaninfo || $recorddingdan )
            {
                $c_money = intval( $dingdaninfo['omoney'] );
                $uid     = $dingdaninfo['ouid'];
                if ( $dingdaninfo )
                {
                    $sql = "UPDATE `@#_orders` SET `opay` = '京东H5支付', `ostatus` = '2' where `oid` = '$dingdaninfo[oid]' and `ocode` = '$dingdaninfo[ocode]'";
                    $up_q1 = $this->db->Query( $sql );
                    $sql = "UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')";
                    $up_q2 = $this->db->Query( $sql );
                    $sql = "INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '京东H5支付充值', '$c_money', '$time')";
                    $up_q3 = $this->db->Query( $sql );
                    if ( $up_q1 && $up_q2 && $up_q3 )
                    {
                        $this->db->sql_commit();
                        $return_tag = "success";
                        /* 开始分佣 */
                        distribute_money( $uid, $c_money, 'cbjpay' );
                    }
                    else
                    {
                        $return_tag = "fail";
                    }
                }
                else
                {
                    if ( $recorddingdan )
                    {
                        $c_money = intval($recorddingdan['money']);
                        $uid = $recorddingdan['uid'];
                        $sql = "UPDATE `@#_user` SET `money` = `money` + $c_money where (`uid` = '$uid')";
                        $up_q2 = $this->db->Query( $sql );
                        $sql = "INSERT INTO `@#_user_account` (`uid`, `type`, `pay`, `content`, `money`, `time`) VALUES ('$uid', '1', '账户', '京东H5支付充值', '$c_money', '$time')";
                        $up_q3 = $this->db->Query( $sql );
                        $pay = System::load_app_class( 'UserPay', 'common' );
                        $scookies = unserialize( $recorddingdan['scookies'] );
                        $pay->scookie = $scookies;
                        $ok = $pay->init( $uid, $pay_type['pay_id'], 'go_record' ); // 云购商品  
                        if ( $ok != 'ok' )
                        {
                            $return_tag = "fail";
                        }
                        $check = $pay->go_pay( 1 );
                        if ( $check && $up_q2 && $up_q3 )
                        {
                            $this->db->sql_commit();
                            $sql = "DELETE FROM `@#_user_money_record` WHERE (`id`='{$recorddingdan[id]}')";
                            $recorddel = $this->db->Query( $sql );  
                            if ( $recorddel )
                            {
                                _setcookie( "Cartlist", null );
                                $return_tag = "success";
                            }
                            $return_tag = "fail";
                        }
                        else
                        {
                            $return_tag = "fail";
                        }
                    }
                    else
                    {
                        $return_tag = "fail";
                    }
                }
            }
            else
            {
                $return_tag = "fail";
            } 
        }
        else
        {
            $return_tag = "fail";
        }
        /* 把结果告诉第三方支付平台 */
        echo $return_tag;
    }

    /**
     * 异步验证签名
     */
    public function notify_execute()
    {
        $xml = file_get_contents("php://input");
        $resdata;
        $falg = XMLUtil::decryptResXml( $xml, $resdata );
        if ( $falg )
        {
            return $resdata;
        }
        else
        {
            dump( 'H5京东支付异步验签失败' );
        }
    }

}