<?php
class Config{
    private $cfg = array(
        'url'=>'https://pay.swiftpass.cn/pay/gateway',
        'mchId'=>'102581496021',
        'key'=>'92737094324a314c043f1538b60ced34',
        'version'=>'1.0',
        'appid' => 'wxdd992cf078094dbf',
        'appsecret' => '1cec390c874a97c1c12d777dde2d1456',
    );
    
    public function C($cfgName){
        return $this->cfg[$cfgName];
    }
}
?>