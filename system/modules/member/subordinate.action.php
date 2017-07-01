<?php
System::load_app_class("UserAction", "common", "no");
/**
 * 下级管理
 */
class subordinate extends UserAction
{

    /**
     * 代开下级
     * @author Yusure  http://yusure.cn
     * @date   2016-10-24
     * @param  [param]
     * @return [type]     [description]
     */
    public function openlower()
    {
        $this->member_model = System::load_app_model("member", "common");
        seo("title", _cfg("web_name") . "_" . L("user.openlower"));
        seo("keywords", L("user.openlower"));
        seo("description", L("user.openlower"));
        $member = $this->UserInfo;
        if ( ! in_array( $member['manage_rank'], array( 1, 2 ) ) )
        {
            _message( '管理商等级不符合要求' );
        }
        if ( isset( $_POST["submit"] ) ) 
        {
            $data = _post();
            unset( $data["submit"] );

            if ( empty( $data["username"] ) ) {
                _message("用户名不能为空");
            }

            if ( empty( $data["password"] ) ) {
                _message("密码不能为空");
            }
            else {
                $data["password"] = md5(md5($data["password"]) . md5($data["password"]));
            }

            if ( ! $data["email"] && ! $data["mobile"] )
            {
                _message("邮箱或手机选填一样！");
            }

            if ( ! empty( $data["email"] ) ) {
                $info = $this->member_model->get_user_one("`email` = '" . $data["email"] . "'");
                $info && _message("该邮箱已经存在！");                
                $data["emailcode"] = 1;
                $data["reg_key"] = $data["email"];
            }
            if ( ! empty( $data["mobile"] ) ) {
                $info = $this->member_model->get_user_one("`mobile` = '" . $data["mobile"] . "'");
                $info && _message("该手机号已经存在！");
                $data["mobilecode"] = 1;
                $data["reg_key"] = $data["reg_key"] ? : $data["mobile"];
            }

            $data["time"]                = time();
            $data["yaoqing"]             = $member['uid'];
            $data["area_id"]             = $member['area_id'];
            $data["manage_rank"]         = $member['manage_rank'] + 1;
            $data["manage_parent"]       = $member['uid'];
            $data["manage_grand_parent"] = $member['manage_parent'];

            $res = $this->member_model->user_add( $data );

            if ( $res ) {
                _message( "增加成功" );
            }
            else {
                _message("增加失败");
            }
        }
        else
        {
            $this->view->data( "member", $this->UserInfo );
            $this->view->show( 'user.openlower' );
        }        
    }
}