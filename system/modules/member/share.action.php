<?php
System::load_app_class("UserAction", "common", "no");
class share extends UserAction
{
    public function sharelist()
    {
        seo("title", _cfg("web_name") . "_" . l("user.singlelist"));
        seo("keywords", l("user.singlelist"));
        seo("description", l("user.singlelist"));
        $member     = $this->UserInfo;
        $shareed_db = System::load_app_model("share", "common");
        $order_db   = System::load_app_model("order", "common");
        $page       = System::load_sys_class("page");
        $selectwords = "`ouid`='{$member["uid"]}' and `owin` > '10000000' order by `otime` desc";
        $cord = $order_db->ready_order($selectwords, 1);
        $shareed = $shareed_db->GetUserShareList($member["uid"]);
        $sd_id = $r_id = array();

        foreach ( $shareed as $sd )
        {
            $sd_id[] = $sd["sd_shopid"];
        }

        foreach ( $cord as $rd )
        {
            if ( ! in_array( $rd["ogid"], $sd_id ) )
            {
                $r_id[] = $rd["ogid"];
            }
        }

        if ( ! empty( $r_id ) )
        {
            $rd_id = implode( ",", $r_id );
            $rd_id = trim( $rd_id, "," );
        }
        else
        {
            $rd_id = "0";
        }

        $selectwords = "ogid in ($rd_id) and `ouid`='{$member["uid"]}' and `owin`>'10000000' order by `otime` desc";
        $share = $order_db->ready_order( $selectwords, 1 );
        foreach ($share as $key => $value) {
            $time_arr                  = array();
            $og_title_arr              = array();
            $og_title_arr              = unserialize( $value['og_title'] );
            $share[ $key ]['g_thumb']  = $og_title_arr['g_thumb'];
            $time_arr                  = explode( '.', $value['otime'] );
            $share[ $key ]['sd_time']  = date( 'Y-m-d H:i:s', $time_arr[0] );
            $share[ $key ]['sd_title'] = $og_title_arr['g_title'];
        }
        $this->view->data( "uid", $member["uid"] );
        $this->view->data( "shareed", $shareed );
        $this->view->data( "share", $share );
        $this->view->data( 'member', $member );
        $this->view->show( "user.sharelist" );
    }

    /**
     * 添加晒单
     */
    public function shareinsert()
    {
        $member = $this->UserInfo;
        $uid    = _getcookie("uid");
        $ushell = _getcookie("ushell");
        $user_record_db = System::load_app_model("cloud_goods", "common");
        $order_db       = System::load_app_model("order", "common");
        $share_db       = System::load_app_model("share", "common");
        $recordid = intval($this->segment(4));
        $shopid   = $recordid;
        $selectwords = "`oid`='$recordid' and `ouid` ='{$member["uid"]}'";
        $share = $order_db->ready_order($selectwords, 1);
        $share = $share[0];

        if ( ! $share ) {
            _message( l("share.goods.no"), WEB_PATH . "/member/share/sharelist" );
        }

        $shaidanyn = $share_db->sharedetail( $recordid, $member["uid"] );
        if ( $shaidanyn ) {
            _message( l("share.goods.rep"), WEB_PATH . "/member/share/sharelist" );
        }

        $ginfo = $user_record_db->cloud_goodsdetail($share["ogid"]);

        if ( ! $ginfo ) {
            _message( l("goods.no"), WEB_PATH . "/member/share/sharelist" );
        }

        /* 提交晒单 Start */
        if ( isset( $_POST["submit"] ) )
        {
            if ( $_POST["title"] == NULL ) {
                _message( l("user.title.emp") );
            }

            if ( $_POST["content"] == NULL ) {
                _message( l("user.content.emp") );
            }

            $g_picarr = array();
            $g_picarr = _post("share_file");

            if ( empty( $g_picarr ) ) {
                _message(l("user.img.emp"));
            }

            System::load_sys_class("upload", "sys", "no");
            $img = $g_picarr;
            $num = count($g_picarr);
            $pic = "";

            for ( $i = 0; $i < $num; $i++ ) {
                $pic .= trim( $img[$i] ) . ";";
            }

            $sd_userid    = $member["uid"];
            $sd_shopid    = $ginfo["id"];
            $sd_title     = _htmtocode($_POST["title"]);
            $sd_thumbs    = $img[0];
            $sd_content   = editor_safe_replace( stripslashes( $_POST["content"] ) );
            $sd_photolist = $pic;
            $sd_time      = time();
            $sd_ip        = _get_ip_dizhi();
            $shareinset_html = "('$sd_userid','$sd_shopid','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')";
            $shareinset = $share_db->InsetSharelist( $shareinset_html );

            if ( $shareinset ) {
                _message( l("share.suc"), WEB_PATH . "/member/share/sharelist" );
            }
        }
        /* 提交晒单 End */

        $this->view->data( "share", $share );
        $this->view->show( "user.shareinsert" );
    }
    /**
     * 手机端晒图
     */
    public function share_mobile(){
        $share_id = $this->segment( 4 );
        $this->view->data( "share_id", $share_id );
        $this->view->data( "WEB_PATH", WEB_PATH );
        $this->view->show( "user.shareinsert" );
    }

    public function mobile_shareinsert(){
        $member         = $this->UserInfo;
        $uid            = _getcookie("uid");
        $ushell         = _getcookie("ushell");
        $user_record_db = System::load_app_model("cloud_goods", "common");
        $order_db       = System::load_app_model("order", "common");
        $share_db       = System::load_app_model("share", "common");
        $recordid       = $_POST['share_id'];
        $shopid         = $_POST['share_id'];
        $selectwords    = "`oid`='$recordid' and `ouid` ='{$member["uid"]}'";
        $share          = $order_db->ready_order($selectwords, 1);
        $share          = $share[0];
        if ( ! $share ) {
            _message( l("share.goods.no"), WEB_PATH . "/member/share/sharelist" );
        }

        $shaidanyn = $share_db->sharedetail( $recordid, $member["uid"] );
        if ( $shaidanyn ) {
            _message( l("share.goods.rep"), WEB_PATH . "/member/share/sharelist" );
        }

        $ginfo = $user_record_db->cloud_goodsdetail($share["ogid"]);

        if ( ! $ginfo ) {
            _message( l("goods.no"), WEB_PATH . "/member/share/sharelist" );
        }
        if ( $_POST['title'] == NULL ) {
            _message( l("user.title.emp"), WEB_PATH . "/member/share/sharelist" );
        }
        if ( $_POST['contents'] == NULL ) {
            _message( l("user.content.emp"), WEB_PATH . "/member/share/sharelist" );
        }

        System::load_sys_class("upload", "sys", "no");
        $upload      = new upload();
        $upload->upload_config( array( 'png', 'jpg', 'jpeg' ), '', 'share' );
        $upload_res      = $upload->go_upload( $_FILES['cover'] );
        $upload_name     = $upload->get_file_name();
        
        $sd_userid       = $member["uid"];
        $sd_shopid       = $ginfo["id"];
        $sd_title        = _htmtocode($_POST["title"]);
        $sd_thumbs       = 'share/' . date('Ymd') . '/' . $upload_name;
        $sd_content      = editor_safe_replace( stripslashes( $_POST["contents"] ) );
        $sd_photolist    = $sd_thumbs;
        $sd_time         = time();
        $sd_ip           = _get_ip_dizhi();
        $shareinset_html = "('$sd_userid','$sd_shopid','$sd_ip','$sd_title','$sd_thumbs','$sd_content','$sd_photolist','$sd_time')";
        $shareinset      = $share_db->InsetSharelist( $shareinset_html );

        if ( $shareinset ) {
            _message( l("share.suc"), WEB_PATH . "/member/share/sharelist" );
        }
    }
    public function shareupdate()
    {
        $this->view->show();
    }

    public function sharephotoup()
    {
        $this->view->show();
    }

    public function sharephotodel()
    {
        $action = (isset($_GET["action"]) ? $_GET["action"] : NULL);
        $filename = (isset($_GET["filename"]) ? $_GET["filename"] : NULL);
        $filename = json_encode($filename);
        if (($action == "del") && !empty($filename)) {
            $size = getimagesize($filename);
            unlink($filename);
            exit();
        }
    }

    public function shaidandel()
    {
        $this->view->show();
    }
}
?>
