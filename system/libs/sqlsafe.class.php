<?php

class sqlsafe
{
    private $getfilter = "'|(and|or)\b.+?(>|<|=|in|like)|\/\*.+?\*\/|<\s*script\b|\bEXEC\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\s+(TABLE|DATABASE)";
    private $postfilter = "\b(and|or)\b.{1,6}?(=|>|<|\bin\b|\blike\b)|\/\*.+?\*\/|<\s*script\b|\bEXEC\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\s+(TABLE|DATABASE)";
    private $cookiefilter = "\b(and|or)\b.{1,6}?(=|>|<|\bin\b|\blike\b)|\/\*.+?\*\/|<\s*script\b|\bEXEC\b|UNION.+?SELECT|UPDATE.+?SET|INSERT\s+INTO.+?VALUES|(SELECT|DELETE).+?FROM|(CREATE|ALTER|DROP|TRUNCATE)\s+(TABLE|DATABASE)";

    public function __construct()
    {
        foreach ($_GET as $key => $value ) {
            $this->stopattack($key, $value, $this->getfilter);
        }

        foreach ($_POST as $key => $value ) {
            $this->stopattack($key, $value, $this->postfilter);
        }

        foreach ($_COOKIE as $key => $value ) {
            $this->stopattack($key, $value, $this->cookiefilter);
        }
    }

    public function stopattack($StrFiltKey, $StrFiltValue, $ArrFiltReq)
    {
        if (is_array($StrFiltValue)) {
            $StrFiltValue = implode($StrFiltValue);
        }

        if (preg_match("/" . $ArrFiltReq . "/is", $StrFiltValue) == 1) {
            $this->writeslog($_SERVER["REMOTE_ADDR"] . "    " . strftime("%Y-%m-%d %H:%M:%S") . "    " . $_SERVER["PHP_SELF"] . "    " . $_SERVER["REQUEST_METHOD"] . "    " . $StrFiltKey . "    " . $StrFiltValue);
            exit("您提交的参数非法,系统已记录您的本次操作！");
        }
    }

    public function writeslog($log)
    {
        $log_path = G_CACHES . "caches_sql_log" . DIRECTORY_SEPARATOR . date("Ymd") . "/sql_logs.php";

        if (!is_dir(dirname($log_path))) {
            mkdir(dirname($log_path), 511, true) || exit("Not sql log Dir");
            chmod(dirname($log_path), 511);
            file_put_contents($log_path, "<?php exit; ?>" . PHP_EOL);
        }

        file_put_contents($log_path, $log . PHP_EOL, FILE_APPEND);
    }
}


?>
