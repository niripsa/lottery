<?php

final class System
{
    static public function load_sys_class($class_name = "", $module = "sys", $new = "yes")
    {
        static $classes = array();
        $path = self::load_class_file_name( $class_name, $module );
        $key  = md5( $class_name . $path );

        if ( isset( $classes[$key . $new] ) ) {
            return $classes[$key . $new];
        }

        if ( ! isset( $classes[$key] ) ) {
            if ( file_exists( $path ) ) {
                include_once ( $path );
                $classes[$key] = true;
            }
            else {
                _error("load system class file: " . $module . " / " . $class_name, "The file does not exist");
            }
        }

        if ( $new == "yes" ) {
            return $classes[$key . $new] = new $class_name();
        }
        else {
            return $classes[$key . $new] = true;
        }
    }

    static public function load_app_class( $class_name = "", $module = "", $new = "yes" )
    {
        if ( empty( $module ) ) {
            $module = ROUTE_M;
        }

        return self::load_sys_class( $class_name, $module, $new );
    }

    static public function load_class_file_name( $class_name = "", $module = "sys" )
    {
        static $filename = array();

        if ( isset( $filename[$module . $class_name] ) ) {
            return $filename[$module . $class_name];
        }

        if ( $module == "sys" ) {
            $filename[$module . $class_name] = G_SYSTEM . "libs" . DIRECTORY_SEPARATOR . $class_name . ".class.php";
        }
        else if ( $module != "sys" ) {
            $filename[$module . $class_name] = G_SYSTEM . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . $class_name . ".class.php";
        }
        else {
            return $filename[$module . $class_name];
        }

        return $filename[$module . $class_name];
    }

    static public function load_sys_config( $filename, $keys = "" )
    {
        static $configs = array();

        if ( isset( $configs[$filename] ) ) {
            if ( empty( $keys ) ) {
                return $configs[$filename];
            }
            else if ( isset( $configs[$filename][$keys] ) ) {
                return $configs[$filename][$keys];
            }
            else {
                return $configs[$filename];
            }
        }

        if ( file_exists( G_CONFIG . $filename . ".inc.php" ) ) {
            $configs[$filename] = include (G_CONFIG . $filename . ".inc.php");

            if ( empty( $keys ) ) {
                return $configs[$filename];
            }
            else {
                return $configs[$filename][$keys];
            }
        }

        _error("load system config file: " . $filename, "The file does not exist+");
    }

    static public function load_app_config( $filename, $keys = "", $module = "" )
    {
        if ( empty( $module ) ) {
            $module = ROUTE_M;
        }

        $key = $filename . $module;
        static $configs = array();

        if ( isset( $configs[$key] ) ) {
            if ( empty( $keys ) ) {
                return $configs[$key];
            }
            else if ( isset( $configs[$key][$keys] ) ) {
                return $configs[$key][$keys];
            }
            else {
                return $configs[$key];
            }
        }

        $path = G_SYSTEM . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "conf" . DIRECTORY_SEPARATOR . $filename . ".inc.php";

        if ( file_exists( $path ) ) {
            $configs[$key] = include ($path);

            if ( empty( $keys ) ) {
                return $configs[$key];
            }
            else {
                return $configs[$key][$keys];
            }
        }

        _error("load app config file: " . $module . " / " . $filename, "The file does not exist");
    }

    static public function load_sys_fun( $fun_name )
    {
        static $funcs = array();
        $path = G_SYSTEM . "funcs" . DIRECTORY_SEPARATOR . $fun_name . ".fun.php";
        $key = md5( $path );

        if ( isset( $funcs[$key] ) ) {
            return true;
        }

        if ( file_exists( $path ) ) {
            $funcs[$key] = true;
            return include ( $path );
        }
        else {
            $funcs[$key] = false;
            _error("load system function file: " . $fun_name, "The file does not exist");
        }
    }

    static public function load_all_fun( $module )
    {
        static $funcs = array();

        if ( isset( $funcs[$module] ) ) {
            return true;
        }

        if ( $module == "sys" ) {
            $path = G_SYSTEM . "funcs" . DIRECTORY_SEPARATOR;
        }
        else {
            $path = G_SYSTEM . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "funcs" . DIRECTORY_SEPARATOR;
        }

        $funcs[$module] = true;

        foreach ( glob( $path . "*.fun.php" ) as $filename ) {
            include ($filename);
        }
    }

    static public function load_app_fun( $fun_name, $module = NULL )
    {
        static $funcs = array();

        if ( empty( $module ) ) {
            $module = ROUTE_M;
        }

        $path = G_SYSTEM . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "funcs" . DIRECTORY_SEPARATOR . $fun_name . ".fun.php";
        $key = md5( $path );

        if ( isset( $funcs[$key] ) ) {
            return true;
        }

        if ( file_exists( $path ) ) {
            $funcs[$key] = true;
            return include ( $path );
        }
        else {
            _error("load app function file: " . $module . " / " . $fun_name, "The file does not exist");
        }
    }

    static public function load_app_model( $model_name = "", $module = "", $new = "yes" )
    {
        static $models = array();

        if ( empty( $module ) ) {
            $module = ROUTE_M;
        }

        $key = md5( $module . $model_name . "_model" );

        if ( isset( $models[$key . $new] ) ) {
            return $models[$key . $new];
        }

        $path = G_SYSTEM . "modules" . DIRECTORY_SEPARATOR . $module . DIRECTORY_SEPARATOR . "model" . DIRECTORY_SEPARATOR . $model_name . ".model.php";

        if ( ! isset( $models[$key] ) ) {
            if ( file_exists( $path ) ) {
                include ( $path );
                $models[$key] = true;
            }
            else {
                _error("load app model file: " . $module . " / " . $model_name, "The file does not exist");
            }
        }

        if ( $new == "yes" ) {
            $model_name .= "_model";
            return $models[$key . $new] = new $model_name();
        }
        else {
            return $models[$key . $new] = true;
        }
    }

    static public function load_plugin_class( $plugin, $classname )
    {
        static $plugins = array();
        $key = md5( $plugin . $classname . "_plugin" );

        if ( isset( $plugins[$key] ) ) {
            return $plugins[$key];
        }

        $path = G_PLUGIN . $plugin . DIRECTORY_SEPARATOR . "lib" . DIRECTORY_SEPARATOR . $classname . ".class.php";

        if ( file_exists( $path ) ) {
            include ( $path );
            $class = $classname . "_plugin";
            return $plugins[$key] = new $class();
        }
        else {
            _error("load plugin class file: " . $plugin . " / " . $classname, "The file does not exist");
        }
    }

    /**
     * 创建应用程序
     */
    static public function CreateApp()
    {
        return self::load_sys_class( 'application' );
    }

    static public function CreateCgi()
    {
        return self::load_sys_class( 'cgi' );
    }

    static public function CreatePlugin()
    {
        return self::load_sys_class( 'plugin' );
    }
}