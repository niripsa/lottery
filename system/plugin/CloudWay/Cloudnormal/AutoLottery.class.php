<?php 
/**
 * 自动开奖
 */
class AutoLottery {

    public function __construct() {
        $this->model   = System::load_app_model( "UserPay_cloud", "common" );
        $this->modela  = System::load_app_model( "UserPay", "common" );     // 加载购买商品model
        $this->orderdb = System::load_app_model( "order", "common" );       // 加载订单model       
    }   
                
    // ajax 商品揭晓
    public function autolottery_ret_install( $shop_info = '' )
    {
        $shop_info['xsjx_time'] = $shop_info['xsjx_time'].'.000'; 
        
        /* 1、用$shop_info['id']去cloud_select_i根据ogid查出所有ogocode */
        /* 2、ogocode合成一个数组随机取出一个作为code */
        $where = "ogid = {$shop_info['id']} AND ogocode <> ''";
        /* 查询中奖表取出中奖码，如果没有中奖码先生成入库 */
        $owin_info = $this->orderdb->get_one( 'xsjx_owin', $where, 'ogid, ogocode' );
        if ( $owin_info )
        {
            $code = $owin_info['ogocode'];
        }
        else
        {
            $cloud_list = $this->orderdb->get_list( 'cloud_select_i', $where, 'oid, ogid, ogocode' );
            foreach ( (array)$cloud_list as $k => $v )
            {
                $ogocode_all .= $v['ogocode'] . ',';
            }
            $ogocode_all = trim( $ogocode_all, ',' );
            $ogocode_all = explode( ',', $ogocode_all );
            $code = $ogocode_all[ array_rand( $ogocode_all ) ];
            $xsjx_owin_res = $this->orderdb->insert_data( 'xsjx_owin', array( 'ogid' => $shop_info['id'], 'ogocode' => $code )  );
            if ( $xsjx_owin_res === false )
            {
                echo '-2';exit;
            }
        }

        $where = "`ogid` = '$shop_info[id]' AND `ogocode` LIKE  '%$code%'";
        $oidinfo    = $this->modela->cloud_gocode( $where ); 
        /* cloud_select 查询 */
        $wherewords = "`oid` = $oidinfo[oid]";        
        $u_go_info  = $this->orderdb->ready_order( $wherewords ); 

        /* 如果 cloud_select 能查到数据 */
        if ( $u_go_info ) {
            $u_go_info  = $u_go_info[0];
            $u_info = $this->modela->SelectUserUid( $u_go_info['ouid'] );
            $u_info['username'] = _htmtocode( $u_info['username'] );
            $q_user = serialize( $u_info );
            $q_uid  = $u_info['uid'];
        } else {

        }
            
        $update_cgoods = "`q_uid` = '$q_uid',
            `q_user` = '$q_user',
            `q_user_code` = '$code',
            `q_content` = '$content',
            `q_counttime` ='$counttime',
            `q_end_time` = '$shop_info[xsjx_time]',
            `q_showtime` = 'Y' 
            WHERE `id` = '$shop_info[id]'";
        $q_1 = $this->model->UpdateCgoods( $update_cgoods );
        if ( $u_go_info ) {
            $wherewords = "`oid` = '$u_go_info[oid]' AND `ouid` = '$u_go_info[ouid]' AND `ogid` = '$shop_info[id]' AND `oqishu` = '$shop_info[qishu]' AND `ofstatus` = '0'";
            $data = array();
            $data['owin']     = $code;
            $data['ofstatus'] = '1';
            $q = $this->orderdb->update_order( $wherewords, $data );
        } else {
            $q_2 = true;
        }
        /* 限时揭晓 自动生成下期 */
        $q_3 = $this->autolottery_install( $shop_info );
        if ( $q_1 )
        {
            $post_arr = array( "uid" => $q_uid, "gid"=>$shop_info['id'], "send" => 1 );
            _g_triggerRequest( WEB_PATH.'/index/send/send_shop_code', false, $post_arr );
            /* 商品正在揭晓中! 跳转至揭晓结果页面 */
            echo '-6';exit;
        }
        else
        {              
            echo '-2';exit;
        }
    }
    
    private function suan_zd_code( $gid, $r_code )
    {
        $wherewords = "`ogid` = '$gid'";        
        $oidinfo = $this->modela->cloud_gocode( $wherewords );  
        if ( empty( $codes ) ) return false;      
        $html = '';
        foreach ( $codes as $key=>$cv )
        {
            $html .= $cv['ogocode'] . ',';
        }           
        if ( empty( $codes ) ) return false;      
        $codes = explode( ',', $html );    
        array_pop( $codes );
        asort( $codes );  // 正序      
        unset( $html );       
        $go_code  = $r_code;
        if ( $go_code > end($codes) )
        {
            $zd_jin_code = end($codes);
        }
        else
        {
            $t = 90000000;
            foreach ( $codes as $k => $v )
            {
                $s = abs( $go_code - $v );
                if ( $s <= $t )
                {
                    $t = $s;
                    $zd_jin_code = $v;
                }
                else
                {
                    break;
                }
            }       
        }           
        unset( $codes );
        return $zd_jin_code;
    }
    
    /**
     * 自动生成下一期
     */
    private function autolottery_install( $shop = null )
    {
        $goods = System::load_app_model( "goods", "common" ); // 加载夺宝购买商品model
        /* 检查下一期是否存在 */
        $next_qishu = $shop['qishu'] + 1;
        $where      = "`gid` = {$shop['gid']} AND qishu = {$next_qishu}";
        $next_info  = $goods->get_one( 'cloud_goods', $where );
        if ( $next_info )
        {
            return true;
        }
        if ( $shop['qishu'] < $shop['maxqishu'] )
        {
            $maxinfo = $this->model->SelectCgoods_gid( $shop['gid'] );
            if ( ! $maxinfo ) {
                $maxinfo = array( "qishu" => $shop['qishu'] );
            }
            
            $q_1 = $goods->cloud_goods_next( $maxinfo );
            /**             
            $time = time();
            System::load_app_fun("content",G_ADMIN_DIR);        
            $goods = $shop;
            $qishu = $goods['qishu']+1;
            $shenyurenshu = $goods['zongrenshu'] - $goods['def_renshu'];
            //$query_table = content_get_codes_table();
                        
            $id = $this->db->insert_id();       
            $q_2 = content_get_go_codes($goods['zongrenshu'],3000,$id);
            */
            if ( $q_1 ) {
                return true;
            } else {
                return false;
            }
        }
        return true;
    }
}