<?php 

class user_plugin_model extends model {
    
    static private $obj;
           private $table = "@#_user_band";
    
    public  function __construct() {        
        parent::__construct();
    }
    
    
    static public function GetObject(){
        if(!self::$obj){
            $C =__CLASS__;      
            self::$obj = new $C();
        }       
        return self::$obj;
    }
    
    /**
    
        CREATE TABLE `go_user_band` (
          `b_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
          `b_uid` int(10) DEFAULT NULL COMMENT '用户ID',
          `b_type` char(10) DEFAULT NULL COMMENT '绑定登陆类型',
          `b_code` varchar(100) DEFAULT NULL COMMENT '返回数据1',
          `b_data` varchar(100) DEFAULT NULL COMMENT '返回数据2',
          `b_time` int(10) DEFAULT NULL COMMENT '绑定时间',
          PRIMARY KEY (`b_id`),
          KEY `b_uid` (`b_uid`) USING BTREE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='会员账户绑定表';
            
    
    **/
    
    
    /**
     *  获取通过第三方绑定的用户记录
     *  @outh 外部账户类型
     *  @code 外部登录不变的验证字符
     *  @return 返回用户的绑定记录
     **/
    public function get_outh_user($outh,$code){
        $sql = "SELECT * FROM `{$this->table}` WHERE `b_type` = '{$outh}' and `b_code` = '{$code}' LIMIT 1";
        return $this->GetOne($sql);
    }
    
    /**
     *  设置用户的绑定记录
     *  @uid 本站用户uid
     *  @outh outh 类型
     *  @token 识别用户的唯一字符串
     */
    public function set_outh_user($uid,$outh,$token){
        /*
        $sql = "SELECT * FROM `@#_user_oauth` WHERE `u_id` = '{$uid}' LIMIT 1";
        $info = $this->GetOne($sql);
        if($info){
            return -1;          
        }
        */
                
        $sql = "INSERT INTO `{$this->table}` (`b_uid`, `b_type`, `b_code`, `b_time`) VALUES ('{$uid}', '{$outh}', '{$token}', '".time()."')";
        return $this->Query($sql);
    }
    
    /**
    根据UID返回用户绑定信息
    **/ 
    public function get_outh_userband($uid,$type='*'){
        $sql = "SELECT {$type} FROM `{$this->table}` WHERE `b_uid`={$uid}";
        return $this->GetList($sql);    
    }

}