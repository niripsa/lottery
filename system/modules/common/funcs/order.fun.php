<?php

function create_record_table()
{
    $table = _db_cfg("database");
    $pre   = _db_cfg("tablepre");
    $db = System::load_sys_class("model");
    $rr = $db->db_test("call p_xxx();");
    var_dump($rr);
    exit();
    $info = $db->GetOne("show table status like '" . $pre . "cloud_record'");
    $m_id = $info["Auto_increment"] % 100000;

    if ($m_id == 1) {
        $field = "Tables_in_" . $table;
        $arr = $db->GetList("show tables from " . $table . " where " . $field . " like '" . $pre . "cloud_record%';");
        sort($arr);
        array_pop($arr);
        $last_table_arr = end($arr);
        $last_table = $last_table_arr[$field];
        $last_table = str_replace($pre . "cloud_record", "", $last_table);
        $last_table_num = trime($last_table, "_");
        if (($last_table_num != "") && is_numeric($last_table_num)) {
            $next_table = ($pre . "cloud_record_" . $last_table_num) + 1;
        }
        else {
            $next_table = $pre . "cloud_record_1";
        }

        $info = $db->GetOne("create table " . $next_table . " select from '" . $pre . "cloud_record' where 1=2");

        if ($info) {
        }
    }
}

/**
 * 判断表是否存在
 */
function table_exists( $table_name )
{
    $db_name = _db_cfg( 'database' );
    $pre     = _db_cfg( 'tablepre' );
    $db    = System::load_sys_class( 'model' );
    $field = "Tables_in_" . $db_name;
    $sql = "SHOW TABLES FROM " . $db_name . " WHERE " . $field . " = '" . $pre . $table_name . "';";
    $arr = $db->GetList( $sql );
    if ( $arr )
    {
        return true;
    }
    else
    {
        return false;
    }
}

function goods_table( $id, $num = "" )
{
    $pre = _db_cfg( 'tablepre' );
    $db  = System::load_sys_class( 'model' );

    if ( empty( $num ) )
    {
        $sql = "SELECT zongrenshu FROM " . $pre . "goods WHERE id='" . $id . "' ";
        $arr = $db->GetOne( $sql );
        $num = $arr["zongrenshu"];
    }

    if ( 10000 < $num )
    {
        $sql = "CREATE TABLE " . $pre . "cloud_g_" . $id . " SELECT FROM '" . $pre . "cloud_g' WHERE 1 = 2";
        $info = $db->GetOne( $sql );
        if ( $info )
        {
            $sql = "UPDATE " . $pre . "cloud_goods SET `record_table` = 'cloud_g_" . $id . "' WHERE id='" . $id . "'";
            $res = $db->query( $sql );
            return "cloud_g_" . $id;
        }
        else
        {
            return "cloud_g";
        }
    }
    else
    {
        return "cloud_g";
    }
}

function user_table( $uid )
{
    $pre = _db_cfg( 'tablepre' );
    $db  = System::load_sys_class( 'model' );
    $sql = "SELECT COUNT(id) AS num FROM " . $pre . "cloud_u WHERE `uid`='" . $uid . "'";
    $info = $db->GetOne( $sql );

    if ( 10000 <= $info["num"] )
    {
        $sql = "CREATE TABLE " . $pre . "cloud_u_" . $uid . " SELECT FROM '" . $pre . "cloud_u' WHERE `uid`='" . $uid . "'";
        $res = $db->GetOne( $sql );
        if ( $res )
        {
            $sql = "UPDATE " . $pre . "user SET `record_table` = 'cloud_u_" . $uid . "' WHERE uid = '" . $uid . "'";
            $rs = $db->query( $sql );
        }
    }
}

function get_user_table()
{
}

function get_goods_table()
{
}

function get_record_table()
{
}