<?php
/**
 * 支付 获取 夺宝码
 * $user_num  人次数量
 * $shopinfo  商品详情
 * $ret_data  要返回的数据
 */
function pay_get_shop_codes( $user_num = 1, $shopinfo = NULL, &$ret_data = NULL )
{
    $db = System::load_sys_class( "model" );
    $ret_data["query"] = true;
    $table = "@#_" . $shopinfo["codes_table"];
    $codes_arr = array();
    $codesql = "SELECT id, cg_id, cg_cid, cg_len, cg_codes 
                    FROM `$table` 
                WHERE `cg_id` = '{$shopinfo["id"]}' 
                ORDER BY `cg_cid` DESC LIMIT 1 for update";
    $codes_one = $db->GetOne( $codesql );
    $codes_arr[$codes_one["cg_cid"]] = $codes_one;
    $codes_count_len = $codes_arr[$codes_one["cg_cid"]]["cg_len"];
    if ( ($codes_count_len < $user_num) && (1 < $codes_one["cg_cid"]) )
    {
        for ( $i = $codes_one["cg_cid"] - 1; 1 <= $i; $i-- )
        {
            $sql = "SELECT id,cg_id,cg_cid,cg_len,cg_codes 
                        FROM `$table` 
                    WHERE `cg_id` = '{$shopinfo["id"]}' AND `cg_cid` = '$i' 
                    LIMIT 1 for update";
            $codes_arr[$i]   = $db->GetOne( $sql );
            $codes_count_len += $codes_arr[$i]["cg_len"];

            if ( $user_num < $codes_count_len )
            {
                break;
            }
        }
    }

    if ( $codes_count_len < $user_num )
    {
        $user_num = $codes_count_len;
    }

    $ret_data["user_code"]     = "";
    $ret_data["user_code_len"] = 0;

    foreach ( $codes_arr as $icodes )
    {
        $u_num = $user_num;
        $icodes["cg_codes"] = unserialize( $icodes["cg_codes"] );
        $code_tmp_arr = array_slice( $icodes["cg_codes"], 0, $u_num );

        foreach ( $code_tmp_arr as $key => $code_add )
        {
            $code_tmp_arr[$key] = $code_add + 10000000;
        }

        $ret_data["user_code"] .= implode( ",", $code_tmp_arr );
        $code_tmp_arr_len = count( $code_tmp_arr );

        if ( $code_tmp_arr_len < $u_num )
        {
            $ret_data["user_code"] .= ",";
        }

        $icodes["cg_codes"] = array_slice( $icodes["cg_codes"], $u_num, count($icodes["cg_codes"]) );
        $icode_sub          = count( $icodes["cg_codes"] );
        $icodes["cg_codes"] = serialize( $icodes["cg_codes"] );

        if ( ! $icode_sub )
        {
            $update1 = "UPDATE `$table` SET `cg_cid` = '0', `cg_codes` = '{$icodes["cg_codes"]}',`cg_len` = '$icode_sub' WHERE `id` = '{$icodes["id"]}'";
            $query = $db->Query( $update1 );
            if ( ! $query )
            {
                $ret_data["query"] = false;
            }
        }
        else
        {
            $update2 = "UPDATE `$table` SET `cg_codes` = '{$icodes["cg_codes"]}', `cg_len` = '$icode_sub' WHERE `id` = '{$icodes["id"]}'";
            $query = $db->Query( $update2 );
            if ( ! $query )
            {
                $ret_data["query"] = false;
            }
        }

        $ret_data["user_code_len"] += $code_tmp_arr_len;
        $user_num = $user_num - $code_tmp_arr_len;
    }
}

function pay_get_dingdan_code($dingdanzhui = "")
{
    return $dingdanzhui . time() . substr(microtime(), 2, 6) . rand(0, 9);
}

function pay_insert_shop_x($shop = "", $type = "")
{
    $g_c_x = System::load_app_config("get_code_x", "", "pay");
    if (is_array($g_c_x) && isset($g_c_x["class"])) {
        $gcx_db = System::load_app_class($g_c_x["class"], "pay");
    }
    else {
        $g_c_x = array("class" => "tocode");
        $gcx_db = System::load_app_class($g_c_x["class"], "pay");
    }

    $gcx_db->config($shop, $type);
    $gcx_db->get_run_tocode();
    $ret_data = $gcx_db->returns();
}

/**
 * 更新基金
 */
function pay_go_fund( $go_number = NULL )
{
    if ( ! $go_number ) {
        return true;
    }

    $db = System::load_sys_class("model");
    $fund = $db->GetOne( "SELECT * FROM `@#_fund` WHERE 1" );
    if ( $fund && $fund["fund_off"] )
    {
        $money = ($fund["fund_money"] * $go_number) + $fund["fund_cmoney"];
        return $db->Query("UPDATE `@#_fund` SET `fund_cmoney` = '$money'");
    }
    else
    {
        return true;
    }
}

/**
 * 发放佣金
 */
function pay_go_yongjin( $uid = NULL, $dingdancode = NULL )
{
    if ( ! $uid || ! $dingdancode )
    {
        return true;
    }

    $db         = System::load_sys_class("model");
    $time       = time();
    $order_db   = System::load_app_model("order", "common");
    $config     = System::load_sys_config("user_fufen");
    $yesyaoqing = $db->GetOne( "SELECT `yaoqing` FROM `@#_user` WHERE `uid`='$uid'" );

    if ( $yesyaoqing["yaoqing"] )
    {
        $yongjin = $config["fufen_yongjin"];
    }
    else
    {
        return true;
    }

    if ( file_exists( G_PLUGIN . "Commission/Commission.action.php" ) )
    {
        include_once G_PLUGIN . "Commission/Commission.action.php";
    }

    $yongjin     = floatval(substr(sprintf("%.3f", $yongjin), 0, -1));
    $dingdancode = $dingdancode[0]["ocode"];
    $selectwords = "`ocode`='$dingdancode'";
    $gorecode    = $order_db->ready_order( $selectwords, 1 );

    foreach ( $gorecode as $val )
    {
        $y_money = $val["omoney"] * $yongjin;
        $val["og_title"] = useri_title($val["og_title"], "g_title");
        $content = "(第" . $val["oqishu"] . "期)" . $val["og_title"];
        $gosql = "INSERT INTO `@#_user_recodes`(`uid`,`r_uid`,`type`,`content`,`shopid`,`money`,`ygmoney`,`time`) VALUES('$uid','" . $yesyaoqing["yaoqing"] . "','1','$content','{$val["ogid"]}','$y_money','{$val["omoney"]}','$time' )";
        $db->Query( $gosql );

        if ( function_exists( 'commission_calc' ) )
        {
            commission_calc( $uid, $yesyaoqing["yaoqing"], $val );
        }
    }
}

/**
 * 生成支付单编号(两位随机 + 从2000-01-01 00:00:00 到现在的秒数+微秒+会员ID%1000)，该值会传给第三方支付接口
 * 长度 =2位 + 10位 + 3位 + 3位  = 18位
 * @return string
 */
function make_paysn( $user_id )
{
    return mt_rand(10,99)
          . sprintf('%010d',time() - 946656000)
          . sprintf('%03d', (float) microtime() * 1000)
          . sprintf('%03d', (int) $user_id % 1000);
}


?>
