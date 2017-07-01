<?php

function get_user_name($uid = "", $type = "username", $key = "sub")
{
    if (is_array($uid)) {
        if (isset($uid["username"]) && !empty($uid["username"])) {
            return $uid["username"];
        }

        if (isset($uid["email"]) && !empty($uid["email"])) {
            if ($key == "sub") {
                $email = explode("@", $uid["email"]);
                return $uid["email"] = substr($uid["email"], 0, 2) . "*" . $email[1];
            }
            else {
                return $uid["email"];
            }
        }

        if (isset($uid["mobile"]) && !empty($uid["mobile"])) {
            if ($key == "sub") {
                return $uid["mobile"] = substr($uid["mobile"], 0, 3) . "****" . substr($uid["mobile"], 7, 4);
            }
            else {
                return $uid["mobile"];
            }
        }

        return "";
    }
    else {
        $db = System::load_sys_class("model");
        $uid = intval($uid);
        $info = $db->GetOne("select username,email,mobile from `@#_user` where `uid` = '$uid' limit 1");
        if (isset($info["username"]) && !empty($info["username"])) {
            return $info["username"];
        }

        if (isset($info["email"]) && !empty($info["email"])) {
            if ($key == "sub") {
                $email = explode("@", $info["email"]);
                return $info["email"] = substr($info["email"], 0, 2) . "*" . $email[1];
            }
            else {
                return $info["email"];
            }
        }

        if (isset($info["mobile"]) && !empty($info["mobile"])) {
            if ($key == "sub") {
                return $info["mobile"] = substr($info["mobile"], 0, 3) . "****" . substr($info["mobile"], 7, 4);
            }
            else {
                return $info["mobile"];
            }
        }

        if (isset($info[$type]) && !empty($info[$type])) {
            return $info[$type];
        }

        return "";
    }
}

function get_user_field($uid = "", $type = "username")
{
    $db = System::load_sys_class("model");
    $uid = intval($uid);
    $info = $db->GetOne("select username,email,mobile from `@#_user` where `uid` = '$uid' limit 1");
    if (isset($info[$type]) && !empty($info[$type])) {
        return $info[$type];
    }

    return "";
}

function get_user_key($uid = "", $type = "img", $size = "")
{
    if (is_array($uid)) {
        if (isset($uid[$type])) {
            if ($type == "img") {
                if ($size) {
                    return $uid[$type] . "." . $size . ".jpg";
                }
                else {
                    return $uid[$type];
                }
            }

            return $uid[$type];
        }

        return "null";
    }
    else {
        $db = System::load_sys_class("model");
        $uid = intval($uid);
        $info = $db->GetOne("select $type from `@#_user` where `uid` = '$uid' limit 1");

        if ($type == "img") {
            if (isset($info[$type]) && !empty($info[$type])) {
                if ($size) {
                    return $info[$type] . "." . $size . ".jpg";
                }
                else {
                    return $info[$type];
                }
            }
            else {
                return "null";
            }
        }

        if (isset($info[$type]) && !empty($info[$type])) {
            return $info[$type];
        }
        else {
            return "null";
        }

        return "null";
    }
}

function get_user_uid($type = "bool")
{
    global $_cfg;
    if (isset($_cfg["userinfo"]) && is_array($_cfg["userinfo"])) {
        return $_cfg["userinfo"]["uid"];
    }
    else {
        return false;
    }
}

function get_user_img($size = "")
{
    $user = System::load_app_class("UserCheck", "common");

    if ($user->UserInfo) {
        $userinfo = $user->UserInfo;

        if ($size) {
            return $userinfo["img"] . "_" . $size . ".jpg";
        }
        else {
            return $userinfo["img"];
        }
    }
    else {
        return "photo/member.jpg";
    }

    global $_cfg;

    if (isset($_cfg["userinfo"])) {
        $fk = explode(".", $_cfg["userinfo"]["img"]);
        $h = array_pop($fk);

        if ($size) {
            return $_cfg["userinfo"]["img"] . "_" . $size . ".jpg";
        }
        else {
            return $_cfg["userinfo"]["img"];
        }
    }
    else {
        return "photo/member.jpg";
    }
}

/**
 * 获取三级管理商ID
 * @date   2016-09-29
 * @param  [邀请人ID]
 * @return [array]     [三级管理商ID]
 * 1、获取邀请人的信息，判断邀请人 manage_rank manage_parent
 * 2、switch 三级分流
 * 3、1级 > 2 3 填充0 返回
 * 4、2级 > 3级填充 0 ，1级 用manage_parent 填充
 * 5、3级 > 2级用manage_parent 填充  1级用manage_parent条件查找manage_parent
 */
function get_manage_id( $yaoqing_id )
{
    $user_model = System::load_app_model( 'user', 'common' );
    $manage_id  = array();
    $userinfo   = $user_model->SelectUser( 'uid = '.$yaoqing_id, 'manage_rank, manage_parent' );
    switch ( $userinfo['manage_rank'] )
    {
        case 1:
            $manage_id['1'] = $yaoqing_id;
            $manage_id['2'] = 0;
            $manage_id['3'] = 0;
        break;

        case 2:
            $manage_id['1'] = $userinfo['manage_parent'];
            $manage_id['2'] = $yaoqing_id;
            $manage_id['3'] = 0;
        break;

        case 3:
            $manage_id['1'] = get_user_key( $userinfo['manage_parent'], 'manage_parent' );
            $manage_id['2'] = $userinfo['manage_parent'];
            $manage_id['3'] = $yaoqing_id;            
        break;

        default:
            return false;
        break;
    }

    return $manage_id;
}

function get_user_arr( $key = "", $where = "" )
{
    $user = System::load_app_class("UserCheck", "common");

    if ( $user->GetUserCheckToBool() )
    {
        $userinfo = $user->UnserInfo;
        return $userinfo["uid"];
    }
    else
    {
        return false;
    }

    if ( empty( $where ) )
    {
        $where = "uid,username,password,email,mobile,img";
    }
    else
    {
        $where = "uid,username,password,email,mobile,img," . $where;
    }

    $db     = System::load_sys_class("model");
    $uid    = $userinfo["uid"];
    $ushell = $userinfo["ushell"];

    if ( ! $uid ) {
        return false;
    }

    $userinfo = $db->GetOne("SELECT $where FROM `@#_user` WHERE `uid` = '$uid'");

    if ( ! $userinfo ) {
        return false;
    }

    $shell = md5($userinfo["uid"] . $userinfo["password"] . $userinfo["mobile"] . $userinfo["email"]);

    if ( $ushell != $shell ) {
        return false;
    }

    if ( empty( $key ) )
    {
        return $userinfo;
    }
    else if ( isset( $userinfo["key"] ) )
    {
        return $userinfo["key"];
    }
    else
    {
        return false;
    }
}

function get_user_goods_num( $uid = NULL, $sid = NULL )
{
    if ( empty( $uid ) || empty( $sid ) )
    {
        return false;
    }

    $db = System::load_sys_class("model");
    $list = $db->GetList("select * from `@#_member_go_record` where `uid` = '$uid' and `shopid` = '$sid' and `status` LIKE '%已付款%'");
    $num = 0;

    foreach ( $list as $v )
    {
        $num += $v["gonumber"];
    }

    return $num;
}

function tubimg( $src, $width, $height )
{
    $url  = G_UPLOAD_PATH . "/" . $src;
    $size = getimagesize($url);
    $name = rand(10, 99) . substr(microtime(), 2, 6) . substr(time(), 4, 6);
    $filetype = explode("/", $src);
    $img = imagecreatefromjpeg($url);
    $dst = imagecreatetruecolor($width, $height);
    imagecopyresampled($dst, $img, 0, 0, 0, 0, $width, $height, $size[0], $size[1]);
    imagejpeg($dst, "statics/uploads/" . $filetype[0] . "/" . $filetype[1] . "/" . $name . ".jpg");
    return $filetype[1] . "/" . $name . ".jpg";
}

function get_ip( $id, $ipmac = NULL )
{
    $db = System::load_sys_class("model");
    $ip = explode( ",", $id );

    if ( $ipmac == "ipmac" )
    {
        return $ip[1];
    }
    else if ( $ipmac == "ipcity" )
    {
        return $ip[0];
    }

    return $ip[0] . "IP:" . $ip[1];
}

function sdimg( $sd_id )
{
    $mysql_model = System::load_sys_class("model");
    $shaidan     = $mysql_model->GetOne("SELECT * FROM `@#_shaidan` WHERE `sd_id` = '$sd_id'");
    $img         = explode( ";", $shaidan["sd_photolist"] );
    $ul_li       = "";
    $img_num = count( $img );
    for ( $i = 0; $i < ($img_num - 1); $i++ )
    {
        $ul_li .= "<li id=\"ulli_" . $i . "\"><img src=\"" . G_UPLOAD_PATH . "/" . $img[$i] . "\" width=\"100\" height=\"100\"><input type=\"hidden\" value=\"" . $img[$i] . "\"><a href=\"javascript:;\" rel=\"ulli_" . $i . "\">删除</a></li>";
    }

    return $ul_li;
}

function img( $img )
{
    $img = explode( ".", $img );
    return $img[1];
}

function member_get_dizhi( $uid = "", $key = "bool" )
{
    $uid = abs(intval( $uid ));

    if ( ! $uid ) {
        return false;
    }

    $db = System::load_sys_class("model");
    $info = $db->GetOne("SELECT * FROM `@#_user_addr` WHERE `uid` = '$uid' and `default` = 'Y'");

    if ( $info )
    {
        return $info;
    }
    else
    {
        return false;
    }
}

function uidcookie( $get_name = NULL )
{
    $user = System::load_app_class("UserCheck", "common");
    $user = $user->UnserInfo;

    if ( ! $user ) {
        return false;
    }

    if ( isset( $user[$get_name] ) ) {
        return $user[$get_name];
    }
    else {
        return NULL;
    }
}

function userid( $uid, $zhi )
{
    $mysql_model = System::load_sys_class("model");
    $member      = $mysql_model->GetOne("SELECT * FROM `@#_user` WHERE `uid` = '$uid'");

    if ( $zhi == "username" )
    {
        if ( $member["username"] != NULL )
        {
            return $member["username"];
        }
        else if ( $member["mobile"] != NULL )
        {
            return _strcut( $member["mobile"], 7, "" );
        }
        else
        {
            return _strcut( $member["email"], 7, "" );
        }
    }
    else
    {
        return $member[$zhi];
    }
}

/**
 * 获取用户信息
 */
function User_GetUserInfo( $field = "" )
{
    $Mysql = System::load_app_class( "UserCheck", "common" );
    $temp = $Mysql->GetUserCheckToBool();

    if ( $temp )
    {
        if ( empty( $field ) )
        {
            return $temp;
        }
        else
        {
            $user = $Mysql->UserInfo;
            return $user[$field];
        }
    }
}

function User_GetRecordStart( $str )
{
    $status = explode( ",", $str );
    if ( ($status[2] == "未完成") || ($status[2] == "待收货") ) {
        if ( $status[1] == "未发货" ) {
            return "等待发货";
        }

        if ( $status[1] == "已发货" ) {
            return "已发货";
        }
    }

    if ( $status[2] == "已完成" ) {
        return "已完成";
    }

    if ( $status[2] == "已作废" ) {
        return "已作废";
    }
}