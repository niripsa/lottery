<?php
require ( G_PLUGIN . './plugin.fun.php' );
_pluginrouteindex();
$plugin = ( isset( $_GET['api'] ) ? basename( $_GET['api'] ) : _SendStatus( 404 ) );

if ( $plugin == 'demo' )
{
    _PluginUpdatePackage('Demo',array("Name"=>"bbbbbbbbbbbb","a"=>"b"));
    _PluginUpdatePackage('Demo',array("Name"=>"CCCCCCCC","a"=>"b"));
    _PluginUpdatePackage('Demo',array("Name"=>"DDDDDDDDDDD","a"=>"b"));

    return;
}

register_shutdown_function( 'plugin_shudtown_check' );

$pluginData = _PluginGetOne( $plugin );

if ( ! $pluginData ) 
{
    echo "Not install Plugin...";return;
}
if ( $pluginData['Status'] != 1 )
{
    if ( ! _PluginCheckAdmin( 1 ) ) 
    {
        echo "Plugin Not start.";return;
    }
}

if ( file_exists( G_PLUGIN . $plugin . "/./" . $pluginData['Index'] ) )
{
    require G_PLUGIN . $plugin . "/./". $pluginData['Index'];
}
else
{
    echo "plugin: not found file.";
}