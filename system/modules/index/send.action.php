<?php

class send extends SystemAction
{
    public function init()
    {
    }

    public function send_shop_code()
    {
        sleep( 2 );
        if ( ! isset( $_POST["send"] ) && ! isset( $_POST["uid"] ) && ! isset( $_POST["gid"] ) )
        {
            exit(0);
        }

        $uid = abs($_POST["uid"]);
        $gid = abs($_POST["gid"]);
        $clouddb    = System::load_app_model("cloud_goods", "common");
        $userdb     = System::load_app_model("user", "common");
        $wherewords = "`gid` = '$gid' and `uid` = '$uid'";
        $sendinfo   = $clouddb->select_send_log( $wherewords );

        if ( $sendinfo ) {
            exit(0);
        }

        $where = "`uid` = '$uid'";
        $member = $userdb->SelectUser( $where );

        if ( ! $member )
        {
            exit(0);
        }

        $info = $clouddb->cloud_goodsdetail( $gid );

        if ( ! $info )
        {
            exit(0);
        }

        $username = get_user_name( $member, "username", "all" );
        $this->send_insert( $uid, $gid, $username, $info["g_title"], "-1" );
        $type = System::load_sys_config( "send", "type" );

        if ( ! $type )
        {
            exit(0);
        }

        $q_time = abs(substr( $info["q_external_time"], 0, 10 ));

        while ( time() < $q_time ) {
            sleep(5);
        }

        $ret_send = false;

        if ( $type == "1" ) {
            if ( ! empty( $member["email"] ) ) {
                send_email_code( $member["email"], $username, $uid, $info["q_user_code"], $info["g_title"] );
                $ret_send = true;
            }
        }

        if ( $type == "2" ) {
            if ( ! empty( $member["mobile"] ) ) {
                send_mobile_shop_code( $member["mobile"], $uid, $info["q_user_code"], $info["g_title"] );
                $ret_send = true;
            }
        }

        if ( $type == "3" ) {
            if ( ! empty( $member["email"] ) ) {
                send_email_code( $member["email"], $username, $uid, $info["q_user_code"], $info["g_title"] );
                $ret_send = true;
            }

            if ( ! empty( $member["mobile"] ) ) {
                send_mobile_shop_code( $member["mobile"], $uid, $info["q_user_code"], $info["g_title"] );
                $ret_send = true;
            }
        }

        if ( $ret_send ) {
            $this->send_insert( $uid, $gid, $username, $info["g_title"], $type );
        }

        exit(0);
    }

    public function send_shop_reg()
    {
    }

    private function send_insert( $uid, $gid, $username, $shoptitle, $send_type )
    {
        $db      = System::load_sys_class("model");
        $clouddb = System::load_app_model("cloud_goods", "common");
        $time    = time();

        if ( $send_type == "-1" ) {
            $data = array();
            $data["uid"]       = $uid;
            $data["gid"]       = $gid;
            $data["username"]  = $username;
            $data["shoptitle"] = $shoptitle;
            $data["send_type"] = $send_type;
            $data["send_time"] = $time;
            $clouddb->insert_send_log( $data );
        }
        else {
            $setwords   = "`send_type` = '$send_type'";
            $wherewords = "`gid` = '$gid' and `uid` = '$uid'";
            $clouddb->update_send_log( $setwords, $wherewords );
        }
    }
}

ignore_user_abort(true);
set_time_limit(0);

?>