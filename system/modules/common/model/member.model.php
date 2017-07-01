<?php

class member_model extends model
{
    public function get_count($where = "")
    {
        $sql = "SELECT count(uid) as num FROM `@#_user` ";

        if (!empty($where)) {
            $sql .= "where " . $where;
        }

        $res = $this->GetOne($sql);

        if (!is_array($res)) {
            return false;
        }

        return $res["num"];
    }

    public function get_user_list($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_user`";
        $sql .= (empty($where) ? " WHERE role = 3 " : " WHERE role = 3 AND" . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_user_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_user` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function user_add($data)
    {
        $gid = $this->Insert("user", $data);
        return $gid;
    }

    public function user_save($where, $data)
    {
        $res = $this->Update("user", $data, $where);
        return $res;
    }

    public function user_restore($id)
    {
        $data = array("status" => 0);
        $where = "uid in(" . $id . ")";
        $res = $this->Update("user", $data, $where);
        return $res;
    }

    public function user_del($id)
    {
        $data = array("status" => -1);
        $where = "uid in(" . $id . ")";
        $res = $this->Update("user", $data, $where);
        return $res;
    }

    public function user_del_true($id)
    {
        $where = "uid in(" . $id . ")";
        $res = $this->data_del("user", $where);
        return $res;
    }

    public function user_account_add($data = array())
    {
        return $this->Insert("user_account", $data);
    }

    public function get_group($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_user_group`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function get_group_one($where, $field = "*")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_user_group` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function group_add($data = array())
    {
        return $this->Insert("user_group", $data);
    }

    public function group_save($where, $data = array())
    {
        return $this->Update("user_group", $data, $where);
    }

    public function get_user_addr_list($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_user_addr`";
        $sql .= (empty($where) ? "" : " WHERE " . $where);
        $sql .= (empty($order) ? "" : " ORDER BY " . $order);
        if (!empty($num) && (strpos($num, ",") <= 0)) {
            $num = "0," . $num;
        }

        $sql .= (empty($num) ? "" : " LIMIT " . $num);
        $res = $this->GetList($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function user_addr_add($data)
    {
        $gid = $this->Insert("user_addr", $data);
        return $gid;
    }

    public function user_addr_save($where, $data)
    {
        $res = $this->Update("user_addr", $data, $where);
        return $res;
    }

    public function user_addr_del($where)
    {
        $res = $this->data_del("user_addr", $where);
        return $res;
    }

    public function get_record($where = "", $field = "*", $order = "", $num = "")
    {
        $res = $this->data_list("user_record", $where, $field, $order, $num);
        return $res;
    }

    public function get_record_num($where = "")
    {
        $res = $this->data_num("user_record", $where);
        return $res;
    }

    public function get_record_sum($where, $field)
    {
        $res = $this->data_sum("user_record", $where, $field);
        return $res;
    }

    public function get_record_one($where, $field = "*")
    {
        $res = $this->data_one("user_record", $where, $field);
        return $res;
    }

    public function record_del($where)
    {
        return $this->data_del("user_record", $where);
    }
}

System::load_sys_class("model", "sys", "no");

?>
