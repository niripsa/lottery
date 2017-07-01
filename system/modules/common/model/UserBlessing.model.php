<?php

class UserBlessing_model extends model
{
    public function select_conf()
    {
        $config = System::load_sys_config("user_fufen");
        return $config;
    }

    public function add_blessing($uid, $fkey, $zkey, $content)
    {
        $isset_user = $this->GetList("select `uid` from `@#_user_account` where `content`='" . $content . "' and `type`='1' and `uid`='$uid' and (`pay`='经验' or `pay`='福分')");

        if (empty($isset_user)) {
            $config = $this->select_conf();
            $time = time();
            $this->Query("insert into `@#_user_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$uid','1','福分','$content','$config[$fkey]','$time')");
            $this->Query("insert into `@#_user_account` (`uid`,`type`,`pay`,`content`,`money`,`time`) values ('$uid','1','经验','$content','$config[$zkey]','$time')");
            $this->Query("UPDATE `@#_user` SET `score`=`score`+'$config[$fkey]',`jingyan`=`jingyan`+'$config[$zkey]' where uid='" . $uid . "'");
            return true;
        }
        else {
            return false;
        }
    }
}


?>
