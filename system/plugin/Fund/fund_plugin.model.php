<?php 

class fund_plugin_model extends model {
    

    private $table = "@#_fund";
    
    public  function __construct() {        
        parent::__construct();
    }
    
    
    public function get_fund_data(){
        $sql = "SELECT * FROM `{$this->table}` WHERE 1";
        return $this->GetOne($sql);             
    }
    
    public function set_fund_data($data=NULL){
        
        $html = "";
        foreach($data as $k=>$v){
            $html.="`".$k."`"."="."'".$v."'".",";                   
        }
        $html = rtrim($html,",");   
        $sql = "UPDATE `{$this->table}` SET {$html} where 1";
        return $this->Query($sql);              
    }
    

}