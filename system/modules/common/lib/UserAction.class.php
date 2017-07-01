<?php
class UserAction extends SystemAction
{
    protected $UserInfo;
    protected $Userid;

    final public function __construct()
    {
        $user = System::load_app_class( 'UserCheck', 'common' )->UserInfo;

        if ( ! $user ) 
        {
            $this->SendStatus( 301, WEB_PATH . '/login&time='.time() );
        }

        $this->UserInfo = $user;
        $this->Userid   = $user["uid"];
    }
}