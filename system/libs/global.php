<?php

class SystemGlobal
{
    public function __construct($system_path, $statics_path)
    {
        define("G_IN_SYSTEM", true);
        define("G_START_TIME", microtime(true));
        define("G_HTTP_HOST", isset($_SERVER["HTTP_HOST"]) ? $_SERVER["HTTP_HOST"] : "");
        define("G_HTTP_REFERER", isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : "");
        define("G_HTTP", isset($_SERVER["SERVER_PORT"]) && ($_SERVER["SERVER_PORT"] == "443") ? "https://" : "http://");

        if (!defined("G_APP_PATH")) {
            define("G_APP_PATH", dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR);
        }

        define("G_SELF", pathinfo(__FILE__, PATHINFO_BASENAME));
        define("G_SYSTEM", G_APP_PATH . $system_path . DIRECTORY_SEPARATOR);
        define("G_SYSTEM_DIR", $system_path);
        unset($system_path);
        define("G_STATICS", G_APP_PATH . $statics_path . DIRECTORY_SEPARATOR);
        define("G_STATICS_DIR", $statics_path);
        unset($statics_path);
        define("G_UPLOAD", G_STATICS . "uploads" . DIRECTORY_SEPARATOR);
        define("G_CONFIG", G_SYSTEM . "config" . DIRECTORY_SEPARATOR);
        define("G_CACHES", G_SYSTEM . "caches" . DIRECTORY_SEPARATOR);
        define("G_PLUGIN", G_SYSTEM . "plugin" . DIRECTORY_SEPARATOR);
        define("G_TEMPLATES", G_STATICS . "templates" . DIRECTORY_SEPARATOR);
        define("G_MODULES", G_SYSTEM . "modules" . DIRECTORY_SEPARATOR);
        define("G_LANGUAGES", G_SYSTEM . "languages" . DIRECTORY_SEPARATOR);
        define("G_WEB_PATH", dirname(G_HTTP . G_HTTP_HOST . $_SERVER["SCRIPT_NAME"]));
        require ("system.class.php");

        if (System::load_sys_config("system", "index_name") == NULL) {
            define("WEB_PATH", G_WEB_PATH);
        }
        else {
            define("WEB_PATH", G_WEB_PATH . "/" . System::load_sys_config("system", "index_name"));
        }

        define("G_UPLOAD_PATH", G_WEB_PATH . "/" . G_STATICS_DIR . "/uploads");
        define("G_PLUGIN_PATH", G_WEB_PATH . "/" . G_STATICS_DIR . "/plugin");
        define("G_PLUGIN_APP", G_SYSTEM . "plugin" . DIRECTORY_SEPARATOR);
        define("G_GLOBAL_STYLE", G_PLUGIN_PATH . "/style");
        System::load_all_fun("sys");

        if (System::load_sys_config("system", "error")) {
            _error_handler();
        }

        _session_start(1);
        function_exists("date_default_timezone_set") && date_default_timezone_set(System::load_sys_config("system", "timezone"));
        define("G_ADMIN_DIR", System::load_sys_config("system", "admindir"));
        define("G_VERSION", System::load_sys_config("version", "version"));

        if (!is_php("5.3")) {
            @set_magic_quotes_runtime(0);
        }

        if ((function_exists("set_time_limit") == true) && (@ini_get("safe_mode") == 0)) {
            set_time_limit(100);
        }

        if (System::load_sys_config("system", "gzip") && function_exists("ob_gzhandler")) {
            if (stripos($_SERVER["HTTP_USER_AGENT"], "flash") === false) {
                ob_start("_ob_gzhandler");
            }
            else {
                ob_start();
            }
        }
        else {
            ob_start();
        }

        define("G_CHARSET", System::load_sys_config("system", "charset"));
        header("Content-type: text/html; charset=" . G_CHARSET);
    }
}