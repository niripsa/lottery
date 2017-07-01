<?php

class single_model extends model
{
    public $db;

    public function __construct()
    {
        parent::__construct();
        $this->db = System::load_sys_class("model");
    }
}

System::load_sys_class("model", "sys", "no");

?>
