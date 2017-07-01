<?php

class article_model extends model
{
    public function GetArticleAid($aid)
    {
        return $this->GetOne("SELECT * FROM `@#_article` WHERE `id` = '" . $aid . "'");
    }

    public function add($data)
    {
        $gid = $this->Insert("article", $data);
        return $gid;
    }

    public function save($data, $where)
    {
        $res = $this->Update("article", $data, $where);
        return $res;
    }

    public function get_articles($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_article`";
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

    public function get_article_one($where, $field = "*")
    {
        $sql = "SELECT " . $field . " FROM `@#_article` ";

        if (!empty($where)) {
            $sql .= " WHERE " . $where;
        }

        $res = $this->GetOne($sql);

        if (!$res) {
            return false;
        }

        return $res;
    }

    public function article_del($where)
    {
        $sql = "DELETE FROM `@#_article` WHERE " . $where;
        return $this->Delete($sql);
    }

    public function article_sort($data)
    {
        $i = false;

        foreach ($data as $k => $v ) {
            $res = $this->Update("article", array("sort" => $v), "id=" . $k);

            if ($res) {
                $i = true;
            }
        }

        return $i;
    }

    public function get_article_num($where)
    {
        $sql = "select count(*) as num from `@#_article`";

        if (!empty($where)) {
            $sql .= " where " . $where;
        }

        $tmp = $this->GetOne($sql);
        return $tmp["num"];
    }

    public function get_cate_list($where = "", $field = "*", $order = "", $num = "")
    {
        if (empty($field)) {
            $field = "*";
        }

        $sql = "SELECT " . $field . " FROM `@#_cate`";
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
}