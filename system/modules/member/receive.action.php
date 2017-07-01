<?php
class receive extends SystemAction
{

    /**
     * 接受邀请成为下级分销商
     * @author Yusure  http://yusure.cn
     * @date   2017-03-08
     * @param  [param]
     * @return [type]     [description]
     */
    public function receive_invitation()
    {
        $distributor_id = $this->segment( 4 );
        
        /* 检查是否登陆 */
        $user = System::load_app_class( 'UserCheck', 'common' )->UserInfo;
        if ( ! $user )
        {
            _setcookie( 'distrobutor', $distributor_id, '86400' );
            $this->SendStatus( 301, WEB_PATH . '/register&time='.time() );
        }
        $userindex_url = WEB_PATH . '/member/home/userindex';
        /* 检查分销状态，是否分销 */
        $res = is_distributor( $user['uid'] );
        if ( $res )
        {
            _message( '您已经是分销商了', $userindex_url );
        }
        /* 检查是否主动申请分销商了 */
        $apply_model = System::load_app_model( 'distributor_apply', 'common' );
        $where = "uid = {$user['uid']} AND apply_status = 0";
        $apply_res = $apply_model->get_info( $where, 'apply_id' );
        if ( $apply_res )
        {
            _message( '您的分销申请正在审核中', $userindex_url );
        }
        /* 加入分销 */
        $res = join_distribution( $user, $distributor_id );
        if ( $res )
        {
            _message( '恭喜您，成为分销商！', $userindex_url );
        }
        else
        {
            _message( '加入失败' );
        }
    }

}