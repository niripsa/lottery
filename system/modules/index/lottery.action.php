<?php
/**
 * 定时开奖
 */
class lottery extends SystemAction
{
    /**
     * 每分钟检查需要开奖的商品，
     * 判断是否符合条件，
     * 符合就curl访问接口进行开奖
     */
    public function init()
    {
        /* xsjx_time 小于 time() &&  canyurenshu 大于0  &&  q_user_code is null */
        $cloud_goods_model = System::load_app_model( 'cloud_goods', 'common' );
        $now_time = time();
        $where = "xsjx_time BETWEEN 1 AND {$now_time} AND canyurenshu > 0 AND q_user_code is null";
        $goods_list = $cloud_goods_model->get_list( 'cloud_goods', $where, 'id', '', '' );
        if ( ! $goods_list ) return;
        foreach ( (array)$goods_list as $k => $v )
        {
            $url  = WEB_PATH . 'plugin-CloudWay-autoway';
            $data = array( 'shopid' => $v['id'] );
            curl_post( $url, $data );
            $str = date( 'Y-m-d H:i:s' ) . '自动开奖：云购商品ID' . $v['id'];
            dump( $str );
        }
    }
}