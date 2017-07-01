<?php

class email
{
    static  private $mail;
    static  private $config;

    static public function config($config = array())
    {
        if (!is_array($config)) {
            return false;
        }

        self::$config = $config;
        self::$mail = System::load_sys_class("phpmailer");
        self::$mail->IsSMTP();
        self::$mail->Host = $config["stmp_host"];
        self::$mail->SMTPAuth = true;
        self::$mail->Username = $config["user"];
        self::$mail->Password = $config["pass"];
        self::$mail->From = $config["from"];
        self::$mail->FromName = $config["fromName"];
        self::$mail->AddReplyTo($config["from"], $config["fromName"]);
        self::$mail->WordWrap = 50;
    }

    static public function adduser($userpath, $username = "")
    {
        if (!is_array($userpath)) {
            self::$mail->AddAddress($userpath, $username);
        }
        else {
            $len = count($userpath);

            foreach ($userpath as $v ) {
                self::$mail->AddAddress($v["email"], $v["name"]);
            }
        }
    }

    static public function addfile($filepath, $filename = "")
    {
        if (empty($filename)) {
            self::$mail->AddAttachment($filepath);
        }
        else {
            self::$mail->AddAttachment($filepath, $filename);
        }
    }

    static public function send($title = "", $content = "", $type = true, $nohtml = "")
    {
        self::$mail->IsHTML($type);
        self::$mail->CharSet = self::$config["big"];
        self::$mail->Encoding = "base64";
        self::$mail->Subject = $title;
        self::$mail->Body = $content;

        if (empty($nohtml)) {
            $nohtml = self::$config["nohtml"];
        }

        self::$mail->AltBody = $nohtml;

        if (!self::$mail->Send()) {
            return false;
        }
        else {
            return true;
        }
    }
}


?>
