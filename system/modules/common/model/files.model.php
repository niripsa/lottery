<?php

class files_model extends model
{
    public function file_add_record($uid, $path, $time)
    {
        $time = ($time ? $time : time());
        $sql = "INSERT INTO `@#_files` (`uid`, `file`, `time`) VALUES ('$uid', '$path', '$time')";
        $this->Query($sql);
    }

    public function file_del($uid, $path, $time)
    {
        $sql = "DELETE FROM `@#_files` WHERE (`uid`='$uid' and `time` = '$time' and `path` = '$path') LIMIT 1";
        return $this->Query($sql);
    }
}


?>
