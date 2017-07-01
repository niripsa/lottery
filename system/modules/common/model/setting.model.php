<?php
class setting_model extends model
{
    public function write_setting( $module, $data = array() )
    {
        unset( $data["dosubmit"] );
        $this->sql_begin();
        $i = 0;

        foreach ( $data as $key => $val )
        {
            $sql = "SELECT * FROM `@#_config` WHERE `name`='" . $key . "' AND `modules`='" . $module . "'";
            if ( is_array( $this->GetOne( $sql ) ) )
            {
                $res = $this->Update("config", array("value" => $val), "`name`='" . $key . "' and modules='" . $module . "'");
                if ( $res )
                {
                    $i++;
                }
            }
            else
            {
                $res = $this->Insert("config", array("value" => $val, "name" => $key, "modules" => $module));
                if ( $res )
                {
                    $i++;
                }
            }
        }

        if ( 0 < $i )
        {
            $this->sql_commit();
            return true;
        }
        else
        {
            $this->sql_rollback();
            return false;
        }
    }

    public function ready_setting($module, $key = "")
    {
        if ($key == "") {
            $sql = "SELECT * FROM `@#_config` WHERE `modules`='" . $module . "'";
            $res = $this->GetList($sql);
            return _arr2to1($res, "name", "value");
        }
        else {
            $sql = "SELECT * FROM `@#_config` WHERE `name`='" . $key . "' AND `modules`='" . $module . "'";
            $res = $this->GetOne($sql);
            return $res["value"];
        }
    }

    public function get_payment($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("payment", $where, $field, $order, $num);
        return $res;
    }

    public function get_payment_one($where, $field = "*")
    {
        $res = $this->data_one("payment", $where, $field);
        return $res;
    }

    public function payment_add($data = array())
    {
        return $this->Insert("payment", $data);
    }

    public function payment_save($where, $data = array())
    {
        return $this->Update("payment", $data, $where);
    }

    public function payment_del($where)
    {
        return $this->data_del("payment", $where);
    }

    public function get_ship($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("ems", $where, $field, $order, $num);
        return $res;
    }

    public function get_ship_one($where, $field = "*")
    {
        $res = $this->data_one("ems", $where, $field);
        return $res;
    }

    public function ship_add($data = array())
    {
        return $this->Insert("ems", $data);
    }

    public function ship_save($where, $data = array())
    {
        return $this->Update("ems", $data, $where);
    }

    public function ship_del($where)
    {
        return $this->data_del("ems", $where);
    }

    public function cfgPut( $module, $filename, $type = 0 )
    {
        if ( $type == 1 )
        {
            $arr = $module;
        }
        else
        {
            $arr = $this->ready_setting( $module );
        }

        $html = "<?php \ndefined('G_IN_SYSTEM') or exit('No permission resources.'); \n";
        $html .= "return array( \n";

        foreach ( $arr as $k => $v )
        {
            if ( is_array( $v ) )
            {
                $html .= "'$k' => " . var_export( $v, true ) . ",";
                $html .= "\n";
            }
            else
            {
                $v = addslashes( $v );
                $html .= "'$k' => '$v',";
                $html .= "\n";
            }
        }
        $html .= ");";

        if ( ! is_writable( G_CONFIG . $filename . ".inc.php" ) )
        {
            _message("Please chmod  " . $filename . "  to 0777 !");
        }

        return $ok = file_put_contents( G_CONFIG . $filename . ".inc.php", $html );
    }

    public function robots($str)
    {
        return $ok = file_put_contents(G_APP_PATH . "robots.txt", $str);
    }
}

System::load_sys_class("model", "sys", "no");