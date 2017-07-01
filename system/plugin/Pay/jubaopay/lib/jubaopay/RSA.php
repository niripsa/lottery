<?php

class RSA
{
    private $_privFile;
    private $_pubFile;
    
    private $_privKey;
    private $_pubKey;
    
    private $_algo ;
    private $_psw;

    public function __construct($conf)
    {
        $xml = new DOMDocument();
        $xml->load($conf);
        $items = $xml->getElementsByTagName("items");       
        
        $this->_privFile = "-----BEGIN RSA PRIVATE KEY-----
MIICXAIBAAKBgQDRpo+NaW2xFLzNTJEZTOrEtqfr+ljl6UvPIgytADNpS/iyP0wq
JePpAWGHAdS2omGux73Pb19xScTKSq1AGrNi9mZQYF+gIJoY77ZU/c4xlEqgkubn
Lndh+bCDPpGe1BKjWf0F5aofLHW3HCPMdQwKZevDNZaMB9F+CLAL0fYKOQIDAQAB
AoGAPk2VU50FMB7NjOU4KaCmFBeEB6i6SnjuQnwH8FGi9aPSIIaXB9+Cv3fdl9V/
dVcEWICbc83RO0WE6ekPW4GT1Uc/mNTEDL043s+KFumapkoyGKbNYu4yeiHFOcxI
EBvZ88xDL+CsUj/I5VDEA0gFeiZ0C886x/P4Dv2NYRqvgRECQQDw48VsiALj5+Qg
LSIwLuCfNNyUu8lhSX8Yhzxajx09VUuDuI3agrUv6R/cBfDWbCwleclQu++mIGJw
mVoqI4s/AkEA3s0mNY11yYe5JJg0EbCQJPpjSnv1N5Ph4JdhQ8gWYmXb1H4/8fd6
O0kRGnQjEnFccFCB0P+H8gRaCPe+U21khwJAHxppzV+qb97rN7RcK7iBzEy5BoNQ
tz0UKgicJF1COz8eJv3XYxVpa6xALtqdhDInaRdWhRQfF2YrD7rdR3+bZQJASLbx
qnaxo4VdQnk+PSu/z6G8eBm+rVXhWVhukR2jWJffyBkwK3tWdDTjlRukOqeuwKU1
yQ0sxCrxa43XmwjPzwJBAK9/FWl79qz4AwlUGdl6f6s12B2LnBNrQ6N8YH4qaKOn
RsOMFJ4hNtv5uEXKYFYDgwd5TBT+RnuETARsdGhEhAM=
-----END RSA PRIVATE KEY-----
";
        $this->_pubFile = "-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCk5V5YMRar1+LuWkW9SvRA6zAk
hCzmUFv3750TjgFRWHI3kfCjd1smdZWtJpAoqLICqNU4Rqc7hMxMkMOY8hHX6wuU
QwBwWXWREdm5lyBRpi8teQTG05GsJ60d3W3Nn5arsShaqvpX3bsSZHbCv1k9N6PR
kj2arrhSzEBJdPjsVQIDAQAB
-----END PUBLIC KEY-----
";
        $this->_algo = OPENSSL_ALGO_SHA1;
        $this->_psw = $items->item(0)->getElementsByTagName('psw')->item(0)->nodeValue;
        
    }
    
    public function __destruct()
    {
        @ fclose($this->_privKey);
        @ fclose($this->_pubKey);
    }

    public function setupPrivKey()
    {
        if(is_resource($this->_privKey)){
            return true;
        }

        $prk = $this->_privFile;
        $this->_privKey = openssl_pkey_get_private($prk);
        return true;
    }
     
    public function setupPubKey()
    {
        if(is_resource($this->_pubKey)){
            return true;
        }

        $puk = $this->_pubFile;
        $this->_pubKey = openssl_pkey_get_public($puk);
        return true;
    }
    
    public function pubEncrypt($data)
    {
        if(!is_string($data)){
            return null;
        }
            
        $this->setupPubKey();
            
        $r = openssl_public_encrypt($data, $encrypted, $this->_pubKey);
        if($r){
            return base64_encode($encrypted);
        }
        return null;
    }
    
    public function sign($data)
    {
        $digest=$data.$this->_psw;
        $privKey = $this->_privFile;
        openssl_sign($digest, $signature, $privKey, $this->_algo);
        return base64_encode($signature);       
    }
    
    public function privDecrypt($encrypted)
    {
        if(!is_string($encrypted)){
            return null;
        }
            
        $this->setupPrivKey();
            
        $encrypted = base64_decode($encrypted);
    
        $r = openssl_private_decrypt($encrypted, $decrypted, $this->_privKey);
        if($r){
            return $decrypted;
        }
        return null;
    }
    
    public function verify($data,$signature)
    {               
        $digest=$data.$this->_psw;
        $pubKey = $this->_pubFile;
        return openssl_verify($digest, base64_decode($signature), $pubKey, $this->_algo );       
    }
    
    public function privEncrypt($data)
    {
        if(!is_string($data)){
            return null;
        }
         
        $this->setupPrivKey();
         
        $r = openssl_private_encrypt($data, $encrypted, $this->_privKey);
        if($r){
            return base64_encode($encrypted);
        }
        return null;
    }
     
    public function pubDecrypt($crypted)
    {
        if(!is_string($crypted)){
            return null;
        }
         
        $this->setupPubKey();
         
        $crypted = base64_decode($crypted);

        $r = openssl_public_decrypt($crypted, $decrypted, $this->_pubKey);
        if($r){
            return $decrypted;
        }
        return null;
    }

}
