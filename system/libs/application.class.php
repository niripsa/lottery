<?php

class application
{
    private $param;

    public function __construct()
    {
        System::load_sys_config( 'system', 'sqlsafe' ) ? System::load_sys_class( 'sqlsafe' ) : false;
        $this->param = System::load_sys_class( 'param' );
        define( 'ROUTE_M', $this->param->route_m() );
        define( 'ROUTE_C', $this->param->route_c() );
        define( 'ROUTE_A', $this->param->route_a() );

        if ( ! empty( $this->param->route_url[4] ) ) {
            define( 'ROUTE_P', $this->param->route_url[4] );
        }

        $this->global_start();
        $this->global_init();
        $this->global_end();
    }

    private function global_start()
    {
        if ( ! System::load_sys_config( "system", "web_off" ) ) 
        {
            $admin_dir = System::load_sys_config( "system", "admindir" );

            if ( $admin_dir !== ROUTE_M ) 
            {
                exit( htmlspecialchars_decode( System::load_sys_config("system", "web_off_text") ) );
            }
        }
    }

    private function global_init()
    {
        System::load_sys_class( 'model', 'sys', 'no' );
        System::load_sys_class( 'SystemAction', 'sys', 'no' );
        System::load_all_fun( 'common' );

        if ( $this->param->plugin ) 
        {
            return self::global_plugin( $this->param->plugin );
        }

        $FilePath = G_SYSTEM . 'modules' . DIRECTORY_SEPARATOR . ROUTE_M . DIRECTORY_SEPARATOR . ROUTE_C . '.action.php';
        $controller = $this->global_controller( $FilePath );

        $controller instanceof SystemAction ? true : true;
        $callAction = array( 'HookConstruct', 'HookDestruct', 'HookSetRoutes', '__construct', '__destruct' );
        $controller->HookSetRoutes($this->param->route_url);
        $controller->HookConstruct();
        in_array( ROUTE_A, $callAction ) 
        ? call_user_func_array( array( $controller, 'SendStatus' ), array( 404 ) ) 
        : true;

        is_callable( array( $controller, ROUTE_A ) )
        ? call_user_func( array( $controller, ROUTE_A ) )
        : call_user_func( array( $controller, '_call' ) );

        $controller->HookDestruct();
        return NULL;
    }

    private function global_end()
    {
        if ( defined( 'G_BANBEN_ERROR' ) ) 
        {
            $content = ob_get_contents();
            ob_clean();
            preg_match_all( "/<title>(.*)<\/title>/", $content, $rusult, PREG_PATTERN_ORDER );

            if ( ! empty( $rusult[1] ) ) 
            {
                echo str_ireplace( "</html>", "", $content ) . base64_decode( G_BANBEN_ERROR ) . "</html>";
            }
            else 
            {
                echo $content;
            }
        }

        if ( isset( $_GET["debug"] ) ) 
        {
            echo "<p>执行时间:";
            echo microtime( true ) - G_START_TIME;
            echo "</p>";
            echo "<p>消耗内存:";
            echo _get_end_memory();
            echo "</p>";
        }
    }

    private function global_controller($filepath)
    {
        if ( file_exists( $filepath ) ) {
            include $filepath;
            $incname = ROUTE_C;

            if ( class_exists( $incname ) ) {
                return new $incname();
            }
            else {
                _error("The \"" . $incname . "\" class does not exist.", "...");
                exit();
            }
        }
        else {
            System::load_sys_class("SystemAction", "sys")->HookConstruct()->SendStatus(404);
            exit();
        }
    }

    static public function global_plugin( $path )
    {
        $_SERVER["plugin_path"] = $path;
        unset( $path );
        return include G_PLUGIN_APP . "plugin.php";
    }
}