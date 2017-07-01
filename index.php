<?php
define( 'G_EXECMODE', 'WebApp' );
$system_path  = 'system';
$statics_path = 'statics';
define( 'G_APP_PATH', dirname( __FILE__ ) . DIRECTORY_SEPARATOR );
require ( G_APP_PATH . $system_path . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'global.php' );
switch ( defined( 'G_EXECMODE' ) ? G_EXECMODE : false ) 
{
    case 'cgi':
        System::CreateCgi();
    break;

    case 'plugin':
        System::CreatePlugin();
    break;

    default:
        System::CreateApp();
}

defined( 'G_EXECMODE' ) ? G_EXECMODE : false;