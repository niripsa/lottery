<?php

class ConfigUtil
{
    public static function get_val_by_key( $key )
    {
        $settings = new Settings_INI ();
        $settings->load( dirname(__FILE__) . DIRECTORY_SEPARATOR . 'config.ini' );
        $settings->load( $loadpath );
        return $settings->get( "wepay." . $key );
    }

    public static function get_trade_num()
    {
        return ConfigUtil::get_val_by_key ( 'merchantNum' ) . ConfigUtil::getMillisecond ();
    }

    public static function getMillisecond()
    {
        list ( $s1, $s2 ) = explode ( ' ', microtime () );
        return ( float ) sprintf ( '%.0f', (floatval ( $s1 ) + floatval ( $s2 )) * 1000 );
    }
}

class Settings
{
    var $_settings = array ();
    /**
     * 获取某些设置的值
     *
     * @param unknown_type $var         
     * @return unknown
     */
    public function get( $var )
    {
        $var = explode ( '.', $var );
        
        $result = $this->_settings;
        foreach ( $var as $key )
        {
            if ( ! isset ( $result[$key] ) )
            {
                return false;
            }
            
            $result = $result[$key];
        }
        
        return $result;
    }

    public function load( $file )
    {
        trigger_error ( 'Not yet implemented', E_USER_ERROR );
    }
}

class Settings_INI extends Settings
{
    public function load( $file )
    {
        if ( file_exists( $file ) == true )
        {
            $this->_settings = parse_ini_file( $file, true );
        }
    }
}