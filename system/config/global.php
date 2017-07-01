<?php

/*
 *  global.inc.php 框架主配置文件
 */

error_reporting( E_ALL & ~E_NOTICE );
 
 
 /*
 *---------------------------------------------------------------
 * START PATH
 *---------------------------------------------------------------
 */
define( 'G_IN_SYSTEM', true );

 /*
 *---------------------------------------------------------------
 * RUN TIME
 *---------------------------------------------------------------
 */
define( 'G_START_TIME', microtime( true ) );



 /*
 *---------------------------------------------------------------
 * HOST PATH
 *---------------------------------------------------------------
 */
define( 'G_HTTP_HOST', (isset($_SERVER['HTTP_HOST']) ? $_SERVER['HTTP_HOST'] : '') );

 /*
 *---------------------------------------------------------------
 * The visiting PATH
 *---------------------------------------------------------------
 */
define( 'G_HTTP_REFERER', isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '' );

 /*
 *---------------------------------------------------------------
 * HTTP and HTTPS
 *---------------------------------------------------------------
 *
 *  80 and 443
 */
define( 'G_HTTP', isset($_SERVER['SERVER_PORT']) && $_SERVER['SERVER_PORT'] == '443' ? 'https://' : 'http://' );


 /*
 *---------------------------------------------------------------
 * G_APP_PATH
 *---------------------------------------------------------------
 */
if ( ! defined( 'G_APP_PATH' ) )
{
    define( 'G_APP_PATH', dirname( dirname( dirname( __FILE__ ) ) ) . DIRECTORY_SEPARATOR );
}


/*
*   
*   THIS FILE
**/
define( 'G_SELF', pathinfo( __FILE__, PATHINFO_BASENAME ) );


 /*
 *---------------------------------------------------------------
 * SYSTEM PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_SYSTEM', G_APP_PATH . $system_path . DIRECTORY_SEPARATOR );
define( 'G_SYSTEM_DIR', $system_path );
unset( $system_path );


 /*
 *---------------------------------------------------------------
 * STATICS PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_STATICS', G_APP_PATH . $statics_path . DIRECTORY_SEPARATOR ); 
define( 'G_STATICS_DIR', $statics_path ); 
unset( $statics_path );


 /*
 *---------------------------------------------------------------
 * UPLOADS PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_UPLOAD', G_STATICS . 'uploads' . DIRECTORY_SEPARATOR );

 /*
 *---------------------------------------------------------------
 * CONFIG PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_CONFIG', G_SYSTEM . 'config' . DIRECTORY_SEPARATOR );


 /*
 *---------------------------------------------------------------
 * CACHES PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_CACHES', G_SYSTEM . 'caches' . DIRECTORY_SEPARATOR );

 /*
 *---------------------------------------------------------------
 * PLUGIN PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_PLUGIN', G_SYSTEM . 'plugin' . DIRECTORY_SEPARATOR );


 /*
 *---------------------------------------------------------------
 * TEMPLATES PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_TEMPLATES', G_STATICS . 'templates' . DIRECTORY_SEPARATOR );




 /*
 *---------------------------------------------------------------
 * MODULES PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_MODULES', G_SYSTEM . 'modules' . DIRECTORY_SEPARATOR );



 /*
 *---------------------------------------------------------------
 * LANGUAGES PATH
 *---------------------------------------------------------------
 *
 */
define( 'G_LANGUAGES', G_SYSTEM . 'languages' . DIRECTORY_SEPARATOR );



 /*
 *---------------------------------------------------------------
 * WEB_APP URL
 *---------------------------------------------------------------
 *
 */
define( 'G_WEB_PATH', dirname( G_HTTP . G_HTTP_HOST . $_SERVER['SCRIPT_NAME'] ) );


require G_SYSTEM . 'libs/system.class.php';
if ( System::load_sys_config( 'system', 'index_name' ) == NULL ) 
{
    define( 'WEB_PATH', G_WEB_PATH );
}
else
{
    define( 'WEB_PATH', G_WEB_PATH . '/' . System::load_sys_config( 'system', 'index_name' ) );
}

 /*
 *---------------------------------------------------------------
 * UPLOAD URL
 *---------------------------------------------------------------
 *
 */
define( 'G_UPLOAD_PATH', G_WEB_PATH . '/' . G_STATICS_DIR . '/uploads' );


 /*
 *---------------------------------------------------------------
 * PLUGIN URL
 *---------------------------------------------------------------
 *
 */
define( 'G_PLUGIN_PATH', G_WEB_PATH . '/' . G_STATICS_DIR . '/plugin' );

 /*
 *---------------------------------------------------------------
 * APP_PLUGIN URL
 *---------------------------------------------------------------
 *
 */
define( 'G_PLUGIN_APP', G_SYSTEM . 'plugin' . DIRECTORY_SEPARATOR );


 /*
 *---------------------------------------------------------------
 * PLUGIN STYLE URL
 *---------------------------------------------------------------
 *
 */
define( 'G_GLOBAL_STYLE', G_PLUGIN_PATH . '/style' );



 /*
 *---------------------------------------------------------------
 *  INCLUDE GLOBAL FUNCTION
 *---------------------------------------------------------------
 *
 */ 
System::load_all_fun( 'sys' );

 /*
 *---------------------------------------------------------------
 *  error set
 *---------------------------------------------------------------
 *
 */
if ( System::load_sys_config( 'system', 'error' ) )
{
    _error_handler();
}



/*
 *---------------------------------------------------------------
 *  timezone set
 *---------------------------------------------------------------
 *
 */
function_exists('date_default_timezone_set') && date_default_timezone_set(System::load_sys_config('system','timezone'));



/*
 *---------------------------------------------------------------
 *  admin set
 *---------------------------------------------------------------
 *
 */
define( 'G_ADMIN_DIR', System::load_sys_config( 'system', 'admindir' ) );


/**
 * Wc Version
 *
 * @var string
 *
 */
define( 'G_VERSION', System::load_sys_config( 'version', 'version' ) );


if ( ! is_php( '5.3' ) ) {
    @set_magic_quotes_runtime(0);
}


/*
 * ------------------------------------------------------
 *  Set a liberal script execution time limit
 * ------------------------------------------------------
 */
if ( function_exists("set_time_limit") == TRUE AND @ini_get("safe_mode") == 0 )
{
    set_time_limit( 100 );
}


if ( System::load_sys_config( 'system', 'gzip' ) && function_exists( 'ob_gzhandler' ) ) 
{
    if ( stripos( $_SERVER['HTTP_USER_AGENT'], 'flash' ) === FALSE )
    {
        ob_start( '_ob_gzhandler' );
    }
    else
    {
        ob_start();
    }
} 
else 
{
    ob_start();
}

/*
 *---------------------------------------------------------------
 *  CHARSET set
 *---------------------------------------------------------------
 *
 */
define( 'G_CHARSET', System::load_sys_config( 'system', 'charset' ) );
header( 'Content-type: text/html; charset='.G_CHARSET );