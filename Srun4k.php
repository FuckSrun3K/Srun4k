<?php
/**
 * Created by PhpStorm.
 * User: skyfire
 * Date: 17-3-19
 * Time: 下午6:05
 */
require_once 'MACAddress.php';

class Srun4k
{
    private $params = [];

    public static $url = "http://172.16.154.130/cgi-bin/srun_portal";

    public function __construct($username, $password)
    {
        register_shutdown_function([$this, 'shutdown']);

        $this->params['username'] = $this->userEncrypt($username);
        $this->params['password'] = $this->pwdEncrypt($password);

        $this->initialize();
    }

    private function initialize()
    {
        $this->params['drop'] = "0";
        $this->params['mbytes'] = "0";
        $this->params['minutes'] = "0";

        $this->params['pop'] = "1";
        $this->params['ac_id'] = "2";
        $this->params['type'] = "2";

        $this->params['n'] = "117";
        $this->params['action'] = "login";

        $this->getMACAddress();
    }

    private function getMACAddress()
    {
        $mac = new MACAddress(PHP_OS);
        $this->params['mac'] = $mac->mac_addr;
    }

    private function userEncrypt($username)
    {
        $rtn = "{SRUN3}\r\n";
        for ($i = 0; $i < strlen($username); $i++) {
            $rtn = $rtn .chr(ord($username[$i]) + 4);
        }
        return $rtn;
    }

    private function pwdEncrypt($pwd)
    {
        $pe = "";
        $cipher = "1234567890";
        for ($i = 0; $i < strlen($pwd); $i++)
        {
            $ki = ord($cipher[strlen($cipher) - $i % strlen($cipher) - 1]) ^ ord($pwd[$i]);
            $_l = (($ki & 0xF) + 54);
            $_h = (($ki >> 4 & 0xF) + 99);
            if ($i % 2 == 0) {
                $pe = $pe . chr($_l) .chr($_h);
            } else {
                $pe = $pe . chr($_h) .chr($_l);
            }
        }
        return $pe;
    }

    public function login()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::$url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($this->params));
        $response = curl_exec($ch);
        if (strpos($response, 'ok'))
        {
            echo date("Y-m-d H:i:s")." - Login succeed!!!".PHP_EOL;
        }
        else    exit("Information is error!!!");
    }

    public static function logout()
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, static::$url.'?'.http_build_query(['action'=>'logout']));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_exec($ch);
        exit();
    }

    public function shutdown(){ }
}