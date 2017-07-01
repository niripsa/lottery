<?php
class UserCheck
{
    public $UserInfo;

    public function __construct()
    {
        $userdb = System::load_app_model( 'user', 'common' );
        if ( ! _getcookie( 'uid' ) || ! _getcookie( 'ushell' ) ) 
        {

        }
        else 
        {
            $weixin = $this->UserWeixinLogin();

            if ( ! $weixin ) 
            {

            }
            else 
            {
                $uid   = $weixin;
                $uinfo = $userdb->SelectUserUid( $uid );
                $this->UserLoginStatus( $uinfo, 86400 * 5 );
            }
        }

        $uid  = intval( _getcookie( 'uid' ) );
        $info = $userdb->SelectUserUid( $uid );

        if ( ! $info ) 
        {
            return false;
        }

        $usershell = md5($info['uid'] . $info['password']);

        if ( ! _ifcookiecode( $usershell, 'ushell' ) ) 
        {
            return false;
        }

        $this->UserInfo = $info;
        return true;
    }

    public function GetUserCheckToBool()
    {
        if ( $this->UserInfo ) 
        {
            return true;
        }
        else 
        {
            return false;
        }
    }

    public function UserLogin( $user, $pass, $login = 0 )
    {
        if ( ! $login ) 
        {
            $pass = md5( md5( $pass ) . md5( $pass ) );
        }

        $return = array( 'status' => '', 'msg' => '' );
        $userdb = System::load_app_model( 'user', 'common' );
        $info = $userdb->SelectUserOne( $user, array( 'password' => $pass ), '*' );

        if ( ! $info ) 
        {
            $return['status'] = -1;
            $return['msg']    = L( 'user.select.no' );
            return $return;
        }

        $return["status"] = 1;
        $return["msg"]    = L("user.login.ok");
        $return["user"]   = $info;
        $return["uid"]    = $info["uid"];
        return $return;
    }

    public function UserBond()
    {
        $openid = _input_filter( $_COOKIE, "openid" );

        if ( empty( $openid ) ) 
        {
            return false;
        }

        $uid = intval( _getcookie( 'uid' ) );

        if ( empty( $uid ) ) 
        {
            return false;
        }

        $userdb = System::load_app_model( 'user', 'common' );
        $info   = $userdb->BondWeixin( $openid, $uid );
    }

    public function UserWeixinLogin()
    {
        $openid = _input_filter( $_COOKIE, 'openid' );

        if ( empty( $openid ) ) 
        {
            return false;
        }

        $userdb = System::load_app_model( 'user', 'common' );
        $info   = $userdb->SelectWeixin($openid);

        if ( ! $info ) 
        {
            return false;
        }
        else 
        {
            return $info['user_id'];
        }
    }

    public function UserLoginStatus( $user, $time )
    {
        $this->UserBond();
        _setcookie( "uid", $user["uid"], $time );
        _setcookie( "username", $user["username"], $time );
        _setcookie( "ushell", _encookiecode(md5($user["uid"] . $user["password"])), $time );
    }

    public function UserLoginUID( $uid )
    {
        $userdb = System::load_app_model("user", "common");
        $user   = $userdb->SelectUserUid($uid);

        if ( ! $user ) 
        {
            return false;
        }

        $this->UserLoginStatus( $user, 3600 * 24 * 7 );
        return true;
    }

    public function UserRegister( $user, $pass, $data = NULL )
    {
        $return = array( 'status' => '', 'msg' => '' );
        if ( ! _checkmobile( $user ) && ! _checkemail( $user ) ) 
        {
            $return['status'] = -1;
            $return['msg']    = '账户类型不正确';
            return $return;
        }

        $userdb = System::load_app_model( 'user', 'common' );
        $info   = $userdb->SelectUserOne( $user, NULL, '*' );

        if ( $info ) 
        {
            $return['status'] = -1;
            $return['msg']    = '该账户已被注册';
            return $return;
        }

        $uid = $userdb->AddUserOne( $user, $pass, $data );

        if ( $uid === false ) 
        {
            $return['status'] = -1;
            $return['msg']    = '注册失败';
        }
        else 
        {
            $return['status'] = 1;
            $return['msg']    = '注册成功';
            $return['uid']    = $uid;
        }

        return $return;
    }
}